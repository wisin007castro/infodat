<?php 
	ob_start();
	require_once '../conexionClass.php';
	$conexion = new MiConexion();

	$id_sol = $_GET['id_sol'];
	// $procesado_por = $conexion->usuario($_GET['procesado_por']);

	$pedidos = $conexion->id_ped_devol($id_sol);
    // var_dump($pedidos);
    
    $solicitudes_todo = array_column($pedidos, 'ID_SOLICITUD');//todos los clientes
    // echo var_dump($clientes_todo);
    $sol_consultas = array_unique($solicitudes_todo);//Conteo de valores
    // echo var_dump($deptos);
	date_default_timezone_set('America/La_Paz'); //definiendo zona horaria
	$script_tz = date_default_timezone_get();

	$tiempo = getdate();
	$fecha = $tiempo['year']."-".$tiempo['mon']."-".$tiempo['mday'];
    $hora = $tiempo['hours'].":".$tiempo['minutes'];
    
    foreach ($sol_consultas as $key => $sc) {
        # code...

 ?>


	
<!-- // $html = file_get_contents('../form_101.php'); -->
<!-- $html = " -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  <title></title>
  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>

<table class="table">
  <tr>
    <td width="25%" align="center"><img src="../dist/img/logoactiva.png" style="width:150px;height:50px;">
        </td>
        <td width="50%" align="center"><h5>FORMULARIO 106</h5>
  		<h5>DEVOLUCION DE CAJAS / DOCUMENTOS</h5>
        </td>
        <td width="25%" style="font-size:16px;" align="center">
            Número: <?php echo $pedidos[0]['ID_SOLICITUD']." / ".$tiempo['year']; ?>
            <small><br><?php echo $tiempo['mday']."/".$tiempo['mon']."/".$tiempo['year']; ?></small>
             - <small><?php echo $hora; ?></small>
        </td>
  </tr>
</table>
  
<hr>
<h5><b>DATOS DEL CLIENTE: </b></h5>
<ul>
  <li>NOMBRE DE LA EMPRESA: <?php echo $pedidos[0]['CLIENTE']; ?> <br></li>
  <li>DEPARTAMENTO: <?php echo $pedidos[0]['DEPARTAMENTO']; ?> <br></li>
  <li>NOMBRE DEL SOLICITANTE: <?php echo $pedidos[0]['NOMBRE']; ?><br></li>
  <li>FECHA DE SOLICITUD: <?php echo $pedidos[0]['FECHA_SOLICITUD']; ?>&nbsp;&nbsp;&nbsp;
  HORA DE SOLICITUD: <?php echo $pedidos[0]['HORA_SOLICITUD']; ?> </li>
  <li> FECHA DE ENTREGA: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  HORA DE ENTREGA:  <br></li>

</ul>
<hr>
<h5><b>DOCUMENTOS A DEVOLVER: </b></h5>
<div class="box-body table-responsive no-padding">
	<table class="table table-bordered" style="font-size:10px;">
    <thead><tr>
        <!-- <th>Nro</th>
        <th>LUGAR</th> -->
        <th>CAJA</th>
        <th>ITEM</th>
        <th>DESCRIPCION</th>
        <th>UNIDAD</th>
        <th>FECHA<br>INICIAL</th>
        <th>FECHA<br>FINAL</th>
    </tr>
    </thead>
    <tbody>
    	<?php foreach ($pedidos as $key => $value): ?>
    		<tr>
    			<!-- <td><?php// echo $key+1; ?> </td>
    			<td>010100101</td> -->
    			<td><?php echo $value['CAJA']; ?> </td>
    			<td><?php echo $value['ITEM']; ?> </td>
				<td>
				<?php //CORTE DE CARACTERES DE DESCRIPCION 
				$desc = $value['DESC_1']." ".$value['DESC_2']." ".$value['DESC_3'];
				$descripcion = "";
					while (strlen($desc) >= 40) {
					$descripcion = substr($desc, 0, 39);
					$desc = substr($desc, 39);
					echo $descripcion."-<br>";
					}
				echo $desc;
				?>
				</td>

    			<td><?php echo $value['CANTIDAD']." ".$value['UNIDAD']; ?> </td>
    			<td>
    				<?php 
    					if ($value['DIA_I'] != "") {
    						echo $value['DIA_I']."-";
    					}
    					echo $value['MES_I']."-".$value['ANO_I'];
    				 ?> 
    			</td>
    			<td>
    				<?php 
    					if ($value['DIA_F'] != "") {
    						echo $value['DIA_F']."-";
    					}
    					echo $value['MES_F']."-".$value['ANO_F'];
    				 ?> 
    			</td>
    		</tr>
    	<?php endforeach ?>
    </tbody>
	</table>
</div>

<hr>
<h5><b>OBSERVACIONES: </b></h5><?php echo $pedidos[0]['OBSERVACION']; ?>

<br>
<center><h4>Uso Exclusivo INFOACTIVA SRL</h4></center>
<b>RECEPCIONADO POR: </b></h4><?php echo $procesado_por[0]['NOMBRE']." ".$procesado_por[0]['APELLIDO']; ?>
<hr>
<table class="table table-bordered" style="font-size:11px;">
  <tr>
    <td><b>CENTRAL DE CONSULTAS: </b><?php echo $pedidos[0]['REGIONAL']; ?></td>
    <td><b>FECHA: </b><?php echo $fecha; ?></td>
    <td><b>HORA: </b><?php echo $hora; ?></td>
  </tr>
</table>

</body>
</html>

<!-- "; -->
<?php 
	require_once '../dompdf/autoload.inc.php';
	// use Dompdf\Dompdf;

	$dompdf = new DOMPDF();
	$dompdf->loadHtml(ob_get_clean());
	// $dompdf->set_paper('A4', 'landscape');
	// ini_set("memory_limit","32M");
	$dompdf->render();
	$pdf = $dompdf->output();

	$dompdf->stream(
		"form_101-".$id_sol.".pdf", array('Attachment' => true)
	);

 ?>

