<?php
session_start();
if (isset($_SESSION['access_token']) || $_SESSION['access_token'] == 'zero'){
  require_once('../../general/models/validateLogin.php');
} else {
  header("Location: ../../general/login/login.php");
  die();
}
?>

<!-- <?php
// session_start();
// if (isset($_SESSION['access_token']) || $_SESSION['access_token'] == 'zero'){
//   if (str_contains(getcwd(), 'preFlaskApi')){
//     require_once('/var/www/html/preFlaskApi/general/models/validateLogin.php');
//   } else {
//     require_once('/var/www/html/general/models/validateLogin.php');
//   }
// } else {
//   if (str_contains(getcwd(), 'preFlaskApi')){
//     header("Location: /var/www/html/preFlaskApi/general/login/login.php");
//     die();
//   }
//   else{
//     header("Location: /var/www/html/general/login/login.php");
//     die();
//   }
// }
?> -->