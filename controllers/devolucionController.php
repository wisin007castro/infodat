<?php   
require_once '../conexionClass.php';
require_once '../PHPMailer/PHPMailerAutoload.php';
$conexion = new MiConexion();

$con = $conexion->conectarBD();

$user = $conexion->usuario($_POST['usuario']);
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
			// Datos para Email Solicitudes
			if($devoluciones['REGIONAL'] == 'LP'){//LA PAZ
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
			$asunto = "SOLICITUD DE DEVOLUCION INFODAT"; 
			$cuerpo = "
			<html>
			<head>
			<title>SOLICITUD DE DEVOLUCION INFODAT</title> 
			</head> 
			<body> 
			<h3>Estimad@ ".$user[0]['NOMBRE']." ".$user[0]['APELLIDO']."</h3> 
			<p> 
			Su solicitud de devolucion fue registrada correctamente con los siguientes datos:
			Solicitud de Devolucion Nro: ".$devoluciones['ID_DEV']."<br>
			Fecha programada: ".$fecha."<br>
			Direccion de entrega: ".$_POST['direccion']."<br>
			<br>
			<br>
			Nuestra central de consultas procesara su solicitud de Devolucion.
			Para realizar el seguimiento a su solicitud puede ingresar al menu ESTADO y seleccionar Devoluciones.<br><br>
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