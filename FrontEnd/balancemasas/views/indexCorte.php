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
   </style>
<?php 
require_once('menuSub.php');
require_once('../../general/views/menu.php');
?>
	<title>CORTE BALANCE MASAS</title>
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
	<!--script src="../jquery-3.2.1.min.js"></script-->
</head>
<body>
<?php 
      require_once("../models/CBalanceMasasCorteBD.php");
      $corte = new CBalanceMasasCorteBD; 
      $corte->seleccionarCort();
?>
<div class="contenedorResumenCorteRep"> 
   <label class="OTCorteRep"><b>OT</b>:</label>
   <input class="inputOTCorteRep" maxlength="7" type="text" name="NOMBRE_OT" disabled style="text-decoration: none; border:none;" value="<?php echo $corte->NOMBRE_OT;?>"/>     
   <label class="ObjetivOTCorteRep"><b>OBJETIVO</b>:</label>
   <input class="inputObjetivOTCorteRep" type="text" name="KG_OBJETIVO" disabled style="text-decoration: none; border:none;" value="<?php echo $corte->KG_OBJETIVO;?>"/> 
   <label class="HoraIniOTCorteRep"><b>HORA INICIO</b>:</label>
   <input class="inputHoraOTCorteRep" type="text" name="HORA_INICIO" disabled style="text-decoration: none; border:none; size: 10;" value="<?php echo $corte->HORA_INICIO;?>"/> 
</div>
<label class="entradaCorte">ENTRADA:</label>
<div class="balanceCorte">
   <input disabled style="background-color: transparent; border: none;" type="text" name="KG_ENT_COR" value="<?php echo $corte->KG_ENT_COR;?> kg"/>
</div>
<!---label>CAUDAL:</label--->
<div class="balanceCorte2">
   <input disabled style="background-color: transparent; border: none;" type="text" name="peso_actual" value="<?php 
      $corteCaudal = new CBalanceMasasCorteBD; 
      $corteCaudal->seleccionarCaudalCorteEntrada();
      echo $corteCaudal->peso_actual;?> kg/h"/>
</div>
<div class="salidaBalanceCortePRD">
<div class="contSalidaCorteFondo">
<input class="inputCorte" disabled style="background-color: transparent; border: none;" type="text" name="KG_SAL_COR" value="<?php echo $corte->KG_SAL_COR_LAM;?> kg"/>
</div>
<div class="contSalidaCorte" id="cambiaColor" style="width:<?php echo($corte->KG_SAL_COR_LAM*100)/$corte->KG_OBJETIVO;?>%">
</div>
</div>
<label class="subprodCortelabel" >SUBPRODUCTOS:</label>
<label class="labelCorteSUBP1">Laminilla</label>
<label class="labelCorteSUBP2">Harinilla</label>
<div class="salidaBalanceCorteSUBPRD1">
<div class="contSalidaCorteFondoSUBPRD1">
<input class="inputCorteSUBPRD1" disabled style="background-color: transparent; border: none;" type="text" name="KG_SAL_COR_TROZOS" value="<?php echo $corte->KG_SAL_COR_TROZOS;?> kg"/>
</div>
<div class="contSalidaCorteSUBPRD1" id="cambiaColorSUBPRD1" style="width:<?php echo (($corte->KG_SAL_COR_TROZOS*100)/($corte->KG_OBJETIVO*0.10));?>%">
</div>
</div>
<div class="salidaBalanceCorteSUBPRD2">
<div class="contSalidaCorteFondoSUBPRD2">
<input class="inputCorteSUBPRD2" disabled style="background-color: transparent; border: none;" type="text" name="KG_SAL_COR_NOCONFORME" value="<?php echo $corte->KG_SAL_COR_NOCONFORME;?> kg"/>
</div>
<div class="contSalidaCorteSUBPRD2" id="cambiaColorSUBPRD2" style="width:<?php echo(($corte->KG_SAL_COR_NOCONFORME*100)/($corte->KG_OBJETIVO*0.18));?>%">
</div>
</div>


<?php
   $pesadas = new CBalanceMasasCorteBD;
   $pesadas_entrada = $pesadas->seleccionarPesadasCorte('ENTRADA');
   $pesadas = new CBalanceMasasCorteBD;
   $pesadas_harinilla = $pesadas->seleccionarPesadasCorte('HARINILLA');
   $pesadas = new CBalanceMasasCorteBD;
   $pesadas_subproducto = $pesadas->seleccionarPesadasCorte('SUBPRODUCTO');
   $pesadas = new CBalanceMasasCorteBD;
   $pesadas_prod_final = $pesadas->seleccionarPesadasCorte('PRODUCTO_FINAL');
   ?>

