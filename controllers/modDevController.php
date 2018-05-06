<?php

require_once '../conexionClass.php';
$conexion = new MiConexion();
$con = $conexion->conectarBD();

$id = $_POST['id'];
$fecha = $_POST['fecha']; 

$items = $conexion->dev_item($_POST['id']);

$devoluciones = $conexion->devoluciones_admin();

$solicitante = $conexion->usuario($_POST['ID_USER']);

$usuario1 = $conexion->usuario($_POST['recogido']);  //Entregado por
$recogido_por = "";

$usuario2 = $conexion->usuario($_POST['usuario']);  //Procesado por
$procesado_por = "";

if ($fecha != '') {

	foreach ($devoluciones as $dev) {
		if ($dev['ID_DEV'] == $id) {

			$solicitante = $conexion->usuario($dev['ID_USER']);
			$cliente = $conexion->cliente($dev['ID_CLIENTE']);
			if($dev['REGIONAL'] == 'LP'){
				$reply_to = 'consultas.lp@infoactiva.com.bo';
			}
			else{
				$reply_to = 'consultas.scz@infoactiva.com.bo';
			}
			if ($dev['ESTADO'] == "POR PROCESAR") {
				$estado = "PROGRAMADA";
				$procesado_por = $usuario2[0]['NOMBRE']." ".$usuario2[0]['APELLIDO'];
				$sql = "UPDATE devoluciones SET PROCESADO_POR = '".$procesado_por."', FECHA_PROGRAMADA = '".$fecha."', RECOGIDO_POR = '".$recogido_por."', ESTADO = '".$estado."' WHERE ID_DEV = '".$id."' ";
				foreach ($items as $item) {
					$sql_inv = "UPDATE inventarios SET ESTADO = '".$estado."' WHERE ID_INV = '".$item['ID_INV']."' ";
					if(!$resultado = mysqli_query($con, $sql_inv)) die();
				}
				if(!$resultado = mysqli_query($con, $sql)) die();

				// Datos para Email Solicitudes
				$destinatario = $solicitante[0]['CORREO']; //DATOS DEL CLIENTE
				$asunto = "solicitud de devolución Nro: ".$dev['ID_DEV'].", Cliente: ".$cliente[0]['CLIENTE']; 
				$cuerpo = "
				<html>
				<head>
				<title>SOLICITUD DE CONSULTA DE DEVOLUCION INFODAT</title> 
				</head> 
				<body> 
				<h3>Estimad@ ".$solicitante[0]['NOMBRE']." ".$solicitante[0]['APELLIDO']."</h3> 
				<p> 
				Su solicitud de devolución Nro: ".$dev['ID_DEV'].", fue programada por: ".$procesado_por.", con fecha: ".$fecha.", para realizar el seguimiento a su solicitud puede ingresar al menú ESTADO y seleccionar Solicitud de Documentos. 
				
				Para mayor información puede comunicarse al teléfono: 2453147 o al celular: 77231547
				
				Sistema de Consultas/Devoluciones de Documentos INFODAT
				Infoactiva ®
				</p> 
				</body> 
				</html> 
				"; 
				//para el envío en formato HTML 
				$headers = "MIME-Version: 1.0\r\n"; 
				$headers .= "Content-type: text/html; charset=utf-8\r\n"; 
				//dirección del remitente 
				$headers .= "From: INFODAT <sistema.consultas@infoactiva.com.bo>\r\n"; 
				//dirección de respuesta, si queremos que sea distinta que la del remitente 
				$headers .= "Reply-To: ".$reply_to."\r\n";
				//direcciones que recibián copia 
				$headers .= "Cc: wissindrako@gmail.com\r\n"; //cambiar a consultas.lp@infoactiva.com.bo 
				mail($destinatario,$asunto,$cuerpo,$headers);

				if ($resultado) {
					echo "POR PROCESAR";
				}
				else{
					echo "error";
					// echo $destinatario." ".$asunto." ".$cuerpo." ".$headers;
				}
			}
			elseif ($dev['ESTADO'] == "PROGRAMADA") {
				$estado = "FINALIZADA";
				$procesado_por = $dev['PROCESADO_POR'];
				$recogido_por = $usuario1[0]['NOMBRE']." ".$usuario1[0]['APELLIDO'];
				$sql = "UPDATE devoluciones SET PROCESADO_POR = '".$procesado_por."', FECHA_PROGRAMADA = '".$fecha."', RECOGIDO_POR = '".$recogido_por."', ESTADO = '".$estado."' WHERE ID_DEV = '".$id."' ";
		
				foreach ($items as $item) {
					$sql_inv = "UPDATE inventarios SET ESTADO = 'EN CUSTODIA' WHERE ID_INV = '".$item['ID_INV']."' ";
					if(!$resultado = mysqli_query($con, $sql_inv)) die();
				}
				if(!$resultado = mysqli_query($con, $sql)) die();

				// Datos para Email Solicitudes
				$destinatario = $solicitante[0]['CORREO']; //DATOS DEL CLIENTE
				$asunto = "solicitud de devolución Nro: ".$dev['ID_DEV'].", Cliente: ".$cliente[0]['CLIENTE']; 
				$cuerpo = "
				<html>
				<head>
				<title>SOLICITUD DE CONSULTA DE DEVOLUCION INFODAT</title> 
				</head> 
				<body> 
				<h3>Estimad@ ".$solicitante[0]['NOMBRE']." ".$solicitante[0]['APELLIDO']."</h3> 
				<p> 
				Su solicitud de devolución Nro: ".$dev['ID_DEV'].", finalizó y los documentos de dicha solicitud pasaron a estado de custodia y estan disponibles para nuevos requeimientos.
				
				Para mayor información puede comunicarse al teléfono: 2453147 o al celular: 77231547
				
				Sistema de Consultas/Devoluciones de Documentos INFODAT
				Infoactiva ®
				</p> 
				</body> 
				</html> 
				"; 
				//para el envío en formato HTML 
				$headers = "MIME-Version: 1.0\r\n"; 
				$headers .= "Content-type: text/html; charset=utf-8\r\n"; 
				//dirección del remitente 
				$headers .= "From: INFODAT <sistema.consultas@infoactiva.com.bo>\r\n"; 
				//dirección de respuesta, si queremos que sea distinta que la del remitente 
				$headers .= "Reply-To: ".$reply_to."\r\n";
				//direcciones que recibián copia 
				$headers .= "Cc: wissindrako@gmail.com\r\n"; //cambiar a consultas.lp@infoactiva.com.bo 
				mail($destinatario,$asunto,$cuerpo,$headers);

				if ($resultado) {
					echo "PROGRAMADA";
				}
				else{
					echo "error";
				}
			}
		}
	}
}
else{
	echo "fecha";
}

?>