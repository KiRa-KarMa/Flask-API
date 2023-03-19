import configparser
import logging
import traceback
from datetime import datetime

import common.Models.error_handlers as error_handlers
from common.coms.db_coms import conectar_MariaDB

from AutoDetox.coms.db_coms import altaDetox, altaExcepciones, getLastID
from common.encrypt.codeDecode import parserReader
from rich import print


def subirDetox(data):
    """
    Esta función se encarga de subir los detox a la base de datos, recibe los
    datos en un diccionario con los parametros establecidos en la
    documentacion. Si no ha ocurrido nada devuelve el valor 1, si ocurre una
    excepcion, devuelve esa excepcion. En este diccionario se detalla lo
    siguiente:
    - En caso de estar vacios los campos hora_terminar o hora_empezar, se
    configurará la hora por defecto, es decir, las 18:00
    - La fecha de inicio y la fecha de fin deberán estar en formato dd/mm/YYYY

    :param data: diccionario con los datos de a subir a la base de datos
    :return: Resultado de la ejecución, 1 o 0
    """
    try:
        # Controlamos que los datos enviados tengan el formato indicado.
        print(data)

        if datetime.strptime(data['fecha_inicio'],
                             '%d/%m/%Y') > datetime.strptime(data['fecha_fin'],
                                                             '%d/%m/%Y'):
            raise error_handlers.ExcepcionFechas("subirDetox",
                                                 "La fecha de inicio es mayor"
                                                 " que la fecha de fin", -1)

        data['fecha_inicio'] = datetime.strptime(data['fecha_inicio'],
                                                 '%d/%m/%Y'
                                                 ).strftime('%Y/%m/%d')
        data['fecha_fin'] = datetime.strptime(data['fecha_fin'],
                                              '%d/%m/%Y').strftime('%Y/%m/%d')
        if data['hora_terminar'] != '':
            data['fecha_fin'] += f' {data["hora_terminar"]}'
        else:
            data['fecha_fin'] += ' 18:00'

        if data['hora_empezar'] != '':
            data['fecha_inicio'] += f' {data["hora_empezar"]}'
        else:
            data['fecha_inicio'] += ' 18:00'
        logging.info(data['fecha_fin'])
        excepciones = data['Excepciones']
        parser = configparser.ConfigParser()
        parser.read("./config_code.ini")
        cred_bd = parserReader('CREDENCIALESBD', parser)
        # Damos de alta los detox y creamos sus excepciones
        logging.info(
            'Conectamos a la base de datos'
        )
        with conectar_MariaDB(cred_bd['host'], cred_bd['user'],
                              cred_bd['pass'], cred_bd['bd'],
                              int(cred_bd['port'])) as conn:
            altaDetox(conn, id_usuario=data['id_usuario'],
                      f_inicio=data['fecha_inicio'],
                      f_fin=data['fecha_fin'],
                      email=data['email_redireccion'])
            logging.info(
                f'Damos de alta el detox de {data["email_redireccion"]}'
            )
            id_detox = getLastID(conn)
            print(id_detox)
            for i in excepciones:
                print(i)
                if i['mantener_redireccion'] == 1:
                    i['mantener_redireccion'] = 'S'
                else:
                    i['mantener_redireccion'] = 'N'
                i['fecha_excepcion'] = datetime.strptime(i['fecha_excepcion'],
                                                         '%d/%m/%Y'
                                                         ).strftime('%Y/%m/%d')
                altaExcepciones(conn, id_detox, i['fecha_excepcion'],
                                i['hora_inicio'], i['hora_fin'],
                                i['mantener_redireccion'])
                logging.info(
                    'Damos de alta excepciones'
                )

        return 1
    except Exception as e:
        logging.error(
            f'Se ha producido un error: {e}'
        )
        return traceback.format_exc()
