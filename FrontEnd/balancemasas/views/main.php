<?php

// session_start();
// if (isset($_SESSION['access_token']) || $_SESSION['access_token'] == 'zero'){
//   require_once('../../general/models/validateLogin.php');
// } else {
//   header("Location: http://localhost/preCalcoTools/general/views/login.php");  
//   die();
// }
require_once('menuSub.php');
require_once('menu.php');

require_once("../models/CBalanceMasasRepeladoBD.php");
$repelado = new CBalanceMasasRepeladoBD; 
$repelado->seleccionarRep();
require_once("../models/CBalanceMasasCorteBD.php");
$corte = new CBalanceMasasCorteBD; 
$corte->seleccionarCort();
require_once("../models/CBalanceMasasHarinaBD.php");
$harina = new CBalanceMasasHarinaBD; 
$harina->seleccionarHar();
require_once("../models/CBalanceMasasGranilloBD.php");
$granillo = new CBalanceMasasGranilloBD; 
$granillo->seleccionarGra();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>Fábrica</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css"/>
        <link rel="stylesheet" href="../../general/css/style.css">
        <link rel="icon" type="image/x-icon" href="../../../general/img/Logotipo Calconut-1 C.png">
        <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
<body>
    <br><br>
    <center>

<div class="INICIO">   
    <table class="tablaINI">
        <th>
            <td>ENTRADA</td>
            <td colspan="4">SALIDA</td>
        </th>
    <tr>
    <td class="titulosInicio"><a href="indexRepelado.php" style="text-decoration: none; color: #28325D;">REPELADO </br><?php echo $repelado->NOMBRE_OT;?></a>
        </td>
        <td class="azul">
        <b><?php echo $repelado->KG_ENT_REP . " kg";?>
        </td>
        <td class="azul">
            Prod Final: </br><b><?php echo $repelado->KG_SAL_REP_FINAL . " kg";?></b>
        </td>
        <td class="azul">
            Rechazo: </br><b><?php echo $repelado->KG_SAL_REP_RECHAZO . " kg";?></b>
        </td>
        <td class="azul">
            Medias: </br><b><?php echo $repelado->KG_SAL_REP_MEDIAS . " kg";?></b>
        </td>
        <td class="azul">
            Trozos: </br><b><?php echo $repelado->KG_SAL_REP_TROZOS . " kg";?></b>
        </td>
    </tr>
    <tr>        
        <td class="titulosInicio">
            <a href="indexCorte.php" style="text-decoration: none; color: #28325D;">CORTE </br><?php echo $corte->NOMBRE_OT;?></a>
        </td>
        <td class="azulclaro">
        <b><?php echo $corte->KG_ENT_COR . " kg";?></b>
        </td>
        <td colspan="2" class="azulclaro">
            Prod Final: </br><b><?php echo $corte->KG_SAL_COR_LAM . " kg";?></b>
        </td>
        <td class="azulclaro">
            Laminillas: </br><b><?php echo $corte->KG_SAL_COR_NOCONFORME . " kg";?></b>
        </td>
        <td class="azulclaro">
            Trozos: </br><b><?php echo $corte->KG_SAL_COR_TROZOS . " kg";?></b>
        </td>
    </tr>
    <tr>
        <td class="titulosInicio">
            <a href="indexHarina.php" style="text-decoration: none; color: #28325D;">HARINA </br> <?php echo $harina->NOMBRE_OT;?></a>
        </td>
        <td class="azul">
            <b><?php echo $harina->KG_ENT_HAR . " kg";?></b>
        </td>
        <td colspan="4" class="azul">
            <b><?php echo $harina->KG_SAL_HAR . " kg";?></b>
        </td>
    </tr>
    <tr>
        <td class="titulosInicio">
            <a href="indexGranillo.php" style="text-decoration: none; color: #28325D;">GRANILLO  </br><?php echo $granillo->NOMBRE_OT;?></a>
        </td>
        <td class="azulclaro">
            <b><?php echo $granillo->KG_ENT_GRA . " kg";?></b>
        </td>
        <td colspan="4" class="azulclaro">
            <b><?php echo $granillo->KG_SAL_GRA . " kg";?></b>
        </td>          
    </tr>
    </table>.
</div>
</center>

</body>
<script type="text/javascript">
   window.onload = function () {
      document.getElementById('VInicio').style.color='#CDA800';}
</script>
</html>
<script>
    interval = setInterval("location.reload()", 20000);
    link_repelado = document.getElementById("VRepelado");
    link_corte = document.getElementById("VCorte");
    link_harina = document.getElementById("VHarina");
    link_granillo = document.getElementById("VGranillo");

    link_repelado.addEventListener("click", () => {
        clearInterval(interval);
    })
    link_corte.addEventListener("click", () => {
        clearInterval(interval);
    })
    link_harina.addEventListener("click", () => {
        clearInterval(interval);
    })
    link_granillo.addEventListener("click", () => {
        clearInterval(interval);
    })
</script>