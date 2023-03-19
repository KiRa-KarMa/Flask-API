<?php
require_once ('../../general/models/check_session.php');
if ($_SESSION['form-tipo-tabla'] == 'tabla_usuario')
{
    $entrada = '{"database":'.'"'.$_SESSION['form-sociedad'].'",
    "table": "'.$_POST['TABLA'].'",
    "id_microsoft": "'.$_SESSION['id_microsoft'].'"}';
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://192.168.99.196:5000//ExcelToSAP/GetTemplates',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_SSL_VERIFYHOST=> 0, //ignora el certificado
      CURLOPT_SSL_VERIFYPEER=> 0, //ignora el certificado
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_POSTFIELDS => $entrada,
      CURLOPT_HTTPHEADER => array(
        'x-api-key: qcZ297WGjpCdQAdri6vlC_jo1h-pOt_TLtAdGitYUOR40U7tsdqZU8txVLkT3f1cILxsb65t2HwbYIWROlOEVQ',
        'api-key: DeadAss',
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    $tmp_name = '../plantillas/plantilla-'.$_SESSION['id_microsoft'].'.xlsx';
    $fd = fopen($tmp_name, 'w');
        fwrite($fd, $response);
    fclose($fd);
    
    curl_close($curl);
    
    header ('Location: ../plantillas/plantilla-'.$_SESSION['id_microsoft'].'.xlsx');
    exit();
} else
{
    if ($_POST['TABLA'] == 'entry_goods')
    {
        header ('Location: ../plantillas/entrada_mercancias.xlsx');
        exit();
    }
    if ($_POST['TABLA'] == 'exit_goods')
    {
        header ('Location: ../plantillas/salida_mercancias.xlsx');
        exit();
    }
    if ($_POST['TABLA'] == 'packs')
    {
        header ('Location: ../plantillas/lotes.xlsx');
        exit();
    }
    if ($_POST['TABLA'] == 'pedidos_cliente')
    {
        header ('Location: ../plantillas/pedidos.xlsx');
        exit();
    }
}



?>