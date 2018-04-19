<?php 
ob_start();

require_once '../conexionClass.php';
require_once '../stringsClass.php';
$conexion = new MiConexion();
$mString = new MiStrings();
$pdfs = false;
if(isset($_POST['id_user']) && isset($_POST['fechas'])){
    $id_user = $_POST['id_user'];
    $date_range = $_POST['fechas'];
    $id_cliente = $_POST['id_cliente'];
}else{
    $id_user = $_GET['id_user'];
    $date_range = $_GET['fechas'];
    $id_cliente = $_GET['id_cliente'];
    $pdfs = true;
}

$usuario = $conexion->usuario($id_user);
$f_inicio = substr($date_range, 0, 10);
$f_fin = substr($date_range, -10);

//Tiempo
$meses = $mString->meses();
date_default_timezone_set('America/La_Paz');//definiendo zona horaria
$script_tz = date_default_timezone_get();

$tiempo = getdate();
$fecha = $tiempo['year']."-".$tiempo['mon']."-".$tiempo['mday'];
$hora = $tiempo['hours'].":".$tiempo['minutes'];



if ($id_cliente == 'TODOS') {
    $pedidos = $conexion->rep_ped_admin($f_inicio, $f_fin);
}
else{
    // $pedidos = $conexion->rep_ped($cliente[0]['ID_CLIENTE'], $f_inicio, $f_fin);
}

// echo var_dump($pedidos);

$clientes_todo = array_column($pedidos, 'CLIENTE');//todos los clientes

$deptos = array_unique($clientes_todo);//Conteo de valores

$sum_tot_sol = 0;
$sum_tot_doc = 0;

$tot_normales = 0;
$tot_urgentes = 0;
//
// $tot_sol = array_count_values(array_column($pedidos, 'ID_SOLICITUD'));

// foreach ($deptos as $key => $value) {
//     echo $key;
//     echo $value."<br>";
// }

//BEGIN IF
if($pdfs){

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reporte Consultas - Devoluciones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
    <style>
        @page { margin: 120px 50px; }
        #cabecera { position: fixed; left: 0px; top: -120px; right: 0px; height: 100px; background-color: lightblue; text-align: center; }
        #pie { position: fixed; left: 0px; bottom: -120px; right: 0px; height: 60px; background-color: lightblue; }
        #pie .page:after { content: counter(page); }
    </style>
</head>
<body>
<div id="cabecera">
    <table class="table">
    <tr>
        <td width="25%" align="center"><br><img src="../dist/img/logoactiva.png" style="width:200px;height:75%;">
        </td>

        <td width="50%" align="center"><h4>Informe de Stock y Consultas de Documentación</h4>
            <h4><?php echo "Usuario: ".$usuario[0]['NOMBRE']." ".$usuario[0]['APELLIDO']; ?></h4>
        </td>
        <td width="25%" style="font-size:12px;" align="center">
            <b><br><?php echo $tiempo['mday']." de ".$meses[$tiempo['mon']]." de ".$tiempo['year']; ?></b>
            <b><br><?php echo $hora; ?></b>
        </td>
    </tr>
    </table>

</div>
<div id="pie">
<p align="center" class="page">Pag. </p>
<p><b>Sistema de Consultas INFODAT v3.0 Copyright © 2018 Infoactiva. Todos los derechos reservados.</b></p>
<!-- <p style="page-break-before: always;"></p> -->
</div>

<?php //END IF
} 
?>

<div id="con_rep">

<hr>
<!-- <h4><b><?php //echo $cliente[0]['CLIENTE']; ?></b></h4> -->
<h5 align="center"><b>CONSULTAS INGRESADAS POR CLIENTE </b></h5>
<?php foreach ($deptos as $keyDep => $dp): ?>

<?php
    $nombre_cliente = $conexion->cliente($dp);
    $num_sol = array();
    $sum_doc = 0;
    $sum_normales = 0;
    $sum_urgentes = 0;
?>

