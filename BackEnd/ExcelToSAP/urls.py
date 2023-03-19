from flask import Flask, Blueprint, render_template, request,\
    jsonify, config, redirect, send_file, url_for, send_from_directory
import os
from ExcelToSAP.Controllers.LoadData import excel_to_hanadb
from ExcelToSAP.Controllers.InventoryGen import excel_to_SAPAPI
from ExcelToSAP.coms.hanaconnect import *
import traceback
from time import sleep
from ExcelToSAP.Controllers.LoadData import borraArchivoTemp
from ExcelToSAP.Controllers.GetColumns import *
from common.urls import token_required
import logging
import pandas as pd
from datetime import datetime, date
from ExcelToSAP.Controllers.LoadData_pack import *
from ExcelToSAP.Controllers.pedidos import *

excelToSAP = Blueprint('excelToSAP', __name__)


logging.basicConfig(level=logging.DEBUG, filename='debug.log',
                    format='%(asctime)s: %(levelname)s: %(message)s')


@excelToSAP.route("/ExcelToSAP/uploadFile")
@token_required
def upload_file():
    """
    Endpoint de /ExcelToSAP/uploadFile que sirve devuelve un formulario de
    entrada para subir el Excel. El método debe ser GET, en caso contrario,
    lanzará un error 404
    """
    # renderiamos la plantilla "formulario.html"
    return render_template('formulario.html')


@excelToSAP.route("/ExcelToSAP/LoadData", methods=['POST'])
@token_required
def uploader():
    """
    Endpoint de /ExcelToSAP/LoadData que crea una copia del excel subido,
    y lo actualiza en SAP. El método debe ser POST, en caso contrario,
    lanzará un error 405
    """
    if request.method == 'POST':
        # obtenemos el archivo del input "archivo"
        database = request.values['database']
        id_usuario = request.values['id_usuario']

        logging.info(
            f"Nueva conexion establecida a /ExcelToSAP/LoadData por el usuario {id_usuario}")
        f = request.files['archivo']
        if (f):
            if f.filename.split('.')[1] == 'xlsx':
                try:
                    # Comprobamos que no se este ejecutando el programa
                    # mediante la comprobación de la existencia del archivo
                    #  temporal
                    comprobador = 0
                    fileTempName = './tempExcelToSAP.xlsx'
                    if os.path.isfile(fileTempName):
                        logging.info(
                            'Archivo temporal tempExcelToSAP.xlsx en uso, espere.')
                        comprobador = 1
                        print('El programa esta en ejecución, espere...')
                        for i in range(1, 4):
                            if os.path.isfile(fileTempName):
                                print(
                                    f"(AVISO {i}) El programa esta en ejecución, espere...")
                                sleep(10)
                            else:
                                comprobador = 0
                                break
                        if comprobador == 1:
                            return jsonify({'Codigo': -1, 'description': 'El programa ya está '
                                            'siendo ejecutado, espere unos segundos.'}), 400
                    else:
                        pass
                except Exception as e:
                    borraArchivoTemp(fileTempName)
                    return jsonify({'Codigo': -1, 'description': e}), 400
                try:
                    f.save(os.path.join('.', 'tempExcelToSAP.xlsx'))
                    dataExcel = excel_to_hanadb(database)
                    if dataExcel[0] == 1:
                        borraArchivoTemp(fileTempName)
                        logging.info('Se ha ejecutado correctamente')
                        return jsonify({'Codigo': 1, 'data': dataExcel[1],
                                        'description': 'Los datos han sido '
                                        'actualizados.'}), 200
                    else:
                        logging.error(
                            f'Se ha producido un error: {dataExcel[1]}')
                        borraArchivoTemp(fileTempName)
                        return jsonify({'Codigo': -1, 'description': dataExcel[1]}), 400

                except Exception as e:
                    logging.error(f'Se ha producido un error: {e}')
                    borraArchivoTemp(fileTempName)
                    return jsonify({'Codigo': -1, 'description': f'Ha ocurrido un '
                                    f'fallo: {e}'}), 400
            else:
                msg = f"El archivo {f.filename} no es un Excel valido (.xlsx)"
                logging.error(f'Se ha producido un error: {msg}')
                borraArchivoTemp(fileTempName)
                return jsonify({'Codigo': -1,
                                'description': f'Ha ocurrido un '
                                f'fallo: {msg}'}), 400
        else:
            logging.error(
                f'Se ha producido un error: Debe subir un archivo Excel')
            borraArchivoTemp(fileTempName)
            return jsonify({'Codigo': -1,
                            'description': 'Debe subir un archivo Excel'}), 400


