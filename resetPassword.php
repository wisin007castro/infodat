<?php
require_once("conexionClass.php");
$conexion = new MiConexion();

$connection = $conexion->conectarBD();

	if (isset($_GET["token"]) && isset($_GET["email"])) {
		
		$email = $connection->real_escape_string($_GET["email"]);
		$token = $connection->real_escape_string($_GET["token"]);

		$data = $connection->query("SELECT ID_USER FROM usuarios WHERE CORREO='$email' AND TOKEN='$token' AND TOKEN <> '' ");

		if ($data->num_rows > 0) {
			$str = "0123456789qwertzuioplkjhgfdsayxcvbnm";
			$str = str_shuffle($str);
			$str = substr($str, 0, 10);

			$password = md5($str);

			$connection->query("UPDATE usuarios SET PASS = '$password', TOKEN = '' WHERE CORREO='$email' ");

			echo "
            <div class='col-xs-12'>
                <div style='text-align: center; background-color: #E9FCFA;padding: 8px;margin-bottom: 10px;border: 1px dotted #006600;'>
                  <span class='label label-success'>Password actualizado <i class='fa fa-check'></i></span><br/>
                  <label style='color:#177F6B'>
                    Su nuevo password es: $str
                  </label> 
				        </div>
            </div>
            <div class='col-sm-4'></div>
            <div class='col-sm-4'><a class='btn btn-primary' role='button' href='login.php'>Login</a></div>
            <div class='col-sm-4'></div>";
		} else {
			echo "<br>
            <div class='col-xs-12'>
                <div style='text-align: center; background-color: #FFEDED;padding: 8px;margin-bottom: 10px;border: 1px dotted #FA206A;'>
                  <span class='label label-danger'>Link no valido<i class='fa fa-check'></i></span><br/>
                  <label style='color:#EC1010'>
                    Por favor revisa tu link!
                  </label> 
                </div>
            </div>";
		}
	} else {
		header("Location: login.php");
		exit();
	}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Password Reset</title>

  <!-- CSS de Bootstrap -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/login-css.css">
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:100,300,400,500" > -->
  
</head>
	<body>
	
	</body>
</html>