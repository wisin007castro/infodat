<!-- pruebas.php -->
<?php 
require_once 'conexionClass.php';
	$conexion = new MiConexion();
	$con = $conexion->conectarBD();
	$c = $conexion->cliente("1");
	$u = $conexion->usuario("6");

	// var_dump($c[0]['CLIENTE']);

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

	// include('buscadorClass.php');
	// $Json = new Json;
	// $inventarios = $Json->deptos(2);
	// foreach ($inventarios as $key => $value) {
	// 	echo $value['DEPARTAMENTO'];
	// }

	$perl = "DICTAMEN DE AUDITORES INDEPENDIENTES || AUDITORIA EXTERNA DEL 1? AL 31 DE DIC. 2013 || INFOACTIVA S.R.L. ";
	echo $perl."<br>" ;
	// $perl = substr($perl, 0, 60);
	$descripcion = "";
	while (strlen($perl) >= 40) {

		$descripcion = $descripcion."+".substr($perl, 0, 39);
		$perl = substr($perl, 39);

	}

echo $descripcion." ".$perl;
	
 ?>