@excelToSAP.route("/ExcelToSAP/sistema", methods=['POST'])
@token_required
def sistema():
    """
    Endpoint de /ExcelToSAP/sistema que actualiza SAP 
    usando su API. El método debe ser POST, en caso contrario,
    lanzará un error 400
    """
    if request.method == 'POST':
        # obtenemos el archivo del input "archivo"
        database = request.values['database']
        id_usuario = request.values['id_usuario']

        logging.info(
            f'Nueva conexion establecida a /ExcelToSAP/sistema por el usuario {id_usuario}')

        f = request.files['archivo']
        if (f):
            if f.filename.split('.')[1] == 'xlsx':
                try:
                    # Comprobamos que no se este ejecutando el programa
                    # mediante la comprobación de la existencia del archivo
                    #  temporal
                    comprobador = 0
                    fileTempName = './tempExcelToSAPSistemas.xlsx'
                    if os.path.isfile(fileTempName):
                        logging.info(
                            'Archivo temporal tempExcelToSAP.xlsx en uso, espere.')
                        comprobador = 1
                        print('El programa esta en ejecución, espere...')
                        for i in range(1, 4):
                            if os.path.isfile(fileTempName):
                                print(
                                    f"(AVISO {i}) El programa esta en ejecución, espere...")
                                sleep(10)
                            else:
                                comprobador = 0
                                break
                        if comprobador == 1:
                            return jsonify({'Codigo': -1, 'description': 'El programa ya está '
                                            'siendo ejecutado, espere unos segundos.'}), 400
                    else:
                        pass
                except Exception as e:
                    borraArchivoTemp(fileTempName)
                    return jsonify({'Codigo': -1, 'description': e}), 400
                try:
                    fileTempName = './tempExcelToSAPSistemas.xlsx'
                    f.save(os.path.join('.', 'tempExcelToSAPSistemas.xlsx'))
                    xls = pd.ExcelFile(fileTempName)
                    sheets = xls.sheet_names
                    xls.close()
# -------------------------------------------------------------------------------------------------------------------------
                    # Caso en el que el excel es de entrada/salida de mercancía
                    if 'det_entrada_ent_merc' in sheets or 'det_salida_sal_merc' in sheets:
                        codigo, res = excel_to_SAPAPI(database, sheets)
                        if codigo < 0:
                            print(str(type(res)))
                            if 'list' in str(type(res)):
                                borraArchivoTemp(fileTempName)
                                logging.error(
                                    f'Se ha producido un error: {res[0]}\n'
                                    f'Se han actualizado: {res[1]}'
                                )
                                return jsonify({'Codigo': -1, 'description': 'Ha ocurrido un '
                                                f'error durante la ejecución: {res[0]}',
                                                'Actualizados': res[1]}), 400
                            else:
                                borraArchivoTemp(fileTempName)
                                logging.info(
                                    f'Se ha producido un error: {res}')
                                return jsonify({'Codigo': -1, 'description': f'{res}'})
                        else:
                            logging.info('Se ha ejecutado correctamente')
                            borraArchivoTemp(fileTempName)
                            return jsonify({'Codigo': 1, 'description': res}), 200
