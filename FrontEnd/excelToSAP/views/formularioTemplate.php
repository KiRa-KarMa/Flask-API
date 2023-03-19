<?php
require_once ('../../general/models/check_session.php');
// $_SESSION['form-sociedad'];
// $_SESSION['form-tipo-tabla'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../general/css/style.css">
    <link rel="shortcut icon" href="../../general/img/api.PNG">
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
    <title>Obtener plantilla excel</title>
</head>
<body>
    <h1 class="titulo">Obtener plantilla Excel</h1>
<div class="menu" style="margin-bottom: 7%">
    <?php 
        include '../../general/views/menu.php' 
    ?>
</div>
<?php
    if ($_SESSION['form-tipo-tabla'] == 'tabla_usuario')
    {
        require_once('../models/CGetTables.php');
    }
?>
<center>
<div class="my-card" style="">
    <div class="card-body">
        <h4 class="card-title" style="font-weight:bold">Elija la tabla de <?php echo $_SESSION['form-sociedad']; ?> para la que quiere la plantilla</h4>
        <form action="../models/CGetTemplate.php" method="post", enctype="multipart/form-data">
            <div class="form-floating">
            <select class="selectpicker" data-live-search="true" name="TABLA" required>
                <option value="" selected>Selecciona la tabla</option>
            <?php
            if ($_SESSION['form-tipo-tabla'] == 'tabla_usuario')
            {
                if (isset($getTables))
                {
                    $codigo = $getTables-> Codigo;
                    if ($codigo == 1)
                    {
                        $tables = $getTables-> data;
                        for ($i = 0; $i < count($tables); $i++)
                        {
                            if ($_SESSION['form-tipo-tabla'] == 'tabla_usuario')
                            {
                                $table = $tables[$i];
                                if (substr($table, 0, 1,) === '@') 
                                {
                                    echo '<option value="'.$table.'">'.$table.'</option>';
                                }
                            } else
                            {
                                $table = $tables[$i];
                                if (substr($table, 0, 1,) != '@')
                                {
                                    echo '<option value="'.$table.'">'.$table.'</option>';
                                }
                            }
                            
                        }
                    } else 
                    {
                        echo '<option value="sap" disabled>Ha ocurrido un error, contacte con IT.</option>';
                    }
                } else {
                    echo '<option value="sap" disabled>Ha ocurrido un error, contacte con IT.</option>';
                }
            } else {
                echo '<option value="entry_goods">Entrada de mercancías</option>';
                echo '<option value="exit_goods">Salida de mercancías</option>';
                echo '<option value="packs">Creación de packs</option>';
                echo '<option value="pedidos_cliente">Pedidos de cliente</option>';
            }
            ?>
            </select>
            </div><br>
            <button type="submit" class="botonSubmitFormulario" target="_blank">Descargar Plantilla</button>
            <a class="botonVolverInicioFormulario" href="./inicio.php">Volver Atrás</a>
        </form>
    </div><br>
    <div class="card" style="width: 35rem;">
        <div class="card-body">
        <h4 class="card-title" style="font-weight:bold">Autoajustar ancho de columnas en EXCEL</h4><br>
            <ul style="text-align: left">
                <li><em>Seleccione la columna o columnas que desea cambiar.</em></li>
                <li><em>En la pestaña Inicio, en el grupo Celdas, haga clic en la opción Formato.</em></li>
                <center><img src="https://support.content.office.net/es-es/media/54fb03a4-7c7c-4a49-b61c-1476d248efce.png" alt="excel formato"></center>
                <li><em>Haga click en Autoajustar ancho de la columna</em></li>
                <li><em>Listo</em></li>
            </ul>
        </div>
    </div>
</div> <br><br>

    </center><br><br><br>

</body>
</html>