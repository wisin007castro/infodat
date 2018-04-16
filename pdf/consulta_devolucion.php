<?php 
ob_start();

require_once '../conexionClass.php';
require_once '../stringsClass.php';
$conexion = new MiConexion();
$mString = new MiStrings();

$id_user = $_GET['id_user'];
$usuario = $conexion->usuario($id_user);
$date_range = $_GET['fechas'];
$f_inicio = substr($date_range, 0, 10);
$f_fin = substr($date_range, -10);

//Tiempo

$meses = $mString->meses();
date_default_timezone_set('America/La_Paz'); //definiendo zona horaria
$script_tz = date_default_timezone_get();

$tiempo = getdate();
$fecha = $tiempo['year']."-".$tiempo['mon']."-".$tiempo['mday'];
$hora = $tiempo['hours'].":".$tiempo['minutes'];

$cliente = $conexion->cliente($usuario[0]['ID_CLIENTE']);

if ($usuario[0]['TIPO'] == 'IA_ADMIN' || $usuario[0]['TIPO'] == 'ADMIN') {
    $pedidos = $conexion->pedidos_admin();
}
else{
    $pedidos = $conexion->rep_ped($usuario[0]['ID_CLIENTE'], $f_inicio, $f_fin);
}

$deptos_todo = array_column($pedidos, 'DEPARTAMENTO');//todos los departamentos
//$deptos = array_unique($deptos_todo);valores unicos(de una columna)
$i_ord_dptos = array_count_values($deptos_todo);//Conteo de valores
$deptos = array_reverse ($i_ord_dptos);


echo var_dump($deptos);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reporte Consultas - Devoluciones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">

</head>
<body>


<table class="table">
  <tr>
    <td style="font-size:18px;" align="center">
        <b><br><?php echo $meses[$tiempo['mon']]." ".$tiempo['year']; ?></b>
    </td>
    <td align="center"><h3>Informe de Stock y Consultas de Documentación</h3>
  		<h3><b><?php echo $cliente[0]['CLIENTE']; ?></b></h3>
    </td>
    <td align="center"><br><img src="../dist/img/logoactiva.png" style="width:180px;height:80px;">
    </td>
  </tr>
</table>
<h4><b>ARCHIVO CENTRAL </b></h4>
<?php foreach ($deptos as $keyDep => $dp) : ?>
<h5><b><?php echo $keyDep; ?></b></h5>
<div class="box-body table-responsive no-padding">
	<table class="table table-bordered" style="font-size:10px;">
    <thead><tr>
        <!-- <th></th> -->
        <!-- <th></th> -->
        <th>No. Solicitud</th>
        <th>Solicitante</th>
        <th>Fecha Entrega</th>
        <th>Cantidad</th>
        <th>Envio</th>
        <th>No. Caja</th>
        <th>Descripción 1</th>
        <th>Descripción 2</th>
    </tr>
    </thead>
    <tbody>
    	<?php foreach ($pedidos as $key => $value): ?>
    		<tr>
            <?php if ($value['DEPARTAMENTO'] == $keyDep): ?>
    			<!-- <td><?php //echo $key+1; ?> </td>
    			<td>010100101</td> -->
    			<td><?php echo $value['ID_SOLICITUD']; ?> </td>
                <td><?php echo $value['NOMBRE']." ".$value['APELLIDO']; ?> </td>
                <td><?php echo $value['FECHA_ENTREGA']; ?> </td>
                <td><?php echo $value['CANTIDAD']; ?> </td>
                <td><?php echo $value['UNIDAD']; ?> </td>
                <td><?php echo $value['CAJA']; ?> </td>
                <td><?php echo $value['DESC_1']; ?> </td>
                <td><?php echo $value['DESC_2']; ?> </td>
    		</tr>
            <?php endif ?>
    	<?php endforeach ?>
    </tbody>
	</table>
</div>
<?php endforeach ?>

<?php 


// echo $id_user."<br>";
// echo var_dump($usuario)."<br>";
echo "FECHA: ".$f_inicio."<br>";
echo "FECHA: ".$f_fin."<br>";
// echo var_dump($pedidos);
?>

</body>
</html>