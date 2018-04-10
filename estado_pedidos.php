<?php

require_once 'header.php';
require_once 'conexionClass.php';
require_once 'stringsClass.php';

$conexion = new MiConexion();
$anios = $conexion->anios();
$pedidos = $conexion->pedidos($usuario_session['ID_CLIENTE']);
// var_dump($usuario_session);
// echo "------".$usuario_session['ID_CLIENTE'];

$mistrings = new MiStrings();
$meses = $mistrings->meses();

$normal = 0;
$urgente = 0;
$tot_tip_ped = 0;

$por_proces = 0;
$buscando = 0;
$finalizada = 0;

if($pedidos > 0){
    foreach($pedidos as $ped){
        if ($ped['ID_USER'] == $usuario_session['ID_USER'] && $ped['REGIONAL'] == $usuario_session['REGIONAL']) {
                    
            if($ped["TIPO_CONSULTA"] == "NORMAL"){
                $normal++;
            }
            else{
                $urgente++;
            }
            if($ped["ESTADO"] == "POR PROCESAR"){
                $por_proces++;
            }
            elseif ($ped["ESTADO"] == "EN PROCESO DE BUSQUEDA") {
                $buscando++;
            }
            elseif ($ped["ESTADO"] == "ATENDIDA/ENTREGADA") {
                $finalizada++;
            }
        }
    }
    $tot_tip_ped = $normal + $urgente;
}

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Estado de Solicitud
            <!-- <small>Control panel</small> -->
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Estado de solicitud</li>
        </ol>
    </section>

    <!-- Main content -->
    <!-- /.content -->
    <section class="content">
        <div class="row" style="font-size:11px;">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Lista de solicitudes</h3>
                    </div>
                    <input type="hidden" name="cliente" id="cliente" value="<?php echo $usuario_session['ID_CLIENTE']; ?>">
                    <input type="hidden" name="id_user" id="id_user" value="<?php echo $usuario_session['ID_USER']; ?>">
                    <div class="box-body table-responsive no-padding scrollable">
                        <table class="table table-bordered" id="tbEstadoSol">
                            <thead><tr>
                                <th></th>
                                <th>Nro. Solicitud</th>
                                <th>Solicitado por</th>
                                <th>Tipo de Consulta</th>
                                <th>Dirección de entrega</th>
                                <th>Fecha/Hora Solicitud</th>
                                <th>Procesado por</th>
                                <th>Fecha/Hora Entrega</th>
                                <th>Entregado por</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($pedidos as $pedido) { ?>
                                <?php if ($pedido['ID_USER'] == $usuario_session['ID_USER'] && $pedido['REGIONAL'] == $usuario_session['REGIONAL']): ?>
                                    <tr>
                                    <td><a href='javascript:void(0);' onclick='cargar_formulario("<?php echo $pedido["ID_SOLICITUD"]; ?>");'><i style='font-size:14px;' class='fa fa-expand text-blue'></i></a></td>
                                    <td><?php echo $pedido["ID_SOLICITUD"]; ?></td>
                                    <td><?php echo $pedido["NOMBRE"]." ".$pedido["APELLIDO"]; ?></td>
                                    <td><?php echo $pedido["TIPO_CONSULTA"]; ?></td>
                                    <td><?php echo $pedido["DIRECCION_ENTREGA"]; ?></td>
                                    <td><?php echo $pedido["FECHA_SOLICITUD"]." ".$pedido["HORA_SOLICITUD"]; ?></td>
                                    <td><?php echo $pedido["PROCESADO_POR"]; ?></td>
                                    <td><?php echo $pedido["FECHA_ENTREGA"]." ".$pedido["HORA_ENTREGA"]; ?></td>
                                    <td><?php echo $pedido["ENTREGADO_POR"]; ?></td>
                                    <?php if ($pedido["ESTADO"] == 'ATENDIDA/ENTREGADA') {?>
                                    <td style="background-color:#18b515; color: #FFFFFF ">
                                        <b><?php echo $pedido["ESTADO"]; ?></b>
                                    </td>
                                    <?php }
                                    elseif ($pedido["ESTADO"] == 'DESPACHADA'){?>
                                    <td style="background-color:#EAD30F; color: #FFFFFF ">
                                        <b><?php echo $pedido["ESTADO"]; ?></b>
                                    </td>
                                     <?php } 
                                    elseif ($pedido["ESTADO"] == 'EN PROCESO DE BUSQUEDA'){?>
                                    <td style="background-color:#326df3; color: #FFFFFF ">
                                        <b><?php echo $pedido["ESTADO"]; ?></b>
                                    </td>
                                     <?php }
                                    elseif ($pedido["ESTADO"] == 'POR PROCESAR'){?>
                                    <td class="bg-red-active color-palette">
                                        <b><?php echo $pedido["ESTADO"]; ?></b>
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
            <div class="col-xs-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Totales por tipo</h3>
                    </div>
                    <div class="box-body">
                        <h4 class="box-title" align="center"> 
                            Normales: <?php echo $normal; ?> &nbsp;&nbsp;&nbsp;&nbsp;  
                            Urgentes: <?php echo $urgente; ?> &nbsp;&nbsp;&nbsp;&nbsp; 
                            Total: <?php echo $tot_tip_ped; ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-xs-8">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Totales por estado</h3>
                    </div>
                    <div class="box-body">
                        <h4 class="box-title" align="center">
                            Por procesar: <?php echo $por_proces; ?> &nbsp;&nbsp;&nbsp;&nbsp;  
                            En Proceso de Búsqueda: <?php echo $buscando; ?> &nbsp;&nbsp;&nbsp;&nbsp;
                            Atendida/Entregada: <?php echo $finalizada; ?></h4>
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
                                <th>Nro. SOLICITUD</th>
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
        var id_usuario = $("#id_user").val();

      $.getJSON("obtieneEstadoSol.php",{id:id_sol},function(objetosretorna){
          // console.log(id_inv);
        $("#error").html("");
        var TamanoArray = objetosretorna.length;
        $.each(objetosretorna, function(i,inventarios){
          console.log(inventarios);
          var nuevaFila =
        "<tr>"
        // +"<td><button type='button' class='btn btn-success' ><i class='fa fa-shopping-cart'></i></button></td>"
        +"<td>"+inventarios.ID_SOLICITUD+"</td>"
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

