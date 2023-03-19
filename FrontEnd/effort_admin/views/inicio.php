<?php
require_once ('../../general/models/check_session.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../general/css/style.css">
    <link rel="shortcut icon" href="../../general/img/api.PNG">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>Effort&Gratitude Tool</title>
</head>
<body>
<div class="menu">
    <?php 
        include '../../general/views/menu.php'
    ?>
</div>
<center>
<div style="width:75%;">
<h1 class="titulo">Effort&Gratitude Tool</h1>
    <div class="card" style="width: 35rem;">
        <div class="card-body">
            <h5 class="card-title">Formulario</h5>
            <form action="../Controllers/effort_res.php" method="post" onsubmit="return alerta(event);" id="my-form" enctype="multipart/form-data">
                <br>
                <input type="file" class="form-control" name="archivo" required><br>
                <button type="submit" class="botonSubmitFormulario">Actualizar</button>
                <input type="reset" class="botonBorrarFormulario" value="Limpiar formulario">
                <br>
            </form>
            <form action="../Controllers/effort_mails.php" method="post" onsubmit="return alerta(event);" id="my-form" enctype="multipart/form-data">
                <br>
                <button type="submit" class="btn btn-warning">Enviar emails</button>
                <br><br><br>
                <a class="botonVolverInicioFormulario" href="../../" style="">Volver Atrás</a>
            </form>
        </div>
    </div><br>
    <br><br>
    <div class="list-group">
    <br><br><br>
</div>
</center>
<br><br><br>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>