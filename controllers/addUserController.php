<?php 

require_once '../conexionClass.php';
$conexion = new MiConexion();
$con = $conexion->conectarBD();

if($_POST['nombre'] && $_POST['apellido'] != "" && $_POST['cargo'] != "" && $_POST['telefono'] != "" && $_POST['direccion'] != "" && $_POST['correo'] != "" && $_POST['user'] != "" && $_POST['pass'] != ""){

	$sql0 = "SELECT * FROM usuarios WHERE USER = '".$_POST['user']."' ";
	if(!$res = mysqli_query($con, $sql0)) die();
	$username = $res->fetch_assoc();

	if ($username>0) {
		echo "repetido";
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

 ?>