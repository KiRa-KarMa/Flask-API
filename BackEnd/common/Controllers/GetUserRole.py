import configparser
import logging
from common.coms.db_coms import conectar_MariaDB, get_viewFromUser
from common.encrypt.codeDecode import parserReader
from flask import Response, json, request


def getUserRole(data):
    """
    Esta función se encarga de devolver una respuesta con las vistas y las
    herramientas a las que debe tener acceso el empleado que se le pasa
    como parametro

    :return: respuesta con los datos preparados
    """
    logging.basicConfig(filename='debug.log', level=logging.DEBUG, filemode='w', format='%(asctime)s - %(name)s - %(message)s')
    logging.debug ('Nueva conexión establecida a getUserRole ' + str(data) + ' ' + str(request.remote_addr))
    parser = configparser.ConfigParser()
    parser.read("./config_code.ini")
    cred_bd = parserReader('CREDENCIALESBD', parser)
    with conectar_MariaDB(cred_bd['host'], cred_bd['user'],
                          cred_bd['pass'], cred_bd['bd'],
                          int(cred_bd['port'])) as conn:
        res = get_viewFromUser(conn, data['id_usuario'])
        # Devolvemos la respuesta con la cabecera y los datos preparados
        json_string = json.dumps(res, ensure_ascii=False)
        response = Response(json_string,
                            content_type="application/json;"
                            " charset=utf-8")
        logging.debug ('Nueva respuesta de getUserRole ' + str(request.remote_user) + ' ' + str(request.remote_addr) + ': ' + str(json_string))
        return response
