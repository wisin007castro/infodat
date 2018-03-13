<?php

require("include/config.php");


class conectorDB extends config
{
	private $conexion;
	public function __construct(){
		$this->conexion = parent::conectar(); //creo una variable con la conexión
		return $this->conexion;										
	}
	
	public function EjecutarSentencia($consulta, $valores = array()){  //funcion principal, ejecuta todas las consultas
		$resultado = false;
		
		if($statement = $this->conexion->prepare($consulta)){  //prepara la consulta
			if(preg_match_all("/(:\w+)/", $consulta, $campo, PREG_PATTERN_ORDER)){ //tomo los nombres de los campos iniciados con :xxxxx
				$campo = array_pop($campo); //inserto en un arreglo
				foreach($campo as $parametro){
					$statement->bindValue($parametro, $valores[substr($parametro, 1)]);
				}
			}
			try {
				if (!$statement->execute()) { //si no se ejecuta la consulta...
					print_r($statement->errorInfo()); //imprimir errores
					return false;
				}
				$resultado = $statement->fetchAll(PDO::FETCH_ASSOC); //si es una consulta que devuelve valores los guarda en un arreglo.
				$statement->closeCursor();
			}
			catch(PDOException $e){
				echo "Error de ejecución: \n";
				print_r($e->getMessage());
			}	
		}
		return $resultado;
		$this->conexion = null; //cerramos la conexión
	} /// Termina funcion consultarBD
}/// Termina clase conectorDB

class Json
{
	private $json;

	public function BuscaUsuarios($filtro){
		if($filtro <> ""){
			$consulta = "SELECT * FROM usuarios".$filtro." LIMIT 10";//Los espacios son importantes
			//echo $consulta;
			$conexion = new conectorDB;
			$this->json = $conexion->EjecutarSentencia($consulta);
			return $this->json;
		}
	}
	
	public function BuscaInventarios($filtro){
		if($filtro <> ""){
			$consulta = "SELECT * FROM inventarios AS inv
						JOIN clientes AS c ON inv.CLIENTE = C.ID_CLIENTE
						 ".$filtro." LIMIT 100";//Los espacios son importantes
			//echo $consulta;
			$conexion = new conectorDB;
			$this->json = $conexion->EjecutarSentencia($consulta);
			return $this->json;
		}
	}


}/// TERMINA CLASE USUARIOS ///