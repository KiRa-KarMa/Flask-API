<?php
// if(!session_start()){
//     header('Location: ./views/inicio.php');
// }else{
//     header('Location: ./views/login.php');
// }

session_start();

if (isset($_SESSION['access_token'])){
    header ('Location: ./views/inicio.php');
}elseif (!isset($_SESSION['userdata'])){
    header ('Location: ./login/login.php');
}

?>

