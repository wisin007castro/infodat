<?php
include('buscadorClass.php');

	$id = $_GET["id"];

	$filtro = " WHERE d.ID_DEV= ".$id." " ;

	$Json     = new Json;
	$estadoDevoluciones = $Json->estado_devoluciones($filtro);
	

	echo  json_encode($estadoDevoluciones);


?>