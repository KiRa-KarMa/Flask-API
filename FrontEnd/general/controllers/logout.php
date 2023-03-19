<?php
//Iniciamos la sesión
session_start();

unset($_SESSION['usuario']); //eliminamos una variable de sesión creada
//cada vez q creemos variables habrá que ir eliminándolas
unset($_SESSION['usuario_id']);
unset($_SESSION['administrador']);
unset($_SESSION['loggedin']);
unset($_SESSION['loggedstart']);

//Eliminamos la sesión
/*session_destroy(); */
//Se eliminarán TODAS las variables de sesión creadas en el navegador

header('Location: ../views/login.php');


?>