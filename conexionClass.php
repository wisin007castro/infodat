<?php

class MiConexion{

    public $IDr = 0 ;
    //Función que crea y devuelve un objeto de conexión a la base de datos y chequea el estado de la misma. 
    function conectarBD(){ 
            $server = "localhost";
            $usuario = "admin";
            $pass = "admin123";
            $BD = "infoact_consultas";
            //variable que guarda la conexión de la base de datos
            $conexion = mysqli_connect($server, $usuario, $pass, $BD); 
            //Comprobamos si la conexión ha tenido exito
            if(!$conexion){ 
               echo 'Ha sucedido un error inexperado en la conexion de la base de datos<br>'; 
            } 
            //devolvemos el objeto de conexión para usarlo en las consultas  
            return $conexion; 
    }  
    /*Desconectar la conexion a la base de datos*/
    function desconectarBD($conexion){
            //Cierra la conexión y guarda el estado de la operación en una variable
            $close = mysqli_close($conexion); 
            //Comprobamos si se ha cerrado la conexión correctamente
            if(!$close){  
               echo 'Ha sucedido un error inexperado en la desconexion de la base de datos<br>'; 
            }    
            //devuelve el estado del cierre de conexión
            return $close;         
    }

    //Devuelve un array multidimensional con el resultado de la consulta
    function getArraySQL($sql){
        //Creamos la conexión
        $conexion = $this->conectarBD();
        //generamos la consulta
        if(!$result = mysqli_query($conexion, $sql)) die();

        $rawdata = array();
        //guardamos en un array multidimensional todos los datos de la consulta
        $i=0;
        while($row = mysqli_fetch_array($result))
        {   
            //guardamos en rawdata todos los vectores/filas que nos devuelve la consulta
            $rawdata[$i] = $row;
            $i++;
        }
        //Cerramos la base de datos
        $this->desconectarBD($conexion);
        //devolvemos rawdata
        return $rawdata;
    }
    //inserta en la base de datos un nuevo registro en la tabla usuarios
    function insertRandom(){
    	//Generamos un número entero aleatorio entre 0 y 100
    	$ran = rand(0, 100);
        $ran2 = rand(0, 100);
        //creamos la conexión
        $conexion = $this->conectarBD();
        //Escribimos la sentencia sql necesaria respetando los tipos de datos
        $sql = "insert into random (valor, valor2) 
        values (".$ran.",".$ran2.")";
        //hacemos la consulta y la comprobamos 
        $consulta = mysqli_query($conexion,$sql);
        if(!$consulta){
            echo "No se ha podido insertar en la base de datos<br><br>".mysqli_error($conexion);
        }
        //Desconectamos la base de datos
        $this->desconectarBD($conexion);
        //devolvemos el resultado de la consulta (true o false)
        return $consulta;
    }
    function getAllInfo(){
        //Creamos la consulta
        $sql = "SELECT * FROM inventarios LIMIT 5;";
        //obtenemos el array con toda la información
        return $this->getArraySQL($sql);
    }

    public function anios()
    {
        $sql = "SELECT DISTINCT ANO_I FROM inventarios LIMIT 20;";
        return $this->getArraySQL($sql);
    }

    public function usuarios()
    {
        $sql = "SELECT ID_USER, c.CLIENTE, NOMBRE, APELLIDO, CARGO, DIRECCION, TELEFONO, INTERNO, CELULAR, CORREO, u.HABILITADO, TIPO, REGIONAL 
                  FROM usuarios AS u JOIN clientes AS c ON u.ID_CLIENTE = c.ID_CLIENTE 
                  ORDER BY ID_USER";
        return $this->getArraySQL($sql);
    }

    public function clientes(){
        $sql = "SELECT * FROM clientes";
        return $this->getArraySQL($sql);
    }

    public function tipoUsuarios(){
        $sql = "SELECT * FROM tipos_user";
        return $this->getArraySQL($sql);
    }

    public function pedidos(){
        $sql = "SELECT inv.ID_INV, s.ID_SOLICITUD, u.NOMBRE, u.APELLIDO, s.TIPO_CONSULTA, s.DIRECCION_ENTREGA, s.FECHA_SOLICITUD, 
                      s.HORA_SOLICITUD, s.PROCESADO_POR, s.FECHA_ENTREGA, s.HORA_ENTREGA, s.ENTREGADO_POR, item.ESTADO
                FROM solicitud AS s JOIN usuarios AS u ON s.ID_USER = u.ID_USER 
                JOIN items AS item ON s.ID_SOLICITUD = item.ID_SOLICITUD
                JOIN inventarios AS inv ON inv.ID_INV = item.ID_INV
                WHERE s.ESTADO <> 'ATENDIDA/ENTREGADA' ORDER BY s.ID_SOLICITUD LIMIT 25";
        return $this->getArraySQL($sql);
    }

    public function devoluciones(){
        $sql = "SELECT inv.ID_INV, d.ID_DEV, u.NOMBRE, u.APELLIDO, d.DIRECCION, d.FECHA_SOLICITUD, d.FECHA_PROGRAMADA, d.PROCESADO_POR, d.RECOGIDO_POR, d.ESTADO 
                FROM devoluciones AS d JOIN usuarios AS u ON d.ID_USER = u.ID_USER
                JOIN dev_item AS d_item ON d.ID_DEV = d_item.ID_DEV
                JOIN inventarios AS inv ON inv.ID_INV = d_item.ID_INV ";
        return $this->getArraySQL($sql);
    }

    public function solicitudes($cliente){
        $sql = "SELECT inv.ID_INV, s.ID_SOLICITUD, u.NOMBRE, u.APELLIDO, s.FECHA_SOLICITUD, s.DIRECCION_ENTREGA, s.ESTADO
                FROM solicitud AS s JOIN usuarios AS u ON s.ID_USER = u.ID_USER
                JOIN items AS item ON s.ID_SOLICITUD = item.ID_SOLICITUD
                JOIN inventarios AS inv ON inv.ID_INV = item.ID_INV
                WHERE s.ESTADO <> 'ATENDIDA/ENTREGADA'
                AND s.ID_CLIENTE = $cliente
                ORDER BY s.ID_SOLICITUD DESC";
        return $this-> getArraySQL($sql);
    }

    public function llamadaSP($desc1, $desc2, $desc3, $mes, $anio, $caja){
        $conexion = $this->conectarBD();
        //generamos la consulta
        if(!$result = mysqli_query($conexion, "CALL buscaInventarios('$desc1', '$desc2', '$desc3', '$mes', '$anio', '$caja');")) die();
        //  or die("Query fail: " . mysqli_error());
        $rawdata = array();
        //guardamos en un array multidimensional todos los datos de la consulta
        $i=0;
        while($row = mysqli_fetch_array($result))
        {   
            //guardamos en rawdata todos los vectores/filas que nos devuelve la consulta
            $rawdata[$i] = $row;
            $i++;
        }
        //Cerramos la base de datos
        $this->desconectarBD($conexion);
        //devolvemos rawdata
        return $rawdata;
    }
}

?>