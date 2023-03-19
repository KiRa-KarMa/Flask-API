<?php 
    session_start();
    $_SESSION['access_token'] = 'zero';
    session_destroy();
    $tenantid = "6d3a8f5f-5299-4f43-ac60-259a52caed3b";
    $logout_url = "https://login.microsoftonline.com/".$tenantid."/oauth2/v2.0/logout?post_logout_redirect_uri=https://192.168.99.197";
    header("Location: ".$logout_url."");
?>