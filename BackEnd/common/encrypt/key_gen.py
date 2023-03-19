# Importamos libreria
from cryptography.fernet import Fernet


# Escribir y guardar clave
def genera_clave():
    clave = Fernet.generate_key()
    with open("clave.key", "wb") as archivo_clave:
        archivo_clave.write(clave)


if __name__ == "__main__":
    genera_clave()
