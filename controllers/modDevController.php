<?php

require_once '../conexionClass.php';
require_once '../PHPMailer/PHPMailerAutoload.php';
$conexion = new MiConexion();
$con = $conexion->conectarBD();
$correos = $conexion->correo();

$id = $_POST['id'];
$fecha = $_POST['fecha']; 

$items = $conexion->dev_item($_POST['id']);

$devoluciones = $conexion->devoluciones_admin();

// $solicitante = $conexion->usuario($_POST['ID_USER']);

$usuario1 = $conexion->usuario($_POST['recogido']);  //Entregado por
$recogido_por = "";

$usuario2 = $conexion->usuario($_POST['usuario']);  //Procesado por
$procesado_por = "";

if ($fecha != '') {

	foreach ($devoluciones as $dev) {
		if ($dev['ID_DEV'] == $id) {

			$solicitante = $conexion->usuario($dev['ID_USER']);
			$cliente = $conexion->cliente($dev['ID_CLIENTE']);
			if($dev['REGIONAL'] == 'LP'){//LA PAZ
				$reply_to = $correos[0]['CORREO'];
				$telefono = $correos[0]['TELEFONO'];
				$celular = $correos[0]['CELULAR'];
			}
			else{//SANTA CRUZ
				$reply_to = $correos[1]['CORREO'];
				$telefono = $correos[1]['TELEFONO'];
				$celular = $correos[1]['CELULAR'];
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
				$asunto = "solicitud de devolucion Nro: ".$dev['ID_DEV'].", Cliente: ".$cliente[0]['CLIENTE']; 
				$cuerpo = "
				<html>
				<head>
				<title>SOLICITUD DE CONSULTA DE DEVOLUCION INFODAT</title> 
				</head> 
				<body> 
				<h3>Estimad@ ".$solicitante[0]['NOMBRE']." ".$solicitante[0]['APELLIDO']."</h3> 
				<p> 
				Su solicitud de devolución Nro: ".$dev['ID_DEV'].", fue programada por: ".$procesado_por.", con fecha: ".$fecha.", para realizar el seguimiento a su solicitud puede ingresar al menú ESTADO y seleccionar Solicitud de Documentos. 
				
				Para mayor información puede comunicarse al teléfono: ".$telefono." o al celular: ".$celular."
				
				Sistema de Consultas/Devoluciones de Documentos INFODAT
				Infoactiva ®
				</p> 
				</body> 
				</html> 
				"; 
				enviar_email($destinatario, $reply_to, $asunto, $cuerpo);

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
				$asunto = "solicitud de devolucion Nro: ".$dev['ID_DEV'].", Cliente: ".$cliente[0]['CLIENTE']; 
				$cuerpo = "
				<html>
				<head>
				<title>SOLICITUD DE CONSULTA DE DEVOLUCION INFODAT</title> 
				</head> 
				<body> 
				<h3>Estimad@ ".$solicitante[0]['NOMBRE']." ".$solicitante[0]['APELLIDO']."</h3> 
				<p> 
				Su solicitud de devolución Nro: ".$dev['ID_DEV'].", finalizó y los documentos de dicha solicitud pasaron a estado de custodia y estan disponibles para nuevos requeimientos.
				
				Para mayor información puede comunicarse al teléfono: ".$telefono." o al celular: ".$celular."
				
				Sistema de Consultas/Devoluciones de Documentos INFODAT
				Infoactiva ®
				</p> 
				</body> 
				</html> 
				"; 
				enviar_email($destinatario, $reply_to, $asunto, $cuerpo);

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

function enviar_email($destinatario, $reply_to, $asunto, $cuerpo){
	$conexion = new MiConexion();
	$con = $conexion->conectarBD();
	$correo = $conexion->correo();
	
	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = $correo[0]['SMTP']; 			  		  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = $correo[0]['USER'];        		  // SMTP username
	$mail->Password = $correo[0]['PASS'];                 // SMTP password
	$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = $correo[0]['PORT'];                     // TCP port to connect to

	$mail->setFrom($correo[0]['USER'], 'INFODAT');
	$mail->addAddress($destinatario);               	  // Destinatario
	$mail->addReplyTo($reply_to);						  // Responder a:
	$mail->addCC($correo[2]['CORREO']);				  	  //cambiar a $correo[0]['CORREO']

	$mail->isHTML(true);                                     // Set email format to HTML

	$mail->Subject = $asunto;
	$mail->Body    = $cuerpo;
	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	}
}

?>