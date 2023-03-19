<?php
$url_actual = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
$obj = json_decode($_SESSION['user']);
$id_us = $obj->id_usuario;

$curl = curl_init();
$data = array("id_usuario" => $_SESSION['user']);
if (strpos($url_actual, 'preFlaskApi') == false) {
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://192.168.99.196:5000/mandar_mails_effort?id_usuario='.$id_us,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_SSL_VERIFYHOST=> 0, //ignora el certificado
      CURLOPT_SSL_VERIFYPEER=> 0, //ignora el certificado
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $_SESSION['user'],
      CURLOPT_HTTPHEADER => array(
        'x-api-key: qcZ297WGjpCdQAdri6vlC_jo1h-pOt_TLtAdGitYUOR40U7tsdqZU8txVLkT3f1cILxsb65t2HwbYIWROlOEVQ',
        'api-key: DeadAss',
        'Content-Type: application/json'
      ),
    ));
} else {
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://192.168.99.196:3500/mandar_mails_effort?id_usuario='.$id_us,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYHOST=> 0, //ignora el certificado
    CURLOPT_SSL_VERIFYPEER=> 0, //ignora el certificado
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $_SESSION['user'],
    CURLOPT_HTTPHEADER => array(
      'x-api-key: qcZ297WGjpCdQAdri6vlC_jo1h-pOt_TLtAdGitYUOR40U7tsdqZU8txVLkT3f1cILxsb65t2HwbYIWROlOEVQ',
      'api-key: DeadAss',
      'Content-Type:application/json'
    ),
  ));
}

$response = curl_exec($curl);


  // (D4) DONE
  curl_close($curl);

  $effort_mail = json_decode($response);
?>