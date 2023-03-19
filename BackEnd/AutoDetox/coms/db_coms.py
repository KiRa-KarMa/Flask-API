from string import Template
from datetime import datetime, date


class DeltaTemplate(Template):
    delimiter = "%"


def strfdelta(tdelta, fmt):
    """
    Convertimos un timedelta a string siguiendo el formato pasado como
    parametro

    :param tdelta: timedelta a formatear
    :param fmt: formato que queremos seguir. Ejemplo: '%H:%M'
    :return: string resultante
    """
    d = {"D": tdelta.days}
    hours, rem = divmod(tdelta.seconds, 3600)
    minutes, seconds = divmod(rem, 60)
    d["H"] = '{:02d}'.format(hours)
    d["M"] = '{:02d}'.format(minutes)
    d["S"] = '{:02d}'.format(seconds)
    t = DeltaTemplate(fmt)
    return t.substitute(**d)


def altaDetox(conexion, id_usuario, f_inicio, f_fin, email):
    """
    Damos de alta un periodo de vacaciones con los datos enviados como
    parametros. La tabla es dtx_periodo_vacaciones en la bd de fichajes
    factorial

    :param conexion: conexion a la base de datos
    :param id_usuario: Id del usuario que va a pasar el detox
    :param f_inicio: Primer día de vacaciones del usuario
    :param f_fin: Primer día en el que el usuario debe tener su correo
    :param email: email de redireccion
    :return: 1 si ha ocurrido todo bien
    """
    curs = conexion.cursor()
    curs.execute("Insert into dtx_periodo_vacaciones (id_trabajador,"
                 " fecha_inicio, fecha_fin, email_redireccion) VALUES"
                 " (?, ?, ?, ?)", (id_usuario, f_inicio, f_fin, email))
    conexion.commit()
    return 1


def getLastID(conexion):
    """
    Obtenemos el ID del último detox creado. La tabla es
    dtx_periodo_vacaciones en la bd de fichajes factorial

    :param conexion: Conexion a la base de datos
    :return: Ultimo ID creado
    """
    curs = conexion.cursor()
    curs.execute("SELECT id FROM dtx_periodo_vacaciones"
                 " ORDER BY id DESC LIMIT 1")
    res = curs.fetchall()
    return res[0][0]


def altaExcepciones(conexion, id_detox,  fecha, hora_i, hora_fin, mantener):
    """
    Damos de altas las excepciones en la base de datos.

    :param conexion: conexion a la base de datos
    :param id_detox: Id del detox para las excepciones
    :param fecha: Fecha de la excepcion del detox
    :param hora_i: hora de inicio de la excepcion
    :param hora_fin: hora de fin de la excepcion
    :param mantener: Valor del campo mantener_redireccion
    :return: 1
    """
    curs = conexion.cursor()
    curs.execute("Insert into dtx_excepciones (id_dtx_periodo_vacaciones,"
                 " fecha_excepcion, hora_inicio, hora_fin,"
                 " mantener_redireccion) VALUES"
                 " (?, ?, ?, ?, ?)", (id_detox, fecha, hora_i, hora_fin,
                                      mantener))
    conexion.commit()
    return 1


def getDetoxFromDB(conexion):
    """
    Obtenemos los detox almacenados en la base de datos. La tabla es
    dtx_periodo_vacaciones en la base de datos de fichajes factorial

    :param conexion: Conexion a la base de datos
    :return: Lista de los detox
    """
    curs = conexion.cursor()
    curs.execute("SELECT a.id, a.email, a.nombre, a.apellidos, b.fecha_inicio,"
                 " b.fecha_fin, b.email_redireccion, b.id FROM"
                 " trabajadores AS a INNER JOIN dtx_periodo_vacaciones AS b"
                 " ON a.id = b.id_trabajador"
                 " ORDER BY a.id ASC, b.id DESC")
    res = curs.fetchall()
    dict_res = []
    for i in res:
        dict_res.append({"id_usuario": i[0], "nombre": i[2], "apellidos": i[3],
                         "email": i[1],
                         "fecha_inicio": i[4].strftime("%d/%m/%Y"),
                         "fecha_fin": i[5].strftime("%d/%m/%Y"),
                         "email_redireccion": i[6],
                         "id_dtx": i[7]})
    return dict_res


def getExcepciones(conexion, id_detox):
    """
    Obtiene las excepciones almacenadas en la base de datos del detox pasado
    como parametro. La tabla es dtx_excepciones en la base de datos de fichajes
    factorial.

    :param conexion: Conexion a la base de datos
    :param id_detox: id del detox del que queremos obtener las excepciones
    :return: diccionario con lista de excepciones
    """
    curs = conexion.cursor()
    curs.execute("SELECT a.fecha_excepcion, a.hora_inicio, a.hora_fin,"
                 " a.mantener_redireccion FROM"
                 " dtx_excepciones as a WHERE a.id_dtx_periodo_vacaciones"
                 f" = '{id_detox}'")
    res = curs.fetchall()
    dict_res = []
    for i in res:
        dict_res.append({"fecha_excepcion": i[0].strftime("%d/%m/%Y"),
                         "hora_inicio": strfdelta(i[1], '%H:%M'),
                         "hora_fin": strfdelta(i[2], '%H:%M'),
                         "mantener_redireccion": i[3]})
    for i in dict_res:
        if i['mantener_redireccion'] == 'S':
            i['mantener_redireccion'] = 1
        else:
            i['mantener_redireccion'] = 0
    return dict_res


def getDetoxTablaFromDB(conexion):
    """
    Obtenemos los detox almacenados en la base de datos. La tabla es
    dtx_periodo_vacaciones en la base de datos de fichajes factorial

    :param conexion: Conexion a la base de datos
    :return: Lista de los detox
    """
    now = datetime.now()
    if now.month == 1:
        fecha = str(date(now.year-1, 12, now.day))
    else:
        fecha = str(date(now.year, now.month - 1, now.day))
    curs = conexion.cursor()
    sql = f"""
            SELECT a.id, a.email, a.nombre, a.apellidos, b.estado_detox, b.fecha_inicio, b.fecha_fin, b.email_redireccion, b.id
            FROM trabajadores AS a INNER JOIN dtx_periodo_vacaciones AS b ON a.id = b.id_trabajador
            WHERE b.fecha_inicio > '{fecha}'
            ORDER BY b.id DESC
            ;
          """
    curs.execute(sql)
    res = curs.fetchall()
    dict_res = []
    for i in res:
        dict_res.append({"id_usuario": i[0], "nombre": i[2], "apellidos": i[3],
                         "estado_detox": i[4],"email": i[1],
                         "fecha_inicio": i[5].strftime("%d/%m/%Y"),
                         "fecha_fin": i[6].strftime("%d/%m/%Y"),
                         "email_redireccion": i[7],
                         "id_dtx": i[8]})
    return dict_res
