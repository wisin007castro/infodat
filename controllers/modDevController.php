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

foreach ($devoluciones as $dev) {
	if ($dev['ID_DEV'] == $id) {
		if ($dev['ESTADO'] == "POR PROCESAR") {
			$estado = "PROGRAMADA";
			$procesado_por = $usuario2[0]['NOMBRE']." ".$usuario2[0]['APELLIDO'];
			$sql = "UPDATE devoluciones SET PROCESADO_POR = '".$procesado_por."', FECHA_PROGRAMADA = '".$fecha."', RECOGIDO_POR = '".$recogido_por."', ESTADO = '".$estado."' WHERE ID_DEV = '".$id."' ";
			foreach ($items as $item) {
				$sql_inv = "UPDATE inventarios SET ESTADO = '".$estado."' WHERE ID_INV = '".$item['ID_INV']."' ";
				if(!$resultado = mysqli_query($con, $sql_inv)) die();
			}
		}
		elseif ($dev['ESTADO'] == "PROGRAMADA") {
			$estado = "FINALIZADA";
			$procesado_por = $dev['PROCESADO_POR'];
			$recogido_por = $usuario1[0]['NOMBRE']." ".$usuario1[0]['APELLIDO'];
			$sql = "UPDATE devoluciones SET PROCESADO_POR = '".$procesado_por."', FECHA_PROGRAMADA = '".$fecha."', RECOGIDO_POR = '".$recogido_por."', ESTADO = '".$estado."' WHERE ID_DEV = '".$id."' ";


			// Datos para Email Solicitudes
$destinatario = $solicitante; //DATOS DEL CLIENTE
$asunto = "Devolución de archivos PROGRAMADA"; 
$cuerpo = "
<html> 
<head> 
   <title>SOLICITUD DE CONSULTA INFODAT</title> 
</head> 
<body> 
<h1>Estimad@".$solicitante[0]['NOMBRE']." ".$solicitante[0]['APELLIDO']."</h1> 
<p> 
Su solicitud de Devolución Nro: ".$dev['ID_DEV']." fue programada para ser recogida por: ".$recogido_por."

Para mayor información puede comunicarse al teléfono: 2453147 o al celular: 77231547


Favor no responder al remitente (sistema.consultas@infoactiva.com.bo) esta cuenta no es monitoreada, de ser necesario puede enviar un correo a consultas.lp@infoactiva.com.bo


Sistema de Consultas/Devoluciones de Documentos INFODAT
Infoactiva ®
</p> 
</body> 
</html> 
"; 

//para el envío en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

//dirección del remitente 
$headers .= "From: INFODAT <sistema.consultas@infoactiva.com.bo>\r\n"; 


//direcciones que recibián copia 
$headers .= "Cc: wissindrako@gmail.com\r\n"; //cambiar a consultas.lp@infoactiva.com.bo 
//$headers .= "Cc: castrow666@gmail.com\r\n"; //


//direcciones que recibirán copia oculta 
// $headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n"; 

		// mail($destinatario,$asunto,$cuerpo,$headers);

			foreach ($items as $item) {
				$sql_inv = "UPDATE inventarios SET ESTADO = 'EN CUSTODIA' WHERE ID_INV = '".$item['ID_INV']."' ";
				if(!$resultado = mysqli_query($con, $sql_inv)) die();
			}
		}
	}
}

try {
	if($fecha != ''){
		if(!$resultado = mysqli_query($con, $sql)) die();
			if ($resultado) {
				echo "success";
			}
	}
	else{
		echo "fecha";
	}
} catch (Exception $e) {
    // echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    echo "Algo salió mal no se pudo modificar a ".$estado;
}

?>