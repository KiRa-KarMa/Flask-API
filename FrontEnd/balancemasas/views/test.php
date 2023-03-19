<?php
echo("HOLA QUE ASE");
//require_once("../models/CBalanceMasasRepeladoBD.php");
//$repelado = new CBalanceMasasRepeladoBD;
//$repelado->seleccionarRep();
//echo ($repelado->KG_OBJETIVO);
require_once('../models/CSGDolaresBD.php');
$SGDolares = new CSGDolaresBD; 
    if ($SGDolaresSC->seleccionarSC_products())
    {
foreach ($SGDolaresSC->filas as $fila)
        {         
    ?>
<tr>
<td style="width:20%"><img width="200" height="200" src="<?php echo $fila->imagen; ?>"></td>
        <td class="AlineaTxt"><?php echo "<b>PRODUCT: </b>".$fila->product.'</br>';
                    echo "<b>ORIGIN: </b>".$fila->origin.'</br>';
                    echo "<b>QUANTITY(KG): </b>".$fila->quantity.'</br>';
                    echo "<b>BASIS: </b>".$fila->basis.'  (DUTY UNPAID)';

        ?></td>
        <td><?php echo "<b>".number_format($fila->precio_dolar, 2)." $/kg</b>"; ?></td>
        <td><?php echo $fila->packaging; ?></td>
        <td><?php echo $fila->disponible; ?></td>
        <td class="imgCargo"><?php 
        if($fila->seethecargo != "NULL"){
            echo "<a href=\"$fila->seethecargo\"><img class=\"imgCargo\" src=\"https://www.calconut.com/semanal/img/seethecargo.jpg\"></a>";
        }
        else echo "";
        ?></a></td>
    </tr>

<?php}}
echo mysqli_error();
echo("HOLA");
?>
