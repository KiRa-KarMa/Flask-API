<?php
require_once ('../models/check_session.php');
require_once ('../models/CGetUser.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../general/css/style.css">
  <link rel="shortcut icon" href="../img/api.PNG">
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  
  <title>Herramientas Disponibles</title>
</head>

<body>
<?php
// Para conseguir el id del usuario mediante el id_microsoft y asi mostrar solo las herramientas
// disponibles para ese usuario.
for ($i=0; $i < count($resp2); $i++) 
{
  $id_microsoft = $resp2[$i] -> id_microsoft;
  if ($id_microsoft == $_SESSION['id_microsoft'])
  {
    $usuario = $resp2[$i] -> id_usuario;
    break;
  }
}

$_SESSION['user'] = '{
  "id_usuario":'.$usuario.'
}';
// Pasamos el id por parámetro para que devuelva sus herramientas
require_once ('../models/CGetUserRole.php');
?>
<div class="menu">
    <?php include '../../general/views/menu.php' ?>
</div>
<br>
<br>
<br>
<br>
<br>

<h2>
</h2>
<br>
<br>
<div class="container" style="width: 75%">
      <?php
      // Listamos las herramientas
      echo '<div class="row d-flex justify-content-around ">';
      $lista = array();
      for ($i = 0; $i < count($resp3); $i++)
      {
        $herramientas = $resp3[$i] -> herramientas;
        for ($j = 0; $j < count($herramientas); $j++)
        {
          $app = $herramientas[$j] -> nombre;
          $comprobador = 0;
          for ($x = 0; $x < count($lista); $x++)
          {
            if ($lista[$x] == $app){
              $comprobador = 1;
              break;
            }
          }
          if ($comprobador == 1)
          {
            continue;
          }
          echo '<div class="col-sm-3">';
            echo '<a style="text-decoration: none;"  href="'.$herramientas[$j]->ruta_php.'" target="_blank">';
            echo '<div class="alert alert-primary" style="height:20em;">';
              echo '<center><div class="box" align="center">';
                echo '<img src="'.$herramientas[$j]->ruta_img.'" style="max-height:205px; max-width:305px;  border-radius:10px;">';
              echo '</div></center>';
              echo '<br>';
              echo '<div class="wrap">';
                echo '<p class="textoCard">'.$app.'</p>';
              echo '</div>';
            echo '</div>';
            echo '</a>';
          echo '</div>';
          array_push($lista, $app);
        }
      }
      ?>
  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


</body>
</html>