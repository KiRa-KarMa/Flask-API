import configparser

from common.coms.hanaconnect import *
from common.encrypt.codeDecode import parserReader


def getTables(database):
    """
    Controlador de common que conecta con la BD y llama a la función que realiza la consulta y obtiene todas las tablas de BD indicada por parámetro.

    :param database: Nombre de la base de datos de la que queremos sacar las tablas
    :return: lista de todas las tablas de la BD
    """

    parser = configparser.ConfigParser()
    parser.read("./config_code.ini")
    cred_bd = parserReader('bd', parser)
    with conectar_HanaDB(cred_bd['host'], cred_bd['user'],
                         cred_bd['pass'],
                         int(cred_bd['port'])) as conn:
        res = getTablesBD(conn, database)
        return res
