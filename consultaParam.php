<?php 
include('buscadorClass.php');

$id_user = $_GET['id_user'];
$modulo = $_GET['modulo'];

if($modulo != '0'){
    $filtro = "WHERE p.TIPO = '".$modulo."' AND p.ID_USER = $id_user";
}
else{
    $filtro = "WHERE p.ID_USER = $id_user";
}

$Json = new Json;
$accesos = $Json->BuscaParametro($filtro);
echo  json_encode($accesos);

?> 