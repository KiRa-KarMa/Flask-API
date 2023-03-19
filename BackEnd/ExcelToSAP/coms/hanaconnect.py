from hdbcli import dbapi
import logging


def dictToSQLUpdate(conn, row, table, db, p_clave):
    """
    Convierte un diccionario a una sentencia SQL para actualizar un registro

    :param conn: conexion sql
    :param row: diccionario con los datos a actualizar
    :param table: tabla a actualizar
    :param db: base de datos donde se realizan los cambios
    """

    sql = f'UPDATE "{db}"."{table}" SET '
    created = False
    skip = True
    keys = []
    for key in row:
        if row[key] is None:
            continue
        keys.append(key)
        if skip:
            skip = False
            continue
        if created:
            sql += ', '
        sql += f"\"{key}\" = '{row[key]}'"
        created = True
    sql += f' WHERE "{p_clave}" = \'{row[p_clave]}\''
    print(sql)

    cur = conn.cursor()
    cur.execute(sql)
    affected_row = cur.rowcount
    if affected_row == 0:
        cur.close()
        if table in ['@CAL_VID_CALIDAD', '@CAL_VID_TEST']:
            dictToSQLInsert(conn, keys, row, table, db, p_clave)
        else:
            raise Exception(f'No se encontró el registro {row["Code"]}')
    logging.info(f"Sentencia SQL: {sql}")
    cur.close()


def dictToSQLInsert(conn, keys, row, table, db, p_clave):
    """
    Convierte un diccionario a una sentencia SQL para insertar un registro

    :param conn: conexion sql
    :param keys: claves del diccionario
    :param row: diccionario con los datos a actualizar
    :param table: tabla a actualizar
    :param db: base de datos donde se realizan los cambios
    """
    try:
        existe = existe_registro(conn, row, table, db, p_clave)
        if existe:
            logging.error(
                f'No se ha podido actualizar {row[p_clave]}'
            )
            raise Exception(
                f'No se ha podido actualizar {row[p_clave]}'
            )
        keys_str = str(tuple(keys)).replace("'", '"')
        sql = f'INSERT INTO "{db}"."{table}" {keys_str} VALUES '
        values = []
        for key in keys:
            values.append(row[key])
        values = str(tuple(values)).replace('None', '')
        sql += values
        print(sql)
        cur = conn.cursor()
        cur.execute(sql)
        affected_row = cur.rowcount
        if affected_row == 0:
            cur.close()
            logging.error(
                f"Error al insertar el registro {row}"
            )
            raise Exception(f"Error al insertar el registro {row}")
        cur.close()
        logging.info(f"Sentencia SQL: {sql}")
        print(f"Insertado el registro {row[p_clave]}")
        logging.info(
            f"Insertado el registro {row[p_clave]}"
        )
    except Exception as e:
        logging.error(
            f"Ha habido un error durante el proceso de insertar el registro {row}: {e}"
        )
        raise Exception(
            f"Ha habido un error durante el proceso de insertar el registro {row}: {e}"
        )


def existe_registro(conn, row, table, db, p_clave):
    print(type(row[p_clave]))
    sql = f'''
            select count(*)
            from "{db}"."{table}"
        '''
    if type(row[p_clave]) == str:
        sql += f'\nwhere "{p_clave}" = \'{row[p_clave]}\' '
    elif type(row[p_clave]) == int:
        sql += f'\nwhere "{p_clave}" = {row[p_clave]} '
    curs = conn.cursor()
    curs.execute(sql)
    res = curs.fetchall()
    print(res[0][0])
    if res[0][0] > 0:
        return True
    else:
        return False


def getColumnsBD(conn, bd, table):
    sql = f"SELECT COLUMN_NAME FROM TABLE_COLUMNS WHERE TABLE_NAME = '{table}' AND SCHEMA_NAME = '{bd}' AND TABLE_NAME NOT LIKE '@ATEC%' AND TABLE_NAME NOT LIKE '@STEC%' AND TABLE_NAME NOT IN ('@BFEXTDBVERSION', '@BPCNTR', '@BPRSTRT') ORDER BY POSITION"
    curs = conn.cursor()
    curs.execute(sql)
    columns = list(curs.fetchall())
    return columns
