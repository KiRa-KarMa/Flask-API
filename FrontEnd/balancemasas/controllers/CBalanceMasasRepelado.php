<?php
require_once('../models/CRepeladoBD.php');
//header("Location:../views/menu.php");
$repelado = new CBalanceMasasRepeladoBD;

header("Location: ../views/indexRepelado.php");
?>