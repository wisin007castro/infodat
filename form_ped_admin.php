<?php

require_once 'header.php';
require_once 'conexionClass.php';
require_once 'stringsClass.php';

$conexion = new MiConexion();
$anios = $conexion->anios();
$pedidos = $conexion->pedidos();
$usuarios = $conexion->usuarios_almacen();//cliente
// var_dump($usuarios);

$mistrings = new MiStrings();
$meses = $mistrings->meses();
$estados = $mistrings->estadoSol();
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
    <!-- Mensajes -->
    </section>
        <section>
      <div id="resp" class="col-lg-12">
<!--         <div class="div_contenido" style=" text-align: center">
          <label id="resp" style='color:#177F6B'></label>
        </div> -->
      </div>
    </section>

    <!-- Main content -->
    <!-- /.content -->
    <section class="content">
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario_session['ID_USER']; ?>">
        <div class="row" style="font-size:11px;">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Lista de solicitudes</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <div class="scrollable-big">
                            <table class="table table-bordered" id="tbEstadoSol">
                                <thead><tr>
                                    <!-- <th></th> -->
                                    <th>#</th>
                                    <th>Solicitado por</th>
                                    <th>Tipo de Consulta</th>
                                    <th>Dirección de entrega</th>
                                    <th>Fecha Solicitud</th>
                                    <th>Hora Solicitud</th>
                                    <th>Procesado por</th>
                                    <th>Fecha Entrega</th>
                                    <th>Hora Entrega</th>
                                    <th>Entregado por</th>
                                    <th>Estado Actual</th>
                                    <th>Estado Siguiente</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($pedidos as $pedido) { ?>
                                    <tr>
                                        <td><?php echo $pedido["ID_SOLICITUD"]; ?></td>
                                        <td><?php echo $pedido["NOMBRE"]." ".$pedido["APELLIDO"]; ?></td>
                                        <td><?php echo $pedido["TIPO_CONSULTA"]; ?></td>
                                        <td><?php echo $pedido["DIRECCION_ENTREGA"]; ?></td>
                                        <td><?php echo $pedido["FECHA_SOLICITUD"]; ?></td>
                                        <td><?php echo $pedido["HORA_SOLICITUD"]; ?></td>
                                        <td><?php echo $pedido["PROCESADO_POR"]; ?></td>
                                        <td><?php echo $pedido["FECHA_ENTREGA"]; ?></td>
                                        <td><?php echo $pedido["HORA_ENTREGA"]; ?></td>
                                        <td><!-- ENTREGADO POR -->
                                        <?php if ($pedido["ESTADO"] == "EN PROCESO DE BUSQUEDA") {?>
                                        <select class="form-control" name="entrega">
                                        <?php
                                            }
                                            else{
                                            ?>
                                        <select disabled class="form-control" name="entrega">
                                        <?php } ?>
                                        <option value=""></option>
                                        <?php foreach ($usuarios as $us) { 
                                            if($pedido["ENTREGADO_POR"] == $us['NOMBRE']." ".$us['APELLIDO']){
                                            ?>
                                            <option selected="<?php echo $pedido["ENTREGADO_POR"]; ?>" value="<?php echo $pedido["ENTREGADO_POR"] ?>"><?php echo $pedido["ENTREGADO_POR"] ?></option>
                                            <?php
                                            }
                                            else{
                                            ?>
                                            <option value="<?php echo $us['NOMBRE']." ".$us['APELLIDO'] ?>"><?php echo $us['NOMBRE']." ".$us['APELLIDO'] ?></option>
                                            <?php
                                            }
                                        } ?>
                                      </select>
                                        </td>
                                        <td><?php echo $pedido["ESTADO"]; ?></td>
                                        <td>
                                            <?php if($pedido["ESTADO"] == "POR PROCESAR"){ ?>
                                            <button type="button" class="btn btn-block btn-primary btn-sm update-sol" data-id="<?php echo $pedido["ID_SOLICITUD"]; ?>" user-id="<?php echo $pedido["ID_USER"]; ?>">EN PROCESO DE BUSQUEDA</button>
                                            <?php
                                            }
                                            elseif($pedido["ESTADO"] == "EN PROCESO DE BUSQUEDA"){
                                            ?>
                                            <button type="button" class="btn btn-block btn-warning btn-sm update-sol" data-id="<?php echo $pedido["ID_SOLICITUD"]; ?>" user-id="<?php echo $pedido["ID_USER"]; ?>">DESPACHADA</button>
                                            <?php
                                            }
                                            elseif($pedido["ESTADO"] == "DESPACHADA"){
                                            ?>
                                            <button type="button" class="btn btn-block btn-success btn-sm update-sol" data-id="<?php echo $pedido["ID_SOLICITUD"]; ?>" user-id="<?php echo $pedido["ID_USER"]; ?>">ATENDIDA/ENTREGADA</button>
                                            <?php
                                            }
                                            elseif($pedido["ESTADO"] == "ATENDIDA/ENTREGADA"){
                                            ?>
                                            <button disabled type="button" class="btn btn-block btn-default btn-sm update-sol" data-id="<?php echo $pedido["ID_SOLICITUD"]; ?>" user-id="<?php echo $pedido["ID_USER"]; ?>">ATENDIDA/ENTREGADA</button>
                                            <?php
                                            }
                                            else{
                                            ?>
                                            <span style='font-size:12px;' class="label bg-gray">ESTADO DESCONOCIDO</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

</div>
<!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?php require_once 'footer.php' ?>

<script type="text/javascript">

    $("#boton").click(function(){
        $.ajax({
            type: "post",
            url: "cargarHotel.php",
            dataType: "html",
            success: function(result) {
                $("#cajaSM").html(result);
            }
        })
    });
</script>
<script type="text/javascript">

var id, user, usuario;
$(document).on('click','.update-sol',function(){
    id = $(this).attr('data-id');
    user = $(this).attr('user-id'); // ENTREGADO POR
    usuario = $("#usuario").val(); //PROCESADO POR
    $.ajax({
        type:'POST',
        url:"controllers/modSolController.php", // sending the request to the same page we're on right now
        data:{'id':id, 'user':user, 'usuario':usuario},
           success: function(result)             
           {
            // $('#resp').html();
            $.get("msj_correcto.php", function(result){
            $("#resp").html(result);
        });
           }
    })
})

</script>

