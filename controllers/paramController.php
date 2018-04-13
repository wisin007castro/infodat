<?php 
$todas = var_dump($_POST);

require_once '../conexionClass.php';
$conexion = new MiConexion();

$con = $conexion->conectarBD();

$id_cliente = $_POST['cliente'];
$id_user = $_POST['id_user'];
$modulos = $_POST['modulos'];//Tipo
$acceso = $_POST['acceso'];
$asignaciones = $_POST['asignacion'];//Departamentos (Array o string)



$regional = $_POST['regional'];
$usuarios = $conexion->usuarios_reg($id_cliente, $regional);
$deptos_access = $conexion->dptos_access($id_cliente);

$cont = 0;

if ($id_user != '0') {
	if ($modulos != '0') {
		if ($acceso == 'NO') {
	
			$sql0 =  "SELECT * FROM parametros 
			WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' 
			";
			if(!$user_access = mysqli_query($con, $sql0)) die();
	
			if (mysqli_num_rows($user_access) > 0) {
				$sql = "UPDATE parametros SET ACCESO = '".$acceso."', HABILITADO = '".$acceso."' WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' ";
				if(!$resultado = mysqli_query($con, $sql)) die();
	
				if($resultado){echo "success";}
			}
			else{
				$sql = "INSERT INTO parametros(ID_CLIENTE, ID_USER, TIPO, ACCESO, ASIGNACION, HABILITADO) VALUES ('".$id_cliente."', '".$id_user."', '".$modulos."',
				'".$acceso ."', '".$asignaciones[0]."','NO')";
				if(!$resultado = mysqli_query($con, $sql)) die();
			}
		}
		elseif($acceso == 'SI'){
		if($asignaciones != 'NINGUNO'){//
			foreach ($asignaciones as $asign)
			{
				if($asign == 'TODOS'){
					$sql0 = "DELETE FROM parametros WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' ";
					if(!$resultado = mysqli_query($con, $sql0)) die();
	
					$sql = "INSERT INTO parametros(ID_CLIENTE, ID_USER, TIPO, ACCESO, ASIGNACION, HABILITADO) VALUES ('".$id_cliente."', '".$id_user."', '".$modulos."',
					'".$acceso ."', '".$asign."','SI')";
					if(!$resultado = mysqli_query($con, $sql)) die();
				}
				else{
				if($modulos == 'solicitud_consultas'){//por Departamentos
					foreach ($deptos_access as $deptos) {
						if ($deptos['DEPARTAMENTO'] == $asign) {
							$sql0 =  "SELECT * FROM parametros 
							WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' 
							AND ASIGNACION = '".$asign."' 
							";
							if(!$user_access = mysqli_query($con, $sql0)) die();
		
							if (mysqli_num_rows($user_access) > 0) {
								$sql = "UPDATE parametros SET ACCESO = '".$acceso."', HABILITADO = 'SI' WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' AND ASIGNACION = '".$asign."'  " ;
								if(!$resultado = mysqli_query($con, $sql)) die();
		
							}
							else{
								$sql0 = "DELETE FROM parametros WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' AND ASIGNACION = 'TODOS' ";
										if(!$resultado = mysqli_query($con, $sql0)) die();
		
											$sql = "INSERT INTO parametros(ID_CLIENTE, ID_USER, TIPO, ACCESO, ASIGNACION, HABILITADO) VALUES ('".$id_cliente."', '".$id_user."', '".$modulos."', '".$acceso ."', '".$asign."','SI')";
											if(!$resultado = mysqli_query($con, $sql)) die();
							}
						}
					}
						$departamentos= array();
					foreach ($deptos_access as $value) {
						$departamentos[] = $value['DEPARTAMENTO'];
					}
		
					$deshabilitados = array_diff($departamentos, $asignaciones);//Deshabilitados
					foreach ($deshabilitados as $deptos) {
						$sql0 =  "SELECT * FROM parametros 
							WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' 
							AND ASIGNACION = '".$deptos."' 
							";
						echo "si es diferente--";
						echo var_dump($sql0);
						echo "--si es diferente";
						if(!$user_access = mysqli_query($con, $sql0)) die();
	
						if (mysqli_num_rows($user_access) > 0) {
							$sql = "UPDATE parametros SET ACCESO = '".$acceso."', HABILITADO = 'NO' WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' AND ASIGNACION = '".$deptos."' " ;
												echo "si es diferente--";
						echo var_dump($sql0);
						echo var_dump($sql);
						echo "--si es diferente";
							if(!$resultado = mysqli_query($con, $sql)) die();
	
							if($resultado){echo "success";}
						}
					}
				}
				else{//por Usuarios
					foreach ($usuarios as $users) {
						if ($users['ID_USER'] == $asign) {
							$sql0 =  "SELECT * FROM parametros 
							WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' 
							AND ASIGNACION = '".$asign."' 
							";
							if(!$user_access = mysqli_query($con, $sql0)) die();
		
							if (mysqli_num_rows($user_access) > 0) {
								$sql = "UPDATE parametros SET ACCESO = '".$acceso."', HABILITADO = 'SI' WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' AND ASIGNACION = '".$asign."'  " ;
								if(!$resultado = mysqli_query($con, $sql)) die();
		
							}
							else{
								$sql0 = "DELETE FROM parametros WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' AND ASIGNACION = 'TODOS' ";
										if(!$resultado = mysqli_query($con, $sql0)) die();
		
											$sql = "INSERT INTO parametros(ID_CLIENTE, ID_USER, TIPO, ACCESO, ASIGNACION, HABILITADO) VALUES ('".$id_cliente."', '".$id_user."', '".$modulos."', '".$acceso ."', '".$asign."','SI')";
											if(!$resultado = mysqli_query($con, $sql)) die();
							}
						}
					}
						$departamentos= array();
					foreach ($usuarios as $value) {
						$departamentos[] = $value['ID_USER'];
					}
		
					$deshabilitados = array_diff($departamentos, $asignaciones);//Deshabilitados
					foreach ($deshabilitados as $users) {
						$sql0 =  "SELECT * FROM parametros 
							WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' 
							AND ASIGNACION = '".$users."' 
							";
						echo "si es diferente--";
						echo var_dump($sql0);
						echo "--si es diferente";
						if(!$user_access = mysqli_query($con, $sql0)) die();
	
						if (mysqli_num_rows($user_access) > 0) {
							$sql = "UPDATE parametros SET ACCESO = '".$acceso."', HABILITADO = 'NO' WHERE ID_USER = '".$id_user."' AND TIPO = '".$modulos."' AND ASIGNACION = '".$users."' " ;
						echo "si es diferente--";
						echo var_dump($sql0);
						echo var_dump($sql);
						echo "--si es diferente";
							if(!$resultado = mysqli_query($con, $sql)) die();
	
							if($resultado){echo "success";}
						}
					}
				}
			  }
			}
		}
		  else{
			  echo 'sin_seleccion';
		  }
	  }
	}
	else{
	echo 'sin_modulo';//seleccione modulos
	}
}
else{
	echo 'sin_user';
}



?>