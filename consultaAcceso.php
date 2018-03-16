<?php
    include('buscadorClass.php');
    $user = $_GET["user"];
    $fecha = $_GET["fecha"];

    $cliente = $_GET["cli"];

    if($user <> "0" && $fecha <> ""){
        $filtro = " WHERE ID_USER = '".$user."'
                    AND FECHA >= '".$fecha."' 
                    AND ID_CLIENTE = ".$cliente." " ;
    }
    else{
        if($user <> "0" && $fecha == ""){
            $filtro = " WHERE ID_USER = '".$user."'
                        AND ID_CLIENTE = ".$cliente." " ;
        }
        elseif ($user == "0" && $fecha <> "") {
            $filtro = " WHERE FECHA >= '".$fecha."' 
                        AND ID_CLIENTE = ".$cliente." " ;
        }
        else{
            $filtro = " WHERE ID_CLIENTE = ".$cliente." ";
        }
    }
	$Json = new Json;
	$usuarios = $Json->BuscaAcceso($filtro);
	echo  json_encode($usuarios);
?>