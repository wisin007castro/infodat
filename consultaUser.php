<?php 
include('buscadorClass.php');

$id_user = $_GET['id_user'];

$filtro = "WHERE ID_USER = $id_user";

$Json = new Json;
$accesos = $Json->BuscaUser($filtro);
echo  json_encode($accesos);

?> 