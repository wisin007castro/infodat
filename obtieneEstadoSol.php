<?php
include('buscadorClass.php');

	$id = $_GET["id"];

	$filtro = " WHERE s.ID_SOLICITUD = ".$id." " ;

	$Json     = new Json;
	$estadoSolicitudes = $Json->estado_pedidos($filtro);
	

	echo  json_encode($estadoSolicitudes);


?>