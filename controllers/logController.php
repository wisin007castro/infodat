<?php   
require_once 'conexionClass.php';

Class Logs{

	function eventos($id_clie, $id_user, $evento){
		$conexion = new MiConexion();
		$con = $conexion->conectarBD();
		$c = $conexion->cliente($id_clie);
		$u = $conexion->usuario($id_user);

		$cliente = $c[0]['CLIENTE'];
		$usuario = $u[0]['NOMBRE']." ".$u[0]['APELLIDO'];


		date_default_timezone_set('America/La_Paz'); //definiendo zona horaria
		$script_tz = date_default_timezone_get();
		// echo $script_tz;
		$tiempo = getdate();
		$fecha = $tiempo['year']."-".$tiempo['mon']."-".$tiempo['mday'];
		$hora = $tiempo['hours'].":".$tiempo['minutes'];

		$sql = "INSERT INTO log_eventos (CLIENTE, USUARIO, FECHA, HORA, EVENTO) 
			VALUES ('".$cliente."','".$usuario."', '".$fecha."','".$hora."','".$evento."')";

		if(!$result = mysqli_query($con, $sql)) die();
		}
}

?>