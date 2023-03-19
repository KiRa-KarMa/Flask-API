<?php
require_once('../models/CCorteBD.php');
//header("Location:../views/menu.php");
$corte = new CBalanceMasasCorteBD;

header("Location: ../views/indexCorte.php");
?>