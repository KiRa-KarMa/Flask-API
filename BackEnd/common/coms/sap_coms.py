import requests
import json
import configparser
import logging
from common.encrypt.codeDecode import cargar_clave, desencriptar_items


class SAPContextManager():
    def __init__(self, ip, CompanyDB, UserName, password):
        self.ip = ip
        self.CompanyDB = CompanyDB
        self.UserName = UserName
        self.password = password
        self.alertConf = {}

    def __enter__(self):
        self.session, self.routeID = self.SAPlogin(self.ip, self.CompanyDB,
                                                   self.UserName,
                                                   self.password)
        return self

    def __exit__(self, exc_type, exc_val, exc_tb):
        self.SAPlogout(self.CompanyDB, self.ip, self.routeID)

    def SAPlogin(self, ip, CompanyDB, UserName, password):
        """
        Se conecta a SAP.

        :param ip: Direccion IP de SAP
        :param CompanyDB: Nombre de la base de datos de SAP
        :param UserName: Nombre de usuario de SAP
        :param password: Contraseña de usuario de SAP
        """
        url = ip+"/b1s/v1/Login"
        payload = "{\r\n  \"CompanyDB\": "\
            f"\"{CompanyDB}\",\r\n\"UserName\": \"{UserName}\",\r\n "\
            f"\"Password\": \"{password}\"\r"\
            "\n}"

        headers = {
            'Content-Type': 'text/plain',
            'Cookie': f'CompanyDB={CompanyDB};ROUTEID=.node1'
        }
        response = self.peticionSAP("POST", url, msg="Ha ocurrido un error"
                                    " comunicandose con SAP en "
                                    "DetectorErroresTraspasoComerzziaSAP al"
                                    " hacer login",  headers=headers,
                                    data=payload, verify=False)
        val = json.loads(response.text)
        routeID = ".node1"

        session_Key = val['SessionId']
        return session_Key, routeID

    def peticionSAP(self, method, url, headers, data, msg, verify=False):
        try:
            response = requests.request(method, url, headers=headers,
                                        data=data, verify=verify)
        except Exception as e:
            print(e)
            raise Exception("Error en la peticion a SAP")
        return response

    def SAPlogout(self, CompanyDB, ip, routeID=".node0"):
        """
        Se desconecta de SAP.

        :param CompanyDB: Nombre de la base de datos de SAP
        :param UserName: Nombre de usuario de SAP
        :param password: Contraseña de usuario de SAP
        :param ip: Direccion IP de SAP
        """

        url = ip+"/b1s/v1/Logout"
        payload = "{\r\n"+f"    \"CompanyDB\": \"{CompanyDB}\",\r\n "
        "   \"UserName\": \"{UserName}\",\r\n    \"Password\":"
        " \"{password}\"\r\n"+"}"
        headers = {
            'Content-Type': 'text/plain',
            'Cookie': f'ROUTEID={routeID}'
        }

        response = self.peticionSAP("POST", url, msg="Ha ocurrido un error"
                                    " comunicandose con SAP en "
                                    "DetectorErroresTraspasoComerzziaSAP al"
                                    " hacer logout", headers=headers,
                                    data=payload,
                                    verify=False)

    def getArticulo(self, ItemCode):
        """
        Obtenemos los datos del articulo en la api de sap.

        :param ItemCode: Codigo de Item
        :return: Respuesta de la consulta
        """

        url = self.ip+f"/b1s/v1/Items?$filter=ItemCode eq '{ItemCode}'"

        payload = {}

        headers = {
            'Cookie': f'B1SESSION={self.session}; CompanyDB={self.CompanyDB}; '
            f'ROUTEID={self.routeID}'
        }
        logging.info(
            f'Obtenemos los datos del ItemCode seleccionado usando: {url}')
        response = self.peticionSAP("GET", url, msg="Ha ocurrido un error"
                                    " comunicandose con SAP en "
                                    "ExcelToSAP al obtener item",
                                    headers=headers,
                                    data=payload,
                                    verify=False)
        res = json.loads(response.content)
        return res

    def getArticuloGroup(self, ItemGroup):
        """
        Obtenemos los datos del grupo del articulo en la api de sap.

        :param ItemGroup: Codigo de grupo
        :return: Respuesta de la consulta
        """

        url = self.ip+f"/b1s/v1/ItemGroups?$filter=Number eq {ItemGroup}"

        payload = {}

        headers = {
            'Cookie': f'B1SESSION={self.session}; CompanyDB={self.CompanyDB}; '
            f'ROUTEID={self.routeID}'
        }
        logging.info(f'Obtenemos el ItemGroup usando: {url}')
        response = self.peticionSAP("GET", url, msg="Ha ocurrido un error"
                                    " comunicandose con SAP en "
                                    "ExcelToSAP al obtener item group",
                                    headers=headers,
                                    data=payload,
                                    verify=False)
        res = json.loads(response.text)
        return res

    def getStockArticulo(self, ItemCode):
        """
        Obtenemos los datos del articulo en la api de sap.

        :param ItemCode: Codigo de Item
        :return: Respuesta de la consulta
        """

        url = self.ip+f"/b1s/v1/Items('{ItemCode}')"\
            "?$select=ItemWarehouseInfoCollection"

        payload = {}

        headers = {
            'Cookie': f'B1SESSION={self.session}; CompanyDB={self.CompanyDB}; '
            f'ROUTEID={self.routeID}'
        }
        logging.info(
            f'Obtenemos los datos del ItemCode seleccionado usando: {url}')
        response = self.peticionSAP("GET", url, msg="Ha ocurrido un error"
                                    " comunicandose con SAP en "
                                    "ExcelToSAP al obtener item",
                                    headers=headers,
                                    data=payload,
                                    verify=False)
        res = json.loads(response.content)
        return res

    def getWarehouses(self):
        """
        Obtenemos los codigos de almacenes en la api de sap.

        :return: Respuesta de la consulta
        """

        url = self.ip+f"/b1s/v1/Warehouses?$select=WarehouseCode"

        payload = {}

        headers = {
            'Cookie': f'B1SESSION={self.session}; CompanyDB={self.CompanyDB}; '
            f'ROUTEID={self.routeID}'
        }
        logging.info(f'Obtenemos los codigos de almacen usando: {url}')
        response = self.peticionSAP("GET", url, msg="Ha ocurrido un error"
                                    " comunicandose con SAP en "
                                    "ExcelToSAP al obtener WarehouseCode",
                                    headers=headers,
                                    data=payload,
                                    verify=False)
        res = json.loads(response.text)
        return res['value']

    def getDoc(self, doc):
        """
        Obtenemos los codigos de documento en la api de sap.

        :param doc: Numero de documento
        :return: Respuesta de la consulta
        """

        url = self.ip + \
            f"/b1s/v1/InventoryGenEntries?$filter=DocNum eq {doc}&$select=DocEntry"

        payload = {}

        headers = {
            'Cookie': f'B1SESSION={self.session}; CompanyDB={self.CompanyDB}; '
            f'ROUTEID={self.routeID}'
        }
        logging.info(f'Obtenemos los codigos de almacen usando: {url}')
        response = self.peticionSAP("GET", url, msg="Ha ocurrido un error"
                                    " comunicandose con SAP en "
                                    "ExcelToSAP al obtener WarehouseCode",
                                    headers=headers,
                                    data=payload,
                                    verify=False)
        res = json.loads(response.text)
        return res['value']

    def InventoryGen(self, cab, detalles, items, item_groups, sheet_name):
        """
        Creamos la entrada de mercancía.

        :param cab: Datos de la cabecera
        :param detalles: Datos de los detalles
        :param items: Datos de los items
        :param item_groups: Datos de los item_groups
        """

        DocumentLines = ''
        if sheet_name == 'det_entrada_ent_merc':
            url = self.ip+"/b1s/v1/InventoryGenEntries"
            payload = '{'\
                f'"DocDueDate": "{cab["fecha_contabilizacion"]}",'\
                f'"Comments": "Entrada de mercancia creada automaticamente por ExcelToSAP",'\
                f'"DocumentLines": ['
            contador = 0
            print(detalles)
            for i, row in detalles.iterrows():
                contador += 1
                warehouse = int(row["almacen"])
                if warehouse < 10:
                    whs = f'0{warehouse}'
                else:
                    whs = f'{warehouse}'
                cantidad = float(row["cantidad"])
                precio = float(row["precio/unidad"])
                batch = row["lote"]

                for j in items:
                    if j['ItemCode'] == row['num articulo']:
                        item = j
                        break

                DocumentLines += '{'\
                    f'"ItemCode": "{item["ItemCode"]}",'\
                    f'"ItemDescription": "{item["ItemName"]}",'\
                    f'"Quantity": {cantidad},'\
                    f'"Price": {precio},'\
                    f'"WarehouseCode": "{whs}",'\
                    f'"AccountCode": "610000",'\
                    f'"CostingCode": "{item_groups[item["ItemCode"]]["U_AdvLinNeg"]}",'\
                    f'"MeasureUnit": "{item["InventoryUOM"]}",'\
                    f'"UnitPrice": {precio},'\
                    f'"UoMCode": "Manual",'\
                    f'"U_ADV_ESTDO": "P",'\
                    f'"U_ADV_USDLB": 0.0,'\
                    f'"U_ADV_TUSDLB": 0.0,'\
                    f'"U_ADV_TipReS": "Consumo",'\
                    f'"U_ADVTIC_CtdApli": "R",'\
                    f'"U_ADVTIC_TIPREP": "NO PROCEDE",'\
                    f'"U_ADVTIC_Muestra": "Recogida muestra",'\
                    f'"U_ADVTIC_Ficha": "Y",'\
                    f'"U_ADVTIC_EnContrato": "N",'\
                    f'"U_ADVTIC_LAME": "N",'\
                    f'"BatchNumbers": ['\
                    '{'\
                    f'"BatchNumber": "{batch}",'\
                    f'"Quantity": {cantidad}'\
                    '}]'
                if contador != len(detalles):
                    DocumentLines += '},'
                else:
                    DocumentLines += '}]}'
        elif sheet_name == 'det_salida_sal_merc':
            url = self.ip+"/b1s/v1/InventoryGenExits"
            payload = '{'\
                f'"DocDueDate": "{cab["fecha_contabilizacion"]}",'\
                f'"Comments": "Salida de mercancia creada automaticamente por ExcelToSAP",'\
                f'"DocumentLines": ['
            contador = 0
            for i, row in detalles.iterrows():
                contador += 1
                print(detalles, len(detalles))
                print(row)
                warehouse = int(row["almacen"])
                if warehouse < 10:
                    whs = f'0{warehouse}'
                else:
                    whs = f'{warehouse}'
                cantidad = float(row["cantidad"])
                batch = row["lote"]

                for j in items:
                    if j['ItemCode'] == row['num articulo']:
                        item = j
                        break

                DocumentLines += '{'\
                    f'"ItemCode": "{item["ItemCode"]}",'\
                    f'"ItemDescription": "{item["ItemName"]}",'\
                    f'"Quantity": {cantidad},'\
                    f'"Price": 0.0,'\
                    f'"WarehouseCode": "{whs}",'\
                    f'"AccountCode": "610000",'\
                    f'"CostingCode": "{item_groups[item["ItemCode"]]["U_AdvLinNeg"]}",'\
                    f'"MeasureUnit": "{item["InventoryUOM"]}",'\
                    f'"UnitPrice": 0.0,'\
                    f'"UoMCode": "Manual",'\
                    f'"U_ADV_ESTDO": "P",'\
                    f'"U_ADV_USDLB": 0.0,'\
                    f'"U_ADV_TUSDLB": 0.0,'\
                    f'"U_ADV_TipReS": "Consumo",'\
                    f'"U_ADVTIC_CtdApli": "R",'\
                    f'"U_ADVTIC_TIPREP": "NO PROCEDE",'\
                    f'"U_ADVTIC_Muestra": "Recogida muestra",'\
                    f'"U_ADVTIC_Ficha": "Y",'\
                    f'"U_ADVTIC_EnContrato": "N",'\
                    f'"U_ADVTIC_LAME": "N",'\
                    f'"BatchNumbers": ['\
                    '{'\
                    f'"BatchNumber": "{batch}",'\
                    f'"Quantity": {cantidad}'\
                    '}]'
                if contador != len(detalles):
                    DocumentLines += '},'
                else:
                    DocumentLines += '}]}'
        payload += DocumentLines
        aux = json.dumps(payload)
        payload = json.loads(aux)
        print(payload)

        headers = {
            'Cookie': f'B1SESSION={self.session}; CompanyDB={self.CompanyDB}; '
            f'ROUTEID={self.routeID}'
        }
        logging.info(f'Creamos la entrada de mercancia usando: {url}')
        response = self.peticionSAP("POST", url, headers=headers, data=payload,
                                    verify=False,
                                    msg="Fallo creando la entrada"
                                    " de mercancía")
        if response.status_code > 210 or response.status_code < 200:
            error = json.loads(response.content)
            raise Exception(
                f"Ha habido un error al realizar la creación de la entrada de mercancía -> {error}")
        else:
            return json.loads(response.content)

    def crearItem(self, ItemCode, Nom_Red, Descrip, ean, GrupoImp):
        """
        Esta función se encarga de crear Items en SAP siguiendo el patrón de los items de lotes

        :param ItemCode: Codigo del Item
        :param Nom_Red: Nombre reducido
        :param Descrip: descripción
        :param ean: Código ean del producto
        :param GrupoImp: Grupo Impositivo
        """

        url = self.ip+"/b1s/v1/Items"

        payload = json.dumps({
            "ItemCode": ItemCode,
            "ItemName": Descrip,
            "ForeignName": Nom_Red,
            "ItemsGroupCode": 252,
            "CustomsGroupCode": -1,
            "SalesVATGroup": GrupoImp,
            "BarCode": ean,
            "VatLiable": "tYES",
            "PurchaseItem": "tNO",
            "SalesItem": "tYES",
            "InventoryItem": "tNO",
            "IncomeAccount": None,
            "ExemptIncomeAccount": None,
            "ExpanseAccount": None,
            "Mainsupplier": None,
            "SupplierCatalogNo": None,
            "DesiredInventory": 0,
            "MinInventory": 0,
            "Picture": None,
            "User_Text": None,
            "SerialNum": None,
            "CommissionPercent": 0,
            "CommissionSum": 0,
            "CommissionGroup": 0,
            "TreeType": "iSalesTree",
            "AssetItem": "tNO",
            "DataExportCode": None,
            "Manufacturer": -1,
            "QuantityOnStock": 0,
            "QuantityOrderedFromVendors": 0,
            "QuantityOrderedByCustomers": 0,
            "ManageSerialNumbers": "tNO",
            "ManageBatchNumbers": "tNO",
            "Valid": "tYES",
            "ValidFrom": None,
            "ValidTo": None,
            "ValidRemarks": None,
            "Frozen": "tNO",
            "FrozenFrom": None,
            "FrozenTo": None,
            "FrozenRemarks": None,
            "SalesUnit": None,
            "SalesItemsPerUnit": 1,
            "SalesPackagingUnit": "",
            "SalesQtyPerPackUnit": 1,
            "SalesUnitLength": 0,
            "SalesLengthUnit": None,
            "SalesUnitWidth": 0,
            "SalesWidthUnit": None,
            "SalesUnitHeight": 0,
            "SalesHeightUnit": None,
            "SalesUnitVolume": 0,
            "SalesVolumeUnit": 2,
            "SalesUnitWeight": 0,
            "SalesWeightUnit": None,
            "PurchaseUnit": None,
            "PurchaseItemsPerUnit": 1,
            "PurchasePackagingUnit": "",
            "PurchaseQtyPerPackUnit": 1,
            "PurchaseUnitLength": 0,
            "PurchaseLengthUnit": None,
            "PurchaseUnitWidth": 0,
            "PurchaseWidthUnit": None,
            "PurchaseUnitHeight": 0,
            "PurchaseHeightUnit": None,
            "PurchaseUnitVolume": 0,
            "PurchaseVolumeUnit": 2,
            "PurchaseUnitWeight": 0,
            "PurchaseWeightUnit": None,
            "PurchaseVATGroup": "S3",
            "SalesFactor1": 1,
            "SalesFactor2": 1,
            "SalesFactor3": 1,
            "SalesFactor4": 1,
            "PurchaseFactor1": 1,
            "PurchaseFactor2": 1,
            "PurchaseFactor3": 1,
            "PurchaseFactor4": 1,
            "MovingAveragePrice": 0,
            "ForeignRevenuesAccount": None,
            "ECRevenuesAccount": None,
            "ForeignExpensesAccount": None,
            "ECExpensesAccount": None,
            "AvgStdPrice": 0,
            "DefaultWarehouse": None,
            "ShipType": 1,
            "GLMethod": "glm_ItemClass",
            "TaxType": "tt_Yes",
            "MaxInventory": 0,
            "ManageStockByWarehouse": "tYES",
            "PurchaseHeightUnit1": None,
            "PurchaseUnitHeight1": 0,
            "PurchaseLengthUnit1": None,
            "PurchaseUnitLength1": 0,
            "PurchaseWeightUnit1": None,
            "PurchaseUnitWeight1": 0,
            "PurchaseWidthUnit1": None,
            "PurchaseUnitWidth1": 0,
            "SalesHeightUnit1": None,
            "SalesUnitHeight1": 0,
            "SalesLengthUnit1": None,
            "SalesUnitLength1": 0,
            "SalesWeightUnit1": None,
            "SalesUnitWeight1": 0,
            "SalesWidthUnit1": None,
            "SalesUnitWidth1": 0,
            "ForceSelectionOfSerialNumber": "tYES",
            "ManageSerialNumbersOnReleaseOnly": "tNO",
            "WTLiable": "tYES",
            "CostAccountingMethod": "bis_MovingAverage",
            "SWW": None,
            "WarrantyTemplate": "",
            "IndirectTax": "tNO",
            "ArTaxCode": None,
            "ApTaxCode": None,
            "BaseUnitName": None,
            "ItemCountryOrg": None,
            "IssueMethod": "im_Backflush",
            "SRIAndBatchManageMethod": "bomm_OnEveryTransaction",
            "IsPhantom": "tNO",
            "InventoryUOM": "",
            "PlanningSystem": "bop_None",
            "ProcurementMethod": "bom_Buy",
            "ComponentWarehouse": "bomcw_BOM",
            "OrderIntervals": None,
            "OrderMultiple": 0,
            "LeadTime": None,
            "MinOrderQuantity": 0,
            "ItemType": "itItems",
            "ItemClass": "itcMaterial",
            "OutgoingServiceCode": -1,
            "IncomingServiceCode": -1,
            "ServiceGroup": -1,
            "NCMCode": -1,
            "MaterialType": "mt_FinishedGoods",
            "MaterialGroup": -1,
            "ProductSource": "",
            "Properties1": "tYES",
            "Properties2": "tNO",
            "Properties3": "tNO",
            "Properties4": "tNO",
            "Properties5": "tNO",
            "Properties6": "tNO",
            "Properties7": "tNO",
            "Properties8": "tNO",
            "Properties9": "tNO",
            "Properties10": "tNO",
            "Properties11": "tNO",
            "Properties12": "tNO",
            "Properties13": "tNO",
            "Properties14": "tNO",
            "Properties15": "tNO",
            "Properties16": "tNO",
            "Properties17": "tNO",
            "Properties18": "tNO",
            "Properties19": "tNO",
            "Properties20": "tNO",
            "Properties21": "tNO",
            "Properties22": "tNO",
            "Properties23": "tNO",
            "Properties24": "tNO",
            "Properties25": "tNO",
            "Properties26": "tNO",
            "Properties27": "tNO",
            "Properties28": "tNO",
            "Properties29": "tNO",
            "Properties30": "tNO",
            "Properties31": "tNO",
            "Properties32": "tNO",
            "Properties33": "tNO",
            "Properties34": "tNO",
            "Properties35": "tNO",
            "Properties36": "tNO",
            "Properties37": "tNO",
            "Properties38": "tNO",
            "Properties39": "tNO",
            "Properties40": "tNO",
            "Properties41": "tNO",
            "Properties42": "tNO",
            "Properties43": "tNO",
            "Properties44": "tNO",
            "Properties45": "tNO",
            "Properties46": "tNO",
            "Properties47": "tNO",
            "Properties48": "tNO",
            "Properties49": "tNO",
            "Properties50": "tNO",
            "Properties51": "tNO",
            "Properties52": "tNO",
            "Properties53": "tNO",
            "Properties54": "tNO",
            "Properties55": "tNO",
            "Properties56": "tNO",
            "Properties57": "tNO",
            "Properties58": "tNO",
            "Properties59": "tNO",
            "Properties60": "tNO",
            "Properties61": "tNO",
            "Properties62": "tNO",
            "Properties63": "tNO",
            "Properties64": "tNO",
            "AutoCreateSerialNumbersOnRelease": "tNO",
            "DNFEntry": -1,
            "GTSItemSpec": None,
            "GTSItemTaxCategory": None,
            "FuelID": None,
            "BeverageTableCode": "",
            "BeverageGroupCode": "",
            "BeverageCommercialBrandCode": None,
            "Series": 3,
            "ToleranceDays": None,
            "TypeOfAdvancedRules": "toarGeneral",
            "IssuePrimarilyBy": "ipbSerialAndBatchNumbers",
            "NoDiscounts": "tNO",
            "AssetClass": "",
            "AssetGroup": "",
            "InventoryNumber": "",
            "Technician": None,
            "Employee": None,
            "Location": None,
            "AssetStatus": "New",
            "CapitalizationDate": None,
            "StatisticalAsset": "tNO",
            "Cession": "tNO",
            "DeactivateAfterUsefulLife": "tNO",
            "ManageByQuantity": "tNO",
            "UoMGroupEntry": -1,
            "InventoryUoMEntry": -1,
            "DefaultSalesUoMEntry": None,
            "DefaultPurchasingUoMEntry": None,
            "DepreciationGroup": None,
            "AssetSerialNumber": "",
            "InventoryWeight": 0,
            "InventoryWeightUnit": None,
            "InventoryWeight1": 0,
            "InventoryWeightUnit1": None,
            "DefaultCountingUnit": None,
            "CountingItemsPerUnit": 1,
            "DefaultCountingUoMEntry": None,
            "Excisable": "tNO",
            "ChapterID": -1,
            "ScsCode": None,
            "SpProdType": None,
            "ProdStdCost": 0,
            "InCostRollup": "tYES",
            "VirtualAssetItem": "tNO",
            "EnforceAssetSerialNumbers": "tNO",
            "AttachmentEntry": None,
            "LinkedResource": None,
            "UpdateDate": "2022-07-28",
            "UpdateTime": "12:13:32",
            "GSTRelevnt": "tNO",
            "SACEntry": -1,
            "GSTTaxCategory": "gtc_Regular",
            "ServiceCategoryEntry": -1,
            "CapitalGoodsOnHoldPercent": 0,
            "CapitalGoodsOnHoldLimit": 0,
            "AssessableValue": 0,
            "AssVal4WTR": 0,
            "SOIExcisable": "se_NotExcisable",
            "TNVED": None,
            "ImportedItem": "tNO",
            "PricingUnit": -1,
            "CreateDate": "2022-07-27",
            "CreateTime": "15:53:17",
            "U_ADV_PROD": "PACK",
            "U_ADV_CASC": None,
            "U_ADV_ORIG": "",
            "U_ADV_ESTA": None,
            "U_ADV_PIEL": None,
            "U_ADV_VARI": "",
            "U_ADV_CAL1": "",
            "U_ADV_CAL2": "",
            "U_ADV_CALI": "",
            "U_ADV_ENVA": "",
            "U_AdvEnlPCa": None,
            "U_ADV_HSCOD": None,
            "U_ADV_TARCOD": None,
            "U_AdvNivOrg": "NO ORGANIC",
            "U_AdvProToF": "",
            "U_AdvProSal": "NO SALTED",
            "U_AdvNomMed": None,
            "U_AdvSegPer": None,
            "U_AdvSegImp": 0,
            "U_AdvSegSin": None,
            "U_AdvEnlVer": None,
            "U_AdvTipRep": "NO PROCEDE",
            "U_ADVTIC_CategoriaId": None,
            "U_ADVTIC_Visible": "Y",
            "U_ADVTIC_VisibleCont": "N",
            "U_ADVTIC_PaisWeb": None,
            "U_ADVTIC_Tienda": "N"
        })
        headers = {
            'Cookie': f'B1SESSION={self.session};'
            f' CompanyDB={self.CompanyDB};'
            f' ROUTEID={self.routeID}'
        }

        response = self.peticionSAP("POST", url, headers=headers,
                                    data=payload, verify=False,
                                    msg="Ha ocurrido un error creando items")
        if response.status_code < 200 or response.status_code > 210:
            error = json.loads(response.content)
            raise Exception(f"Ha ocurrido un error al crear Item: {response.status_code} => {error}")

    def crearProductTree(self, item):
        """
        Creamos la lista de materiales

        :param item: Diccionario con las caracteristicas de la lista de materiales
        """

        url = self.ip+"/b1s/v1/ProductTrees"
        payload_dict = {
            "TreeCode": item['ItemCode_Lote'],
            "TreeType": "iSalesTree",
            "Quantity": 1,
            "DistributionRule": "LIN076",
            "Project": None,
            "DistributionRule2": item['Canal'],
            "DistributionRule3": "",
            "DistributionRule4": "",
            "DistributionRule5": "",
            "PriceList": 1,
            "PlanAvgProdSize": 1,
            "HideBOMComponentsInPrintout": "tNO",
            "ProductDescription": item['ItemCode_Lote'],
            "ProductTreeStages": []
        }
        aux_list = []
        int_aux = 0
        for i in item['Items']:
            aux_list.append(
                {
                    "ItemCode": i['ItemCode'],
                    "Quantity": i['Cantidad'],
                    "Warehouse": i['Almacen'],
                    "Price": 0.0,
                    "Currency": "EUR",
                    "IssueMethod": "im_Manual",
                    "InventoryUOM": None,
                    "Comment": None,
                    "ParentItem": item['ItemCode_Lote'],
                    "PriceList": 1,
                    "DistributionRule": None,
                    "Project": None,
                    "DistributionRule2": item['Canal'],
                    "DistributionRule3": None,
                    "DistributionRule4": None,
                    "DistributionRule5": None,
                    "WipAccount": None,
                    "ItemType": "pit_Item",
                    "LineText": None,
                    "AdditionalQuantity": 0,
                    "StageID": None,
                    "ChildNum": int_aux,
                    "VisualOrder": int_aux
                }
            )
            int_aux += 1
        payload_dict['ProductTreeLines'] = aux_list
        headers = {
            'Cookie': f'B1SESSION={self.session};'
            f' CompanyDB={self.CompanyDB};'
            f' ROUTEID={self.routeID}'
        }

        response = self.peticionSAP("POST", url, headers=headers,
                                    data=json.dumps(payload_dict),
                                    verify=False,
                                    msg="Ha ocurrido un error creand"
                                    "o un ProductTree")
        if response.status_code < 200 or response.status_code > 210:
            error = json.loads(response.content)
            raise Exception(f"Ha ocurrido un error al crear ProductTrees: {response.status_code} => {error}")

    def post_order(self, payload):
        """
        Creamos el pedido de cliente

        :param payload: json de entrada para la peticion
        """

        url = self.ip+"/b1s/v1/Orders"
        cliente = payload['CardCode']
        payload = str(payload).replace("'", '"')
        aux = json.dumps(payload)
        payload = json.loads(aux)
        print(payload)
        headers = {
            'Cookie': f'B1SESSION={self.session};'
            f' CompanyDB={self.CompanyDB};'
            f' ROUTEID={self.routeID}'
        }

        response = self.peticionSAP("POST", url, headers=headers,
                                    data=payload,
                                    verify=False,
                                    msg="")
        if response.status_code < 200 or response.status_code > 210:
            error = json.loads(response.content)
            print(error)
            raise Exception(
                f"Ha ocurrido un error al crear el pedido de {cliente}: {response.status_code} => {error}"
            )
        else:
            return json.loads(response.content)
