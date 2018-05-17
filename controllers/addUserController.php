<?php 

require_once '../conexionClass.php';
$conexion = new MiConexion();
$con = $conexion->conectarBD();

if($_POST['nombre'] && $_POST['apellido'] != "" && $_POST['cargo'] != "" && $_POST['telefono'] != "" && $_POST['direccion'] != "" && $_POST['correo'] != "" && $_POST['user'] != "" && $_POST['pass'] != ""){

	$sql0 = "SELECT * FROM usuarios WHERE USER = '".$_POST['user']."' ";
	if(!$res = mysqli_query($con, $sql0)) die();
	$username = $res->fetch_assoc();

	$sql1 = "SELECT * FROM usuarios WHERE CORREO = '".$_POST['correo']."' ";
	if(!$res = mysqli_query($con, $sql1)) die();
	$correo = $res->fetch_assoc();

	if ($username>0) {
		echo "user-repetido";
	}
	elseif($correo>0) {
		echo "correo-repetido";
	}
	else {
		$sql = "INSERT INTO usuarios(ID_CLIENTE, NOMBRE, APELLIDO, CARGO, DIRECCION, TELEFONO, INTERNO, CELULAR, CORREO, USER, PASS, HABILITADO, TIPO, REGIONAL) 
		VALUES ('".$_POST['id_cliente']."',
				'".strtoupper($_POST['nombre'])."',
				'".strtoupper($_POST['apellido'])."',
				'".strtoupper($_POST['cargo'])."',
				'".strtoupper($_POST['direccion'])."',
				'".$_POST['telefono']."',
				'".$_POST['interno']."',
				'".$_POST['celular']."',
				'".$_POST['correo']."',
				'".strtoupper($_POST['user'])."',
				'".md5($_POST['pass'])."',	
				'".$_POST['habilitado']."',
				'".$_POST['tipo']."',
				'".$_POST['regional']."')";
	
		if(!$resultado = mysqli_query($con, $sql)) die();
	
		if($resultado){

			$destinatario = $_POST['correo']; //DATOS DEL CLIENTE
			$reply_to = "wcastro@infoactiva.com.bo";//cambiar a un correo a responder
			$asunto = "Usuario Registrado - INFODAT"; 
			$cuerpo = "
			<html>
			<head>
			<title>Registro de usuarios INFODAT</title> 
			</head> 
			<body> 
			<h3>Estimad@ ".$_POST['nombre']." ".$_POST['apellido']."</h3> 
			<p> 
			Su Usuario fue registrado con el Nombre de Usuario: ".$_POST['user'].", Password: ".$_POST['pass'].". 
			<br><br>
			Para mayor información puede comunicarse al teléfono: 2453147 o al celular: 77231547
			<br><br>
			Sistema de Consultas/Devoluciones de Documentos INFODAT
			Infoactiva ®
			</p> 
			</body> 
			</html> 
			"; 

			enviar_email($destinatario, $reply_to, $asunto, $cuerpo);

			echo "success";
		}
		else{
			echo "Ocurrió un error";
		}
	}
}
else{
	echo "vacio";
}

function enviar_email($destinatario, $reply_to, $asunto, $cuerpo){
	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'mail.infoactiva.com.bo';  			  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'wcastro@infoactiva.com.bo';        // SMTP username
	$mail->Password = 'wilTemoral123';                    // SMTP password
	$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 465;                                    // TCP port to connect to

	$mail->setFrom('sistema.consultas@infoactiva.com.bo', 'INFODAT');
	$mail->addAddress($destinatario);               // Name is optional
	$mail->addReplyTo($reply_to);

	// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(true);                                     // Set email format to HTML

	$mail->Subject = $asunto;
	$mail->Body    = $cuerpo;
	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'Message has been sent';
	}

}

 ?>