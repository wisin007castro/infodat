<?php
require_once '../conexionClass.php';
$conexion = new MiConexion();

$con = $conexion->conectarBD();

$user = $conexion->usuario($_POST['usuario']);//por consultas

date_default_timezone_set('America/La_Paz'); //definiendo zona horaria
$script_tz = date_default_timezone_get();
// echo $script_tz;
$tiempo = getdate();
$fecha = $tiempo['year']."-".$tiempo['mon']."-".$tiempo['mday'];
$hora = $tiempo['hours'].":".$tiempo['minutes'];

$c = 0;
$success = 0;

foreach ($_POST as $key => $value) {
	if (substr($key, 0, 3) == "id-") {
		$c++;
	}
}

if($c > 0){

	if($_POST['direccion'] != ""){
	$sql = "INSERT INTO solicitud (ID_CLIENTE, ID_USER, TIPO_ENVIO, TIPO_CONSULTA, DIRECCION_ENTREGA, OBSERVACION, FECHA_SOLICITUD, HORA_SOLICITUD, ESTADO, REGIONAL) 
		VALUES ('".$_POST['cliente']."','".$_POST['usuario']."','".$_POST['tipo_envio']."','".$_POST['tipo_consulta']."','".$_POST['direccion']."', '".$_POST['observacion']."', '".$fecha."','".$hora."', 'POR PROCESAR','".$_POST['regional']."')";

	if(!$result = mysqli_query($con, $sql)) die();//Guardando la solicitud

	if ($result) {

		$sql = "SELECT * FROM solicitud Order by ID_SOLICITUD desc LIMIT 1";
		if(!$resultado = mysqli_query($con, $sql)) die();

		if($resultado){
			$solicitud = $resultado->fetch_assoc();

		    foreach ($_POST as $key => $value) {
		    	if (substr($key, 0, 3) == "id-") {
					$sql = "INSERT INTO items(ID_CLIENTE, ID_SOLICITUD, ID_INV, ESTADO) 
							VALUES ('".$solicitud['ID_CLIENTE']."', '".$solicitud['ID_SOLICITUD']."','".$value."','".$solicitud['ESTADO']."')";
					if(!$item = mysqli_query($con, $sql)) die();

					$sql_inv = "UPDATE inventarios SET ESTADO = 'POR PROCESAR' WHERE ID_INV = '".$value."' ";
					if(!$resultado = mysqli_query($con, $sql_inv)) die();

					if($item){
						$success++;
					}
		    	}
		    }
		    if ($c == $success) {
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