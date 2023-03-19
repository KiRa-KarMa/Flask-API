<?php
session_start();


$usuario = filter_input(INPUT_POST, 'usuario');
$contrasena = filter_input(INPUT_POST, 'contrasena');

require_once('../models/login.php');
require_once('../funciones.php');

$login = new CLoginBD;

$login->email = $usuario;

if ($login->seleccionar())
{
    if ($contrasena == encriptar_desencriptar(DESENCRIPTAR, $login->contrasenya))
    {
        
        $_SESSION['usuario_id'] = $login->usuario_id;  //['app']
        $_SESSION['usuario'] = $login->usuario;
        $_SESSION['administrador'] = $login->administrador;
        $_SESSION['loggedin'] = true;
        $_SESSION['loggedstart'] = time();
       
        header('Location: ../views/main.php');      
        die();
    }
}

header ('Location: ../views/login.php');

?>