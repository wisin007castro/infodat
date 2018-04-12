<?php 
$todas = var_dump($_POST);

require_once '../conexionClass.php';
$conexion = new MiConexion();

$con = $conexion->conectarBD();

$id_cliente = $_POST['cliente'];
$id_user = $_POST['id_user'];
$modulos = $_POST['modulos'];//Tipo
$acceso = $_POST['acceso'];
$asignaciones = $_POST['asignacion'];//Departamentos (Array o string)



$regional = $_POST['regional'];
$usuarios = $conexion->usuarios_reg($id_cliente, $regional);

$cont = 0;

if ($modulos != '0') {
    if ($asignaciones > 1 && $acceso == 'SI') {
        foreach ($asignaciones as $value)
        {
            $user_access = mysql_query($con, "SELECT * FROM parametros 
            WHERE ID_USER = '".$id_user."' 
            AND TIPO = '".$modulos."' 
            AND ASIGNACION = '".$asignaciones."' 
            ");
            if (mysql_num_rows($user_access) > 0) {
                
            }
        }
        echo 'Se haran '.$cont.' inserciones';
    }
    else{
        echo 'una insercion';
    }
}

// if(is_array($asignaciones) || is_object($asignaciones)) // Array
// {
//     foreach ($asignaciones as $value)
//     {
//         // $user_access = mysql_query($con, "SELECT * FROM parametros 
//         // WHERE ID_USER = '".$id_user."' 
//         // AND TIPO = '".$modulos."' 
//         // AND ASIGNACION = '".$asignacion."' 
//         // ");
//         // if (mysql_num_rows($user_access) > 0) {
            
//         // }


//     }
//     echo 'Se haran '.$cont.' inserciones';
// }
// else{
//     if($modulos == 'solicitud_consultas'){

//     }
//     // if($asignaciones == 'ninguno' && $acceso == 'NO' && $modulos != '0'){
        
//     //     // $sql = "INSERT INTO parametros(ID_CLIENTE, ID_USER, TIPO, ACCESO, DEPARTAMENTO, HABILITADO) VALUES ('value-2','value-3','value-4','value-5','value-6','value-7')";
//     // }
//     // else{
//     //     if ($modulos != '0') {
//     //         echo 'deberia seleccionar de la lista';
//     //     }
//     //     else{
//     //         echo 'Seleccione un modulo';
//     //     }
//     // }
// }


?>