<?php 

require_once '../conexionClass.php';
require_once '../PHPMailer/PHPMailerAutoload.php';
$conexion = new MiConexion();
$con = $conexion->conectarBD();
$correos = $conexion->correo();

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
		$solicitante = $conexion->usuario($ped['ID_USER']);
		$cliente = $conexion->cliente($ped['ID_CLIENTE']);
		if($ped['REGIONAL'] == 'LP'){//LA PAZ
			$reply_to = $correos[0]['CORREO'];
			$telefono = $correos[0]['TELEFONO'];
			$celular = $correos[0]['CELULAR'];
		}
		else{//SANTA CRUZ
			$reply_to = $correos[1]['CORREO'];
			$telefono = $correos[1]['TELEFONO'];
			$celular = $correos[1]['CELULAR'];
		}
		if ($ped["ESTADO"] == "POR PROCESAR") {
			$estado = "EN PROCESO DE BUSQUEDA";
			$procesado_por = $usuario2[0]['NOMBRE']." ".$usuario2[0]['APELLIDO'];
			$sql = "UPDATE solicitud SET PROCESADO_POR = '".$procesado_por."', ENTREGADO_POR='".$entregado_por."', ESTADO='".$estado."' WHERE ID_SOLICITUD = '".$id."' ";

			foreach ($items as $item) {
				$sql_inv = "UPDATE inventarios SET ESTADO = '".$estado."' WHERE ID_INV = '".$item['ID_INV']."' ";
				if(!$resultado = mysqli_query($con, $sql_inv)) die();
			}
			if(!$resultado = mysqli_query($con, $sql)) die();

			// Datos para Email Solicitudes
			$destinatario = $solicitante[0]['CORREO']; //DATOS DEL CLIENTE
			$asunto = "solicitud Nro: ".$ped['ID_SOLICITUD'].", Cliente: ".$cliente[0]['CLIENTE']; 
			$cuerpo = "
			<html>
			<head>
			<title>SOLICITUD DE CONSULTA INFODAT</title> 
			</head> 
			<body> 
			<h3>Estimad@ ".$solicitante[0]['NOMBRE']." ".$solicitante[0]['APELLIDO']."</h3> 
			<p> 
			Su solicitud Nro: ".$ped['ID_SOLICITUD'].", con tipo de consulta: ".$ped['TIPO_CONSULTA']." tipo de envio: ".$ped['TIPO_ENVIO'].",
			 pasó al estado: ".$estado." atendida por: ".$procesado_por."
			<br>
			Para realizar el seguimiento a su solicitud puede ingresar al menú ESTADO y seleccionar Solicitud de Documentos. 
			<br><br>
			Para mayor información puede comunicarse al teléfono: ".$telefono." o al celular: ".$celular."
			<br><br>

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

				foreach ($items as $item) {
					$sql_des = "UPDATE inventarios SET ESTADO = 'DESESTIMADO' WHERE ESTADO != 'DESPACHADA' AND ID_INV = '".$item['ID_INV']."' ";
					if(!$resultado = mysqli_query($con, $sql_des)) die();
				}

				// Datos para Email Solicitudes
				$destinatario = $solicitante[0]['CORREO']; //DATOS DEL CLIENTE
				$asunto = "solicitud Nro: ".$ped['ID_SOLICITUD'].", Cliente: ".$cliente[0]['CLIENTE']; 
				$cuerpo = "
				<html>
				<head>
				<title>SOLICITUD DE CONSULTA INFODAT</title> 
				</head> 
				<body> 
				<h3>Estimad@ ".$solicitante[0]['NOMBRE']." ".$solicitante[0]['APELLIDO']."</h3>
				<p> 
				Su solicitud Nro: ".$ped['ID_SOLICITUD'].", con tipo de consulta: ".$ped['TIPO_CONSULTA']." tipo de envio: ".$ped['TIPO_ENVIO'].",
				atendida por: ".$procesado_por.", fue despachada de nuestros almacenes.
				<br>
				El/los Documentos seran entregados por: ".$entregado_por."
				<br><br>
				Para realizar el seguimiento a su solicitud puede ingresar al menú ESTADO y seleccionar Solicitud de Documentos. 
				<br><br>
				Para mayor información puede comunicarse al teléfono: ".$telefono." o al celular: ".$celular."
				<br><br>
				Sistema de Consultas/Devoluciones de Documentos INFODAT
				Infoactiva ®
				</p> 
				</body> 
				</html> 
				"; 
				enviar_email($destinatario, $reply_to, $asunto, $cuerpo);

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
				
				if ($ped["TIPO_ENVIO"] == 'INTERNET') {
					$sql_inv = "UPDATE inventarios SET ESTADO = 'EN CUSTODIA' 
					WHERE ID_INV = '".$item['ID_INV']."' AND ESTADO !='DESESTIMADO' ";
				}
				else {
					$sql_inv = "UPDATE inventarios SET ESTADO = 'EN CONSULTA' 
					WHERE ID_INV = '".$item['ID_INV']."' AND ESTADO !='DESESTIMADO' ";
				}

				if(!$resultado = mysqli_query($con, $sql_inv)) die();
			}
			if(!$resultado = mysqli_query($con, $sql)) die();

				// Datos para Email Solicitudes
				$destinatario = $solicitante[0]['CORREO']; //DATOS DEL CLIENTE
				$asunto = "solicitud Nro: ".$ped['ID_SOLICITUD'].", Cliente: ".$cliente[0]['CLIENTE']; 
				$cuerpo = "
				<html>
				<head>
				<title>SOLICITUD DE CONSULTA INFODAT</title> 
				</head> 
				<body> 
				<h3>Estimad@ ".$solicitante[0]['NOMBRE']." ".$solicitante[0]['APELLIDO']."</h3> 
				<p> 
				Su solicitud Nro: ".$ped['ID_SOLICITUD'].", con tipo de consulta: ".$ped['TIPO_CONSULTA']." tipo de envio: ".$ped['TIPO_ENVIO'].",
				atendida por: ".$procesado_por.", Fecha de entrega: ".$fecha.", Hora de entrega: ".$hora.". 
				<br><br>
				Para mayor información puede comunicarse al teléfono: ".$telefono." o al celular: ".$celular."
				<br><br>
				Sistema de Consultas/Devoluciones de Documentos INFODAT
				Infoactiva ®
				</p> 
				</body> 
				</html> 
				"; 

				enviar_email($destinatario, $reply_to, $asunto, $cuerpo);

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