import pandas as pd
from openpyxl import load_workbook
from sqlalchemy import create_engine
from common.encrypt.codeDecode import cargar_clave, desencriptar_items
import configparser
import os
import pymssql


def getExcelData(path):
    """
    Obtiene los datos de un archivo excel

    :param path: Ruta del archivo excel
    :type path: str
    :return: Datos del archivo excel
    :rtype: pd.DataFrame
    """
    xls = pd.ExcelFile(path)
    sheet_names = xls.sheet_names
    xls.close()
    # sheet_names = load_workbook(path, read_only=True, keep_links=False)
    data = pd.read_excel(io=path, sheet_name=sheet_names)
    print(data)
    return data


def connectDB(path, section):
    """
    Nos conectamos a la base de datos con los parametros de configuración almacenados en el archivo .ini bajo el parametro 'EXCEL'

    :param path: ruta al archivo.ini
    :type path: str
    :return: conexión a la base de datos
    """
    try:
        parser = configparser.ConfigParser()
        parser.read(path)
        db = {}
        if parser.has_section(section):
            params = parser.items(section)
            for param in params:
                db[param[0]] = (desencriptar_items(param[1], cargar_clave()).decode("utf-8"))
        if str(db['motor']) == 'mysql':
            connection = str(db['motor']) + '+pymysql://' + db['user'] + ':' + db['password'] + '@' + str(db['host']) + ':' + str(db['port']) + "/" + db['database']
        elif 'mssql' in str(db['motor']):
            engine = pymssql.connect(server=str(db['host']), user=db['user'], password=db['password'], database=db['database'])
            return engine
        else:
            connection = str(db['motor']) + '://' + db['user'] + ':' + db['password'] + '@' + str(db['host']) + ':' + str(db['port']) + "/" + db['database']
        engine = create_engine(connection, encoding='iso-8859-1')  # Establish connection
        print("Conexion a la base de datos completada")
    except Exception as e:
        raise Exception("No se ha podido conectar a la base de datos: ", e)
    return engine


def actualizar_db(file):
    """
    Actualizamos los datos en la base de datos con los datos del excel

    :param file: dirección del archivo excel
    """

    print("Actualizando datos...")
    datos = getExcelData(file)
    print("Conectando a la base de datos...")
    engine = connectDB(os.path.join('.', 'DB_cnf_effort_code.ini'), 'EXCEL')

    opcion = 0
    if opcion == 1:
        for keys in datos.keys():
            if keys == 'datos':
                df = pd.DataFrame.from_dict(datos[keys]).astype(str)
                df['MOTIVO'] = df['MOTIVO'].str.encode('iso-8859-1')
                # df.to_sql(keys.lower(), con=engine, if_exists='replace')
                print("La tabla: " + str(keys) + " ha sido actualizada")
    elif opcion == 0:
        print("Actualizando registros nuevos...")
        keys = 'datos'
        try:
            if keys == 'datos':
                try:
                    datos_excel = pd.DataFrame.from_dict(datos[keys]).reset_index()
                    print(datos_excel)
                    datos_db = pd.read_sql(keys.lower(), con=engine)
                    print(datos_db)
                except Exception as e:
                    print(e)
                    raise Exception("Ha habido un problema al obtener los datos del excel/DB", e)
                try:
                    print(datos_db.iloc[-1:, 0:1].values[0])
                    print("Excel")
                    print(datos_excel.iloc[-1:, 0:1].values[0])
                    if datos_db.iloc[-1:, 0:1].values[0] == datos_excel.iloc[-1:, 0:1].values[0]:
                        msg = "La base de datos ya estaba actualizada, no se han insertado nuevos valores"
                        print(msg)
                        return msg, False
                    elif datos_db.iloc[-1:, 0:1].values[0] > datos_excel.iloc[-1:, 0:1].values[0]:
                        msg = "Hay mas registros en la base de datos que en el excel"
                        print(msg)
                        return msg, False
                    else:
                        try:
                            for i in range(int(datos_db.iloc[-1:, 0:1].values[0]) + 1, int(datos_excel.iloc[-1:, 0:1].values[0]) + 1):
                                #print(datos_excel.iloc[i:i+1, :].values[0])
                                columnas = datos_db.columns.values
                                cont = 0
                                print(columnas)
                                for columna in columnas:
                                    if columna == 'index':
                                        sql_insert = "INSERT INTO `" + str(keys) + "` (`" + str(columna) + "`) VALUES (" + str(
                                            datos_excel.iloc[i:i + 1, cont].values[0]) + ")"
                                        print(sql_insert)
                                        indice = datos_excel.iloc[i:i + 1, cont].values[0]
                                        cont += 1
                                        print(indice)
                                        connection = engine.connect()
                                        # result = connection.execute(sql_insert)
                                        # print(result)
                                    else:
                                        print("COLUMNA" + str(columna))
                                        print(datos_excel.iloc[i:i + 1, cont].values[0])
                                        sql_update = "UPDATE `" + str(keys).lower() + "` SET `" + str(columna) + "` = '" + \
                                                        str(datos_excel.iloc[i:i + 1, cont].values[0]) + "' WHERE `INDEX` = " + \
                                                        str(indice)
                                        print(sql_update)
                                        cont += 1
                                        connection = engine.connect()
                                        # result = connection.execute(sql_update)
                                        # print(result)
                            msg = "Se han insertado correctamente", True
                            return msg
                        except Exception as e:
                            print("ha habido un problema al recorrer todas las columnas")
                            raise Exception(
                                "ha habido un problema al recorrer todas las columnas", e
                            )
                except Exception as e:
                    print("Ha habido un problema al comparar la base de datos con el excel")
                    raise Exception(
                        "Ha habido un problema al comparar la base de datos con el excel",
                        e
                    )
        except Exception as e:
            print("Ha habido un problema en la hoja: " + str(keys))
            raise Exception(
                "Ha habido un problema en la hoja: ", str(keys)
            )
