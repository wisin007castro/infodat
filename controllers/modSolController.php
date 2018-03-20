<?php 
require_once '../conexionClass.php';
$conexion = new MiConexion();
$con = $conexion->conectarBD();

$pedidos = $conexion->pedidos();
$usuario1 = $conexion->usuario($_POST['user']);  //Entregado por
$entregado_por = $usuario1[0]['NOMBRE']." ".$usuario1[0]['APELLIDO'];

$usuario2 = $conexion->usuario($_POST['usuario']);  //Procesado por
$procesado_por = "";

echo $usuario2[0]['NOMBRE'];

// foreach ($pedidos as $ped) {
// 	if ($ped['ID_SOLICITUD'] == $_POST['id']) {
// 		if ($ped["ESTADO"] == "POR PROCESAR") {
// 			$estado = "EN PROCESO DE BUSQUEDA";
// 			$procesado_por = $usuario2[0]['NOMBRE']." ".$usuario2[0]['APELLIDO'];
// 		}
// 		elseif ($ped["ESTADO"] == "EN PROCESO DE BUSQUEDA") {
// 			$estado = "DESPACHADA";
// 			$procesado_por = $ped['PROCESADO_POR'];
// 		}
// 		elseif ($ped["ESTADO"] == "DESPACHADA") {
// 			$estado = "ATENDIDA/ENTREGADA";
// 			$procesado_por = $ped['PROCESADO_POR'];
// 		}
// 	}
// }

// $sql = "UPDATE solicitud SET PROCESADO_POR = '".$procesado_por."', ENTREGADO_POR='".$entregado_por."', ESTADO='".$estado."' WHERE ID_SOLICITUD = '".$_POST['id']."'";

// if(!$resultado = mysqli_query($con, $sql)) die();
// if ($resultado) {
// 	echo "Estado modificado a ".$estado;
// }

?>