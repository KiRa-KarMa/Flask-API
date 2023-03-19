<?php
require_once ('../models/CUserDetoxDB.php');
require_once('../../general/views/menu.php');
require_once ('../../general/models/check_session.php');
?>
<!DOCTYPE html>
<html lang="es_ES">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../general/css/style.css">
    <link rel="icon" type="image/x-icon" href="../../general/img/api.PNG">
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <title>Excepciones de DETOX</title>
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

$curr_us = $_GET['id_usuario'];
foreach ($resp as $f){
        if ($f->id_usuario == $_GET['id_usuario']){
        $curr_us = $f;
    }
}
$excepciones = array();
if( isset($_GET['detox_arrast']) )
{
    $aux = array(
        'fecha_excepcion' => date('d/m/Y', strtotime($_GET['fecha_excepcion'])),
        'hora_inicio' => $_GET['hora_inicio'],
        'hora_fin' => $_GET['hora_fin'],
        'mantener_redireccion' => $_GET['mantener_redireccion']
    );
    $excepciones = unserialize($_GET['detox_arrast']);
    array_push($excepciones, $aux);


} 
$excepciones = serialize($excepciones);
?>
<br><br>
<center>
<h2>Excepciones de DETOX para usuario <?php echo $curr_us->nombre . " " . $curr_us->apellidos?></h2><br>
<form action="./detoxUsuarioExcep.php" method="get">
    <br>
    <input type="text" style="text-align: center;" size="70" name="nombreApellidos" maxlength="100" readonly="" value="<?php echo $curr_us->nombre . " " . $curr_us->apellidos. " " . " (". $curr_us->email.") "?>"/>
    <br>
    <input type="hidden" name="id_usuario" value="<?php echo $curr_us->id_usuario;?>"/>
    <input type="hidden" name="fecha_inicio" value="<?php echo $_GET['fecha_inicio'];?>"/>
    <input type="hidden" name="detox_arrast" value = <?php echo $excepciones;?>/>
    <input type="hidden" name="fecha_fin" value="<?php echo $_GET['fecha_fin'];?>"/> 
    <input type="hidden" name="hora_empezar" value="<?php echo $_GET['hora_empezar'];?>"/> 
    <input type="hidden" name="hora_terminar" value="<?php echo $_GET['hora_terminar'];?>"/> 
    <input type="hidden" name="email_redireccion" value="<?php echo $_GET['email_redireccion'];?>"/> 
    <label>Fecha:</label>
    <input type="date" date-format="dd/MM/yyyy" name="fecha_excepcion" value=""> 
    <br>
    <label>Hora INICIO:</label>
    <input type="time" name="hora_inicio" value="">  
    <br>
    <label>Hora FIN:</label>
    <input type="time" name="hora_fin" value="">  
    <br>
    <label>Mantener Redirección</label>
    <br>
    <label>NO</label>
    <input type="radio" name="mantener_redireccion" value="N" checked>
    <label>SI</label>
    <input type="radio" name="mantener_redireccion" value="S" >
    <br><br>
    <button class="botonFinal" type="submit">Añadir EXCEPCIÓN</button>
</form>
<br><br>
<br><br>
<?php 
$exceptions=unserialize($excepciones);
if($exceptions == NULL){
}
else{?>
<table class="tablaaux1">
    <thead class="theadtablaaux1">
    <tr>
        <th>Excepción</th>
        <th>Fecha </th>
        <th>Hora Inicio</th>
        <th>Hora Fin</th>
        <th>Mantener Redirección</th>
    </tr>
    </thead>
    <tbody class="tbodytablaaux1" >
    <?php
        $i=1;
        foreach ($exceptions as $exception => $excep){
            ?>
            <tr>
                <!-- <td><?php //echo ($exceptions[$exception]->$excep)->fecha_excepcion;?> -->
                <td><?php echo $i++; ?>
                <td><?php echo ($excep['fecha_excepcion']);?></td>
                <td><?php echo ($excep['hora_inicio']);?></td>
                <td><?php echo ($excep['hora_fin']);?></td>
                <td><?php echo ($excep['mantener_redireccion']);?></td>
                <!-- <td><a onclick="window.load()" href="<?php unset($exception);?>">Eliminar</td> -->
            </tr><?php
    }}?>
    </tbody>    
</table>
<br><br>
<form action="../models/CUserDetoxDBPost.php" method="get">
    <input type="hidden" name="id_usuario" value="<?php echo $curr_us->id_usuario;?>"/>
    <input type="hidden" name="fecha_inicio" value="<?php echo $_GET['fecha_inicio'];?>"/>
    <input type="hidden" name="detox_arrast" value = <?php echo $excepciones;?>/>
    <input type="hidden" name="fecha_fin" value="<?php echo $_GET['fecha_fin'];?>"/> 
    <input type="hidden" name="email_redireccion" value="<?php echo $_GET['email_redireccion'];?>"/>
    <input type="hidden" name="hora_empezar" value="<?php echo $_GET['hora_empezar'];?>"/> 
    <input type="hidden" name="hora_terminar" value="<?php echo $_GET['hora_terminar'];?>"/> 
    <button class="botonFinal" type="submit">Dar de alta DETOX</button>
</form>
<br><br>
<a class="botonVolverInicio" href="./seleccionaUsuario.php">Volver al INICIO</a>
<a class="botonVolverAnterior" href="./detoxUsuario.php?id_usuario=<?php echo $curr_us->id_usuario;?>">Volver a la pestaña anterior</a>
</center>
</body>
</html>
