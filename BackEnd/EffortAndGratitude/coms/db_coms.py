def getDNI(conexion, id_usuario):
    """
    Obtenemos dni de los empleados

    :param conexion: Conexion a la base de datos
    :param id_usuario: Id del usuario
    :return: dni del empleado
    """
    curs = conexion.cursor()
    sql = f"SELECT dni from fichajes_factorial.trabajadores "\
          f"where id = {id_usuario}"
    curs.execute(sql)
    dni = curs.fetchall()
    return dni[0][0]


def getUserEAndGPoints(conexion, dni):
    """
    Obtenemos los EffortsAndGratitude points de los usuarios

    :param conexion: Conexion a la base de datos
    :param dni: DNI del usuario
    :return: diccionario con lista puntos EffortsAndGratitude
    """
    curs = conexion.cursor()
    sql = f"SELECT * FROM funfriends.datos where `DNI.1` like '{dni}'"
    curs.execute(sql)
    res = curs.fetchall()
    dict_res = []
    for i in res:
        dict_res.append({"index": i[0], "marca_temporal": i[1],
                         "nombre": i[2], "apellidos": i[3],
                         "enviado por": i[4], "dni_emisor": i[5],
                         "dirigido a": i[6],
                         "dni_receptor": i[7], "puntos": i[8], "motivo": i[9]})
    return dict_res
