<?php
echo("TEST-VIS");

$mysqli = new mysqli("192.168.99.241","pyl_user_web","pyl_pass_web","pyl_trazability");
echo("TEST-VIS2");
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

// Perform a query, check for error
if (!$mysqli -> query("SELECT * FROM BASCULAS")) {
  echo("Error description: " . $mysqli -> error);
}

$mysqli -> close();
?>
