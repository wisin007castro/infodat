<!-- pruebas.php -->
<?php 
require_once 'conexionClass.php';
	$conexion = new MiConexion();
	$con = $conexion->conectarBD();
	$c = $conexion->cliente("1");
	$u = $conexion->usuario("6");

	var_dump($c[0]['CLIENTE']);

	// $cliente = $c['CLIENTE'];
	// $usuario = $u['NOMBRE']." ".$u['APELLIDO'];

	// date_default_timezone_set('America/La_Paz'); //definiendo zona horaria
	// $script_tz = date_default_timezone_get();
	// // echo $script_tz;
	// $tiempo = getdate();
	// $fecha = $tiempo['year']."-".$tiempo['mon']."-".$tiempo['mday'];
	// $hora = $tiempo['hours'].":".$tiempo['minutes'];

	// $sql = "INSERT INTO log_eventos (ID_CLIENTE, ID_USER, FECHA, HORA, EVENTO) 
	// 	VALUES ('".$cliente."','".$usuario."', '".$fecha."','".$hora."','".$evento."')";

	// if(!$result = mysqli_query($con, $sql)) die();
	// }

 ?>