<?php
require_once('../models/CBalanceMasasGranilloBD.php');
//header("Location:../views/menu.php");
$granillo = new CBalanceMasasGranilloBD;

header("Location: ../views/indexGranillo.php");

?>