<?php   
require_once '../conexionClass.php';
$conexion = new MiConexion();

$con = $conexion->conectarBD();

date_default_timezone_set('America/La_Paz'); //definiendo zona horaria
$script_tz = date_default_timezone_get();
// echo $script_tz;
$tiempo = getdate();
$fecha = $tiempo['year']."-".$tiempo['mon']."-".$tiempo['mday'];
$hora = $tiempo['hours'].":".$tiempo['minutes'];

$c = 0;
$success = 0;

foreach ($_POST as $key => $value) {
	if (substr($key, 0, 2) == "id") {
		$c++;
	}
}

if($c > 0){
	$sql = "INSERT INTO solicitud (ID_CLIENTE, ID_USER, TIPO_CONSULTA, DIRECCION_ENTREGA, OBSERVACION, FECHA_SOLICITUD, HORA_SOLICITUD, PROCESADO_POR, ESTADO, REGIONAL) VALUES ('1','1','".$_POST['optionsRadios']."','".$_POST['direccion']."', '".$_POST['observacion']."', '".$fecha."','".$hora."', 'William Castro', 'POR PROCESAR','LA PAZ')";

	if(!$result = mysqli_query($con, $sql)) die();

	if ($result) {
		
		$sql = "SELECT * FROM solicitud Order by ID_SOLICITUD desc LIMIT 1";
		if(!$resultado = mysqli_query($con, $sql)) die();

		if($resultado){
			$solicitud = $resultado->fetch_assoc();
			// echo json_encode($data);

		    foreach ($_POST as $key => $value) {
		    	if (substr($key, 0, 2) == "id") {
					$sql = "INSERT INTO items(ID_CLIENTE, ID_SOLICITUD, ID_INV, ESTADO) 
							VALUES ('".$solicitud['ID_CLIENTE']."', '".$solicitud['ID_SOLICITUD']."','".$value."','".$solicitud['ESTADO']."')";
					if(!$item = mysqli_query($con, $sql)) die();
					if($item){
						$success++;
					}
		    	}
		    }
		    if ($c == $success) {
		    	echo "Solicitud realizada exitosamente";
		    }
		    else{
		    	echo "Algo salio mal";
		    }

		}
	}
}
else{
	echo "Debe seleccionar al menos un item";
}



    // $direccion = $_POST['direccion'];
    // $observacion  = $_POST['observacion'];

    // // echo "tu dirección es: ".$direccion; 
    // // echo "<br>";
    // // echo "observación es: ".$observacion;
    // // var_dump($_POST);

    // foreach ($_POST as $key => $value) {
    // 	if (substr($key, 0, 2) == "id") {
    // 		echo $value;
    // 	}
    // }
?>