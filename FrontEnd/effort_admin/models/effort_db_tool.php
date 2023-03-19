<?php
$name = $_FILES['archivo']['name'];
$type = $_FILES['archivo']['type'];
$tmp_name = $_FILES['archivo']['tmp_name'];

$url_actual = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

$curl = curl_init();
$data = array("id_usuario" => $_SESSION['user'],
              "archivo" =>curl_file_create($tmp_name, $type, $name));
if (strpos($url_actual, 'preFlaskApi') == false) {
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://192.168.99.196:5000/actualizar_bd_effort',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYHOST=> 0, //ignora el certificado
    CURLOPT_SSL_VERIFYPEER=> 0, //ignora el certificado
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array(
      'x-api-key: qcZ297WGjpCdQAdri6vlC_jo1h-pOt_TLtAdGitYUOR40U7tsdqZU8txVLkT3f1cILxsb65t2HwbYIWROlOEVQ',
      'Content-Type:multipart/form-data'
    ),
  ));
} else {
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://192.168.99.196:3500/actualizar_bd_effort',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYHOST=> 0, //ignora el certificado
    CURLOPT_SSL_VERIFYPEER=> 0, //ignora el certificado
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array(
      'x-api-key: qcZ297WGjpCdQAdri6vlC_jo1h-pOt_TLtAdGitYUOR40U7tsdqZU8txVLkT3f1cILxsb65t2HwbYIWROlOEVQ',
      'Content-Type:multipart/form-data'
    ),
  ));
}

$response = curl_exec($curl);


  // (D4) DONE
  curl_close($curl);

  $effort_tool = json_decode($response);
?>