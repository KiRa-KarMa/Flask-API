<!DOCTYPE html>
<html>
<head>
   <style>
      .head_tabla {
         border: 2px solid black;
         padding: 10px;
         text-align: center;
         background-color: rgb(216,236,215);
      }
      .titulo_tabla {
         text-align: center;
      }
      .elementos {
         text-align: center;
         border: 2px solid black;
         padding: 10px;
      }
      table {
         margin-top: 20px;
      }
   </style>
<?php 
require_once('menuSub.php');
require_once('../../general/views/menu.php');
// ini_set("log_errors", 1);
// ini_set("error_log", "./registro.log");
?>
 
	<title>REPELADO BALANCE MASAS</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../css/style.css"/>
   <link rel="stylesheet" href="../../general/css/style.css">
   <link rel="icon" type="image/x-icon" href="../../../general/img/Logotipo Calconut-1 C.png">
   <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
   <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>   <!-- <link rel="stylesheet" href="../../general/css/style.css"> -->


   <!-- <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'> -->

</head>
<body>
<?php require_once("../models/CBalanceMasasRepeladoBD.php");
      $repelado = new CBalanceMasasRepeladoBD; 
      $repelado->seleccionarRep();
?>
<div class="contenedorResumenCorteRep"> 
   <label class="OTCorteRep"><b>OT</b>:</label>
   <input class="inputOTCorteRep" maxlength="7" type="text" name="NOMBRE_OT" disabled style="text-decoration: none; border:none;" value="<?php echo $repelado->NOMBRE_OT;?>"/>     
   <label class="ObjetivOTCorteRep"><b>OBJETIVO</b>:</label>
   <input class="inputObjetivOTCorteRep" type="text" name="KG_OBJETIVO" disabled style="text-decoration: none; border:none;" value="<?php echo $repelado->KG_OBJETIVO;?>"/> 
   <label class="HoraIniOTCorteRep"><b>HORA INICIO</b>:</label>
   <input class="inputHoraOTCorteRep" type="text" name="HORA_INICIO" disabled style="text-decoration: none; border:none; size: 10;" value=" <?php echo $repelado->HORA_INICIO;?>"/> 
</div>
<label class="entradaRep">ENTRADA:</label>
<div class="entradaRepeladoPRD1">
<div class="contEntradaRepeladoFondoPRD1">
<input class="inputEntradaRepeladoPRD1" disabled style="background-color: transparent; border: none;" type="text" name="KG_ENT_REP" value="<?php echo $repelado->KG_ENT_REP;?> kg"/>
</div>
<div class="contEntradaRepeladoPRD1" id="cambiaColorENT" name="PRD_ENT" style="width:<?php
if ($repelado->KG_OBJETIVO > 0)
{echo ((($repelado->KG_ENT_REP*100)/$repelado->KG_OBJETIVO));}?>%">
</div>
</div>
<label class="salidaRepPF">PROD FINAL:</label>
<div class="salidaRepeladoPF">
<div class="contSalidaRepeladoFondoPF">
   <input class="inputSalidaRepeladoPF" disabled style="background-color: transparent; border: none;" type="text" name="KG_SAL_REP_FINAL" value="<?php echo $repelado->KG_SAL_REP_FINAL;?> kg"/>
</div>
<div class="contSalidaRepeladoPF" id="cambiaColorPF" style="width:<?php
if ($repelado->KG_OBJETIVO > 0){ echo ((($repelado->KG_SAL_REP_FINAL*100)/$repelado->KG_OBJETIVO));}?>%"></div>
</div>
<div class="balanceRepelado2">
   <input disabled style="background-color: transparent; border: none;" type="text" name="peso_actual" value="<?php 
         $repeladoCaudal = new CBalanceMasasRepeladoBD; 
         $repeladoCaudal->seleccionarCaudalRepEntrada();
         echo $repeladoCaudal->peso_actual;?> kg/h"/>
