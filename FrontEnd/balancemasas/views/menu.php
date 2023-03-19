<?php 
    $tenantid = "6d3a8f5f-5299-4f43-ac60-259a52caed3b";
    $logout_url = "https://login.microsoftonline.com/".$tenantid."/oauth2/v2.0/logout?post_logout_redirect_uri=https://192.168.99.197";
?>
<title>CALCOTOOLS</title>
<table class="menu">
    <tr class="menusuperior">
    <td colspan="5"> </td> 
    <td class="login"><center><img src="../../general/img/icon_user[0].bmp" style="width:7%;">&nbsp;&nbsp;&nbsp;
    <a id="logout" href="<?php echo $logout_url;?>">Logout</a></center></td>
    </tr>
</table>