<?php
    // require_once ('../../general/models/check_session.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../general/css/style.css">
    <link rel="shortcut icon" href="../../general/img/api.PNG">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>ExcelToSAP</title>
</head>
<body>
<div class="menu" style="margin-bottom: 7%">
    <?php 
        include '../../general/views/menu.php' 
    ?>
</div>
<h1 class="titulo">ExcelToSAP</h1>
<?php 
require_once('../models/CGetSchemas.php'); 
?>
<div class="container">
    <center>
    <div class="card" style="width: 35rem;">
        <div class="card-body">
            <h5 class="card-title">Formulario</h5>
            <form action="./actualizarExcel.php" method="post" onsubmit="return alerta(event);" id="my-form" enctype="multipart/form-data">
            <div class="form-check" style="text-align:left">
                <input class="form-check-input" type="radio" name="opcion" value="actualizar" id="flexRadioDefault1" checked>
                <label class="form-check-label" for="flexRadioDefault1">
                    Actualizar
                </label>
                </div>
                <div class="form-check" style="text-align:left">
                <input class="form-check-input" type="radio" name="opcion" value="descargar" id="flexRadioDefault2">
                <label class="form-check-label" for="flexRadioDefault2">
                    Descargar Plantilla
                </label>
            </div><br>
                <div class="form-floating">
                <select class="form-select" id="floatingSelect" name="tabla" required>
                    <option value="" selected>Selecciona tipo de tabla</option>
                    <option value="tabla_usuario">Tabla Usuario</option>
                    <option value="sap">Tabla propia de SAP</option>
                </select>
                <label for="floatingSelect">Tipo Tabla:</label>
                </div>
                <br>
                <div class="form-floating">
                <select class="form-select" id="floatingSelect" name="sociedad" required>
                    <option value="" selected>Selecciona la sociedad</option>
                <?php
                if (isset($getSchemas))
                {
                    $codigo = $getSchemas-> Codigo;
                    if ($codigo == 1)
                    {
                        $schemas = $getSchemas-> data;
                        for ($i = 0; $i < count($schemas); $i++)
                        {
                            $schema = $schemas[$i];
                            echo '<option value="'.$schema.'">'.$schema.'</option>';
                        }
                    } else 
                    {
                        echo '<option value="sap" disabled>Ha ocurrido un error, contacte con IT.</option>';
                    }
                } else {
                    echo '<option value="sap" disabled>Ha ocurrido un error, contacte con IT.</option>';
                }
                ?>
                </select>
                <label for="floatingSelect">Sociedad:</label>
                </div><br>
                <input type="file" class="form-control" name="archivo"><br>
                <button type="submit" class="botonSubmitFormulario">Siguiente</button>
                <input type="reset" class="botonBorrarFormulario" value="Limpiar formulario">
                <br><br><br>
                <a class="botonVolverInicioFormulario" href="../../" style="">Volver Atrás</a>
            </form>
        </div>
    </div><br>
        </div>
    </div>
    </center>
</div>
        <!-- borrar el gif -->
        <!-- <center>
          <div align="center">
            <img src="../../general/img/construccion.gif" style="width:22em; border-radius:10px">
            <h3>Esta página está en construcción...</h3><br>
            <a class="botonVolverInicio" href="../../">Volver Atrás</a>
          </div>
        </center> -->
<br><br><br><br><br>
<script>
        function alerta(){
            var radios = document.getElementsByName('opcion');
            for (var radio of radios)
            {
                if (radio.checked) {
                    opcion = radio.value;
                }
            }
            if (opcion == 'actualizar')
            {
                if (window.confirm("¿Estás seguro de actualizar con el siguiente excel?")) {
                    console.log('Actualizar');
                    return true;
                } else {
                    console.log('NO Actualizar');
                    return false;
                }
            }
        }
        
    </script>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

<script>
    form = document.getElementById('my-form')
    form.onsubmit = function() {
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
}
</script>
</body>
</html>