</div>
<label class="subprodRepeladolabel" >SUBPRODUCTOS:</label>
<label class="labelRepeladoSUBP1">Rechazo</label>
<label class="labelRepeladoSUBP2">Trozos</label>
<label class="labelRepeladoSUBP3">Medias</label>
<div class="salidabalanceRepeladoSUBPRD1">
<div class="contSalidaRepeladoFondoSUBPRD1">
<input class="inputRepeladoSUBPRD1" disabled style="background-color: transparent; border: none;" type="text" name="KG_SAL_REP_RECHAZO" value="<?php echo $repelado->KG_SAL_REP_RECHAZO;?> kg"/>
</div>
<div class="contSalidaRepeladoSUBPRD1" id="cambiaColorSUBPRD1" style="width:<?php
if ($repelado->KG_OBJETIVO > 0){ echo ((($repelado->KG_SAL_REP_RECHAZO*100)/($repelado->KG_OBJETIVO*0.02)));}?>%"></div>
</div>
</div>
<div class="salidabalanceRepeladoSUBPRD2">
<div class="contSalidaRepeladoFondoSUBPRD2">
<input class="inputRepeladoSUBPRD2" disabled style="background-color: transparent; border: none;" type="text" name="KG_SAL_REP_TROZOS" value="<?php echo $repelado->KG_SAL_REP_TROZOS;?> kg"/>
</div>
<div class="contSalidaRepeladoSUBPRD2" id="cambiaColorSUBPRD2" style="width:<?php
if ($repelado->KG_OBJETIVO > 0) { echo ((($repelado->KG_SAL_REP_TROZOS*100)/($repelado->KG_OBJETIVO*0.04)));}?>%"></div>
</div>
<div class="salidabalanceRepeladoSUBPRD3">
<div class="contSalidaRepeladoFondoSUBPRD3">
<input class="inputRepeladoSUBPRD3" disabled style="background-color: transparent; border: none;" type="text" name="KG_SAL_REP_MEDIAS" value="<?php echo $repelado->KG_SAL_REP_MEDIAS;?> kg"/>
</div>
<div class="contSalidaRepeladoSUBPRD3" id="cambiaColorSUBPRD3" name="PRD_MEDIAS" style="width:<?php
if ($repelado->KG_OBJETIVO > 0){ echo ((($repelado->KG_SAL_REP_MEDIAS*100)/($repelado->KG_OBJETIVO*0.05)));}?>%"></div>


<?php
   $pesadas = new CBalanceMasasRepeladoBD;
   $pesadas_entrada = $pesadas->seleccionarPesadasRepelado('ENTRADA');
   $pesadas = new CBalanceMasasRepeladoBD;
   $pesadas_rechaz_elect = $pesadas->seleccionarPesadasRepelado('RECHAZO_ELECTRONICA');
   $pesadas = new CBalanceMasasRepeladoBD;
   $pesadas_trozos = $pesadas->seleccionarPesadasRepelado('TROZOS');
   $pesadas = new CBalanceMasasRepeladoBD;
   $pesadas_medias = $pesadas->seleccionarPesadasRepelado('MEDIAS');
   $pesadas = new CBalanceMasasRepeladoBD;
   $pesadas_grano_repel = $pesadas->seleccionarPesadasRepelado('GRANO_REPELADO');
   ?>

