<?php
require_once ('../models/CUserDetoxDB.php');
require_once ('../models/CGetUserDetoxDB.php');
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Listado DETOX de usuario</title>
</head>
<body>
<br><br>
<div class="menu"></div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<center>

<?php
$curr_us2 = $_GET['id_usuario'];
foreach ($resp2 as $j){
    if ($j->id_usuario == $_GET['id_usuario']){
        $curr_us2 = $j;
    }
}
if ($curr_us2 === $_GET['id_usuario']){
    echo "<div class=\"mensajeAviso\" style=\"font-weight:bolder; font-size:27px;\">NO HAY DETOX PARA ESTE USUARIO</div>";
}else{
?>
<h2>DETOX del usuario <?php echo $curr_us2->nombre. " " . $curr_us2->apellidos?></h2>
<br>
<br>
<br>
<table class="tablaaux1">
<thead class="theadtablaaux1">
<tr>
    <th rowspan="2">
        ID
    </th>
    <th rowspan="2">
        Nombre
    </th>
    <th rowspan="2">
        Email
    </th>
    <th rowspan="2">
        Email_redirección
    </th>
    <th rowspan="2">
        Fecha Inicio
    </th>
    <th rowspan="2">
        Fecha Fin
    </th>
    <th rowspan="2">
        EXCEPCIONES
    </th>
    <th rowspan="2">
        MODIFICAR DETOX
    </th>
    <!-- <th colspan="4">
        Excepciones
    </th>
</tr>
<tr>     
    <th>Día</th>
    <th>Hora Inicio</th>
    <th>Hora Fin</th>
    <th>Mantener Redirección</th>
</tr> -->
</thead>
<tbody class="tbodytablaaux1" >
<tr>
<?php
$_SESSION['resp2'] = $resp2;
$a = 0;
foreach($resp2 as $re2){
        if ($re2->id_usuario == $_GET['id_usuario']){
                    $curr_us2 = $re2;
                    
                   // print_r($curr_us2);
                
        ?>
<?php 
?>  
        <td>
        <?php echo $curr_us2->id_dtx?> 
        </td>
        <td>
        <?php echo $curr_us2->nombre  . " " . $curr_us2->apellidos?>
        </td>
        <td>
        <?php echo $curr_us2->email?>
        </td>
        <td>
        <?php echo $curr_us2->email_redireccion?>
        </td>
        <td>
        <?php echo $curr_us2->fecha_inicio?>
        </td>
        <td>
        <?php echo $curr_us2->fecha_fin?>
        </td>
        <td>
            <?php 
                $_SESSION['id_usuario'] = $_GET['id_usuario'];
            ?>
            <form action="tablaExcepciones.php" style="margin-top: 1em" method="get">
                <input type="hidden" name="id_dtx" value="<?php echo $curr_us2->id_dtx; ?>" />
                <input type="hidden" name="id_usuario" value="<?php echo $curr_us2->id_usuario; ?>" />
                <input type="hidden" name="del_mes" value="0" />
                <input type="submit" class="botonVolverInicio excepcion" value="VER EXCEPCIONES"/>
            </form>
           
        </td>
        <td>
            <a href="" target="_blank" class="botonVolverInicio">MODIFICAR</a>
        </td>
        <!-- <?php foreach ($curr_us2->excepciones as $excepc2 => $exc2){ ?>
        <td>
        <?php echo $exc2->fecha_excepcion;?>    
        </td>
        <td>
        <?php echo $exc2->hora_inicio;?>    
        </td>
        <td>
        <?php echo $exc2->hora_fin;?>    
        </td>
        <td>
        <?php echo $exc2->mantener_redireccion;?>    
        </td>
        <?php } /*end foreach execepciones*/?> -->
    </tr>
    <?php } /*end foreach execepciones*/?>
    <?php } /*end foreach execepciones*/?>
    <?php } /*end foreach execepciones*/?>

</tbody>
</table>

<br>
<br>
<br>

<br>
<a class="botonVolverInicio" href="./seleccionaUsuario.php">Volver Atrás</a> 
</center>
<br><br><br>
</body>
</html>








