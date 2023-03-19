from common.urls import token_required
from flask import Blueprint, jsonify, request
import traceback
import logging
from AutoDetox.Controllers.GetCurrentDetox import getDetox
from AutoDetox.Controllers.PostDetox import subirDetox
from AutoDetox.Controllers.getDetoxTabla import *
from logging.config import fileConfig
autoDetox = Blueprint('autoDetox', __name__)
fileConfig('logging.cfg')
logging.basicConfig(level=logging.DEBUG, filename='debug.log',
                    format='%(asctime)s: %(levelname)s: %(message)s')

@autoDetox.route('/AutoDetox/GetCurrentDetox')
@token_required
def listaDetox():
    """
    Endpoint de /listaDetox para mostrar los detox activados. El metodo debe
    ser GET, en caso contrario, lanzará un error 405
    """

    try:
        logging.info(
            f'Nueva conexion establecida a /GetCurrentDetox'
        )
        return getDetox()
    except Exception as e:
        logging.error(
                    f'Se ha producido un error: {e}')
        return jsonify({'Codigo': -1,
                        'descripción': f'Ha ocurrido un '
                                       f'fallo: {traceback.format_exc()}'})


@autoDetox.route('/AutoDetox/PostDetox', methods=['POST'])
@token_required
def addDetox():
    """
    Endpoint de /PostDetox para añadir un detox. El metodo debe ser POST y
    debe incluir un json como mensaje, en caso contrario, lanzará un error 405
    """
    data = request.get_json()
    logging.info(f"{request.remote_addr} -> {data}")
    try:
        logging.info(
            f'Nueva conexion establecida a /GetUserEAndGPoints'
        )
        res = subirDetox(data)
        logging.info(
            'Se ha ejecutado correctamente'
        )
        return jsonify({'Codigo': res,
                        'descripción': 'Detox dado de '
                        'alta correctamente'}), 200
    except Exception as e:
        logging.error(f'Se ha producido un error: {e}')
        return jsonify({'Codigo': -1,
                        'descripción': f'Ha ocurrido un fallo: {res}'})


@autoDetox.route('/AutoDetox/getDetoxTabla', methods=['GET'])
@token_required
def getDetoxTabla():
    """
    Endpoint de /AutoDetox/getDetoxTabla para visualizar los detox a partir del mes pasado hacia delante. El metodo debe ser GET.
    En caso contrario, lanzará un error 405
    """

    logging.info(f"Se ha establecido una nueva conexión a /AutoDetox/getDetoxTabla")
    try:
        return get_detox_tabla(), 200
    except Exception as e:
        logging.error(f'Se ha producido un error: {e}')
        return jsonify({'Codigo': -1,
                        'descripción': f'Ha ocurrido un fallo: {e}'})
