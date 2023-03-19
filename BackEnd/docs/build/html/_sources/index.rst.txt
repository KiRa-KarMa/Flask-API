Documentación de la API Calconut
=======================================
Esta es la documentación de la API de Calconut.

Esta API cumple la función de back-end del despliegue CalcoTools. 
Funciona gracias al framework Flask, framework que se encarga de 
generar un servicio web API a la que se puede acceder mediante una petición HTTPS.

Todas las llamadas a la API tienen que tener una cabecera llamada x-api-key que tiene 
que tener el valor de la api key para acceder a los recuros. Esta api key será proporcionada 
por un encargado de IT.

Los endpoints tendrán la siguiente estructura:

* ``/NombreDeProyecto/endpoint``

Cabe destacar que los endpoints de uso general no tienen nombre de proyecto, por lo que 
se pondrá el endpoint directamente.

Los endpoints usables actualmente son los siguientes:

* ``/GetUsers``: haciendo una llamada a este endpoint(con un método GET), obtenemos una lista de los empleados activos actualmente
* ``/GetUserRole``: haciendo una llamada a este endpoint(con un método GET), y enviando el id del usuario en el body, obtenemos una lista de los roles y las herramientas que el usuario tiene disponibles
* ``/AutoDetox/GetCurrentDetox``: haciendo una llamada a este endpoint(con un método GET) obtenemos una lista de los detox activos actualmente
* ``/AutoDetox/PostDetox``: haciendo una llamada a este endpoint(con un método POST), y enviando los datos del detox necesarios en el cuerpo, podemos dar de alta un detox en la base de datos
* ``/ExcelToSAP/LoadData``: haciendo una llamada a este endpoint(con un metodo POST), leemos el Excel subido y actualizamos SAP
* ``/ExcelToSAP/GetTemplates``: haciendo una llamada a este endpoint(con un metodo GET), nos devolverá un excel segun la BD y la tabla elegida.
* ``/GetTablesfromDB``: haciendo una llamada a este endpoint(con un metodo GET), nos devolverá todas las tablas de la BD elegida.
* ``/GetSchemas``: haciendo una llamada a este endpoint(con un metodo GET), nos devolverá todas las BD.
* ``/GetUserEAndGPoints``: haciendo una llamada a este endpoint(con un metodo GET), nos devolverá los puntos y los motivos de EffortAndGratitude.


Descripción:
=======================================
*AutoDetox:* Este porograma aplica Detox a los correos cuyos usuarios están de baja o de vacaciones.

*Common:* Libreria de funciones en común que usan todas las aplicaciones.

*ExcelToSAP:* Este programa recibe un Excel, obtiene sus datos y los sube a SAP.


.. toctree::
   :maxdepth: 2
   :caption: AutoDetox:

   AutoDetox_Coms
   AutoDetox_Controllers
   AutoDetox_URL


.. toctree::
   :maxdepth: 2
   :caption: Common:

   Common_Coms
   Common_Controllers
   Common_URL
   Common_Models

.. toctree::
   :maxdepth: 2
   :caption: ExcelToSAP:

   ExcelToSAP_Coms
   ExcelToSAP_Controllers
   ExcelToSAP_URL

.. toctree::
   :maxdepth: 2
   :caption: EffortAndGratitude:

   EffortAndGratitude_Coms
   EffortAndGratitude_Controllers
   EffortAndGratitude_URL