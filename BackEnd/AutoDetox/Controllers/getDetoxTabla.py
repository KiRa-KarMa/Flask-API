import configparser
import logging
from common.coms.db_coms import conectar_MariaDB
from AutoDetox.coms.db_coms import *
from common.encrypt.codeDecode import parserReader
from flask import Response, json


def get_detox_tabla():
    """
    Esta función se encarga de devolver una respuesta con los detox que están
    de hace un mes hacia delante en la base de datos, junto con sus excepciones en caso
    de haberlas. Esta respuesta tiene en la cabecera el valor
    content_type a application/json y el valor obtenido de la base de datos
    en formato json

    :return: respuesta en formato detox
    """
    parser = configparser.ConfigParser()
    parser.read("./config_code.ini")
    cred_bd = parserReader('CREDENCIALESBD', parser)
    # Iniciamos la conexión a la base de datos
    with conectar_MariaDB(cred_bd['host'], cred_bd['user'],
                          cred_bd['pass'], cred_bd['bd'],
                          int(cred_bd['port'])) as conn:
        # Obtenemos los detox y las excepciones de la base de datos y
        # preparamos el diccionario
        res = getDetoxTablaFromDB(conn)
        logging.info('Obteniendo detox de DB')
        for i in res:
            i['excepciones'] = getExcepciones(conn, i['id_dtx'])
            # i.pop("id_dtx", None)
        json_string = json.dumps(res,
                                 ensure_ascii=False)
        logging.info('Preparamos y devolvemos la respuesta')
        response = Response(json_string,
                            content_type="application/json;"
                            " charset=utf-8")
        return response
