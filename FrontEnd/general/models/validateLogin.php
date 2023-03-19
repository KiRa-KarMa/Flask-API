<?php

if (isset($_SESSION['access_token']))
{
    $t = $_SESSION['access_token'];
    $ch = curl_init ();
    curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Authorization: Bearer '.$t,
                                                'Conent-type: application/json'));
    curl_setopt ($ch, CURLOPT_URL, "https://graph.microsoft.com/v1.0/me/");
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    $rez = json_decode (curl_exec ($ch), 1);
    if (array_key_exists ('error', $rez)){ 
        session_destroy(); 
        header("Location: ../../general/login/login.php");  
        die();
    }
    curl_close ($ch);
} 
else {
    session_destroy();
    header("Location: ../../general/login/login.php");  
    die();
}

?>