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
<br><br>
<?php
    if ($_FILES['archivo']['name'] != '')
    {   
        if ($_FILES['archivo']['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        {
            require_once('../models/effort_db_tool.php');
        }
        if (isset($effort_tool)){
            $Codigo = $effort_tool->Codigo;
            $description = $effort_tool->description;
            if ($Codigo == 1) 
            {
                echo '<em><a class="list-group-item list-group-item-action motivos" style="border: 0px;text-align:center;margin-bottom:7px">'.$description.'</a></em>';
            } else {
                echo '<em><a class="list-group-item list-group-item-action motivos" style="border: 0px;text-align:center;margin-bottom:7px">Error en la consulta</a></em>';
            }
        } else {
            echo '<em><a class="list-group-item list-group-item-action motivos" style="border: 0px;text-align:center;margin-bottom:7px">No hay datos</a></em>';
        }
    }
?>
<br><br>
<a class="botonVolverInicioFormulario" href="../" style="align:center">Volver Atrás</a>
    </div>
</div>
</center>
<br><br><br>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>