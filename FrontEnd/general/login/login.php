<?php
session_start();

if (isset($_SESSION['access_token'])){
    header ('Location: ../../general/views/inicio.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" style type="text/css"  href="../css/style.css">
  <link rel="icon" type="image/x-icon"  href="../img/api.PNG">
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <?php include '../../general/views/menu.php' ?>
</head>

<body onload="parseUrl();">
<?php 
$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
if (strpos($actual_link, 'preFlaskApi'))
{
    $appid = "ITS-SECRET";
    $redirect_uri = 'https://192.168.99.197/preFlaskApi/general/views/inicio.php';
} else 
{
    $appid = "ITS-SECRET";
    $redirect_uri = 'https://192.168.99.197/general/views/inicio.php';
}

$tenantid = "ITS-SECRET";
$secret = "ITS-SECRET";
$login_url = "http://login.microsoftonline.com/".$tenantid."/oauth2/v2.0/authorize";
$logout_url = "https://login.microsoftonline.com/".$tenantid."/oauth2/v2.0/logout";


$_SESSION['state']=session_id();
?>
<br><br><br><br><br><br><br><br><br>


<div ALIGN="center"><img style="width:30%; margin-bottom:13px" src="../img/api.PNG"></div>



    

<div ALIGN="center"><p><a class="botonVolverInicio" style="border-radius: 40px;" href="?action=login">Login con Microsoft</a> </p></div>
<div ALIGN="center"><p><a class="botonHEALTHY" style="border-radius: 40px;" href="../views/inicioInvitado.php">Entrar como Invitado</a> </p></div>

<?php
if (isset($_GET['action'])){
    if ($_GET['action'] == 'login'){
        $params = array ('client_id' =>$appid,
    'redirect_uri' => $redirect_uri,
            'response_type' =>'token',
            'scope' =>'https://graph.microsoft.com/User.Read',
            'state' =>$_SESSION['state']);
        header ('Location: '.$login_url.'?'.http_build_query ($params));
    }
}


if (array_key_exists ('access_token', $_GET))
{
    $_SESSION['access_token'] = $_GET['access_token'];
    $t = $_SESSION['access_token'];
    $ch = curl_init ();
    curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Authorization: Bearer '.$t,
                                                'Conent-type: application/json'));
    curl_setopt ($ch, CURLOPT_URL, "https://graph.microsoft.com/v1.0/me/");
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    $rez = json_decode (curl_exec ($ch), 1);
    $_SESSION['id_microsoft'] = $rez['id'];
    if (array_key_exists ('error', $rez)){  
        var_dump ($rez['error']);    
        die();
    }
    curl_close ($ch);
}

if (isset($_SESSION['access_token'])){
    header ('Location: ../../general/views/inicio.php');
    exit();
}



?>
<script> 
    function parseUrl(){
        url = window.location.href;
        i=url.indexOf("#");
        if(i>0) {
        url=url.replace("#","?");
        window.location.href=url;}
    }
    
</script>
</body>