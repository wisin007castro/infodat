<?php
require_once '../conexionClass.php';
$conexion = new MiConexion();

$con = $conexion->conectarBD();

$sol_auth = $conexion->solicitud_auth_id($_POST['id_sol']);


$items = $conexion->item_auth($_POST['id_sol']);
// echo var_dump($sol_auth);
$user = $conexion->usuario($sol_auth[0]['ID_USER']);//por consultas

date_default_timezone_set('America/La_Paz'); //definiendo zona horaria
$script_tz = date_default_timezone_get();

$tiempo = getdate();
$fecha = $tiempo['year']."-".$tiempo['mon']."-".$tiempo['mday'];
$hora = $tiempo['hours'].":".$tiempo['minutes'];

$c = 0;
$success = 0;

foreach ($items as $key => $value) {
	$c++;
}

if($c > 0){

	if($sol_auth[0]['DIRECCION_ENTREGA'] != ""){
	$sql = "INSERT INTO solicitud (ID_CLIENTE, ID_USER, TIPO_ENVIO, TIPO_CONSULTA, DIRECCION_ENTREGA, OBSERVACION, FECHA_SOLICITUD, HORA_SOLICITUD, ESTADO, REGIONAL) 
		VALUES ('".$sol_auth[0]['ID_CLIENTE']."','".$sol_auth[0]['ID_USER']."','".$sol_auth[0]['TIPO_ENVIO']."','".$sol_auth[0]['TIPO_CONSULTA']."','".$sol_auth[0]['DIRECCION_ENTREGA']."', '".$sol_auth[0]['OBSERVACION']."', '".$fecha."','".$hora."', 'POR PROCESAR','".$sol_auth[0]['REGIONAL']."')";

	if(!$result = mysqli_query($con, $sql)) die();//Guardando la solicitud

	if ($result) {

		$sql = "SELECT * FROM solicitud Order by ID_SOLICITUD desc LIMIT 1";
		if(!$resultado = mysqli_query($con, $sql)) die();

		if($resultado){
			$solicitud = $resultado->fetch_assoc();

		    foreach ($items as $key => $value) {

				$sql = "INSERT INTO items(ID_CLIENTE, ID_SOLICITUD, ID_INV, ESTADO) 
						VALUES ('".$sol_auth[0]['ID_CLIENTE']."', '".$sol_auth[0]['ID_SOLICITUD']."','".$value['ID_INV']."','".$sol_auth[0]['ESTADO']."')";
				if(!$item = mysqli_query($con, $sql)) die();

				$sql_inv = "UPDATE inventarios SET ESTADO = 'POR PROCESAR' WHERE ID_INV = '".$value['ID_INV']."' ";
				if(!$resultado = mysqli_query($con, $sql_inv)) die();

				if($item){
					$success++;
				}

		    }
		    if ($c == $success) {
				$sql_auth = "UPDATE solicitud_auth SET ESTADO = 'APROBADO' WHERE ID_SOLICITUD = '".$sol_auth[0]['ID_SOLICITUD']."' ";
				if(!$resultado = mysqli_query($con, $sql_auth)) die();
		    	echo "success";
		    }
		    else{
		    	echo "Algo salio mal";
		    }

		}
	}
	}
	else{
		echo "vacio_dir";
	}

}
else{
	echo "vacio";
}

?>