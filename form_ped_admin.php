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
                                            <button type="button" class="btn btn-block btn-warning btn-sm update-sol" data-id="<?php echo $pedido["ID_SOLICITUD"]; ?>">DESPACHADA</button>
<!--                                             <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#modal-default">DESPACHADA -->
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
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Lista de items a entregar</h4>
              </div>
              <div class="modal-body">
                <p>One fine body&hellip;</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-block btn-warning btn-sm update-sol" data-id="<?php echo $pedido["ID_SOLICITUD"]; ?>">DESPACHADA</button>
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

var id, entrega, usuario;
$(document).on('click','.update-sol',function(){
    id = $(this).attr('data-id');
    usuario = $("#usuario").val(); //PROCESADO POR
    entrega = $("#entrega-"+id).val();// ENTREGADO POR
    console.log(estado);
    if (!entrega) {
        entrega = "0";
    }

    $.ajax({
        type:'POST',
        url:"controllers/modSolController.php",
        data:{'id':id, 'usuario':usuario, 'entrega':entrega},
           success: function(result){
                if (result == 'success') {
                    $.get("msj_correcto.php?msj="+"Solicitud actualizada correctamente", function(result){
                    $("#resp").html(result);
                    });
                    if(estado == 'POR PROCESAR'){
                        form(id);
                    }
                }
                else{
                    $.get("msj_incorrecto.php?msj="+"No se pudo realizar la actualización de la modificación", function(result){
                        $("#resp").html(result);
                    });
                }
            }
        }
    )
});

    function form(id_sol) {
        window.open('pdf/formClass.php?id_sol='+id_sol+'&procesado_por='+usuario);
    }
 
    
 
    // window.onload=function(){
        
    // }

</script>