<center>
<div style="margin-top: 35%; margin-left: 5%;">
   <div class="row d-flex justify-content-around">
      <div class="col-sm-6">
         <table>
            <tr>
               <th colspan="3" class="titulo_tabla">ENTRADA</th>
            </tr>
            <tr>
               <th class="head_tabla">Tiempo</th>
               <th class="head_tabla">Peso</th>
               <th class="head_tabla">Operacion</th>
            </tr>
            <?php
               // echo var_dump($pesadas_entrada);
               if (isset($pesadas_entrada))
               {
                  for ($i = 0; $i < count($pesadas_entrada); $i++)
                  {
                     echo '<tr>';
                     echo '<td class="elementos">'.$pesadas_entrada[$i]->tiempo.'</td>';
                     echo '<td class="elementos">'.$pesadas_entrada[$i]->peso.'</td>';
                     echo '<td class="elementos">'.$pesadas_entrada[$i]->OPERACION.'</td>';
                     echo '</tr>';
                  }
               }
            ?>
         </table>
      </div>
      <div class="col-sm-6">
         <table>
            <tr>
               <th colspan="3" class="titulo_tabla">RECHAZO ELECTRONICA</th>
            </tr>
            <tr>
               <th class="head_tabla">Tiempo</th>
               <th class="head_tabla">Peso</th>
               <th class="head_tabla">Operacion</th>
            </tr>
            <?php
               // echo var_dump($pesadas_entrada);
               if (isset($pesadas_rechaz_elect))
               {
                  for ($i = 0; $i < count($pesadas_rechaz_elect); $i++)
                  {
                     echo '<tr>';
                     echo '<td class="elementos">'.$pesadas_rechaz_elect[$i]->tiempo.'</td>';
                     echo '<td class="elementos">'.$pesadas_rechaz_elect[$i]->peso.'</td>';
                     echo '<td class="elementos">'.$pesadas_rechaz_elect[$i]->OPERACION.'</td>';
                     echo '</tr>';
                  }
               }
            ?>
         </table>
      </div>
      <div class="col-sm-6">
         <table>
            <tr>
               <th colspan="3" class="titulo_tabla">TROZOS</th>
            </tr>
            <tr>
               <th class="head_tabla">Tiempo</th>
               <th class="head_tabla">Peso</th>
               <th class="head_tabla">Operacion</th>
            </tr>
            <?php
               // echo var_dump($pesadas_entrada);
               if (isset($pesadas_trozos))
               {
                  for ($i = 0; $i < count($pesadas_trozos); $i++)
                  {
                     echo '<tr>';
                     echo '<td class="elementos">'.$pesadas_trozos[$i]->tiempo.'</td>';
                     echo '<td class="elementos">'.$pesadas_trozos[$i]->peso.'</td>';
                     echo '<td class="elementos">'.$pesadas_trozos[$i]->OPERACION.'</td>';
                     echo '</tr>';
                  }
               }
            ?>
         </table>
      </div>
      <div class="col-sm-6">
         <table>
            <tr>
               <th colspan="3" class="titulo_tabla">MEDIAS</th>
            </tr>
            <tr>
               <th class="head_tabla">Tiempo</th>
               <th class="head_tabla">Peso</th>
               <th class="head_tabla">Operacion</th>
            </tr>
            <?php
               if (isset($pesadas_medias))
               {
                  for ($i = 0; $i < count($pesadas_medias); $i++)
                  {
                     echo '<tr>';
                     echo '<td class="elementos">'.$pesadas_medias[$i]->tiempo.'</td>';
                     echo '<td class="elementos">'.$pesadas_medias[$i]->peso.'</td>';
                     echo '<td class="elementos">'.$pesadas_medias[$i]->OPERACION.'</td>';
                     echo '</tr>';
                  }
               }
            ?>
         </table>
      </div>
      <div class="col-sm-6">
         <table>
            <tr>
               <th colspan="3" class="titulo_tabla">GRANO REPELADO</th>
            </tr>
            <tr>
               <th class="head_tabla">Tiempo</th>
               <th class="head_tabla">Peso</th>
               <th class="head_tabla">Operacion</th>
            </tr>
            <?php
               if (isset($pesadas_grano_repel))
               {
                  for ($i = 0; $i < count($pesadas_grano_repel); $i++)
                  {
                     echo '<tr>';
                     echo '<td class="elementos">'.$pesadas_grano_repel[$i]->tiempo.'</td>';
                     echo '<td class="elementos">'.$pesadas_grano_repel[$i]->peso.'</td>';
                     echo '<td class="elementos">'.$pesadas_grano_repel[$i]->OPERACION.'</td>';
                     echo '</tr>';
                  }
               }
            ?>
         </table>
      </div>
   </div>
