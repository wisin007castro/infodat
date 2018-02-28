<?php
include('buscadorClass.php');
	$id = $_GET["id"];
	$desc_1 = $_GET["desc_1"];//Si recibimos un dato entonces hacemos filtro si recibimos 0 no hacemos filtro
	$desc_2 = $_GET["desc_2"];
	$desc_3 = $_GET["desc_3"];
	$caja = $_GET["caja"];
	$mes = $_GET["mes"];
	$anio = $_GET["anio"];

	$tabla    = $_GET["control"];

	if($tabla>0){
		$filtro = " WHERE ID_INV=".$id;
	}
	else{
		if($desc_1 == "" && $desc_2 == "" && $desc_3 == "" && $caja == "" && $mes == "0" && $anio == "0"){
		$filtro   = "";
		}
		else{
			if($desc_1 == ""){$desc_1 = " ";}
			if($desc_2 == ""){$desc_2 = " ";}
			if($desc_3 == ""){$desc_3 = " ";}
			if($mes == "0"){$mes = "%";}
			if($anio == "0"){$anio = "%";}
			// if($caja == ""){$caja = "";}
			$filtro = " WHERE DESC_1 like '%".$desc_1."%' 
						and DESC_2 like '%".$desc_2."%' 
						and DESC_3 like '%".$desc_3."%'
						and MES_I like '".$mes."'
						and ANO_I like '%".$anio."%'
						and CAJA like '%".$caja."%' " ;
		}
	}
	$Json     = new Json;
	$inventarios = $Json->BuscaInventarios($filtro);
	echo  json_encode($inventarios);
?>