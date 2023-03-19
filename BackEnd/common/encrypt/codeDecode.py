# Importamos libreria
from cryptography.fernet import Fernet
import configparser


def parserReader(section, parser):
    """
    Reads the parameters from the configuration file
    """
    data = {}
    if parser.has_section(section):
        params = parser.items(section)
        for param in params:
            data[param[0]] = (desencriptar_items(param[1],
                              cargar_clave()).decode("utf-8"))

    return data


def cargar_clave():
    """
    Carga la clave del archivo clave.key
    """
    return open('clave.key', "rb").read()


# Encriptar items del .ini
def encriptar_items(config, clave):
    """
    Encripta los items del .ini
    :param config: ConfigParser en el que se ha leido el .ini
    :param clave: Clave para encriptar
    """
    for sections in config.sections():
        print("\n\n["+sections+"]\n")
        f = Fernet(clave)
        for items in list(config[sections]):
            mensaje = config[sections][items].encode("latin1", "strict")
            encriptado = f.encrypt(mensaje).decode("utf-8", "strict")
            print(items + " = " + encriptado)


# Desencriptar un item determinado
def desencriptar_items(item: str, clave):
    """
    Desencripta un item determinado

    :param item: Item a desencriptar
    :param clave: Clave para desencriptar
    :return: Item desencriptado
    """
    f = Fernet(clave)
    decrypted = f.decrypt(item.encode())
    return decrypted


if __name__ == "__main__":
    archivoRead = r'..\..\config.ini'

    configR = configparser.ConfigParser()
    configR.read(archivoRead)

    encriptar_items(configR, cargar_clave())
