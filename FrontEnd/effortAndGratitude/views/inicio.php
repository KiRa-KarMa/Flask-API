<?php
require_once ('../../general/models/check_session.php');
require_once ('../models/getEffortAndGratitude.php');
if(isset($effort))
{
    $puntos = $effort->puntos;
}
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
    <title>Effort&Gratitude</title>
</head>
<body>
<div class="menu">
    <?php 
        include '../../general/views/menu.php' 
    ?>
</div>
<center>
<div style="width:75%;">
    <?php
    if ($puntos > 0)
    {
        echo '<h1 class="titulo">Enorabuena! Hasta el momento has conseguido '.$puntos.' puntos en el programa Effort and gratitude.</h1>';
    } else 
    {
        echo '<h1 class="titulo">Por ahora tienes '.$puntos.' puntos en el programa Effort and gratitude. Sigue esforzándote!!</h1>';
    }
    
    ?>

    <div class="card" style="width: 35rem;">
        <div class="card-body">
            <h5 class="card-title">Que debes tener en cuenta?</h5>
            <ul class="list-group" style="text-align:left ">
                <li class="list-group-item">Todos los empleados tienen 1 punto a destinar a otro empleado al final de cada mes, indicando el motivo por el que se le asigna.</li>
                <li class="list-group-item">Si no das el punto, lo pierdes y no podrás acumularlos en otros meses.</li>
                <li class="list-group-item">Podemos votar a cualquier compañero/a incluido si trabaja en otro país.</li>
            </ul>
            <br>
            <a class="btn btn-primary" href="../archives/Effort&Gratitude.pdf" target="_blank">Como canjear mis puntos</a>
        </div>
    </div>
    <br><br>
    <div class="list-group">
    <h4 style="text-align:left; font-size: 30px; color:#1865BB; font-weight: 600;">Los motivos por los que la gente te ha votado:</h4>
    <br><br><br>
    <?php
        
        if (isset($effort)){
            $Codigo = $effort->Codigo;
            $motivos = $effort->data;
            if ($Codigo == 1) 
            {
                for ($i=0; $i<count($motivos); $i++) 
                {
                    $motivo = $motivos[$i];
                    echo '<em><a class="list-group-item list-group-item-action motivos" style="border: 0px;text-align:left;margin-bottom:7px">'.$motivo.'</a></em>';

                }
            } else {
                echo '<em><a class="list-group-item list-group-item-action motivos" style="border: 0px;text-align:left;margin-bottom:7px">'.$motivo.'</a></em>';
            }
        }

    ?>
    </div>
</div>
</center>
<br><br><br>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>