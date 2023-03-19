<?php
    require_once ('../../general/models/check_session.php');
    $_SESSION['form-sociedad'] = $_POST['sociedad'];
    $_SESSION['form-tipo-tabla'] = $_POST['tabla'];
    if ($_POST['opcion'] != 'actualizar')
    {
        header("Location: ./formularioTemplate.php");
        die();
    }
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
<?php
    if ($_FILES['archivo']['name'] != '')
    {   
        if ($_FILES['archivo']['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        {
            if ($_SESSION['form-tipo-tabla'] == 'tabla_usuario')
            {
                require_once('../models/excelToSAP.php');
            } else {
                require_once('../models/excelToSAP_sistemas.php');
            }
        }
    } 
?>
<div class="menu" style="margin-bottom: 7%">
    <?php 
        include '../../general/views/menu.php' 
    ?>
</div>
<h1 class="titulo">ExcelToSAP</h1>
<center>
<div style="width:75%">
    <h4 style="text-align:left; font-size: 30px; color:#1865BB; font-weight: 600;">Elementos que se han actualizado en <?php echo $_SESSION['form-sociedad']; ?>:</h4>
    <br>
    <table class="table table-striped">
    <?php
        if (isset($excelToSAP)){
            $Codigo = $excelToSAP->Codigo;
            $description = $excelToSAP->description;
            if ($Codigo == 1) 
            {
                if ($_SESSION['form-tipo-tabla'] == 'tabla_usuario')
                {
                    // Actualizacion a una tabla de usuario
                    $data = $excelToSAP->data;               
                    echo '<p style="font-weight:bold; color:Red">Se han actualizado '.count($data).' elementos.</p>';
                    $keys = array();
                    while ($x = current($data[0])) {
                        $key = key($data[0]);
                        array_push($keys, $key);
                    next($data[0]);
                    }
                    echo '<tr class="table-dark">';
                    for ($i = 0; $i < count($keys); $i++)
                    {
                        echo '<td>'.$keys[$i].'</td>';
                    }
                    echo '</tr>';
                    $values = array(); 
                    for ($i=0; $i<count($data); $i++) 
                    { 
                        echo '<tr>';
                        for ($j = 0; $j < count($keys); $j++)
                        {
                            $key = $keys[$j];
                            echo '<td>'.$data[$i]->$key.'</td>';
                        }
                        echo '</tr>';
                    }
                } else {
                    // Si es una actualizacion mediante API a una tabla propia de SAP
                    if (gettype($description) == 'string')
                    {
                        echo '<p style="font-weight:bold; color:Red">'.$description.'</p>';
                    } else
                    {
                        echo '<p style="font-weight:bold; color:Red">Se han actualizado '.count($description).' documentos.</p>';
                        echo '<tr class="table-dark">';
                        echo '<td>DocEntry</td>';
                        echo '<td>DocNum</td>';
                        echo '<td>ItemCode</td>';
                        echo '<td>ItemDescription</td>';
                        echo '<tr>';
                        // echo var_dump($description);
                        for ($i = 0; $i < count($description); $i++)
                        {
                            // echo var_dump($description[$i]);
                            $doc_num = $description[$i]->DocNum;
                            $doc_entry = $description[$i]->DocEntry;
                            $document_lines = $description[$i]->DocumentLines;
                            for ($j = 0; $j < count($document_lines); $j++)
                            {
                                $item_code = $document_lines[$j]->ItemCode;
                                $item_description = $document_lines[$j]->ItemDescription;
                                echo '<tr>';
                                echo '<td>'.$doc_num.'</td>';
                                echo '<td>'.$doc_entry.'</td>';                      
                                echo '<td>'.$item_code.'</td>';
                                echo '<td>'.$item_description.'</td>';
                                echo '</tr>';
                            }
                        }
                    }
                }
            } else {
                echo '<em><a class="list-group-item list-group-item-action motivos" style="border: 0px;text-align:left;margin-bottom:7px">Error: '.$description.'</a></em>';
                echo '<br>';
                if (property_exists($excelToSAP, 'Actualizados'))
                {
                    $actualizados = $excelToSAP->Actualizados;
                    echo '<p style="font-weight:bold; color:Red">Se han llegado a actualizar '.count($actualizados).' elementos.</p>';
                    echo '<tr class="table-dark">';
                    echo '<td>DocEntry</td>';
                    echo '<td>DocNum</td>';
                    echo '<td>ItemCode</td>';
                    echo '<td>ItemDescription</td>';
                    echo '<tr>';
                    for ($i = 0; $i < count($actualizados); $i++)
                    {
                        $doc_num = $actualizados[$i]->DocNum;
                        $doc_entry = $actualizados[$i]->DocEntry;
                        $document_lines = $actualizados[$i]->DocumentLines;
                        for ($j = 0; $j < count($document_lines); $j++)
                        {
                            $item_code = $document_lines[$j]->ItemCode;
                            $item_description = $document_lines[$j]->ItemDescription;
                            echo '<tr>';
                            echo '<td>'.$doc_num.'</td>';
                            echo '<td>'.$doc_entry.'</td>';                      
                            echo '<td>'.$item_code.'</td>';
                            echo '<td>'.$item_description.'</td>';
                            echo '</tr>';
                        }
                    }
                }
            }
        } else 
        {
            echo '<h3>Por favor suba un excel...</h3>';
        }

    ?>
    </table>
    <br><br>
    <a class="botonVolverInicio" href="../">Volver Atrás</a>
    <br><br>
</div></center>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    
</body>
</html>
