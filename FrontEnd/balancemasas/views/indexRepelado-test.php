<!DOCTYPE html>
<html>
<head>
<?php require_once('menuSub.php');?>  
	<title>REPELADO BALANCE MASAS</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css"/>
</head>
<body>
<?php // require_once("../models/CBalanceMasasRepeladoBD.php");
      // $repelado = new CBalanceMasasRepeladoBD; 
      // $repelado->seleccionarRep();
?>
<div class="contenedorResumenCorteRep"> 
   <label class="OTCorteRep"><b>OT</b>:</label>
   <input class="inputOTCorteRep" maxlength="7" type="text" name="NOMBRE_OT" disabled style="text-decoration: none; border:none;" value="<?php //echo $repelado->NOMBRE_OT;?>"/>     
   <label class="ObjetivOTCorteRep"><b>OBJETIVO</b>:</label>
   <input class="inputObjetivOTCorteRep" type="text" name="KG_OBJETIVO" disabled style="text-decoration: none; border:none;" value="<?php //echo $repelado->KG_OBJETIVO;?>"/> 
   <label class="HoraIniOTCorteRep"><b>HORA INICIO</b>:</label>
   <input class="inputHoraOTCorteRep" type="text" name="HORA_INICIO" disabled style="text-decoration: none; border:none; size: 10;" value=" <?php //echo $repelado->HORA_INICIO;?>"/> 
</div>
<label class="entradaRep">ENTRADA:</label>
<div class="entradaRepeladoPRD1">
<div class="contEntradaRepeladoFondoPRD1">
<input class="inputEntradaRepeladoPRD1" disabled style="background-color: transparent; border: none;" type="text" name="KG_ENT_REP" value="<?php //echo $repelado->KG_ENT_REP;?> kg"/>
</div>
<div class="contEntradaRepeladoPRD1" id="cambiaColorENT" name="PRD_ENT" style="width:<?php //echo ((($repelado->KG_ENT_REP*100)/$repelado->KG_OBJETIVO));?>%">
</div>
</div>
<label class="salidaRepPF">PROD FINAL:</label>
<div class="salidaRepeladoPF">
<div class="contSalidaRepeladoFondoPF">
   <input class="inputSalidaRepeladoPF" disabled style="background-color: transparent; border: none;" type="text" name="KG_SAL_REP_FINAL" value="<?php //echo $repelado->KG_SAL_REP_FINAL;?> kg"/>
</div>
<div class="contSalidaRepeladoPF" id="cambiaColorPF" style="width:<?php //echo ((($repelado->KG_SAL_REP_FINAL*100)/$repelado->KG_OBJETIVO));?>%"></div>
</div>
<div class="balanceRepelado2">
   <input disabled style="background-color: transparent; border: none;" type="text" name="peso_actual" value="<?php 
        // $repeladoCaudal = new CBalanceMasasRepeladoBD; 
        // $repeladoCaudal->seleccionarCaudalRepEntrada();
         //echo $repeladoCaudal->peso_actual;?> kg/h"/>
</div>
<label class="subprodRepeladolabel" >SUBPRODUCTOS:</label>
<label class="labelRepeladoSUBP1">Rechazo</label>
<label class="labelRepeladoSUBP2">Trozos</label>
<label class="labelRepeladoSUBP3">Medias</label>
<div class="salidabalanceRepeladoSUBPRD1">
<div class="contSalidaRepeladoFondoSUBPRD1">
<input class="inputRepeladoSUBPRD1" disabled style="background-color: transparent; border: none;" type="text" name="KG_SAL_REP_RECHAZO" value="<?php //echo $repelado->KG_SAL_REP_RECHAZO;?> kg"/>
</div>
<div class="contSalidaRepeladoSUBPRD1" id="cambiaColorSUBPRD1" style="width:<?php //echo ((($repelado->KG_SAL_REP_RECHAZO*100)/($repelado->KG_OBJETIVO*0.02)));?>%"></div>
</div>
</div>
<div class="salidabalanceRepeladoSUBPRD2">
<div class="contSalidaRepeladoFondoSUBPRD2">
<input class="inputRepeladoSUBPRD2" disabled style="background-color: transparent; border: none;" type="text" name="KG_SAL_REP_TROZOS" value="<?php //echo $repelado->KG_SAL_REP_TROZOS;?> kg"/>
</div>
<div class="contSalidaRepeladoSUBPRD2" id="cambiaColorSUBPRD2" style="width:<?php //echo ((($repelado->KG_SAL_REP_TROZOS*100)/($repelado->KG_OBJETIVO*0.04)));?>%"></div>
</div>
<div class="salidabalanceRepeladoSUBPRD3">
<div class="contSalidaRepeladoFondoSUBPRD3">
<input class="inputRepeladoSUBPRD3" disabled style="background-color: transparent; border: none;" type="text" name="KG_SAL_REP_MEDIAS" value="<?php //echo $repelado->KG_SAL_REP_MEDIAS;?> kg"/>
</div>
<div class="contSalidaRepeladoSUBPRD3" id="cambiaColorSUBPRD3" name="PRD_MEDIAS" style="width:<?php //echo ((($repelado->KG_SAL_REP_MEDIAS*100)/($repelado->KG_OBJETIVO*0.05)));?>%"></div>
</body>
</html>