</div>
</center><br><br>
</body>
</html>
<script type="text/javascript">
      window.onload = function () {
      document.getElementById('VRepelado').style.color='#CDA800';

      var cambioColor = document.getElementById('cambiaColorENT');
      <?php if($repelado->KG_OBJETIVO > 0){ if (((($repelado->KG_ENT_REP*100)/$repelado->KG_OBJETIVO) > 98) && ((($repelado->KG_ENT_REP*100)/$repelado->KG_OBJETIVO) < 102.5))
      {?>
         cambioColor.style.backgroundColor = "green";
      <?php }
      elseif ((($repelado->KG_ENT_REP*100)/$repelado->KG_OBJETIVO) < 98){?>
         cambioColor.style.backgroundColor = 'orange';
      <?php }else{?>
         cambioColor.style.backgroundColor = "red";
      <?php }}?>

      var cambioColor2 = document.getElementById('cambiaColorPF');
      <?php if ($repelado->KG_OBJETIVO > 0){ if (((($repelado->KG_SAL_REP_FINAL*100)/($repelado->KG_OBJETIVO*0.02)) > 98) && ((($repelado->KG_SAL_REP_FINAL*100)/($repelado->KG_OBJETIVO*0.02)) < 102.5))
      {?>
         cambioColor2.style.backgroundColor = "green";
      <?php }
      elseif ((($repelado->KG_SAL_REP_FINAL*100)/($repelado->KG_OBJETIVO*0.02)) < 98){?>
         cambioColor2.style.backgroundColor = 'orange';
      <?php }else{?>
         cambioColor2.style.backgroundColor = "red";
      <?php }}?>


      var cambioColorSUBP1 = document.getElementById('cambiaColorSUBPRD1');
      <?php if ($repelado->KG_OBJETIVO > 0){ if (((($repelado->KG_SAL_REP_RECHAZO*100)/$repelado->KG_OBJETIVO) > 98) && ((($repelado->KG_SAL_REP_RECHAZO*100)/$repelado->KG_OBJETIVO) < 102.5))
      {?>
         cambioColorSUBP1.style.backgroundColor = "green";
      <?php }
      elseif ((($repelado->KG_SAL_REP_RECHAZO*100)/$repelado->KG_OBJETIVO) < 98){?>
         cambioColorSUBP1.style.backgroundColor = 'orange';
      <?php }else{?>
      cambioColorSUBP1.style.backgroundColor = "red";
      <?php }}?>

      var cambioColorSUBP2 = document.getElementById('cambiaColorSUBPRD2');
      <?php if ($repelado->KG_OBJETIVO > 0){ if (((($repelado->KG_SAL_REP_TROZOS*100)/($repelado->KG_OBJETIVO*0.04)) > 98) && ((($repelado->KG_SAL_REP_TROZOS*100)/($repelado->KG_OBJETIVO*0.04)) < 102.5))
      {?>
         cambioColorSUBP2.style.backgroundColor = "green";
      <?php }
      elseif ((($repelado->KG_SAL_REP_TROZOS*100)/($repelado->KG_OBJETIVO*0.04)) < 98){?>
         cambioColorSUBP2.style.backgroundColor = 'orange';
      <?php }else{?>
      cambioColorSUBP2.style.backgroundColor = "red";
      <?php }}?>     

      var cambioColorSUBP3 = document.getElementById('cambiaColorSUBPRD3');
      <?php if ($repelado->KG_OBJETIVO > 0){ if ( ((($repelado->KG_SAL_REP_MEDIAS*100)/($repelado->KG_OBJETIVO*0.05)) > 98) && ((($repelado->KG_SAL_REP_MEDIAS*100)/($repelado->KG_OBJETIVO*0.05)) < 102.5))
      {?>
         cambioColorSUBP3.style.backgroundColor = "green";
      <?php }
      elseif ((($repelado->KG_SAL_REP_MEDIAS*100)/($repelado->KG_OBJETIVO*0.05)) < 98){?>
         cambioColorSUBP3.style.backgroundColor = 'orange';
      <?php }else{?>
      cambioColorSUBP3.style.backgroundColor = "red";
      <?php }}?>  
   }

	interval = setInterval("location.reload()", 5000);
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
