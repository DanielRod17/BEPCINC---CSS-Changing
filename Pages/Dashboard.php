
<?php
session_start();
$IDUsuario =            $_SESSION['consultor']["ID"];
$UserName =             $_SESSION['consultor']["SN"];
include('../Resources/WebResponses/connection.php');
if (isset($_SESSION['consultor']['Login']) && $_SESSION['consultor']['Login'] == true){
?>
  <html>
      <head>
          <title>

          </title>
      </head>
      <body>
          WIP
      </body>
  </html>
<?php
}
 ?>
