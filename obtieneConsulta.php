<?php
include('buscadorClass.php');
include('conexionClass.php');
include('controllers/logController.php');
$log = new Logs();//llamando a la clase Logs

	$id = $_GET["id"];
	$desc_1 = $_GET["desc_1"];// hacemos filtro si recibimos algun dato
	$desc_2 = $_GET["desc_2"];
	$desc_3 = $_GET["desc_3"];
	$caja = $_GET["caja"];
	$mes = $_GET["mes"];
	$anio = $_GET["anio"];
	$cliente = $_GET["cli"];
	$usuario = $_GET["user"];

	$conexion = new MiConexion();
	$con = $conexion->conectarBD();
	$user = $conexion->usuario($usuario);
	$id_user = $user[0]['ID_USER'];

	$regional = $user[0]['REGIONAL'];


	$evento = "No. Caja: \" ".$caja."\", Desc1: \" ".$desc_1."\", Desc2: \" ".$desc_2."\", Desc3: \" ".$desc_3."\", Mes: \" ".$mes."\", Año: \" ".$anio."\"";
	$tabla    = $_GET["control"];

	if($tabla>0){
		$filtro = " WHERE ID_INV= ".$id ;
	}
	else{
		if($desc_1 == "" && $desc_2 == "" && $desc_3 == "" && $caja == "" && $mes == "0" && $anio == "0"){
		$filtro   = "";
		}
		else{
			if($desc_1 == ""){$desc_1 = "%";}
			if($desc_2 == ""){$desc_2 = "%";}
			if($desc_3 == ""){$desc_3 = "%";}
			if($mes == "0"){$mes = "%";}
			if($anio == "0"){$anio = "%";}
			if($caja == ""){$caja = "%";}

			$filtro = " WHERE DESC_1 like '%".$desc_1."%' 
			and DESC_2 like '%".$desc_2."%' 
			and DESC_3 like '%".$desc_3."%'
			and MES_I >= '".$mes."'
			and ANO_I >= '".$anio."'
			and CAJA like '%".$caja."%'
			and ID_CLIENTE = '".$cliente."'  
			and REGIONAL = '".$regional."' " ;


		}
	}
	$Json     = new Json;
	$inventarios = $Json->BuscaInventarios($filtro, $id_user);
	

	echo  json_encode($inventarios);

	$log->eventos($cliente, $usuario, $evento);


?>