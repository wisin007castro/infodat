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

$sql = "UPDATE usuarios SET ID_CLIENTE='".$_POST['id_cliente']."',
							NOMBRE='".$_POST['nombre']."',
							APELLIDO='".$_POST['apellido']."',
							CARGO='".$_POST['cargo']."',
							DIRECCION='".$_POST['direccion']."',
							TELEFONO='".$_POST['telefono']."',
							INTERNO='".$_POST['interno']."',
							CELULAR='".$_POST['celular']."',
							CORREO='".$_POST['correo']."',
							PASS='".$pass."',
							HABILITADO='".$_POST['habilitado']."',
							TIPO='".$_POST['tipo']."',
							REGIONAL='".$_POST['regional']."' WHERE ID_USER='".$_POST['id_user']."'";

	if(!$resultado = mysqli_query($con, $sql)) die();

	if($resultado){
	echo "success";
	}
	else{
		echo "Ocurrió un error";
	}
}
else{
	echo "vacio";
}


 ?>