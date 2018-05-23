<?php
require_once '../conexionClass.php';
require_once '../PHPMailer/PHPMailerAutoload.php';
$conexion = new MiConexion();

$con = $conexion->conectarBD();

$user = $conexion->usuario($_POST['asignado']);//por consultas
$correos = $conexion->correo();

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
	$sql = "INSERT INTO solicitud_auth (ID_CLIENTE, ID_USER, TIPO_ENVIO, TIPO_CONSULTA, DIRECCION_ENTREGA, OBSERVACION, FECHA_SOLICITUD, HORA_SOLICITUD, ESTADO, REGIONAL) 
		VALUES ('".$_POST['cliente']."','".$_POST['usuario']."','".$_POST['tipo_envio']."','".$_POST['tipo_consulta']."','".$_POST['direccion']."', '".$_POST['observacion']."', '".$fecha."','".$hora."', 'POR APROBAR','".$_POST['regional']."')";

	if(!$result = mysqli_query($con, $sql)) die();//Guardando la solicitud

	if ($result) {

		$sql = "SELECT * FROM solicitud_auth Order by ID_SOLICITUD desc LIMIT 1";
		if(!$resultado = mysqli_query($con, $sql)) die();

		if($resultado){
			$solicitud = $resultado->fetch_assoc();

			// Datos para Email Solicitudes
			if($solicitud['REGIONAL'] == 'LP'){//LA PAZ
				$reply_to = $correos[0]['CORREO'];
				$telefono = $correos[0]['TELEFONO'];
				$celular = $correos[0]['CELULAR'];
			}
			else{//SANTA CRUZ
				$reply_to = $correos[1]['CORREO'];
				$telefono = $correos[1]['TELEFONO'];
				$celular = $correos[1]['CELULAR'];
			}
			
			$destinatario = $user[0]['CORREO']; //DATOS DEL USUARIO
			$asunto = "AUTORIZACION DE SOLICITUD DE CONSULTA INFODAT"; 
			$cuerpo = "
			<html>
			<head>
			<title>AUTORIZACION DE SOLICITUD DE CONSULTA INFODAT</title> 
			</head> 
			<body> 
			<h3>Estimad@ ".$user[0]['NOMBRE']." ".$user[0]['APELLIDO']."</h3> 
			<p> 
			La solicitud con los siguientes datos requiere autorización:<br>
			Fecha: ".$solicitud['FECHA_SOLICITUD']."<br>
			Hora: ".$solicitud['HORA_SOLICITUD']."<br>
			Prioridad: ".$solicitud['TIPO_CONSULTA']."<br>
			Direccion de entrega: ".$solicitud['DIRECCION_ENTREGA']."<br>
			<br>
			Nuestra central de consultas procesara su solicitud.<br><br>
			Para realizar el seguimiento a la solicitud debe ingresar al menu REQUERIMIENTOS y seleccionar Aprobación de Consultas.<br><br>
			Para mayor información puede comunicarse al teléfono: ".$telefono." o al celular: ".$celular."
			<br>
			Sistema de Consultas/Devoluciones de Documentos INFODAT
			Infoactiva ®
			</p> 
			</body> 
			</html> 
			";
			enviar_email($destinatario, $reply_to, $asunto, $cuerpo);		

		    foreach ($_POST as $key => $value) {
		    	if (substr($key, 0, 3) == "id-") {
					$sql = "INSERT INTO items_auth(ID_CLIENTE, ID_SOLICITUD, ID_INV, ESTADO) 
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