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

    public function estado_pedidos($filtro){
        $consulta = "SELECT s.ID_SOLICITUD, inv.CAJA, inv.ITEM, inv.DESC_1, 
		inv.DESC_2, inv.DESC_3, inv.DESC_4, inv.CANTIDAD, inv.UNIDAD, inv.DIA_I, inv.MES_I, 
		inv.ANO_I, inv.DIA_F, inv.MES_F, inv.ANO_F, inv.DEPARTAMENTO, inv.ESTADO, inv.ID_INV
                FROM solicitud AS s JOIN usuarios AS u ON s.ID_USER = u.ID_USER 
                JOIN items AS item ON s.ID_SOLICITUD = item.ID_SOLICITUD
                JOIN inventarios AS inv ON inv.ID_INV = item.ID_INV ".$filtro."";
        	$conexion = new conectorDB;
			$this->json = $conexion->EjecutarSentencia($consulta);
			return $this->json;
	}

	public function estado_pedidos_auth($filtro){
        $consulta = "SELECT s.ID_SOLICITUD, inv.CAJA, inv.ITEM, inv.DESC_1, 
		inv.DESC_2, inv.DESC_3, inv.DESC_4, inv.CANTIDAD, inv.UNIDAD, inv.DIA_I, inv.MES_I, 
		inv.ANO_I, inv.DIA_F, inv.MES_F, inv.ANO_F, inv.DEPARTAMENTO, inv.ESTADO, inv.ID_INV
                FROM solicitud_auth AS s JOIN usuarios AS u ON s.ID_USER = u.ID_USER 
                JOIN items_auth AS item ON s.ID_SOLICITUD = item.ID_SOLICITUD
                JOIN inventarios AS inv ON inv.ID_INV = item.ID_INV ".$filtro."";
        	$conexion = new conectorDB;
			$this->json = $conexion->EjecutarSentencia($consulta);
			return $this->json;
    }

    public function estado_devoluciones($filtro){
        $consulta = "SELECT d.ID_DEV, inv.CAJA, inv.ITEM, inv.DESC_1, inv.DESC_2, inv.DESC_3, inv.DESC_4, inv.CANTIDAD, inv.UNIDAD, inv.DIA_I, inv.MES_I, inv.ANO_I, inv.DIA_F, inv.MES_F, inv.ANO_F, inv.DEPARTAMENTO, inv.ESTADO
                FROM devoluciones AS d JOIN usuarios AS u ON d.ID_USER = u.ID_USER
                JOIN dev_item AS d_item ON d.ID_DEV = d_item.ID_DEV
                JOIN inventarios AS inv ON inv.ID_INV = d_item.ID_INV ".$filtro."";
        	$conexion = new conectorDB;
			$this->json = $conexion->EjecutarSentencia($consulta);
			return $this->json;
	}

	public function BuscaUsuarios($filtro){
		if($filtro <> ""){
			$consulta = "SELECT * FROM usuarios ".$filtro." ";//Los espacios son importantes
			//echo $consulta;
			$conexion = new conectorDB;
			$this->json = $conexion->EjecutarSentencia($consulta);
			return $this->json;
		}
	}

	public function deptos($id_usuario)
    {
    	$conexion = new conectorDB;
        // $sql = "SELECT DISTINCT DEPARTAMENTO FROM (
        //             SELECT * FROM accesos WHERE ID_USER = $id_usuario AND HABILITADO = 'SI'
		// 		) a ";
		$sql = "SELECT DISTINCT ASIGNACION FROM (
			SELECT * FROM parametros WHERE ID_USER = $id_usuario AND HABILITADO = 'SI'
		) a ";
        			$this->json = $conexion->EjecutarSentencia($sql);
		return $this->json;
    }
	
	public function BuscaInventarios($filtro, $id_usuario){

		$conexion = new conectorDB;
		$resultado = array();
		$deptos = $this->deptos($id_usuario);

		if($filtro <> ""){
			//Los espacios son importantes
			foreach ($deptos as $key => $value) {
				if($value['ASIGNACION'] != 'TODOS'){
					$consulta = "SELECT * FROM inventarios AS inv
						JOIN clientes AS c ON inv.CLIENTE = c.ID_CLIENTE
						 ".$filtro." 
						 AND DEPARTAMENTO = '".$value['ASIGNACION']."' LIMIT 100";
						 $resultado = array_merge($resultado, $conexion->EjecutarSentencia($consulta));
				}
				else{
					$consulta = "SELECT * FROM inventarios AS inv
						JOIN clientes AS c ON inv.CLIENTE = c.ID_CLIENTE
						 ".$filtro." LIMIT 100";
					$resultado = $conexion->EjecutarSentencia($consulta);
				}

			}
		}
		return $resultado;
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

	public function BuscaParametro($filtro)
    {
    	$conexion = new conectorDB;
        $sql = "SELECT u.ID_USER, u.NOMBRE, u.APELLIDO, p.TIPO, p.ACCESO, p.ASIGNACION, CONCAT(ua.NOMBRE, ' ',ua.APELLIDO) AS NASIGNACION, p.HABILITADO  
		FROM parametros AS p JOIN usuarios AS u ON p.ID_USER = u.ID_USER
		LEFT JOIN usuarios AS ua ON p.ASIGNACION = ua.ID_USER $filtro ";
        	$this->json = $conexion->EjecutarSentencia($sql);
		return $this->json;
    }


}/// TERMINA CLASE USUARIOS ///