# -------------------------------------------------------------------------------------------------------------------------
                    # Caso en el que el excel es de packs
                    elif 'Items' in sheets and 'ProductTrees' in sheets:
                        codigo, res = excel_to_hanadb_pack(database)
                        if codigo < 0:
                            borraArchivoTemp(fileTempName)
                            return jsonify({'Codigo': -1,
                                            'description': f'{res}, revise el excel.'}), 400
                        else:
                            borraArchivoTemp(fileTempName)
                            return jsonify({'Codigo': 1, 'description':
                                            'Los datos se han actualizado correctamente'})
# -------------------------------------------------------------------------------------------------------------------------
                    # Caso en el que el excel sea un pedido
                    elif 'cab_pedidos' in sheets and 'det_pedidos' in sheets:
                        codigo, res = excel_to_SAP_pedidos(database, sheets)
                        if codigo < 0:
                            print(str(type(res)))
                            if 'list' in str(type(res)):
                                borraArchivoTemp(fileTempName)
                                logging.error(
                                    f'Se ha producido un error: {res[0]}\n'
                                    f'Se han actualizado: {res[1]}'
                                )
                                return jsonify({'Codigo': -1, 'description': 'Ha ocurrido un '
                                                f'error durante la ejecución: {res[0]}',
                                                'Actualizados': res[1]}), 400
                            else:
                                borraArchivoTemp(fileTempName)
                                logging.info(
                                    f'Se ha producido un error: {res}')
                                return jsonify({'Codigo': -1, 'description': f'{res}'})
                        else:
                            return jsonify({'Codigo': 1, 'description': res}), 200
                    else:
                        logging.error(
                            'El excel de entrada no es válido'
                        )
                        borraArchivoTemp(fileTempName)
                        return jsonify({'Codigo': -1, 'description': f'El excel de entrada '
                                        'no es válido.'}), 400

                except Exception as e:
                    logging.error(
                        f'Se ha producido un error en la ejecucion: {e}')
                    borraArchivoTemp(fileTempName)
                    return jsonify({'Codigo': -1, 'description': f'Ha ocurrido un '
                                    f'fallo: {e}'}), 400
            else:
                msg = f"El archivo {f.filename} no es un Excel valido (.xlsx)"
                logging.error(
                    msg
                )
                borraArchivoTemp(fileTempName)
                return jsonify({'Codigo': -1,
                                'description': f'Ha ocurrido un '
                                f'fallo: {msg}'}), 400
        else:
            logging.error(f'Se ha producido un error: {e}')
            borraArchivoTemp(fileTempName)
            return jsonify({'Codigo': -1,
                            'description': 'Debe subir un archivo Excel'}), 400


@excelToSAP.route("/ExcelToSAP/GetTemplates", methods=["GET"])
@token_required
def getTemplates():
    """
    EndPoint de /ExcelToSAP/GetTemplates que crea nuestra plantilla de excel segun los datos de entrada, base de datos y tabla. Lo guardará con el nombre 
    de plantilla_{id_microsoft}.xlsx
    """
    try:
        logging.info(
            f'Nueva conexion establecida a /ExcelToSAP/GetTemplates'
        )
        data = request.get_json()
        db = data['database']
        table = data['table']
        id_microsoft = data['id_microsoft']
        res = getColumns(db, table)
        dic = {}
        columns = []
        for i in res:
            columns.append(i[0])
            dic[i[0]] = [i[0]]

        df = pd.DataFrame(columns=columns)
        df1 = pd.DataFrame(dic)
        df = pd.concat([df1, df])
        nombre = f"plantilla_{str(id_microsoft)}.xlsx"
        print(nombre)
        df.to_excel(nombre, sheet_name=table, index=False)
        print('Enviando excel...')
        logging.info('Enviando excel...')
        return send_file(nombre, as_attachment=True)
    except Exception as e:
        logging.error(f'Se ha producido un error: {e}')
        return jsonify({'Codigo': -1,
                        'description': e})
