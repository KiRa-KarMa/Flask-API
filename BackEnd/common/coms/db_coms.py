from string import Template
import common.Models.error_handlers as error_handlers
import mariadb
from rich import print


class conectar_MariaDB():
    """
    Contextmanager para conectar a MariaDB. Esta clase se encarga de
    iniciar las conexiones y cerrarlas de forma automatica.
    """

    def __init__(self, host: str, user: str, password: str, database: str,
                 port: int = 3306):
        self.host = host
        self.user = user
        self.password = password
        self.database = database
        self.port = port

    def __enter__(self):
        try:
            self.conn = conectarMariaDB(host=self.host, user=self.user,
                                        password=self.password,
                                        db=self.database,
                                        port=self.port)
        except mariadb.Error as mdbe:
            raise error_handlers.ExcepcionMariadb("/getUsers", mdbe, -1)
        return self.conn

    def __exit__(self, exc_type, exc_val, exc_tb):
        self.conn.close()


def conectarMariaDB(host: str, user: str, password: str, db: str,
                    port: int = 3306):
    """
    Establecemos una conexion a mariadb

    :param host: ip del host
    :param user: usuario
    :param password: contraseña
    :param db: nombre de la base de datos
    :param port: puerto de conexion. Por defecto 3306
    :return: conexión a la base de datos
    """

    conexion = mariadb.connect(
        user=user,
        password=password,
        host=host,
        port=port,
        database=db
    )
    return conexion


def getEmpleadosFromDB(conexion):
    """
    Obtenemos los empleados de la base de datos

    :param conexion: Conexion al servidor de la base de datos
    :return: Lista de diccionarios con los datos de los empleados de
    la base de datos
    """
    curs = conexion.cursor()
    curs.execute('SELECT a.id, a.email, a.nombre, a.apellidos, a.id_microsoft'
                 ' FROM trabajadores AS a WHERE a.activo = "S"')
    res = curs.fetchall()
    dict_res = []
    for i in res:
        dict_res.append({"id_usuario": i[0], "nombre": i[2],
                         "apellidos": i[3], "email": i[1],
                         "id_microsoft": i[4]})
    return dict_res


def get_viewFromUser(conexion, user):
    """
    Obtenemos las vistas y herramientas a las que tiene acceso el usuario
    indicado como parametro de la función

    :param conexion: Conexion a la base de datos
    :param usuario: Usuario del que queremos obtener la informacion
    :return: diccionario con lista de excepciones
    """
    curs = conexion.cursor()
    sql = "SELECT a.id, a.nombre, a.descripcion, a.vista FROM"\
          f" (SELECT id_rol AS rol FROM r_u_r WHERE id_usuario = {user}) "\
          "AS b, roles AS a WHERE a.id = b.rol "
    curs.execute(sql)
    res = curs.fetchall()
    dict_res = []
    for i in res:
        sql = "Select a.id, a.nombre, a.ruta_php, a.ruta_imagen from "\
              " herramientas as a, (Select * from"\
              f" r_r_h where id_rol = {i[0]}) as b where a.id = "\
              "b.id_herramienta"
        curs.execute(sql)
        res_aux = curs.fetchall()
        print(res_aux)
        list_aux = []
        for j in res_aux:
            list_aux.append({'id': j[0],
                             'nombre': j[1],
                             'ruta_php': j[2],
                             'ruta_img': j[3]})
        dict_res.append({"UserID": user,
                         "RolID": i[0],
                         "Rol": i[1],
                         "descripcion": i[2],
                         "Vista": i[3],
                         "herramientas": list_aux})
    return dict_res


def get_hashed_key(conexion):
    """
    Obtenemos la api key y la sal de la base de datos

    :param conexion: Conexion a la base de datos
    :return: contraseña hasheada y la sal
    """
    curs = conexion.cursor()
    sql = "SELECT * FROM api_keys"
    curs.execute(sql)
    res = curs.fetchall()
    hashed = res[0][1]
    salt = res[0][2]
    return hashed, salt