<center>
<div style="margin-top: 55%; margin-left: 5%;">
   <div class="row d-flex justify-content-around">
      <div class="col-sm-3">
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
      <div class="col-sm-3">
         <table>
            <tr>
               <th colspan="3" class="titulo_tabla">HARINILLA</th>
            </tr>
            <tr>
               <th class="head_tabla">Tiempo</th>
               <th class="head_tabla">Peso</th>
               <th class="head_tabla">Operacion</th>
            </tr>
            <?php
               // echo var_dump($pesadas_entrada);
               if (isset($pesadas_harinilla))
               {
                  for ($i = 0; $i < count($pesadas_harinilla); $i++)
                  {
                     echo '<tr>';
                     echo '<td class="elementos">'.$pesadas_harinilla[$i]->tiempo.'</td>';
                     echo '<td class="elementos">'.$pesadas_harinilla[$i]->peso.'</td>';
                     echo '<td class="elementos">'.$pesadas_harinilla[$i]->OPERACION.'</td>';
                     echo '</tr>';
                  }
               }
            ?>
         </table>
      </div>
      <div class="col-sm-3">
         <table>
            <tr>
               <th colspan="3" class="titulo_tabla">SUBPRODUCTO</th>
            </tr>
            <tr>
               <th class="head_tabla">Tiempo</th>
               <th class="head_tabla">Peso</th>
               <th class="head_tabla">Operacion</th>
            </tr>
            <?php
               // echo var_dump($pesadas_entrada);
               if (isset($pesadas_subproducto))
               {
                  for ($i = 0; $i < count($pesadas_subproducto); $i++)
                  {
                     echo '<tr>';
                     echo '<td class="elementos">'.$pesadas_subproducto[$i]->tiempo.'</td>';
                     echo '<td class="elementos">'.$pesadas_subproducto[$i]->peso.'</td>';
                     echo '<td class="elementos">'.$pesadas_subproducto[$i]->OPERACION.'</td>';
                     echo '</tr>';
                  }
               }
            ?>
         </table>
      </div>
      <div class="col-sm-3">
         <table>
            <tr>
               <th colspan="3" class="titulo_tabla">PRODUCTO FINAL</th>
            </tr>
            <tr>
               <th class="head_tabla">Tiempo</th>
               <th class="head_tabla">Peso</th>
               <th class="head_tabla">Operacion</th>
            </tr>
            <?php
               if (isset($pesadas_prod_final))
               {
                  for ($i = 0; $i < count($pesadas_prod_final); $i++)
                  {
                     echo '<tr>';
                     echo '<td class="elementos">'.$pesadas_prod_final[$i]->tiempo.'</td>';
                     echo '<td class="elementos">'.$pesadas_prod_final[$i]->peso.'</td>';
                     echo '<td class="elementos">'.$pesadas_prod_final[$i]->OPERACION.'</td>';
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
      document.getElementById('VCorte').style.color='#CDA800';

      var cambioColor = document.getElementById('cambiaColor');
      <?php if ( ((($corte->KG_SAL_COR_LAM*100)/$corte->KG_OBJETIVO) > 98) && ((($corte->KG_SAL_COR_LAM*100)/$corte->KG_OBJETIVO) < 102.5))
      {?>
         cambioColor.style.backgroundColor = "green";
    <?php }
     elseif ((($corte->KG_SAL_COR_LAM*100)/$corte->KG_OBJETIVO) < 98){?>
         cambioColor.style.backgroundColor = 'orange';
    <?php }else{?>
         cambioColor.style.backgroundColor = "red";
    <?php }?>

      var cambioColorSUBP1 = document.getElementById('cambiaColorSUBPRD1');
      <?php if (((($corte->KG_SAL_COR_TROZOS*100)/($corte->KG_OBJETIVO*0.10)) > 98) && ((($corte->KG_SAL_COR_TROZOS*100)/($corte->KG_OBJETIVO*0.10)) < 102.5))
      {?>
         cambioColorSUBP1.style.backgroundColor = "green";
    <?php }
     elseif ((($corte->KG_SAL_COR_TROZOS*100)/($corte->KG_OBJETIVO*0.10)) < 98){?>
         cambioColorSUBP1.style.backgroundColor = 'orange';
    <?php }else{?>
      cambioColorSUBP1.style.backgroundColor = "red";
    <?php }?>

      var cambioColorSUBP2 = document.getElementById('cambiaColorSUBPRD2');
      <?php if (((($corte->KG_SAL_COR_NOCONFORME*100)/($corte->KG_OBJETIVO*0.18)) > 98) && ((($corte->KG_SAL_COR_NOCONFORME*100)/($corte->KG_OBJETIVO*0.18)) < 102.5))
      {?>
         cambioColorSUBP2.style.backgroundColor = "green";
    <?php }
     elseif ((($corte->KG_SAL_COR_NOCONFORME*100)/($corte->KG_OBJETIVO*0.18)) < 98){?>
         cambioColorSUBP2.style.backgroundColor = 'orange';
    <?php }else{?>
      cambioColorSUBP2.style.backgroundColor = "red";
    <?php }?>    
   }
	interval = setInterval("location.reload()", 15000);
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
