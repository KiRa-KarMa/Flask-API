<?php
require_once ('../../general/models/check_session.php');
require_once('../../general/views/menu.php');
$resp2 = $_SESSION['resp2'];
$id = $_SESSION['id_usuario'];
$id_dtx = $_GET['id_dtx'];
?>

<!DOCTYPE html>
<html lang="en">
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
    <title>Excepciones</title>
</head>
<body>
    <div class="menu"></div>
    <br><br><br><br><br><br><br><br>
    <center>

<h2>EXCEPCIONES</h2>
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
    <th colspan="8">
        Excepciones
    </th>
</tr>
<tr>     
    <th>Día</th>
    <th>Hora Inicio</th>
    <th>Hora Fin</th>
    <th>Mantener Redirección</th>
    <th>Día</th>
    <th>Hora Inicio</th>
    <th>Hora Fin</th>
    <th>Mantener Redirección</th>
</tr>
</thead>
<tbody class="tbodytablaaux1" >
<tr>
<?php
// Obtenemos las excepciones del detox seleccionado
foreach($resp2 as $re2){
        if ($re2->id_usuario == $_GET['id_usuario'] && $re2->id_dtx == $id_dtx){
            $curr_us2 = $re2;
        //    print_r($curr_us2); 
                
        ?>
<?php 
?>  
        <td>
        <?php echo $id_dtx?> 
        </td>
        <td>
        <?php echo $curr_us2->nombre  . " " . $curr_us2->apellidos?>
        </td>

        <?php foreach ($curr_us2->excepciones as $excepc2 => $exc2){ ?>
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
        <?php } /*end foreach execepciones*/?>
    </tr>
    <?php } /*end foreach execepciones*/?>
    <?php } /*end foreach execepciones*/?>


</tbody>
</table>

<br>
<br>
<br>

<br>
<?php
    if ($_GET['del_mes'] == 1)
    {
        echo '<a class="botonVolverInicio" href="./ObtenerDetoxDelMes.php">Volver Atrás</a>';
    } else {
        $USER = $_GET['id_usuario'];
        echo <<< EOT
            <a class="botonVolverInicio" href="./ObtenerDetoxUsuario.php?id_usuario=$USER">Volver Atrás</a>  
        EOT;
    }
?>  
</center>

</body>
</html>