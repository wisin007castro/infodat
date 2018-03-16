<?php 

require_once '../conexionClass.php';
$conexion = new MyConexion();
$con = $conexion->conectarDB();

$sql = "INSERT INTO usuarios(ID_CLIENTE, NOMBRE, APELLIDO, CARGO, DIRECCION, TELEFONO, INTERNO, CELULAR, CORREO, USER, PASS, HABILITADO, TIPO, REGIONAL) VALUES ('value-2','value-3','value-4','value-5','value-6','value-7','value-8','value-9','value-10','value-11','value-12','value-13','value-14','value-15')";

 ?>