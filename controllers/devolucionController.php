<?php   
require_once '../conexionClass.php';
$conexion = new MiConexion();

$con = $conexion->conectarBD();
$user = $conexion->usuario($_POST['usuario']);

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
	if ($_POST['direccion'] != "") {
		$sql = "INSERT INTO devoluciones(ID_CLIENTE, ID_USER, DIRECCION, OBSERVACION, FECHA_SOLICITUD, ESTADO, REGIONAL) 
		VALUES ('".$_POST['cliente']."','".$_POST['usuario']."','".$_POST['direccion']."', '".$_POST['observacion']."', '".$fecha."', 'POR PROCESAR', '".$_POST['regional']."')";

		if(!$result = mysqli_query($con, $sql)) die();

		if ($result) {

			$sql = "SELECT * FROM devoluciones Order by ID_DEV desc LIMIT 1";
			if(!$resultado = mysqli_query($con, $sql)) die();

			if($resultado){
				$devoluciones = $resultado->fetch_assoc();
				// echo json_encode($data);

				foreach ($_POST as $key => $value) {
					if (substr($key, 0, 3) == "id-") {
						$sql = "INSERT INTO dev_item(ID_CLIENTE, ID_DEV, ID_INV, ESTADO) 
								VALUES ('".$devoluciones['ID_CLIENTE']."', '".$devoluciones['ID_DEV']."','".$value."','".$devoluciones['ESTADO']."')";
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
		echo "sin_dirección";
	}
}
else{
	echo "vacio";
}

?>