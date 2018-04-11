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

    // public function deptos($id_usuario)
    // {
        // $sql = "SELECT DISTINCT DEPARTAMENTO FROM (
        //             SELECT * FROM accesos where ID_USER = $id_usuario
        //         ) a ";
        // return $this->getArraySQL($sql);
    // }

    public function dptos_access($id_cliente){
        $sql = "SELECT DISTINCT DEPARTAMENTO FROM (
                    SELECT * FROM inventarios where CLIENTE = $id_cliente
                ) a ";
        return $this->getArraySQL($sql);
    }

    public function usuarios_almacen()
    {
        $sql = "SELECT ID_USER, c.CLIENTE, NOMBRE, APELLIDO, CARGO, DIRECCION, TELEFONO, INTERNO, CELULAR, CORREO, u.HABILITADO, TIPO, REGIONAL 
                  FROM usuarios AS u JOIN clientes AS c ON c.ID_CLIENTE = u.ID_CLIENTE 
                  WHERE c.CLIENTE = 'INFOACTIVA SRL'
                  AND (u.TIPO = 'ALMACEN'
                  OR u.TIPO = 'CONSULTA')
                  ORDER BY ID_USER ASC";
        return $this->getArraySQL($sql);
    }

    public function usuarios($cliente)
    {
        $sql = "SELECT ID_USER, c.CLIENTE, NOMBRE, APELLIDO, CARGO, DIRECCION, TELEFONO, INTERNO, CELULAR, CORREO, u.HABILITADO, TIPO, REGIONAL 
                  FROM usuarios AS u JOIN clientes AS c ON c.ID_CLIENTE = u.ID_CLIENTE 
                  WHERE u.ID_CLIENTE = $cliente
                  ORDER BY ID_USER ASC";
        return $this->getArraySQL($sql);
    }

    public function usuarios_reg($cliente, $reg)
    {
        $sql = "SELECT ID_USER, c.CLIENTE, NOMBRE, APELLIDO, CARGO, DIRECCION, TELEFONO, INTERNO, CELULAR, CORREO, u.HABILITADO, TIPO, REGIONAL 
                  FROM usuarios AS u JOIN clientes AS c ON c.ID_CLIENTE = u.ID_CLIENTE 
                  WHERE u.ID_CLIENTE = $cliente
                  AND u.REGIONAL = '".$reg."'
                  ORDER BY ID_USER ASC";
        return $this->getArraySQL($sql);
    }

    public function usuario($id){
        $sql = "SELECT * FROM usuarios WHERE ID_USER = $id";
        return $this->getArraySQL($sql);
    }

    public function clientes(){
        $sql = "SELECT * FROM clientes";
        return $this->getArraySQL($sql);
    }

    public function cliente($id){
        $sql = "SELECT * FROM clientes WHERE ID_CLIENTE = $id";
        return $this->getArraySQL($sql);
    }

    public function tipoUsuarios(){
        $sql = "SELECT * FROM tipos_user";
        return $this->getArraySQL($sql);
    }

    public function item($id_sol){
        $sql = "SELECT * FROM items WHERE ID_SOLICITUD = $id_sol";
        return $this->getArraySQL($sql);
    }

    public function item_sol(){
        $sql = "SELECT * FROM items";
        return $this->getArraySQL($sql);
    }

    public function dev_item($id_dev){
        $sql = "SELECT * FROM dev_item WHERE ID_DEV = $id_dev";
        return $this->getArraySQL($sql);
    }

    // public function pedidos(){
    //     $sql = "SELECT inv.ID_INV, s.ID_SOLICITUD, u.NOMBRE, u.APELLIDO, s.TIPO_CONSULTA, s.DIRECCION_ENTREGA, s.FECHA_SOLICITUD, 
    //                   s.HORA_SOLICITUD, s.PROCESADO_POR, s.FECHA_ENTREGA, s.HORA_ENTREGA, s.ENTREGADO_POR, item.ESTADO
    //             FROM solicitud AS s JOIN usuarios AS u ON s.ID_USER = u.ID_USER 
    //             JOIN items AS item ON s.ID_SOLICITUD = item.ID_SOLICITUD
    //             JOIN inventarios AS inv ON inv.ID_INV = item.ID_INV
    //             WHERE s.ESTADO <> 'ATENDIDA/ENTREGADA' ORDER BY s.ID_SOLICITUD LIMIT 25";
    //     return $this->getArraySQL($sql);
    // }
    public function id_pedidos($id_sol)//forms
    {
        $sql = "SELECT c.CLIENTE, u.NOMBRE, u.APELLIDO, u.CARGO, u.TELEFONO, u.INTERNO, u.CELULAR, s.DIRECCION_ENTREGA, s.TIPO_CONSULTA, s.ID_SOLICITUD, inv.CAJA, inv.ITEM, inv.DESC_1, inv.DESC_2, inv.DESC_3, inv.DESC_4, inv.CANTIDAD, inv.UNIDAD, inv.DIA_I, inv.MES_I, inv.ANO_I, inv.DIA_F, inv.MES_F, inv.ANO_F, inv.DEPARTAMENTO, inv.ESTADO, s.OBSERVACION, s.PROCESADO_POR, s.REGIONAL, s.FECHA_SOLICITUD, s.HORA_SOLICITUD
        FROM solicitud AS s JOIN usuarios AS u ON s.ID_USER = u.ID_USER JOIN clientes as c ON s.ID_CLIENTE = c.ID_CLIENTE JOIN items AS item ON s.ID_SOLICITUD = item.ID_SOLICITUD JOIN inventarios AS inv ON inv.ID_INV = item.ID_INV WHERE s.ID_SOLICITUD = $id_sol";
        return $this->getArraySQL($sql);

    }

    public function pedidos($id_cliente){
        $sql = "SELECT s.ID_SOLICITUD, s.ID_USER, u.NOMBRE, u.APELLIDO, s.TIPO_CONSULTA, s.DIRECCION_ENTREGA, s.FECHA_SOLICITUD, s.HORA_SOLICITUD, s.PROCESADO_POR, s.FECHA_ENTREGA, s.HORA_ENTREGA, s.ENTREGADO_POR, s.ESTADO, s.REGIONAL
                FROM solicitud AS s JOIN usuarios AS u ON s.ID_USER = u.ID_USER 
                WHERE s.ID_CLIENTE = $id_cliente
                ORDER BY s.ID_SOLICITUD DESC";
        return $this->getArraySQL($sql);
    }

    public function pedidos_admin(){
        $sql = "SELECT s.ID_SOLICITUD, s.ID_USER, u.NOMBRE, u.APELLIDO, s.TIPO_CONSULTA, s.DIRECCION_ENTREGA, s.FECHA_SOLICITUD, 
                      s.HORA_SOLICITUD, s.PROCESADO_POR, s.FECHA_ENTREGA, s.HORA_ENTREGA, s.ENTREGADO_POR, s.ESTADO, s.REGIONAL
                FROM solicitud AS s JOIN usuarios AS u ON s.ID_USER = u.ID_USER 

                 ORDER BY s.ESTADO DESC, s.ID_SOLICITUD  DESC";
        return $this->getArraySQL($sql);
    }

    // public function devoluciones(){
    //     $sql = "SELECT inv.ID_INV, d.ID_DEV, u.NOMBRE, u.APELLIDO, d.DIRECCION, d.FECHA_SOLICITUD, d.FECHA_PROGRAMADA, d.PROCESADO_POR, d.RECOGIDO_POR, d.ESTADO 
    //             FROM devoluciones AS d JOIN usuarios AS u ON d.ID_USER = u.ID_USER
    //             JOIN dev_item AS d_item ON d.ID_DEV = d_item.ID_DEV
    //             JOIN inventarios AS inv ON inv.ID_INV = d_item.ID_INV ";
    //     return $this->getArraySQL($sql);
    // }

    public function devoluciones($id_cliente){
        $sql = "SELECT d.ID_DEV, d.ID_DEV, u.NOMBRE, u.APELLIDO, d.DIRECCION, d.FECHA_SOLICITUD, d.FECHA_PROGRAMADA, d.PROCESADO_POR, d.RECOGIDO_POR, d.ESTADO, d.REGIONAL, d.ID_USER
                FROM devoluciones AS d JOIN usuarios AS u ON d.ID_USER = u.ID_USER 
                WHERE d.ID_CLIENTE = $id_cliente 
                ORDER BY d.ID_DEV DESC";
        return $this->getArraySQL($sql);
    }

    public function devoluciones_admin(){
        $sql = "SELECT d.ID_DEV, u.NOMBRE, u.APELLIDO, d.DIRECCION, d.FECHA_SOLICITUD, d.FECHA_PROGRAMADA, d.PROCESADO_POR, d.RECOGIDO_POR, d.ESTADO, d.REGIONAL
                FROM devoluciones AS d JOIN usuarios AS u ON d.ID_USER = u.ID_USER 
                ORDER BY d.ESTADO DESC, d.ID_DEV  DESC";
        return $this->getArraySQL($sql);
    }

    public function solicitudes($cliente){
        $sql = "SELECT inv.ID_INV, inv.CAJA, s.ID_SOLICITUD, u.NOMBRE, u.APELLIDO, s.FECHA_SOLICITUD, s.DIRECCION_ENTREGA, s.ESTADO, inv.ESTADO as ESTADO_INV, s.ID_USER
                FROM solicitud AS s JOIN usuarios AS u ON s.ID_USER = u.ID_USER
                JOIN items AS item ON s.ID_SOLICITUD = item.ID_SOLICITUD
                JOIN inventarios AS inv ON inv.ID_INV = item.ID_INV
                WHERE s.ESTADO = 'ATENDIDA/ENTREGADA'
                AND s.ID_CLIENTE = $cliente
                ORDER BY s.ID_SOLICITUD DESC";
        return $this-> getArraySQL($sql);
    }

    public function repAccesso($cliente){
        $sql = "SELECT * FROM(
                    SELECT u.ID_CLIENTE, NOMBRE, APELLIDO, (SELECT IF(1>3,'Devolucion','Solicitud')) AS TIPO, s.FECHA_SOLICITUD AS FECHA, inv.CAJA FROM usuarios AS u 
                        JOIN solicitud AS s ON u.ID_USER = s.ID_USER
                        JOIN items AS i ON s.ID_SOLICITUD = i.ID_SOLICITUD
                        JOIN inventarios as inv ON i.ID_INV = inv.ID_INV
                    UNION ALL
                    SELECT u.ID_CLIENTE, NOMBRE, APELLIDO, (SELECT IF(3>1,'Devolucion','Solicitud')) AS TIPO, d.FECHA_SOLICITUD AS FECHA, inv.CAJA AS FECHA_DEVOLUCION FROM usuarios AS u 
                        JOIN devoluciones as d ON u.ID_USER = d.ID_USER
                        JOIN dev_item as di ON d.ID_DEV = di.ID_DEV
                        JOIN inventarios as inv ON di.ID_INV = inv.ID_INV
                ) a 
                WHERE ID_CLIENTE = $cliente
                ORDER BY FECHA";
        return $this->getArraySQL($sql);
    }
//
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