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

$sum_tot_sol = 0;

//
// $tot_sol = array_count_values(array_column($pedidos, 'ID_SOLICITUD'));

// foreach ($tot_sol as $key => $value) {
//     echo $key;
//     echo $value."<br>";
// }

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
            <h4><b><?php echo $cliente[0]['CLIENTE']; ?></b></h4>
        </td>
        <td width="25%" style="font-size:12px;" align="center">
            <b><br><?php echo $tiempo['mday']." de ".$meses[$tiempo['mon']]." de ".$tiempo['year']; ?></b>
        </td>
    </tr>
    </table>
    
</div>
<div id="pie">
<p align="center" class="page">Pag. </p>
<p><b>Sistema de Consultas INFODAT v3.0 Copyright © 2018 Infoactiva. Todos los derechos reservados.</b></p>
<!-- <p style="page-break-before: always;"></p> -->
</div>
<div id="content">
<table class="table">
    <tr>
        <td align="center">
        <h6><?php echo "Usuario: ".$usuario[0]['NOMBRE']." ".$usuario[0]['APELLIDO']; ?></h6>
        </td>
        <td align="center"> <?php echo "Hora de consulta: ".$hora; ?>
        </td>
    </tr>
</table>
<h5><b></b></h5>

<h5 align="center"><b>Consultas ingresadas por Departamento </b></h5>
<?php foreach ($deptos as $keyDep => $dp): ?>

<?php 
    $num_sol = array();
?>

<h5><b><?php echo $keyDep; ?></b></h5>
<div class="box-body no-padding">
	<table class="table table-bordered" style="font-size:10px;">
    <thead><tr>
        <th width="12%">No. Solicitud</th>
        <th width="20%">Solicitante</th>
        <th width="10%">Fecha Entrega</th>
        <th width="5%">Cantidad</th>
        <th width="5%">Envio</th>
        <th width="10%">No. Caja</th>
        <th width="38%">Descripción</th>
    
    </tr>
    </thead>
    <tbody>
    	<?php foreach ($pedidos as $key => $value): ?>
            <?php if ($value['DEPARTAMENTO'] == $keyDep): ?>

                <?php 
                    $num_sol[$key] = $value['ID_SOLICITUD'];
                ?>

            <tr>
    			<td><?php echo $value['ID_SOLICITUD']; ?> </td>
                <td><?php echo $value['NOMBRE']." ".$value['APELLIDO']; ?> </td>
                <td><?php echo $value['FECHA_ENTREGA']; ?> </td>
                <td><?php echo $value['CANTIDAD']; ?> </td>
                <td><?php echo $value['UNIDAD']; ?> </td>
                <td><?php echo $value['CAJA']; ?> </td>
                <td>
                    <?php //CORTE DE CARACTERES DE DESCRIPCION 
                    $desc = $value['DESC_1']." ".$value['DESC_2']." ".$value['DESC_3'];
                    $descripcion = "";
                        while (strlen($desc) >= 30) {
                        $descripcion = substr($desc, 0, 29);
                        $desc = substr($desc, 29);
                        echo $descripcion."-<br>";
                        }
                    echo $desc;
                    ?>
                </td>
    		</tr>
            <?php endif ?>
    	<?php endforeach ?>
    </tbody>
	</table>
</div>
<?php 
    $sol = count(array_count_values($num_sol));
    $sum_tot_sol = $sum_tot_sol + $sol;
?>
<div class="row">
    <table class="table">
        <tr>
            <td align="center">
            <h6><b><?php echo "Subtotal Formularios: ".$sol; ?></b></h6>
            </td>
            <td align="center"><h6><b><?php echo "Subtotal Consultas: ".$dp; ?></b></h6>
            </td>
        </tr>
    </table>
</div>
<?php endforeach ?>


<div class="row">
    <table class="table">
        <tr>
            <td align="center">
                <span class="bg-gray info-box-text"><?php echo "Total General Formularios: ".$sum_tot_sol; ?></span>
            </td>
            <td align="center">
                <span class="bg-gray info-box-text"><?php echo "Total General Consultas: ".array_sum($deptos); ?></span>
            </td>
        </tr>
    </table>
</div>


</div>




<?php 


// echo $id_user."<br>";
// echo var_dump($usuario)."<br>";
// echo "FECHA: ".$f_inicio."<br>";
// echo "FECHA: ".$f_fin."<br>";
// echo var_dump($pedidos);

require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$dompdf = new DOMPDF();
$dompdf->loadHtml(ob_get_clean());
// $dompdf->set_paper('A4', 'landscape');
// ini_set("memory_limit","32M");
$dompdf->render();
$pdf = $dompdf->output();

$dompdf->stream(
    "Reporte_Solicitud_-".$f_inicio."-".$f_fin.".pdf", array('Attachment' => false)
);
?>

</body>
</html>