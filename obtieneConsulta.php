<?php
	include('buscadorClass.php');
	$id = $_GET["id"];
	$desc_1 = $_GET["desc_1"];//Si recibimos un dato entonces hacemos filtro si recibimos 0 no hacemos filtro
	$desc_2 = $_GET["desc_2"];
	$caja = $_GET["caja"];
	$tabla    = $_GET["control"];

	if($tabla>0){
		$filtro = " WHERE ID_INV=".$id;
	}
	else{
		if($desc_1 == "" && $desc_2 == "" && $caja == ""){
		$filtro   = "";
		}
		else{
			if($desc_1 == ""){$desc_1 = " ";}
			if($desc_2 == ""){$desc_2 = " ";}
			// if($caja == ""){$caja = "";}
			$filtro = " WHERE DESC_1 like '%".$desc_1."%' and DESC_2 like '%".$desc_2."%' and CAJA like '%".$caja."%' " ;
		}
	}
	$Json     = new Json;
	$inventarios = $Json->BuscaInventarios($filtro);
	echo  json_encode($inventarios);
?>