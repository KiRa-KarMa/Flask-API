from datetime import datetime
from ExcelToSAP.coms.hanaconnect import *
from common.coms.hanaconnect import *
from common.coms.sap_coms import *
import configparser
from common.encrypt.codeDecode import cargar_clave, desencriptar_items
import logging
import os
from time import sleep
import pandas as pd
from flask import request


def comprobar_excel(cabecera, detalles):
    """
    Función que comprueba los datos del excel

    :param cabecera: Datos sacados de la hoja del excel Cabecera
    :param detalles: Datos sacados de la hoja del excel Detalles
    :return: lista de errores encontrados
    """
    errores = []
    columnas_cab = list(cabecera.columns)
    columnas_det = list(detalles.columns)
    for i, cab in cabecera.iterrows():
        for col in columnas_cab:
            if col == 'Agente Intermediario' or col == 'Comentarios de fecha':
                continue
            if pd.isnull(cab[col]):
                errores.append(
                    f'El campo {col} tiene que estar rellenado')
        if pd.isnull(cab['ID_Cliente']) is False:
            id_cliente = cab['ID_Cliente']
            articulos = detalles[detalles['Cliente'] == id_cliente]
            print(articulos)
            if articulos.empty:
                errores.append(
                    f'No existe el documento {id_cliente} en detalles.')
            for i, articulo in articulos.iterrows():
                print(articulo)
                for col in columnas_det:
                    if col == 'Numero articulo':
                        if pd.isnull(articulo[col]):
                            errores.append(
                                f'El campo {col} tiene que estar rellenado')
                    excepc = ['Cosecha', 'Num Palets',
                              'Texto libre', 'Canal']
                    if pd.isnull(articulo[col]) and col not in excepc:
                        errores.append(
                            f'El campo {col} tiene que estar rellenado')

    return errores


def preparar_json(cabecera, detalles):
    """
    Función que prepara el json de entrada para la peticion a SAP

    :param cabecera: Datos sacados de la hoja del excel Cabecera
    :param detalles: Datos sacados de la hoja del excel Detalles
    :return: lista de diccionarios con los datos preparados para la peticion
    """
    data = []
    for i, cab in cabecera.iterrows():
        id_cliente = cab['ID_Cliente']
        articulos = detalles[detalles['Cliente'] == id_cliente]

        payload = {
            "CardCode": cab['ID_Cliente'],
            "CardName": cab['Cliente'],
            "U_AdvIncTer": cab['Incoterms'],
            "U_AdvLugInc": cab['Lugar Incoterms'],
            "DocDueDate": cab['Fecha hasta'],
            "U_AdvFecDes": cab['Fecha desde'],
            "DocumentsOwner": int(cab['Comercial Calconut']),
            "Series": int(cab['Num Serie']),
            # faltan num serie y Agente Intermediario
            "DocumentLines": []
        }
        if pd.isnull(cab['Agente Intermediario']) is False:
            payload['SalesPersonCode'] = int(cab['Agente Intermediario'])
        if pd.isnull(cab['Comentarios de fecha']) is False:
            payload['U_AdvComFec'] = cab['Comentarios de fecha']
        for i, art in articulos.iterrows():
            if int(art['Almacen']) < 10:
                warehouse = f"0{art['Almacen']}"
            else:
                warehouse = f"{art['Almacen']}"
            aux = {
                "ItemCode": art['ItemCode'],
                "Quantity": float(art['Cantidad']),
                "UnitPrice": float(art['Precio unidad']),
                "WarehouseCode": warehouse,
                "VatGroup": art['Indicador Impuestos']
            }
            if pd.isnull(art['Num Palets']) is False:
                aux['U_AdvNumPal'] = art['Num Palets']
            if pd.isnull(art['Cosecha']) is False:
                aux['U_ADV_VARI'] = art['Cosecha']
            if pd.isnull(art['Texto libre']) is False:
                aux['FreeText'] = art['Texto libre']
            if pd.isnull(art['Canal']) is False:
                aux['CostingCode2'] = art['Canal']
            payload['DocumentLines'].append(aux)
        data.append(payload)
    return data


def excel_to_SAP_pedidos(database, sheets):
    """
    Cuerpo del ExcelToSAP para pedidos de clientes. Abrimos el archivo de excel, leemos los datos y
    actualizamos SAP.

    :param database: La base de datos que se quiere actualizar.
    :param sheets: Lista con el nombre de las hojas.
    """

    try:
        parser = configparser.ConfigParser()
        parser.read('./config_code.ini')
        sap_data = {}
        db = {}
        actualizados = []
        if parser.has_section('SAP'):
            params = parser.items('SAP')
            for param in params:
                sap_data[param[0]] = (desencriptar_items(param[1],
                                                         cargar_clave()).decode("utf-8"))
        if parser.has_section('bd'):
            params = parser.items('bd')
            for param in params:
                db[param[0]] = (desencriptar_items(param[1],
                                cargar_clave()).decode("utf-8"))

        logging.info('Iniciando sesion en la API de SAP')
        with SAPContextManager(ip=sap_data['ip'], CompanyDB=database,
                               UserName=sap_data['username'],
                               password=sap_data['password']) as conn:

            # Obtenemos los datos que necesitamos de los Items, junto a su linea de negocio
            cabecera = pd.read_excel(
                './tempExcelToSAPSistemas.xlsx', sheet_name=sheets[0])
            cabecera['Fecha hasta'] = pd.to_datetime(
                cabecera['Fecha hasta']).dt.strftime('%Y-%m-%d')
            cabecera['Fecha desde'] = pd.to_datetime(
                cabecera['Fecha desde']).dt.strftime('%Y-%m-%d')
            print(cabecera)
            detalles = pd.read_excel(
                './tempExcelToSAPSistemas.xlsx', sheet_name=sheets[1])
            print(detalles)

            errores = comprobar_excel(cabecera, detalles)
            if errores:
                msg = ''
                for i in errores:
                    msg += f'\n{i}'
                return -1, msg

            data = preparar_json(cabecera, detalles)
            print(data)
            for payload in data:
                response = conn.post_order(payload)
                pedido = {
                    "DocEntry": response['DocEntry'],
                    "DocNum": response['DocNum'],
                    "CardCode": response['CardCode'],
                    "CardName": response['CardName']
                }
                actualizados.append(pedido)

        if len(actualizados) == len(cabecera):
            return 1, 'Se han actualizado todos los elementos'
        else:
            return -1, f'Se han actualizado los primeros {len(actualizados)} elementos'
    except Exception as e:
        print(e)
        logging.error(f"Error: {e}")
        return -1, f'Ha habido un error: {e}'
