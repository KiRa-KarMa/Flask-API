import configparser
import logging
from common.coms.db_coms import conectar_MariaDB, getEmpleadosFromDB
from common.encrypt.codeDecode import parserReader
from flask import Response, json, request


def getEmpleados():
    """
    Esta función se encarga de devolver una respuesta con los empleados que
    están ahora mismo activos. Esta respuesta tiene en la cabecera el valor
    content_type a application/json y el valor obtenido de la base de datos
    en formato json

    :return: respuesta con los datos preparados
    """
    # Leemos las credenciales de acceso del archivo de configuracion y
    # accedemos a la base de datos
    parser = configparser.ConfigParser()
    parser.read("./config_code.ini")
    logging.basicConfig(filename='debug.log', level=logging.DEBUG, filemode='w', format='%(asctime)s - %(name)s - %(message)s')
    logging.debug('Nueva conexión establecida a /GetUserRole ' + str(request.remote_addr))
    cred_bd = parserReader('CREDENCIALESBD', parser)
    with conectar_MariaDB(cred_bd['host'], cred_bd['user'],
                          cred_bd['pass'], cred_bd['bd'],
                          int(cred_bd['port'])) as conn:
        res = getEmpleadosFromDB(conn)
        # Devolvemos la respuesta con la cabecera y los datos preparados
        json_string = json.dumps(res,
                                 ensure_ascii=False)
        response = Response(json_string,
                            content_type="application/json;"
                            " charset=utf-8")

        # logging.debug ('Nueva respuesta de getUserRole ' + str(request.remote_user) + ' ' + str(request.remote_addr) + ': ' + str(json_string))
        return response
