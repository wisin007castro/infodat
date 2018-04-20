<?php
	include('buscadorClass.php');
	$nombre = $_GET["nombre"];
    $cliente = $_GET["cli"];
    
    if($nombre <> ""){
        $filtro = " WHERE (NOMBRE like '%".$nombre."%' or APELLIDO like '%".$nombre."%' or CARGO like '%".$nombre."%') AND ID_CLIENTE = '".$cliente."' " ;       
    }
    else{
        $filtro = "WHERE ID_CLIENTE = '".$cliente."' ";
    }
	$Json     = new Json;
	$usuarios = $Json->BuscaUsuarios($filtro);
	echo  json_encode($usuarios);
?>