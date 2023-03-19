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
?>	<title>HARINA BALANCE MASAS</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../css/style.css"/>
   <link rel="stylesheet" href="../../general/css/style.css">
   <link rel="icon" type="image/x-icon" href="../../../general/img/Logotipo Calconut-1 C.png">
   <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
   <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>   <!-- <link rel="stylesheet" href="../../general/css/style.css"> -->	<!--script src="../jquery-3.2.1.min.js"></script--->
</head>
<body>
<?php require_once("../models/CBalanceMasasHarinaBD.php"); 
      $harina = new CBalanceMasasHarinaBD; 
      $harina->seleccionarHar();
      ?>
<div class="contenedorResumenGraHar"> 
   <label class="OT"><b>OT</b>:</label>
   <input class="inputOT" maxlength="7" type="text" name="NOMBRE_OT" disabled style="text-decoration: none; border:none;" value="<?php echo $harina->NOMBRE_OT;?>"/>     
   <label class="ObjetivOT"><b>OBJETIVO</b>:</label>
   <input class="inputObjetivOT" type="text" name="OBJETIVO" disabled style="text-decoration: none; border:none;" value="<?php echo $harina->KG_OBJETIVO;?>"/> 
   <label class="HoraIniOT"><b>HORA INICIO</b>:</label>
   <input class="inputHoraOT" type="text" name="HORA_INICIO" disabled style="text-decoration: none; border:none;" value="<?php echo $harina->HORA_INICIO;?>"/> 
</div>
<label class="tituloEntradaGranHar">ENTRADA:</label>
<div class="entGranHar">
   <input disabled style="background-color: transparent; border: none;" type="text" name="KG_ENT_HAR" value="<?php echo $harina->KG_ENT_HAR;?> kg"/>
</div>
<!---label>CAUDAL:</label--->
<div class="entGranHar2">
   <input disabled style="background-color: transparent; border: none;" type="text" name="peso_actual" value="<?php 
         $harinaCaudal = new CBalanceMasasHarinaBD; 
         $harinaCaudal->seleccionarCaudalHarEntrada();
         echo $harinaCaudal->peso_actual;?> kg/h"/>
</div>
        </div>
<div class="salidaBalanceGraHar">
<div class="contSalidaFondo">
   <input class="inputGraHar" disabled style="background-color: transparent; border: none;" type="text" name="KG_SAL_HAR" value="<?php echo $harina->KG_SAL_HAR;?> kg"/>
</div>
<div class="contSalidaGranHar" id="cambiaColor" style="width:<?php echo (($harina->KG_SAL_HAR*100)/$harina->KG_OBJETIVO);?>%"></div>

<?php
   $pesadas = new CBalanceMasasHarinaBD;
   $pesadas_entrada = $pesadas->seleccionarPesadasHarina('ENTRADA');
   $pesadas = new CBalanceMasasHarinaBD;
   $pesadas_prod_final = $pesadas->seleccionarPesadasHarina('PRODUCTO_FINAL');
   ?>

<center>
<div style="margin-top: 35%; ">
   <div class="row d-flex justify-content-around">
      <div class="col-sm-4">
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
      <div class="col-sm-4">
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
      document.getElementById('VHarina').style.color='#CDA800';

      var cambioColor = document.getElementById('cambiaColor');
      <?php if ( ((($harina->KG_SAL_HAR*100)/$harina->KG_OBJETIVO) > 98) && ((($harina->KG_SAL_HAR*100)/$harina->KG_OBJETIVO) < 102.5))
      {?>
         cambioColor.style.backgroundColor = "green";
    <?php }
     elseif ((($harina->KG_SAL_HAR*100)/$harina->KG_OBJETIVO) < 98){?>
         cambioColor.style.backgroundColor = 'orange';
    <?php }else{?>
         cambioColor.style.backgroundColor = "red";
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
