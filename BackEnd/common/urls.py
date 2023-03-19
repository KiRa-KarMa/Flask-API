import configparser
import traceback
from functools import wraps
from logging.config import fileConfig
import logging
import bcrypt
from flask import Blueprint, jsonify, make_response, request

from common.coms.db_coms import conectar_MariaDB, get_hashed_key
from common.Controllers.GetUserRole import getUserRole
from common.Controllers.GetUsers import getEmpleados
from common.Controllers.GetSchemas import getSchemas
from common.Controllers.GetTables import getTables
from common.encrypt.codeDecode import parserReader

common = Blueprint('common', __name__)
fileConfig('logging.cfg')
logging.basicConfig(level=logging.DEBUG, filename='debug.log',
                    format='%(asctime)s: %(levelname)s: %(message)s')


def token_required(f):
    """
    Wrapper encargado de recibir una función, procesar la petición y comprobar
    si la api key enviada en la cabecera x-api-key tiene acceso a los recuros
    """
    @wraps(f)
    def decorator(*args, **kwargs):
        token = None
        if 'x-api-key' in request.headers:
            token = request.headers['x-api-key']
        if not token:
            return make_response(jsonify({"message": "Falta la cabecera "
                                          "x-api-key!"}), 401)
        parser = configparser.ConfigParser()
        parser.read("./config_code.ini")
        cred_bd = parserReader('CREDENCIALESBD', parser)
        with conectar_MariaDB(cred_bd['host'], cred_bd['user'],
                              cred_bd['pass'], cred_bd['bd'],
                              int(cred_bd['port'])) as conn:
            hashed, salt = get_hashed_key(conn)

            bytePwd = token.encode("utf-8")
            salt_encode = salt.encode("utf-8")

            hashedToken = bcrypt.hashpw(bytePwd, salt_encode)
        try:
            if (hashed == hashedToken.decode("utf-8")):
                return f(*args, **kwargs)
            else:
                return make_response(jsonify({"message": "API-key no valida"}),
                                     401)

        except Exception as e:
            print(e)
            return make_response(jsonify({"message": f"{e}"}),
                                 401)
    return decorator


@common.route('/GetUsers')
@token_required
def listaEmpleados():
    """
    Endpoint de /GetUsers. Se debe acceder a este endpoint mediante una
    petición GET. En caso contrario devolverá un error 405
    """
    try:
        logging.info('Nueva conexion establecida a /GetUsers')
        return getEmpleados(), 200
    except Exception as e:
        logging.error(f'Ha habido un error: {e}')
        return jsonify({'Codigo': -1,
                        'descripción': f'Ha ocurrido un'
                                       f' fallo: {traceback.format_exc()}'})


@common.route('/GetUserRole', methods=['GET'])
@token_required
def get_user_role():
    """
    Endpoint de /GetUserRole. Se debe acceder a este endpoint mediante una
    petición GET. En caso contrario devolverá un error 405
    """
    try:
        logging.info('Nueva conexion establecida a /GetUserRole')
        data = request.get_json()
        print(data)
        logging.info('Se ha ejecutado correctamente')
        return getUserRole(data)
    except Exception as e:
        logging.error(
            f'Ha habido un error: {e}'
        )
        return jsonify({'Codigo': -1,
                        'descripción': f'Ha ocurrido un fallo:{e}'})


@common.route('/GetSchemas', methods=['GET'])
@token_required
def GetSchemesFromBD():
    """
    Endpoint de /GetSchemes para obtener las BD. El metodo debe
    ser GET, en caso contrario, lanzará un error 405
    """

    try:
        logging.info('Nueva conexion establecida a /GetSchemas')
        bd = getSchemas()
        schemas = []
        for i in bd:
            schemas.append(i[0])
        print(schemas)
        logging.info(f'Se ha ejecutado correctamente')
        return jsonify({'Codigo': 1,
                        'data': schemas}), 200
    except Exception as e:
        logging.error(f'Ha habido un error: {e}')
        return jsonify({'Codigo': -1,
                        'data': f'Ha ocurrido un fallo: {e}'}), 400


@common.route('/GetTablesfromDB', methods=['GET'])
@token_required
def GetTables():
    """
    Endpoint de /GetTables para conseguir las tablas de la base de datos pasada por parámetro. Se debe acceder a este endpoint mediante una
    petición GET. En caso contrario devolverá un error 405
    """
    try:
        logging.info('Nueva conexion establecida a /GetTablesfromDB')
        data = request.get_json()
        tablas = []
        database = data['database']
        tables = getTables(database)
        for i in tables:
            tablas.append(i[0])
        print(tablas)
        logging.info(f'Se ha ejecutado correctamente')
        return jsonify({'Codigo': 1,
                        'data': tablas}), 200
    except Exception as e:
        logging.error(f'Ha habido un error: {e}')
        return jsonify({'Codigo': -1,
                        'descripción': f'Ha ocurrido un fallo:{e}'}), 400
