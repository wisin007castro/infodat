<?php 
	ob_start();
	require_once '../conexionClass.php';
	require_once '../fpdf/fpdf.php';

//array_column for PHP <= 5.5 versions
if (!function_exists('array_column')) {
	function array_column(array $array, $columnKey, $indexKey = null)
	{
		$result = array();
		foreach ($array as $subArray) {
			if (!is_array($subArray)) {
				continue;
			} elseif (is_null($indexKey) && array_key_exists($columnKey, $subArray)) {
				$result[] = $subArray[$columnKey];
			} elseif (array_key_exists($indexKey, $subArray)) {
				if (is_null($columnKey)) {
					$result[$subArray[$indexKey]] = $subArray;
				} elseif (array_key_exists($columnKey, $subArray)) {
					$result[$subArray[$indexKey]] = $subArray[$columnKey];
				}
			}
		}
		return $result;
	}
  }

	$conexion = new MiConexion();

	$id_sol = $_GET['id_sol'];
	// $procesado_por = $conexion->usuario($_GET['procesado_por']);

	$pedidos = $conexion->id_ped_devol($id_sol);
    // var_dump($pedidos);
    
    $solicitudes_todo = array_column($pedidos, 'ID_SOLICITUD');//todos los clientes
    // echo var_dump($clientes_todo);
    $sol_consultas = array_unique($solicitudes_todo);//Conteo de valores
	
	date_default_timezone_set('America/La_Paz'); //definiendo zona horaria
	$script_tz = date_default_timezone_get();

	$tiempo = getdate();
	$fecha = $tiempo['year']."-".$tiempo['mon']."-".$tiempo['mday'];
    $hora = $tiempo['hours'].":".$tiempo['minutes'];
	
	$pdf = new FPDF();
	foreach ($sol_consultas as $sc){

	$c=0;
	
	$pdf->AddPage();

	foreach ($pedidos as $value) {
		if ($sc == $value['ID_SOLICITUD'] && $c == 0) {
			$c++;
			$pdf->SetFont('Arial', '', 10);
			$pdf->Image('../dist/img/logoactiva.gif' , 10 ,15, 40 , 15,'GIF');
			$pdf->Cell(40, 10, '', 0);
			$pdf->SetFont('Arial', 'B', 11);
			$pdf->Cell(120, 10, 'FORMULARIO 106', 0,0,'C');
			$pdf->SetFont('Arial', '', 10);
			$pdf->Cell(50, 10, 'Numero: '.$id_sol." / ".$value['ID_SOLICITUD'].'', 0,1);
			$pdf->Cell(0, 10, ''.date('d/m/Y').' - '.$hora.' ', 0,0,'R');
			
			$pdf->SetFont('Arial', 'B', 11);
			$pdf->Ln(1);
			$pdf->Cell(0, 8, 'DEVOLUCION DE CAJAS / DOCUMENTOS', 0, 1, 'C');
			$pdf->Ln(10);
			$pdf->SetFont('Arial', '', 11);
		
			$pdf->Cell(0,6,"Nombre de la Empresa: ".$value['CLIENTE'], 'LTR', 1,1);
			$pdf->Cell(0,6,'Departamento: '.$value['DEPARTAMENTO'], 'LR',  1,1);
			$pdf->Cell(0,6,'NOMBRE DEL SOLICITANTE: '.$value['NOMBRE'], 'LR', 1,1);
			$pdf->Cell(0,6,'Fecha de Solicitud de Devolucion : '.$value['FECHA_SOLICITUD']."", 'LR', 1,1);
			$pdf->Cell(0,6,'Fecha de Devolucion: '.$value['FECHA_PROGRAMADA'], 'LRB', 1,1);
			$pdf->Ln(2);
			$pdf->Cell(0,6,"Prioridad: ".$value['TIPO_CONSULTA'], 'LTR', 1,1);
			$pdf->Cell(0,6,'Observaciones: '.$value['OBSERVACION'], 'LRB', 1,1);
		
			$pdf->Ln(10);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(20,10,"Caja", 1, 0);
			$pdf->Cell(90,10,"Descripcion", 1, 0);
			$pdf->Cell(30,10,"Unidad", 1, 0);
			$pdf->Cell(25,10,"Fecha Inicial", 1, 0);
			$pdf->Cell(25,10,"Fecha Final", 1, 0);
			$pdf->Ln(10);
			$pdf->SetFont('Arial', '', 8);
		}
	}
	//CONTENIDO DE LA TABLA
	foreach ($pedidos as $value){
		if ($sc == $value['ID_SOLICITUD']){
			$pdf->Cell(20,8, $value['CAJA'], 1, 0);
			$desc = $value['DESC_1']." ".$value['DESC_2']." ".$value['DESC_3'];
			$descripcion = "";
				if (strlen($desc) >= 50) {
				$desc = substr($desc, 0, 49);
				}

			$pdf->Cell(90,8, $desc, 1, 0);
			$pdf->Cell(30,8,$value['CANTIDAD']." ".$value['UNIDAD'], 1, 0);
			$pdf->Cell(25,8,$value['DIA_I']."-".$value['MES_I']."-".$value['ANO_I'], 1, 0);
			$pdf->Cell(25,8,$value['DIA_F']."-".$value['MES_F']."-".$value['ANO_F'], 1, 0);
			$pdf->Ln(8);
		}
	}

	$pdf->Ln(10);
	$pdf->Cell(95,6,$pedidos[0]['CLIENTE'], 'LT',0, 'C');
	$pdf->Cell(95,6,"INFOACTIVA S.R.L.", 'LTR',1, 'C');
	
	$pdf->Cell(95,6,'Entregado por: ', 'L',0, 'L');
	$pdf->Cell(95,6,'Recibido por: ', 'LR',1, 'L');

	$pdf->Cell(95,6,'Cedula de Identidad: ', 'L',0, 'L');
	$pdf->Cell(95,6,'Cedula de Identidad: ', 'LR',1, 'L');

	$pdf->Cell(95,6,'', 'L',0);
	$pdf->Cell(95,6,'', 'LR',1);
	$pdf->Cell(95,6,'FIRMA', 'L',0, 'C');
	$pdf->Cell(95,6,'FIRMA', 'LR',1, 'C');

	$pdf->Cell(95,6,'(Autorizacion)', 'LB',0, 'C');
	$pdf->Cell(95,6,'', 'LRB',1);


}



	$pdf->Output();
	


