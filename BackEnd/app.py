from logging.config import fileConfig

from flask import Flask

from AutoDetox.urls import autoDetox
from common.urls import common
from ExcelToSAP.urls import excelToSAP
from EffortAndGratitude.urls import EAndGPoints
from Menu.urls import menu_admin

if __name__ == "__main__":
    fileConfig('logging.cfg')
    app = Flask(__name__)
    app.register_blueprint(common)
    app.register_blueprint(autoDetox)
    app.register_blueprint(excelToSAP)
    app.register_blueprint(EAndGPoints)
    app.register_blueprint(menu_admin)

    app.config['JSON_AS_ASCII'] = False

    # Start server
    app.run(debug=True, host='0.0.0.0', ssl_context='adhoc')
