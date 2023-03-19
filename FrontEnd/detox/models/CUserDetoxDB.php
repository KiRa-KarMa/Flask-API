<?php
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => 'https://192.168.99.196:5000/GetUsers',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'GET',
CURLOPT_SSL_VERIFYHOST=> 0, //ignora el certificado
CURLOPT_SSL_VERIFYPEER=> 0, //ignora el certificado
CURLOPT_HTTPHEADER => array(
  'x-api-key: qcZ297WGjpCdQAdri6vlC_jo1h-pOt_TLtAdGitYUOR40U7tsdqZU8txVLkT3f1cILxsb65t2HwbYIWROlOEVQ',
  'api-key: DeadAss',
  'Content-Type: application/json'
),
));

$response = curl_exec($curl);

curl_close($curl);
$resp = json_decode($response);
$dict_aux = [];
?>