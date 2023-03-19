<?php
require_once('../../general/views/menu.php');
session_start();
// if (isset($_SESSION['access_token']) || $_SESSION['access_token'] == 'zero'){
//   require_once('../../general/models/validateLogin.php');
// } else {
//   header("Location: http://localhost/preFlaskApi/general/views/login.php");  
//   die();
// }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../general/css/style.css">
  <link rel="icon" type="image/x-icon" href="../img/api.PNG">
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  <title>Herramientas Disponibles</title>
</head>
<body>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<?php
$appid = "ITS-SECRET";
$tenantid = "ITS-SECRET";
$secret = "ITS-SECRET";
$login_url = "http://login.microsoftonline.com/".$tenantid."/oauth2/v2.0/authorize";
$logout_url = "https://login.microsoftonline.com/".$tenantid."/oauth2/v2.0/logout";


?>
<div style="text-align:center !important;">
  El envío de sugerencias se realizará a través de <a href="https://anonymousemail.me/"><b>Anonymous Email</b></a>.<br><br><br>
    <!-- <p style="left:0;"></p> -->
    Para ello deberemos:<br>
  <ul style="list-style: none;text-align: left;display:inline-block;">
    <li><b>1.</b> Acceder al siguiente enlace: <a target="_blank" href="https://anonymousemail.me/">https://anonymousemail.me/</a></li>
    <li><b>2.</b> Escribimos el destinatario, en este caso será <b>sugerencias@mail.es</b>.</li>
    <li><b>3.</b> Escribimos el asunto y el cuerpo de correo.</li>
    <li><b>4.</b> El resto de valores los dejamos por defecto.</li>
  </ul>
<br>
<!-- <iframe src="https://www.anonymousemail.me/" frameborder="0">AnonymousEmail</iframe> -->

  <iframe width="560" height="315" src="https://www.youtube.com/embed/uxXvLNLcwIg?start=30" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    <br><br>
<p class="mensajeAviso">Si no estás seguro de cómo se ve el mensaje redactado, envíatelo primero a tu propio correo para comprobarlo​😊.</p>
    <!-- <img style="width:40%" src="../../../general/img/anonymousemail.png"> -->
</div><br><br>
<center><a class="botonVolverInicio" href="../../general/views/inicio.php">Volver al INICIO</a></center>
</body>
</html>

