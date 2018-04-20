<?php 
Class MiStrings{
	public function meses(){
		return array("Mes", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	}

	public function estadoSol(){
		return array("POR PROCESAR", "EN PROCESO DE BUSQUEDA", "DESPACHADA", "ATENDIDA/ENTREGADA");
	}

	public function estadoDev(){
		return array("POR PROCESAR", "PROGRAMADA", "FINALIZADA");
	}

	public function estadoCliente(){
		return array("SI", "NO");
	}
	
	public function estadoUsuario(){
		return array("SI", "NO", "RESET");
	}

	public function regional(){
		return array("LP", "SCZ");
	}

	public function modulos(){
		return array(
			"solicitud_consultas",
			"solicitud_devoluciones",
			"estado_consultas",
			"estado_devoluciones",
			"emision_reportes",
			"gestion_usuarios",
			"parametricas"
			);
	}
}
 ?>
