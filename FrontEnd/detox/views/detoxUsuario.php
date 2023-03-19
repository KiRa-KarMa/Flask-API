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
    <link rel="icon" type="image/x-icon" href="../../../general/img/api.PNG">
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>DETOX</title>
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
foreach ($resp as $i){
    if ($i->id_usuario == $_GET['id_usuario']){
        $curr_us = $i;
    }
}
?>
<br><br>
<center>
<h2>DETOX para <?php echo $curr_us->nombre . " " . $curr_us->apellidos;?></h2><br>
<form action="./detoxUsuarioExcep.php" method="get">
        <br>
        <label>ID:</label>
        <input type="text" name="id_usuario" size="31" maxlength="5" readonly="" value="<?php echo $curr_us->id_usuario?>"/>
        <br>
        <label>Nombre:</label>
        <input type="text" name="nombreApellidos" size="31" maxlength="100" readonly="" value="<?php echo $curr_us->nombre . " " . $curr_us->apellidos;?>"/>
        <br>
        <label>Email:</label>
        <input type="text" name="email" readonly="" size="31" maxlength="100" value="<?php echo $curr_us->email;?>"/>
        <br><br>
        <label>Fecha DESDE:</label>
        <input type="date" date-format="dd/MM/yyyy" id="fecha_inicio" name="fecha_inicio" value=""> 
        &nbsp;&nbsp;&nbsp;&nbsp;
        <label>Hora DESDE:</label>
        <input type="time" name="hora_empezar" value="00:00">  
        <br>
        <label>Fecha HASTA:</label>
        <input type="date" date-format="dd/MM/yyyy" name="fecha_fin" value="">  
        &nbsp;&nbsp;&nbsp;&nbsp;
        <label>Hora HASTA:</label>
        <input type="time" name="hora_terminar" value="18:00">  
        <br><br><br>
        <label>Correo Redirección</label>
        <br><br>
        <select name="email_redireccion" class="selectpicker" 
        data-style="form-control" 
        data-live-search="true"
        title="Seleccione un usuario">
            <option value="-">No se redirecciona a nadie</option>

        <?php
            foreach($resp as $res){
                if ($res->id_usuario == $curr_us->id_usuario){
                    continue;
                }
                $email = $res->email;  
                }?>
        <?php
            $start_row = 2; //define start row
            $col=3;
            $i = 1; //define row count flag
            $file = fopen("./Groups.csv", "r");
            while (($row = fgetcsv($file)) !== FALSE) {
            if($i >= $start_row) {
                $total= $row[$col]. ",";
                $arrayResultados = explode(",", $total);
                foreach ($arrayResultados as $arrRes[0] => $arRe) {
                    if ($arRe == ""){
                        continue;
                    }else
                    echo "<option value=\"$arRe\">$arRe</option>";
                }
                }
                $i++;
            }
            fclose($file);
        ?>
        </select>
        <br><br>
<label class="mensajeAviso">Recuerda que se debe indicar el primer y el último día que la persona tiene detox. Es decir, el primer y el último día de vacaciones.</label>
<br><br>
<button class="botonFinal" type="submit">Siguiente</button>
</form>
<br><br>
<br><br>
<a class="botonVolverInicio" href="./seleccionaUsuario.php">Volver al INICIO</a>
</center>
<script>
    var fecha_inicio = document.getElementById('fecha_inicio');
    fecha_inicio.setAttribute('min', new Date().toISOString().split('T')[0]);
</script>
</body>
</html>
