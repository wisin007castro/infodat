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

$items = $conexion->item($_POST['id']);

$pedidos = $conexion->pedidos_admin();

$usuario1 = $conexion->usuario($_POST['entrega']);  //Entregado por
$entregado_por = "";

$usuario2 = $conexion->usuario($_POST['usuario']);  //Procesado por
$procesado_por = "";

// echo $usuario2[0]['NOMBRE'];

foreach ($pedidos as $ped) {
	if ($ped['ID_SOLICITUD'] == $_POST['id']) {
		if ($ped["ESTADO"] == "POR PROCESAR") {
			$estado = "EN PROCESO DE BUSQUEDA";
			$procesado_por = $usuario2[0]['NOMBRE']." ".$usuario2[0]['APELLIDO'];
			$sql = "UPDATE solicitud SET PROCESADO_POR = '".$procesado_por."', ENTREGADO_POR='".$entregado_por."', ESTADO='".$estado."' WHERE ID_SOLICITUD = '".$_POST['id']."' ";

			foreach ($items as $item) {
				$sql_inv = "UPDATE inventarios SET ESTADO = '".$estado."' WHERE ID_INV = '".$item['ID_INV']."' ";
				if(!$resultado = mysqli_query($con, $sql_inv)) die();
			}
		}
		elseif ($ped["ESTADO"] == "EN PROCESO DE BUSQUEDA") {
			$estado = "DESPACHADA";
			$procesado_por = $ped['PROCESADO_POR'];
			$entregado_por = $usuario1[0]['NOMBRE']." ".$usuario1[0]['APELLIDO'];
			$sql = "UPDATE solicitud SET PROCESADO_POR = '".$procesado_por."', ENTREGADO_POR='".$entregado_por."', ESTADO='".$estado."' WHERE ID_SOLICITUD = '".$_POST['id']."' ";
			foreach ($items as $item) {
				$sql_inv = "UPDATE inventarios SET ESTADO = '".$estado."' WHERE ID_INV = '".$item['ID_INV']."' ";
				if(!$resultado = mysqli_query($con, $sql_inv)) die();
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

		}
		elseif ($ped["ESTADO"] == "DESPACHADA") {
			$estado = "ATENDIDA/ENTREGADA";
			$procesado_por = $ped['PROCESADO_POR'];
			$entregado_por = $ped['ENTREGADO_POR'];

			$sql = "UPDATE solicitud SET 
			PROCESADO_POR = '".$procesado_por."', FECHA_ENTREGA='".$fecha."', HORA_ENTREGA='".$hora."', ENTREGADO_POR='".$entregado_por."', ESTADO='".$estado."' WHERE ID_SOLICITUD = '".$_POST['id']."' ";

			foreach ($items as $item) {
				$sql_inv = "UPDATE inventarios SET ESTADO = 'EN CONSULTA' WHERE ID_INV = '".$item['ID_INV']."' ";
				if(!$resultado = mysqli_query($con, $sql_inv)) die();
			}
		}
	}
}


try {
	if(!$resultado = mysqli_query($con, $sql)) die();
	if ($resultado) {
		echo "success";
	}
} catch (Exception $e) {
    // echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    echo "Algo salió mal no se pudo modificar a ".$estado;
}


?>