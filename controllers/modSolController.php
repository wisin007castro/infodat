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

if(isset($_POST['id']) && isset($_POST['entrega']) && isset($_POST['usuario'])){
	$entrega = $_POST['entrega'];
	$usuario = $_POST['usuario'];
	$id = $_POST['id'];
	$items = $conexion->item($id);
	$usuario1 = $conexion->usuario($entrega);  //Entregado por
	$usuario2 = $conexion->usuario($usuario);  //Procesado por


	$pedidos = $conexion->pedidos_admin();


$entregado_por = "";
$procesado_por = "";
$count_items = 0;

foreach ($pedidos as $ped) {
	if ($ped['ID_SOLICITUD'] == $id) {
		if ($ped["ESTADO"] == "POR PROCESAR") {
			$estado = "EN PROCESO DE BUSQUEDA";
			$procesado_por = $usuario2[0]['NOMBRE']." ".$usuario2[0]['APELLIDO'];
			$sql = "UPDATE solicitud SET PROCESADO_POR = '".$procesado_por."', ENTREGADO_POR='".$entregado_por."', ESTADO='".$estado."' WHERE ID_SOLICITUD = '".$id."' ";

			foreach ($items as $item) {
				$sql_inv = "UPDATE inventarios SET ESTADO = '".$estado."' WHERE ID_INV = '".$item['ID_INV']."' ";
				if(!$resultado = mysqli_query($con, $sql_inv)) die();
			}
			if(!$resultado = mysqli_query($con, $sql)) die();
			if ($resultado) {
				echo "POR PROCESAR";
			}
			else{
				echo "error";
			}
		}
		elseif ($ped["ESTADO"] == "EN PROCESO DE BUSQUEDA") {
			if($entrega != '0'){
				$estado = "DESPACHADA";
				$procesado_por = $ped['PROCESADO_POR'];
				$entregado_por = $usuario1[0]['NOMBRE']." ".$usuario1[0]['APELLIDO'];
				$sql = "UPDATE solicitud SET PROCESADO_POR = '".$procesado_por."', ENTREGADO_POR='".$entregado_por."', ESTADO='".$estado."' WHERE ID_SOLICITUD = '".$id."' ";
				foreach ($items as $item) {
					foreach ($_POST as $key => $value) {
						if (substr($key, 0, 3) == "id-") {
							if ($value == $item['ID_INV']) {
								$sql_inv = "UPDATE inventarios SET ESTADO = '".$estado."' WHERE ID_INV = '".$item['ID_INV']."' ";
								if(!$resultado = mysqli_query($con, $sql_inv)) die();
								if($resultado){$count_items++;}
							}
						}
					}
				}
	
	$solicitante = $conexion->usuario($ped['ID_USER']);
				// Datos para Email Solicitudes
	$destinatario = $solicitante[0]['CORREO']; //DATOS DEL CLIENTE
	$asunto = "Solicitud de archivos DESPACHADA"; 
	$cuerpo = "
	<html> 
	<head> 
	   <title>SOLICITUD DE CONSULTA INFODAT</title> 
	</head> 
	<body> 
	<h1>Estimad@".$solicitante[0]['NOMBRE']." ".$solicitante[0]['APELLIDO']."</h1> 
	<p> 
	Su solicitud Nro: ".$ped['ID_SOLICITUD']." fue enviada para su entrega a ".$entregado_por."
	<br>
	Para mayor información puede comunicarse al teléfono: 2453147 o al celular: 77231547
	<br>
	
	Favor no responder al remitente (sistema.consultas@infoactiva.com.bo) esta cuenta no es monitoreada, de ser necesario puede enviar un correo a consultas.lp@infoactiva.com.bo
	
	
	Sistema de Consultas/Devoluciones de Documentos INFODAT
	Infoactiva
	</p> 
	</body> 
	</html> 
	"; 
	
	//para el envío en formato HTML 
	$headers = "MIME-Version: 1.0\r\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
	
	//dirección del remitente 
	$headers .= "From: INFODAT <sistema.consultas@infoactiva.com.bo>\r\n"; 
	
	//dirección de respuesta, si queremos que sea distinta que la del remitente 
	// $headers .= "Reply-To: mariano@desarrolloweb.com\r\n"; 
	
	//ruta del mensaje desde origen a destino 
	// $headers .= "Return-path: holahola@desarrolloweb.com\r\n"; 
	
	//direcciones que recibián copia 
	$headers .= "Cc: wissindrako@gmail.com\r\n"; //cambiar a consultas.lp@infoactiva.com.bo 
	//$headers .= "Cc: castrow666@gmail.com\r\n"; //
	
	
	//direcciones que recibirán copia oculta 
	// $headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n"; 
	
			// mail($destinatario,$asunto,$cuerpo,$headers);
				//echo $destinatario." ".$asunto." ".$cuerpo." ".$headers;
				foreach ($items as $item) {
					$sql_des = "UPDATE inventarios SET ESTADO = 'DESESTIMADO' WHERE ESTADO != 'DESPACHADA' AND ID_INV = '".$item['ID_INV']."' ";
					if(!$resultado = mysqli_query($con, $sql_des)) die();
				}


				if($count_items > 0){
					if(!$resultado = mysqli_query($con, $sql)) die();
					if ($resultado) {
						echo "EN PROCESO DE BUSQUEDA";
					}
				}else{
					echo "sin_items";
				}
			}
			else{
				echo "error";
			}

		}
		elseif ($ped["ESTADO"] == "DESPACHADA") {
			$estado = "ATENDIDA/ENTREGADA";
			$procesado_por = $ped['PROCESADO_POR'];
			$entregado_por = $ped['ENTREGADO_POR'];

			$sql = "UPDATE solicitud SET 
			PROCESADO_POR = '".$procesado_por."', FECHA_ENTREGA='".$fecha."', HORA_ENTREGA='".$hora."', ENTREGADO_POR='".$entregado_por."', ESTADO='".$estado."' WHERE ID_SOLICITUD = '".$id."' ";

			foreach ($items as $item) {
				$sql_inv = "UPDATE inventarios SET ESTADO = 'EN CONSULTA' 
				WHERE ID_INV = '".$item['ID_INV']."' AND ESTADO !='DESESTIMADO' ";
				if(!$resultado = mysqli_query($con, $sql_inv)) die();
			}
			if(!$resultado = mysqli_query($con, $sql)) die();
			if ($resultado) {
				echo "DESPACHADA";
			}
			else{
				echo "error-papu";
			}
		}
	}
}

	// try {
	// 	if(!$resultado = mysqli_query($con, $sql)) die();
	// 	if ($resultado) {
	// 		echo "success";
	// 	}
	// } catch (Exception $e) {
	//     echo "Algo salió mal no se pudo modificar a ".$estado;
	// }
// echo "success";
}else{
	// $id = "0";
	// $entrega = "0";
	// $usuario = "0";
}









?>