<div class="box-header" style="background-color: #ea8720d6">
    <b><?php echo $nombre_cliente[0]['CLIENTE']; ?></b>
</div>
<div class="box-body no-padding">
	<table class="table table-bordered" style="font-size:10px;">
    <thead><tr>
        <th width="5%">Formulario</th>
        <th width="10%">Departamento</th>
        <th width="20%">Solicitante</th>
        <th width="10%">Fecha Solicitud</th>
        <th width="10%">Fecha Entrega</th>
        <th width="10%">Cantidad/Envio</th>
        <th width="10%">Cajas File</th>
        <th width="15%">Entregado por</th>
        <th width="10%">Prioridad</th>
    
    </tr>
    </thead>
    <tbody>
    	<?php foreach ($pedidos as $key => $value): ?>
            <?php if ($value['CLIENTE'] == $dp): ?>

                <?php
                    $sum_doc = $sum_doc + $value['CANTIDADES'];
                    $num_sol[$key] = $value['ID_SOLICITUD'];

                    if ($value['TIPO_CONSULTA'] == 'NORMAL') {
                        $sum_normales++;
                    }
                    elseif ($value['TIPO_CONSULTA'] == 'URGENTE') {
                        $sum_urgentes++;
                    }
                ?>

            <tr>
    			<td><?php echo $value['ID_SOLICITUD']; ?> </td>
                <td><?php echo $value['DEPARTAMENTO']; ?> </td>
                <td><?php echo $value['NOMBRE']." ".$value['APELLIDO']; ?> </td>
                <td><?php echo $value['FECHA_SOLICITUD']; ?> </td>
                <td><?php echo $value['FECHA_ENTREGA']; ?> </td>
                <td><?php echo $value['CANTIDADES']." ".$value['UNIDAD']; ?> </td>
                <td><?php echo $value['CAJAS']; ?> </td>
                <td><?php echo $value['ENTREGADO_POR']; ?> </td>
                <td><?php echo $value['TIPO_CONSULTA']; ?> </td>
    		</tr>
            <?php endif ?>
    	<?php endforeach ?>
    </tbody>
	</table>
</div>
<?php 
    $sum_tot_doc = $sum_tot_doc + $sum_doc;
    $sol = count(array_count_values($num_sol));
    $sum_tot_sol = $sum_tot_sol + $sol;

    $tot_normales = $tot_normales + $sum_normales;
    $tot_urgentes = $tot_urgentes + $sum_urgentes;
?>

<?php endforeach ?>


<div class="row">
    <table class="table">
        <tr>
            <td align="center">
                <span class="bg-gray info-box-text"><?php echo "Total Consultas: ".$sum_tot_sol; ?></span>
            </td>
            <td align="center">
                <!-- <span class="bg-gray info-box-text"><?php //echo "Total General Consultas: ".array_sum($deptos); ?></span> -->
                <span style="background-color: #F3F992" class="info-box-text"><?php echo "Total Normales: ".$tot_normales; ?></span>
            </td>
            <td align="center">
                <span style="background-color: #F3F992" class="info-box-text"><?php echo "Total Urgentes: ".$tot_urgentes; ?></span>
            </td>
            <td align="center">
                <!-- <span class="bg-gray info-box-text"><?php //echo "Total General Consultas: ".array_sum($deptos); ?></span> -->
                <span class="bg-gray info-box-text"><?php echo "Total Documentos: ".$sum_tot_doc; ?></span>
            </td>
        </tr>
    </table>
</div>

<hr>
</div>

<?php 

require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

if($pdfs){

    $dompdf = new DOMPDF();
    $dompdf->loadHtml(ob_get_clean());
    $dompdf->set_paper('A4', 'landscape');
    
    $dompdf->render();
    $pdf = $dompdf->output();

    $dompdf->stream(
        "Reporte_Solicitud_All-".$f_inicio."-".$f_fin.".pdf", array('Attachment' => true)
    );
}
?>

</body>
</html>