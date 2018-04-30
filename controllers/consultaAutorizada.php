<?php
require_once '../conexionClass.php';
$conexion = new MiConexion();

$con = $conexion->conectarBD();

$sol_auth = $conexion->solicitud_auth_id($_POST['id_sol']);


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
			// echo json_encode($data);

			// Datos para Email Solicitudes
$destinatario = $user[0]['CORREO']; 
$asunto = "Solicitud de archivos"; 
$cuerpo = "
<html> 
<head> 
   <title>SOLICITUD DE CONSULTA INFODAT</title> 
</head> 
<body> 
<h3>Estimad@".$user[0]['NOMBRE']." ".$user[0]['APELLIDO']."</h3> 
<p> 
Su solicitud de fue registrada correctamente con los siguientes datos:

Consulta Nro: ".$solicitud['ID_SOLICITUD']."<br>
Fecha: ".$solicitud['FECHA_SOLICITUD']."<br>
Hora: ".$solicitud['HORA_SOLICITUD']."<br>
Prioridad:".$solicitud['TIPO_CONSULTA']."<br>
Direccion de entrega:".$solicitud['DIRECCION_ENTREGA']."<br>
<br><br>
Nuestra central de consultas procesara su solicitud.
Para realizar el seguimiento a su solicitud puede ingresar al menu ESTADO y seleccionar Solicitud de Documentos.<br><br>
Para mayor informacion puede comunicarse al telefono: 2453147 o al celular: 77231547
<br>

Favor no responder al remitente (sistema.consultas@infoactiva.com.bo) esta cuenta no es monitoreada, de ser necesario puede enviar un correo a consultas.lp@infoactiva.com.bo

<br>
<b>Sistema de Consultas/Devoluciones de Documentos INFODAT</b>
Infoactiva
</p> 
</body> 
</html> 
"; 

//para el envío en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset='utf-8'\r\n"; 

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

?>