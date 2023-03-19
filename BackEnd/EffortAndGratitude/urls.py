from common.urls import token_required
from flask import Blueprint, jsonify, request
import traceback
import logging
import os
from time import sleep
from logging.config import fileConfig
from EffortAndGratitude.Controllers.getPointsEAG import getPointsEAG
from ExcelToSAP.Controllers.LoadData import borraArchivoTemp
from EffortAndGratitude.Controllers.func_herr_effort import *
from EffortAndGratitude.Controllers.enviar_mail_effort import *

EAndGPoints = Blueprint('EAndGPoints', __name__)
fileConfig('logging.cfg')
logging.basicConfig(level=logging.DEBUG, filename='debug.log',
                    format='%(asctime)s: %(levelname)s: %(message)s')

@EAndGPoints.route('/GetUserEAndGPoints', methods=['GET'])
@token_required
def getUserEAndGPoints():
    """
    Endpoint de /GetUserEAndGPoints para mostrar los puntos de Effort && Gratitude. El metodo debe
    ser GET, en caso contrario, lanzará un error 405
    """

    try:
        logging.info("Nueva conexion establecida a /GetUserEAndGPoints")
        data = request.get_json()
        print(data['id_usuario'])
        total_puntos, motivos = getPointsEAG(data['id_usuario'])
        logging.info('Se ha ejecutado correctamente')
        if len(motivos) > 0:
            return jsonify({'Codigo': 1,
                            'puntos': total_puntos,
                            'data': motivos}), 200
        else:
            return jsonify({'Codigo': 1,
                            'puntos': 0,
                            'data': ['No hay puntos todavia.']}), 200
    except Exception as e:
        logging.error(f'Se ha producido un error: {e}')
        return jsonify({'Codigo': -1,
                        'data': [f'Ha ocurrido un fallo: {e}']}), 400


@EAndGPoints.route('/actualizar_bd_effort', methods=['POST'])
@token_required
def actualizar_db_effort():
    id_usuario = request.values['id_usuario']
    logging.info(f"Nueva conexion establecida a /actualizar_bd_effort por {id_usuario}")
    f = request.files['archivo']
    if (f):
        if f.filename.split('.')[1] == 'xlsx':
            try:
                # Comprobamos que no se este ejecutando el programa
                # mediante la comprobación de la existencia del archivo
                #  temporal
                comprobador = 0
                fileTempName = './tempEffort.xlsx'
                if os.path.isfile(fileTempName):
                    logging.info(
                        'Archivo temporal tempEffort.xlsx en uso, espere.')
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
                """EJECICIÓN FUNCIÓN PRINCIPAL ----------------------------------------------------------------------------------------"""
                ruta_excel = os.path.join('.', 'tempEffort.xlsx')
                f.save(ruta_excel)
                msg, mails = actualizar_db(ruta_excel)
                # Se envian los email a los responsables y a la gente que ha recibido el punto.
                borraArchivoTemp(fileTempName)
                if msg:
                    return jsonify({'Codigo': 1, 'description': msg})
            except Exception as e:
                borraArchivoTemp(fileTempName)
                print(e)
                return jsonify(
                    {'Codigo': -1,
                     'description': f'Ha habido un error: {e}'}
                )


@EAndGPoints.route('/mandar_mails_effort', methods=['POST'])
@token_required
def mandar_mails_effort():
    id_usuario = request.values['id_usuario']
    logging.info(f"Nueva conexion establecida a /mandar_mails_effort por {id_usuario}")
    try:
        EnviarEmail_effort()
        return jsonify({
            'Codigo': 1,
            'description': f'Se han mandado los correos'
        })
    except Exception as e:
        return jsonify({
            'Codigo': -1,
            'description': f'Ha habido un error: {e}'
        })
