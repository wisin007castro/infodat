<?php
require_once '../conexionClass.php';
require_once '../PHPMailer/PHPMailerAutoload.php';
$conexion = new MiConexion();

$con = $conexion->conectarBD();

$sol_auth = $conexion->solicitud_auth_id($_POST['id_sol']);
$correos = $conexion->correo();

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
			$asunto = "SOLICITUD DE CONSULTA INFODAT"; 
			$cuerpo = "
			<html>
			<head>
			<title>SOLICITUD DE CONSULTA INFODAT</title> 
			</head> 
			<body> 
			<h3>Estimad@ ".$user[0]['NOMBRE']." ".$user[0]['APELLIDO']."</h3> 
			<p> 
			Su solicitud de fue aprobada y registrada correctamente con los siguientes datos:<br>
			Consulta Nro: ".$solicitud['ID_SOLICITUD']."<br>
			Fecha: ".$solicitud['FECHA_SOLICITUD']."<br>
			Hora: ".$solicitud['HORA_SOLICITUD']."<br>
			Prioridad: ".$solicitud['TIPO_CONSULTA']."<br>
			Direccion de entrega: ".$solicitud['DIRECCION_ENTREGA']."<br>
			<br>
			Nuestra central de consultas procesara su solicitud.<br><br>
			Para realizar el seguimiento a su solicitud puede ingresar al menu ESTADO y seleccionar Solicitud de Documentos.<br><br>
			Para mayor información puede comunicarse al teléfono: ".$telefono." o al celular: ".$celular."
			<br>
			Sistema de Consultas/Devoluciones de Documentos INFODAT
			Infoactiva ®
			</p> 
			</body> 
			</html> 
			";
			enviar_email($destinatario, $reply_to, $asunto, $cuerpo);


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