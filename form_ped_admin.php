<?php

require_once 'header.php';
require_once 'conexionClass.php';
require_once 'stringsClass.php';

$conexion = new MiConexion();

$pedidos = $conexion->pedidos_admin();
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

    <section>
      <div id="resp" class="col-lg-12">
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
                            <table class="table" id="tbEstadoSol">
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
                                    <?php if ($pedido['REGIONAL'] == $usuario_session['REGIONAL']): ?>
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
                                        <td>

                                        <!-- ENTREGADO POR -->
                                        <?php 
                                        if ($pedido["ESTADO"] != "EN PROCESO DE BUSQUEDA") {
                                            foreach ($usuarios as $user) {
                                                if ($user['NOMBRE']." ".$user['APELLIDO'] == $pedido["ENTREGADO_POR"]) {
                                        ?>
                                            <input type="hidden" id="entrega-<?php echo $pedido['ID_SOLICITUD']?>" value="<?php echo $user['ID_USER']; ?>"><?php echo $pedido['ENTREGADO_POR']; ?>
                                        <?php    
                                                }
                                            }
                                        }
                                        else{
                                        ?>
                                        <select class="form-control" id="entrega-<?php echo $pedido['ID_SOLICITUD']; ?>">
                                        <?php foreach ($usuarios as $user) { ?>
                                        <?php if ($user['REGIONAL'] == $pedido['REGIONAL']): ?>
                                            

                                            <option value="<?php echo $user['ID_USER'] ?>"><?php echo $user['NOMBRE']." ".$user['APELLIDO'] ?></option>
                                        <?php else: ?>
                                            
                                        <?php endif ?>
                                        <?php 
                                            }
                                        ?>
                                        </select>
                                        <?php } ?>

                                        </td>
                                        <!-- ESTADOS -->
                                        <td><?php echo $pedido["ESTADO"]; ?></td>
                                        <td>
                                            <?php if($pedido["ESTADO"] == "POR PROCESAR"){ ?>

                                            <button type="button" class="btn btn-block btn-primary btn-sm update-sol" data-id="<?php echo $pedido["ID_SOLICITUD"]; ?>">EN PROCESO DE BUSQUEDA</button>
                                            <?php
                                            }
                                            elseif($pedido["ESTADO"] == "EN PROCESO DE BUSQUEDA"){
                                            ?>
                                            <!-- <button type="button" class="btn btn-block btn-warning btn-sm update-sol" data-id="<?php //echo $pedido["ID_SOLICITUD"]; ?>">DESPACHADA</button> -->
                                            <button type="button" class="btn btn-block btn-warning" data-toggle="modal" onClick="modal('<?php echo $pedido['ID_SOLICITUD']?>')">DESPACHADA
                                            </button>
                                            <?php
                                            }
                                            elseif($pedido["ESTADO"] == "DESPACHADA"){
                                            ?>
                                            <button type="button" class="btn btn-block btn-success btn-sm update-sol" data-id="<?php echo $pedido["ID_SOLICITUD"]; ?>">ATENDIDA/ENTREGADA</button>
                                            <?php
                                            }
                                            elseif($pedido["ESTADO"] == "ATENDIDA/ENTREGADA"){
                                            ?>
                                            <button disabled type="button" class="btn btn-block btn-default btn-sm update-sol" data-id="<?php echo $pedido["ID_SOLICITUD"]; ?>">ATENDIDA/ENTREGADA</button>
                                            <?php
                                            }
                                            else{
                                            ?>
                                            <span style='font-size:12px;' class="label bg-gray">ESTADO DESCONOCIDO</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        
                                    <?php endif ?>
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

        <div class="modal fade" id="modal-default">
          <div class="modal-dialog" style="width:70%;">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Lista de items a entregar</h4>
              </div>
              <div class="modal-body">
                <div >
                    <input type="hidden" id="entrega">
                </div>

                <section class="content">
                <div class="row" style="font-size:11px;">
                    <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                        <h3 class="box-title">Lista de archivos encontrados</h3>
                        </div>
                        <div class="box-body table-responsive no-padding">
                        <div class="scrollable">
                            <table class="table table-bordered" id="tablajson">
                            <thead><tr>
                                <th></th>
                                <!-- <th>#</th> -->
                                <!-- <th>CLIENTE</th> -->
                                <th>CAJA</th>
                                <th>ITEM</th>
                                <th>DESCRIPCION 1</th>
                                <th>DESCRIPCION 2</th>
                                <th>DESCRIPCION 3</th>
                                <th>DESCRIPCION 4</th>
                                <th>CANT</th>
                                <th>UNIDAD</th>
                                <th>FECHA INICIO</th>
                                <th>FECHA FIN</th>
                                <th>DEPARTAMENTO</th>
                                <th>ESTADO</th>
                                <!-- <th>REGIONAL</th> -->
                            </tr>
                            </thead>
                            <tbody></tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                    <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
                </section>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" onclick="GuardaEntrega()">DESPACHADA</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

</div>
<!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?php require_once 'footer.php' ?>

<script type="text/javascript">


$(document).on('click','.update-sol',function(){
    id = $(this).attr('data-id');
    GuardaEstado(id);
});

    function form(id_sol) {
        window.open('pdf/form-101.php?id_sol='+id_sol+'&procesado_por='+usuario);
    }

    function modal(valor) {
    // var valor = valor;

    $("#entrega").val(valor);
    $('#modal-default').modal('show');

    }

    function GuardaEstado(id_sol){
    id = id_sol;
    usuario = $("#usuario").val(); //PROCESADO POR
    entrega = $("#entrega-"+id).val();// ENTREGADO POR

    if (!entrega) {
        entrega = "0";
    }

    $.ajax({
        type:'POST',
        url:"controllers/modSolController.php",
        data:{'id':id, 'usuario':usuario, 'entrega':entrega},
           success: function(result){
                if (result == 'POR PROCESAR') {
                    $.get("msj_correcto.php?msj="+"Solicitud actualizada a EN PROCESO DE BUSQUEDA", function(result){
                    $("#resp").html(result);
                    });
                    form(id);
                }
                if (result == 'EN PROCESO DE BUSQUEDA') {
                    $.get("msj_correcto.php?msj="+"Solicitud actualizada a DESPACHADA", function(result){
                    $("#resp").html(result);
                    });
                }
                if(result == 'error'){
                    $.get("msj_incorrecto.php?msj="+"No se pudo actualizar la solicitud", function(result){
                        $("#resp").html(result);
                    });
                }
            }
        }
    )};

    function GuardaEntrega(){//CON CONFIRMACION DESPACHADO
    id = $("#entrega").val();
    usuario = $("#usuario").val(); //PROCESADO POR
    entrega = $("#entrega-"+id).val();// ENTREGADO POR
    console.log(id);
    if (!entrega) {
        entrega = "0";
    }

    $.ajax({
        type:'POST',
        url:"controllers/modSolController.php",
        data:{'id':id, 'usuario':usuario, 'entrega':entrega},
           success: function(result){
                if (result == 'DESPACHADA') {
                    $.get("msj_correcto.php?msj="+"Solicitud actualizada a ATENDIDA/ENTREGADA", function(result){
                    $("#resp").html(result);
                    $('#modal-default').modal('hidde');
                    });
                }
                if(result == 'error'){
                    $.get("msj_incorrecto.php?msj="+"No se pudo actualizar la solicitud", function(result){
                        $("#resp").html(result);
                    });
                }
            }
        }
    )};

</script>

