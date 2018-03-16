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

	public function BuscaAcceso($filtro){
		if($filtro <> ""){
			$consulta = "SELECT * FROM(
SELECT u.ID_USER, u.ID_CLIENTE, NOMBRE, APELLIDO, (SELECT IF(1>3,'Devolucion','Solicitud')) AS TIPO, s.FECHA_SOLICITUD AS FECHA, inv.CAJA FROM usuarios AS u 
        JOIN solicitud AS s ON u.ID_USER = s.ID_USER
        JOIN items AS i ON s.ID_SOLICITUD = i.ID_SOLICITUD
        JOIN inventarios as inv ON i.ID_INV = inv.ID_INV
UNION ALL
SELECT u.ID_USER, u.ID_CLIENTE, NOMBRE, APELLIDO, (SELECT IF(3>1,'Devolucion','Solicitud')) AS TIPO, d.FECHA_SOLICITUD AS FECHA, inv.CAJA AS FECHA_DEVOLUCION FROM usuarios AS u 
        JOIN devoluciones as d ON u.ID_USER = d.ID_USER
        JOIN dev_item as di ON d.ID_DEV = di.ID_DEV
        JOIN inventarios as inv ON di.ID_INV = inv.ID_INV
) a 
".$filtro." ";

			//echo $consulta;
			$conexion = new conectorDB;
			$this->json = $conexion->EjecutarSentencia($consulta);
			return $this->json;
		}
	}


}/// TERMINA CLASE USUARIOS ///