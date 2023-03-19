from openpyxl import load_workbook
from common.coms.hanaconnect import conectar_HanaDB
import configparser
from common.encrypt.codeDecode import cargar_clave, desencriptar_items
import logging
from common.encrypt.codeDecode import parserReader
from ExcelToSAP.coms.hanaconnect import *


def getColumns(bd, table):
    """
    Función que nos devuelve el nombre de las columnas de una tabla

    :param bd: Nombre de la base de datos
    :param table: Nombre de la tabla
    :return: Lista con las columnas de la tabla
    """

    parser = configparser.ConfigParser()
    parser.read("./config_code.ini")
    cred_bd = parserReader('bd', parser)
    with conectar_HanaDB(cred_bd['host'], cred_bd['user'],
                         cred_bd['pass'],
                         int(cred_bd['port'])) as conn:
        res = getColumnsBD(conn, bd, table)
        return res
