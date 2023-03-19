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
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"></script-->
    <!---script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></script-->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script> -->
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script-->
    <!--link media="all" type="text/css" rel="stylesheet" href="http://crowdtest.dev:8888/assets/CSS/style.css"-->
    <!--link media="all" type="text/css" rel="stylesheet" href="http://crowdtest.dev:8888/assets/CSS/jquery-ui-1.10.1.custom.min.css"-->
    <!--link media="all" type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css"-->
    <!--link media="all" type="text/css" rel="stylesheet" href="http://crowdtest.dev:8888/assets/CSS/bootstrap.css"-->
    <!-- <script src="http://code.jquery.com/jquery-1.9.1.js"></script> -->
    <!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script> -->
    <!---script src="http://crowdtest.dev:8888/assets/js/script.js"></script--->
    <!---script src="http://crowdtest.dev:8888/assets/js/jquery-ui-1.10.1.custom.min.js"></script--->
    <!---script src="http://crowdtest.dev:8888/assets/js/bootstrap.js"></script--->
    <title>Selección de usuario para DETOX</title>
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

?>
<h2>Selección de usuario para DETOX</h2><br>
<form action="detoxUsuario.php" method="get">
<br>
<br>
<br>
    <select name="id_usuario" class="selectpicker" 
        data-style="form-control" 
        data-live-search="true"
        title="Seleccione un usuario"
        required="required">
        <?php
    foreach($resp as $res){
        $nombre = $res->nombre;        
        $apellidos = $res->apellidos;
        $id_usuario = $res->id_usuario;
        $email = $res->email;
       // echo "<option value='111'></option>";
       }
        ?>
    </select>
    <br><br>
    <br><br>
    <button class="botonFinal submit" type="submit" >Seleccionar usuario</button>
    <button class="botonFinal2 submit" type="submit" formaction="ObtenerDetoxUsuario.php">Ver DETOX del usuario</button>
    <a href="./ObtenerDetoxDelMes.php" class="botonFinal submit">Ver los últimos DETOX</a>
    </form>
        <?php 
        //if ( $resp2->id_usuario /*== $resp2->id_usuario*/){
                        // print_r($resp2);

        //print_r(json_decode($response2));
        //print_r(json_decode($response2));
        //}
        ?>
<br><br><br><br>
<a class="botonVolverInicio" href="../../general/views/inicio.php">Volver al INICIO</a>

</center>

<script>
    form = document.getElementsByClassName('submit')
    for (var i = 0; i < form.length; i++){
        form[i].addEventListener('click', function(){
            var loading = document.createElement('div');
        loading.style.position = 'fixed';
        loading.style.top = '0';
        loading.style.left = '0';
        loading.style.width = '100%';
        loading.style.height = '100%';
        loading.style.background = 'rgba(0, 0, 0, 0.5)';
        loading.style.zIndex = '9999';
        loading.innerHTML = '<img src="../../general/img/loading.gif" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />';
        document.body.style.backgroundColor = 'black';
        document.body.appendChild(loading);
            })
        }
</script>
</body>
</html>
