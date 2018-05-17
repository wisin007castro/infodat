<?php 

require_once '../conexionClass.php';
$conexion = new MiConexion();
$con = $conexion->conectarBD();
$usuario = $conexion->usuario($_POST['id_user']);
$pass = $usuario[0]['PASS'];

if($_POST['nombre'] != "" && $_POST['apellido'] != "" && $_POST['cargo'] != "" && $_POST['telefono'] != "" && $_POST['direccion'] != "" && $_POST['correo'] != "" && $_POST['pass'] != ""){

if($_POST['pass'] != $pass){
	$pass = md5($_POST['pass']);
}

if($_POST['correo'] != $usuario[0]['CORREO']){
	$sql0 = "SELECT * FROM usuarios WHERE CORREO = '".$_POST['correo']."' ";
	if(!$res = mysqli_query($con, $sql0)) die();
	$correo_result = $res->fetch_assoc();
}
else{
	$correo_result = 0;
}

if ($correo_result>0) {
	echo "repetido";
}
else{
	$sql = "UPDATE usuarios SET ID_CLIENTE='".$_POST['id_cliente']."',
							NOMBRE='".strtoupper($_POST['nombre'])."',
							APELLIDO='".strtoupper($_POST['apellido'])."',
							CARGO='".strtoupper($_POST['cargo'])."',
							DIRECCION='".strtoupper($_POST['direccion'])."',
							TELEFONO='".$_POST['telefono']."',
							INTERNO='".$_POST['interno']."',
							CELULAR='".$_POST['celular']."',
							CORREO='".$_POST['correo']."',
							PASS='".$pass."'
                            WHERE ID_USER='".$_POST['id_user']."'";

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