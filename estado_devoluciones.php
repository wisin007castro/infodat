<?php

require_once 'header.php';
require_once 'conexionClass.php';
require_once 'stringsClass.php';

$conexion = new MiConexion();
// $anios = $conexion->anios();
$devoluciones = $conexion->devoluciones($usuario_session['ID_CLIENTE']);
// var_dump($devoluciones);

$mistrings = new MiStrings();
$meses = $mistrings->meses();

$por_proces = 0;
$programada = 0;
$finalizada = 0;

if($devoluciones > 0){
    foreach ($devoluciones as $dev) {
        if ($dev['ID_USER'] == $usuario_session['ID_USER'] && $dev['REGIONAL'] == $usuario_session['REGIONAL']) {
            if($dev["ESTADO"] == "POR PROCESAR"){
                $por_proces++;
            }
            elseif ($dev["ESTADO"] == "PROGRAMADA") {
                $programada++;
            }
            else{
                $finalizada++;
            }
        }
    }
}

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Estado de devoluciones
            <!-- <small>Control panel</small> -->
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Estado de devoluciones</li>
        </ol>
    </section>

    <!-- Main content -->
    <!-- /.content -->
    <section class="content">
        <div class="row" style="font-size:11px;">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Lista de solicitudes de devoluciones</h3>
                    </div>
                    <div class="box-body table-responsive no-padding scrollable">
                        <table class="table table-bordered" id="tbEstadoSol">
                            <thead><tr>
                                <th></th>
                                <th>Nro. Solicitud</th>
                                <th>Solicitado por</th>
                                <th>Dirección de recojo</th>
                                <th>Fecha Solicitud</th>
                                <th>Fecha Programada</th>
                                <th>Procesado por</th>
                                <th>Recogido por</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($devoluciones as $dev) { ?>
                                <?php if ($dev['ID_USER'] == $usuario_session['ID_USER'] && $dev['REGIONAL'] == $usuario_session['REGIONAL']): ?>
                                    
                                    <tr>
                                    <td><a href='javascript:void(0);' onclick='cargar_formulario("<?php echo $dev["ID_DEV"]; ?>");'><i style='font-size:14px;' class='fa fa-expand text-blue'></i></a></td>
                                    <td><?php echo $dev["ID_DEV"]; ?></td>
                                    <td><?php echo $dev["NOMBRE"]." ".$dev["APELLIDO"]; ?></td>
                                    <td><?php echo $dev["DIRECCION"]; ?></td>
                                    <td><?php echo $dev["FECHA_SOLICITUD"]; ?></td>
                                    <td><?php echo $dev["FECHA_PROGRAMADA"]; ?></td>
                                    <td><?php echo $dev["PROCESADO_POR"]; ?></td>
                                    <td><?php echo $dev["RECOGIDO_POR"]; ?></td>
                                    <?php if ($dev["ESTADO"] == 'FINALIZADA') {?>
                                    <td style="background-color:#18b515; color: #FFFFFF ">
                                        <b><?php echo $dev["ESTADO"]; ?></b>
                                    </td>
                                    <?php }
                                    elseif ($dev["ESTADO"] == 'PROGRAMADA'){?>
                                    <td style="background-color:#326df3; color: #FFFFFF ">
                                        <b><?php echo $dev["ESTADO"]; ?></b>
                                    </td>
                                     <?php }
                                    elseif ($dev["ESTADO"] == 'POR PROCESAR'){?>
                                    <td class="bg-red-active color-palette">
                                        <b><?php echo $dev["ESTADO"]; ?></b>
                                    </td>
                                     <?php } ?>
                                </tr>
                                <?php else: ?>
                                    
                                <?php endif ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Totales por estado</h3>
                    </div>
                    <div class="box-body">
                        <h4 class="box-title" align="center"><strong> 
                            Por procesar: <?php echo $por_proces; ?> &nbsp;&nbsp;&nbsp;&nbsp;  
                            Programada: <?php echo $programada; ?> &nbsp;&nbsp;&nbsp;&nbsp; 
                            Finalizada: <?php echo $finalizada; ?></strong></h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row" style="font-size:11px;">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Lista de archivos</h3>
                    </div>
                    <div class="box-body table-responsive no-padding scrollable">
                        <table class="table table-bordered" id="seleccionados">
                            <thead><tr>
                                <th>NRO. SOLICITUD</th>
                                <th>CAJA</th>
                                <th>ITEM</th>
                                <th>DESC_1</th>
                                <th>DESC_2</th>
                                <th>DESC_3</th>
                                <th>DESC_4</th>
                                <th>CANT</th>
                                <th>UNIDAD</th>
                                <th>FECHA INICIAL</th>
                                <th>FECHA FINAL</th>
                                <th>DPTO</th>
                                <th>ESTADO</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
</div>
<!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?php require_once 'footer.php' ?>

<script type="text/javascript">
  $(document).ready(function(){
    // Limpiamos el cuerpo tbody

   // $("#agregar").click(function(){

  });

      function cargar_formulario(id_sol){
      // $("#error").html("<div class='modal1'><div class='center1'> <center> <img src='img/gif-load.gif'> Buscando Informacion...</center></div></div>");
      // var id_inv = $("#id_inv").val();
      $("#seleccionados tbody").html("");
      $.getJSON("obtieneEstadoDev.php",{id:id_sol},function(objetosretorna){
          // console.log(id_inv);
        $("#error").html("");
        var TamanoArray = objetosretorna.length;
        $.each(objetosretorna, function(i,inventarios){
          console.log(inventarios);
          var nuevaFila =
        "<tr>"
        // +"<td><button type='button' class='btn btn-success' ><i class='fa fa-shopping-cart'></i></button></td>"
        +"<td>"+inventarios.ID_DEV+"</td>"
        +"<td>"+inventarios.CAJA+"</td>"
        +"<td>"+inventarios.ITEM+"</td>"
        +"<td>"+inventarios.DESC_1+"</td>"
        +"<td>"+inventarios.DESC_2+"</td>"
        +"<td>"+inventarios.DESC_3+"</td>"
        +"<td>"+inventarios.DESC_4+"</td>"
        +"<td>"+inventarios.CANTIDAD+"</td>"
        +"<td>"+inventarios.UNIDAD+"</td>"
        +"<td>"+inventarios.DIA_I+"/"+inventarios.MES_I+"/"+inventarios.ANO_I+"</td>"
        +"<td>"+inventarios.DIA_F+"/"+inventarios.MES_F+"/"+inventarios.ANO_F+"</td>"
        +"<td>"+inventarios.DEPARTAMENTO+"</td>"
        +"<td>"+inventarios.ESTADO+"</td>"
        +"</tr>";
        
          $(nuevaFila).appendTo("#seleccionados tbody");
        });
        // console.log($("#asd").val());
        if(TamanoArray==0){
          var nuevaFila =
          "<tr><td colspan=6>No Existe Registros</td>"
          +"</tr>";
          $(nuevaFila).appendTo("#seleccionados tbody");
        }
      });
    };

</script>
