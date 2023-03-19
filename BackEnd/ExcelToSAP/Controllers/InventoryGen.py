from datetime import datetime
from openpyxl import load_workbook
from common.coms.hanaconnect import conectar_HanaDB
from common.coms.sap_coms import *
from ExcelToSAP.coms.hanaconnect import dictToSQLUpdate
import configparser
from common.encrypt.codeDecode import cargar_clave, desencriptar_items
import logging
import os
from time import sleep
import pandas as pd
from flask import request


def gestionErrores(conn, articulo, sheet_name):
    """
    Gestiona los errores de los detalles del articulo.

    :param conn: Conexión con SAP
    :param articulo: Datos del articulo en el excel
    :param sheet_name: Nombre de la hoja
    :return: Lista con errores encontrados
    """

    errores = []
    almacenes = []
    cantidad = 0

    if pd.isnull(articulo['cantidad']):
        errores.append(f'La cantidad debe estar puesta en el excel')
    if sheet_name == 'det_entrada_ent_merc':
        if pd.isnull(articulo['precio/unidad']):
            errores.append(f'El precio/unidad debe estar puesto en el excel')
        if pd.isnull(articulo['almacen']):
            errores.append(f'Revise que el campo almacen esté relleno')
    else:
        stock = conn.getStockArticulo(articulo['num articulo'])[
            'ItemWarehouseInfoCollection']
        if pd.isnull(articulo['almacen']):
            errores.append(f'Revise que el campo almacen esté relleno')
        else:
            for i in stock:
                if int(i['WarehouseCode']) == int(articulo['almacen']):
                    if i['InStock'] == 0:
                        errores.append(f'Compruebe que el lote {articulo["lote"]} '
                                       f'corresponda al item {articulo["num articulo"]}, '
                                       f'porque el stock en ese almacén es {i["InStock"]}')
                    cantidad = i['InStock']
                    break
        if cantidad:
            if not pd.isnull(articulo['cantidad']):
                if articulo['cantidad'] > cantidad:
                    errores.append(f'El lote {articulo["lote"]} no dispone de cantidad suficiente'
                                   f' para la cantidad que ha establecido en'
                                   f' el item {articulo["num articulo"]}')
        else:
            errores.append(f'Comprueba que exista el lote {articulo["lote"]}'
                           f' para el item {articulo["num articulo"]}')
    if pd.isnull(articulo['lote']):
        errores.append(f'El lote debe estar puesto en el excel')
    # Comprobamos que el almacen existe en nuestra lista de almacenes
    warehouse = conn.getWarehouses()
    for i in warehouse:
        almacenes.append(int(i['WarehouseCode']))
    if articulo['almacen'] not in almacenes:
        errores.append(
            'El almacen introducido en el Excel no fue encontrado en nuestra lista de almacenes, Reviselo.')

    return errores


def comprobar_excel(cabecera, detalles, sheets, conn):
    """
    Comprueba el excel en busca de errores, reune todos los errores y los devuelve en forma de lista.

    :param cabecera: Cabecera del excel
    :param detalles: Detalles del excel
    :param sheets: Lista con el numero de hojas
    :param conn: Conexión a SAP
    :return: Lista de errores
    """

    errores = []
    for i, cab in cabecera.iterrows():
        # Cogemos el primer numero de documento y toda su
        # información en el excel
        DocNum = cab['numero documento']
        # Gestionamos errores
        if pd.isnull(cab['fecha_contabilizacion']):
            errores.append(
                f'El campo fecha_contabilizacion tiene que estar rellenado')
        articulos = detalles[detalles['num_documento'] == DocNum]
        if articulos.empty:
            errores.append(f'No existe el documento {DocNum} en detalles.')
        for i, articulo in articulos.iterrows():
            if pd.isnull(articulo['num articulo']):
                errores.append(
                    'El campo num articulo tiene que estar rellenado en el excel')
                return str(errores)
            errores2 = gestionErrores(
                conn, articulo, sheets[1])
            if errores2:
                for i in errores2:
                    errores.append(i)
            item = conn.getArticulo(articulo['num articulo'])
            if not item['value']:
                errores.append(f'No existe el item {articulo["num articulo"]}')
            else:
                medida = item['value'][0]['InventoryUOM']
                if str(medida) != 'Kg':
                    errores.append(
                        f'El Item {articulo["num articulo"]} no tiene '
                        'unidad de medida o la unidad de medida no es Kg.')
            itemGroup = conn.getArticuloGroup(
                item['value'][0]['ItemsGroupCode'])
            if not itemGroup['value']:
                errores.append(f'No existe el itemGroup')
    return errores


def excel_to_SAPAPI(database, sheets):
    """
    Cuerpo del ExcelToSAP para entrada y salida de mercancías. Abrimos el archivo de excel, leemos los datos y
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
            detalles = pd.read_excel(
                './tempExcelToSAPSistemas.xlsx', sheet_name=sheets[1])

            errores = comprobar_excel(cabecera, detalles, sheets, conn)
            if errores:
                return -1, str(errores)
            for i, cab in cabecera.iterrows():
                # Cogemos el primer numero de documento y toda su
                # información en el excel
                DocNum = cab['numero documento']
                articulos = detalles[detalles['num_documento'] == DocNum]
                if articulos.empty:
                    return -1, f'No existe el documento {DocNum} en detalles.'
                items = []
                item_groups = {}
                for i, articulo in articulos.iterrows():
                    item = conn.getArticulo(articulo['num articulo'])
                    if not item['value']:
                        return -1, f'No existe el item {articulo["num articulo"]}'
                    itemGroup = conn.getArticuloGroup(
                        item['value'][0]['ItemsGroupCode'])
                    if not itemGroup['value']:
                        return -1, f'No existe el itemGroup'
                    items.append(item['value'][0])
                    item_groups[item['value'][0]['ItemCode']
                                ] = itemGroup['value'][0]
                res = conn.InventoryGen(
                    cab, articulos, items, item_groups, sheets[1])
                # Preparamos la respuesta
                elemento = {'DocEntry': res['DocEntry'],
                            'DocNum': res['DocNum'],
                            }
                docLine = []
                for i in res['DocumentLines']:
                    item_code = i['ItemCode']
                    item_description = i['ItemDescription']
                    docLine.append({
                        'ItemCode': item_code,
                        'ItemDescription': item_description
                    })
                elemento['DocumentLines'] = docLine
                actualizados.append(elemento)
            return 1, actualizados

    except Exception as e:
        logging.error(f'Ha habido un error: {e}')
        return -1, [e, actualizados]
