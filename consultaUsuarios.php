<?php 
include('buscadorClass.php');

$id_cliente = $_GET['id_cliente'];

$filtro = "WHERE ID_CLIENTE = $id_cliente
           AND HABILITADO = 'SI' ";

$Json = new Json;
$accesos = $Json->BuscaUsuarios($filtro);
echo  json_encode($accesos);

?>