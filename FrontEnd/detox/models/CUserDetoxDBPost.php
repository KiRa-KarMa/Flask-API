<?php
//print_r($_GET);

$arr = array(
  "id_usuario" => intval($_GET['id_usuario']),
  "fecha_inicio" => date('d/m/Y', strtotime($_GET['fecha_inicio'])),
  "fecha_fin" => date('d/m/Y', strtotime($_GET['fecha_fin']) ),
  "hora_empezar" => $_GET['hora_empezar'],
  "hora_terminar" => $_GET['hora_terminar'],
  "email_redireccion" => $_GET['email_redireccion'],
  "Excepciones" => unserialize($_GET['detox_arrast'])
  );
$jsonstr = json_encode($arr);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://192.168.99.196:5000/AutoDetox/PostDetox',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$jsonstr,
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

//print_r($response);

if(json_decode($response)->Codigo == 1){
  echo "<br><br><center>Detox dado de alta correctamente<br><br>";
  echo '<a id="botonInicio" href="../index.php">Volver al inicio</a></center>';
//  echo $response;
}
else{
  echo "<br><br><center>Se ha producido un error, por favor contacte con IT</center>";
  ///echo $response['descripci\u00f3n'];
    echo $response;
  echo "<br><br><br><center>".json_decode($response)->descripción."</center>";
}
/*
//if ($response->codigo == '1'){

}*/
?>