?>	
<!-- // $html = file_get_contents('../form_101.php'); -->
<!-- $html = " -->
<!DOCTYPE html>
<html>

<body>

<?php 

	foreach ($pedidos as $value){

?>

  
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
	<?php foreach ($sol_consultas as $sc) : ?>
	<?php if ($sc == $value['ID_SOLICITUD']):?>
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
			<?php endif ?>
			<?php endforeach ?>
    </tbody>
	</table>
</div>

<hr>
<h5><b>OBSERVACIONES: </b></h5><?php echo $value['OBSERVACION']; ?>

<br>
<center><h4>Uso Exclusivo INFOACTIVA SRL</h4></center>
<b>RECEPCIONADO POR: </b></h4><?php echo $procesado_por[0]['NOMBRE']." ".$procesado_por[0]['APELLIDO']; ?>
<hr>
<table class="table table-bordered" style="font-size:11px;">
  <tr>
    <td><b>CENTRAL DE CONSULTAS: </b><?php echo $value['REGIONAL']; ?></td>
    <td><b>FECHA: </b><?php echo $fecha; ?></td>
    <td><b>HORA: </b><?php echo $hora; ?></td>
  </tr>
</table>

<?php 

}//ENDFOREACH
?>
<div id="pie">
	<p align="center" class="page">Pag. </p>
	<p><b>Sistema de Consultas INFODAT v3.0 Copyright © 2018 Infoactiva. Todos los derechos reservados.</b></p>
	<p style="page-break-before: always;"></p>
</div>
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
		"form_106-".$id_sol.".pdf", array('Attachment' => false)
	);



 ?>

