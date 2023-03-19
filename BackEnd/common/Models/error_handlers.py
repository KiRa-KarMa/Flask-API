from flask import Blueprint, Flask, jsonify

blueprint = Blueprint('error_handlers', __name__)


class Proximamente(Exception):
    """
    Excepcion que lanzaremos con los endpoints que están en desarrollo

    """
    def __init__(self, endpoint, code, status_code=None, payload=None):
        """
        Inicializamos la excepcion

        :param endpoint: endpoint donde ha ocurrido la excepcion
        :param code: Codigo de error
        """
        Exception.__init__(self)
        self.message = f"El endpoint {endpoint} todavia no está soportado"
        self.status_code = 501
        self.payload = payload
        self.code = code

    def to_dict(self):
        rv = dict(self.payload or ())
        rv['descripción'] = self.message
        rv['Codigo'] = self.code
        return rv


class Prohibido(Exception):
    """
    Excepcion que lanzaremos cuando intentemos comunicarnos con un endpoint al
    que no se tiene acceso
    """
    def __init__(self, endpoint, code, status_code=None, payload=None):
        """
        Inicializamos la excepcion

        :param endpoint: endpoint donde ha ocurrido la excepcion
        :param code: Codigo de error
        """
        Exception.__init__(self)
        self.message = f"No tienes los permisos suficientes para acceder"\
                       f" al endpoint {endpoint}"
        self.status_code = 403
        self.payload = payload
        self.code = code

    def to_dict(self):
        rv = dict(self.payload or ())
        rv['message'] = self.message
        rv['Codigo'] = self.code
        return rv


class ExcepcionMariadb(Exception):
    """
    Excepcion con la que manejaremos las excepciones de MariaDB
    """
    def __init__(self, funcion, error, code, status_code=None, payload=None):
        """
        Inicializamos la excepcion

        :param endpoint: endpoint donde ha ocurrido la excepcion
        :param code: Codigo de error
        :param error: Error lanzado por Mariabd
        """
        Exception.__init__(self)
        self.message = f"Ha ocurrido el siguiente fallo en la comunicación "\
                       f"con la base de datos en la funcion {funcion}: {error}"
        self.status_code = 500
        self.payload = payload
        self.code = code

    def to_dict(self):
        rv = dict(self.payload or ())
        rv['message'] = self.message
        rv['Codigo'] = self.code
        return rv


@blueprint.app_errorhandler(Proximamente)
def manejarProximamente(error):
    """
    Manejamos la excepcion Proximamente, lanzando una respuesta con el error

    :param error: Excepcion de tipo Proximamente
    """
    response = jsonify(error.to_dict())
    response.status_code = error.status_code
    return response


@blueprint.app_errorhandler(405)
def manejar405(error):
    """
    Manejamos el error 405, lanzando una respuesta con el error

    """
    response = jsonify({'Codigo': -1,
                        'message': "Ha usado un metodo no valido"})
    response.status_code = 405
    return response


@blueprint.app_errorhandler(Prohibido)
def manejarProhibido(error):
    """
    Manejamos la excepcion Prohibido, lanzando una respuesta con el error

    :param error: Excepcion de tipo Prohibido
    """
    print("Prohibido")
    response = jsonify(error.to_dict())
    response.status_code = error.status_code
    return response


@blueprint.app_errorhandler(ExcepcionMariadb)
def manejarMariaDB(error):
    """
    Manejamos la excepcion ExcepcionMariadb, lanzando una respuesta con el
    error

    :param error: Excepcion de tipo ExcepcionMariadb
    """
    response = jsonify(error.to_dict())
    response.status_code = error.status_code
    return response
