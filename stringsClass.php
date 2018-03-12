<?php 
Class MiStrings{
	public function meses(){
		$meses = array("Mes", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		return $meses;
	}

	public function estadoSol(){
		$estSol = array("POR PROCESAR", "EN PROCESO DE BUSQUEDA", "DESPACHADA", "ATENDIDA/ENTREGADA");
		return $estSol;
	}

	public function estadoDev(){
		$estDev = array("POR PROCESAR", "PROGRAMADA", "FINALIZADA");
		return $estDev;
	}
}
 ?>
