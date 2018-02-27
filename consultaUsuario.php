<?php
	include('buscadorClass.php');
	$nombre = $_GET["nombre"];
    
    if($nombre <> ""){
        $filtro = " WHERE NOMBRE like '%".$nombre."%' or APELLIDO like '%".$nombre."%' or CARGO like '%".$nombre."%' " ;       
    }
    else{
        $filtro = "";
    }
	$Json     = new Json;
	$usuarios = $Json->BuscaUsuarios($filtro);
	echo  json_encode($usuarios);
?>