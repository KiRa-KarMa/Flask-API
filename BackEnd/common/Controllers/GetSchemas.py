import configparser

from common.coms.hanaconnect import *
from common.encrypt.codeDecode import parserReader
from flask import Response, json


def getSchemas():
    """
    Controlador de common que conecta con la BD y llama a la función que realiza la consulta y obtiene todas las BD.
    
    :return: lista de todas las BD
    """

    parser = configparser.ConfigParser()
    parser.read("./config_code.ini")
    cred_bd = parserReader('bd', parser)
    with conectar_HanaDB(cred_bd['host'], cred_bd['user'],
                         cred_bd['pass'],
                         int(cred_bd['port'])) as conn:
        res = getSchemasBD(conn)
        print(res)
        return res
