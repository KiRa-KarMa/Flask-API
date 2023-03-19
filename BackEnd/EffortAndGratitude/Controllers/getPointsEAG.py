import configparser
import logging
from common.coms.db_coms import conectar_MariaDB
from EffortAndGratitude.coms.db_coms import getUserEAndGPoints, getDNI
from common.encrypt.codeDecode import parserReader
from flask import Response, json


def getPointsEAG(id_usuario):
    """
    Controlador de EffortAndGratitude que realiza la conexion con la base de datos y llama a la funcion para realizar las consultas y obtener el DNI del usuario
    y sus correspondientes puntos.

    :param id_usuario: ID del usuario que solicida los puntos de EffortAndGratitude
    :return: Total puntos de EffortAndGratitude y sus motivos.
    """

    parser = configparser.ConfigParser()
    parser.read("./config_code.ini")
    cred_bd = parserReader('CREDENCIALESBD', parser)
    with conectar_MariaDB(cred_bd['host'], cred_bd['user'],
                          cred_bd['pass'], cred_bd['bd'],
                          int(cred_bd['port'])) as conn:
        DNI = getDNI(conn, id_usuario)
        res = getUserEAndGPoints(conn, DNI)
        logging.info(
            f'Obtenemos los puntos del usuario {id_usuario}'
        )
        total_puntos = 0
        motivos = []
        for i in res:
            total_puntos += int(i['puntos'])
            motivos.append(i['motivo'])
        return total_puntos, motivos
