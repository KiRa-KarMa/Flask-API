from hdbcli import dbapi


class conectar_HanaDB():
    """
    ContextManager de las conexiones de HanaDB
    """

    def __init__(self, host: str, user: str, password: str, port: int = 3306):
        self.host = host
        self.user = user
        self.password = password
        self.port = port

    def __enter__(self):
        self.conn = hanaConnection(host=self.host, user=self.user,
                                   passwd=self.password, port=self.port)
        return self.conn

    def __exit__(self, exc_type, exc_val, exc_tb):
        self.conn.close()


def hanaConnection(host, port, user, passwd):
    """
    Establecemos una conexion a hanadb

    :param host: ip del host
    :param port: puerto de conexion
    :param user: usuario
    :param passwd: contraseña
    :return: conexión a la base de datos
    """
    conexion = dbapi.connect(
        address=host,
        port=port,
        user=user,
        password=passwd
    )
    return conexion


def getSchemasBD(conexion):
    """
    Obtenemos todas las BD mediante la consulta

    :param conexion: Conexion a la base de datos
    :return: schemes BD
    """
    curs = conexion.cursor()
    sql = f"select SCHEMA_NAME from schemas WHERE SCHEMA_NAME NOT IN ('MISTRAL', 'SYS')"
    curs.execute(sql)
    schemes = list(curs.fetchall())
    return schemes


def getTablesBD(conexion, database):
    """
    Obtenemos todas las tablas de la BD mediante la consulta

    :param conexion: Conexion a la base de datos
    :return: schemes BD
    """
    curs = conexion.cursor()
    sql = f"select TABLE_NAME from tables where \"SCHEMA_NAME\" like '{database}'"
    curs.execute(sql)
    tables = list(curs.fetchall())
    return tables
