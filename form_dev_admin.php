<?php

require_once 'header.php';
require_once 'conexionClass.php';
require_once 'stringsClass.php';

$conexion = new MiConexion();

$devoluciones = $conexion->devoluciones_admin();
$usuarios = $conexion->usuarios_almacen();//cliente

$mistrings = new MiStrings();
$meses = $mistrings->meses();
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
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Estado de devoluciones</li>
        </ol>
    </section>

    <section>
      <div id="resp" class="col-lg-12">
    </section>

<?php if($usuario_session['TIPO'] == 'ADMIN_ALMACEN' || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>
    <!-- Main content -->
    <section class="content">
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario_session['ID_USER']; ?>">
        <div class="row" style="font-size:10px;">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Lista de devoluciones</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered" id="tbEstadoSol">
                            <thead>
                            <?php if(count($devoluciones) > 0){ ?>
                            <tr>
                                <!-- <th></th> -->
                                <th>#</th>
                                <th>Solicitado por</th>
                                <th>Dirección de recojo</th>
                                <th>Fecha Solicitud</th>
                                <th>Fecha Programada</th>
                                <th>Procesado por</th>
                                <th>Recogido por</th>
                                <th>Estado Actual</th>
                                <th>Acción</th>
                            </tr>
                            <?php }else{ ?>
                                    <div class="col-xs-12">
                                    <div class='warning' style="text-align: center">
                                        <span class="label label-warning"> Sin Solicitudes de devolución</span><br/>
                                        <label class="text-muted">
                                            No se encontraron registros                                          
                                        </label> 
                                    </div>
                                    </div> 
                                <?php 
                                }
                                ?>
                            </thead>
                            <tbody>
                            <?php foreach ($devoluciones as $dev) { ?>
                            <?php if ($dev['REGIONAL'] == $usuario_session['REGIONAL']): ?>
                                <tr>
                                    <td><?php echo $dev["ID_DEV"]; ?></td>
                                    <td><?php echo $dev["NOMBRE"]." ".$dev["APELLIDO"]; ?></td>
                                    <td><?php echo $dev["DIRECCION"]; ?></td>
                                    <td><?php echo $dev["FECHA_SOLICITUD"]; ?></td>
                                    <td>
                                    <?php 
                                        if ($dev["ESTADO"] != "POR PROCESAR") {
                                            // echo $dev["FECHA_PROGRAMADA"];
                                            echo "<input type='hidden' class='form-control'  id='fecha-".$dev['ID_DEV']."' value='".$dev['FECHA_PROGRAMADA']."'>".$dev["FECHA_PROGRAMADA"];
                                        }
                                        else{
                                        ?>
                                        <input type="text" class="form-control input-sm" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask=""  id="fecha-<?php echo $dev["ID_DEV"]; ?>">
                                        <?php } ?>

                                    </td>
                                    <td><?php echo $dev["PROCESADO_POR"]; ?></td>
                                    <td>
                                        
                                        <?php 
                                        if ($dev["ESTADO"] != "PROGRAMADA") {
                                            echo $dev["RECOGIDO_POR"];

                                            foreach ($usuarios as $user) {
                                                if ($user['NOMBRE']." ".$user['APELLIDO'] == $dev["RECOGIDO_POR"]) {
                                        ?>
                                            <input type="hidden" id="recogido-<?php echo $dev['ID_DEV']?>" value="<?php echo $user['ID_USER']; ?>">
                                        <?php    
                                                }
                                            }

                                        }
                                        else{
                                        ?>
                                        <select class="form-control" id="recogido-<?php echo $dev["ID_DEV"]; ?>">
                                        <?php foreach ($usuarios as $user) { ?>
                                            <option value="<?php echo $user['ID_USER'] ?>"><?php echo $user['NOMBRE']." ".$user['APELLIDO'] ?></option>
                                        <?php 
                                            }
                                        ?>
                                        </select>
                                        <?php } ?>


                                    </td>
                                    <td><?php echo $dev["ESTADO"]; ?></td>
                                    <td><input type="hidden" id="id_dev" value="<?php echo $dev['ID_DEV']; ?>">
                                        <?php if($dev["ESTADO"] == "POR PROCESAR"){ ?>
                                            <button type="button" class="btn btn-block btn-primary btn-sm update-dev" data-id="<?php echo $dev["ID_DEV"]; ?>">PROGRAMAR</button>
                                            <?php
                                            }
                                            elseif($dev["ESTADO"] == "PROGRAMADA"){
                                            ?>
                                            <button type="button" class="btn btn-block btn-success btn-sm update-dev" data-id="<?php echo $dev["ID_DEV"]; ?>">FINALIZAR</button>
                                            <?php
                                            }
                                            elseif($dev["ESTADO"] == "FINALIZADA"){
                                            ?>
                                            <button disabled type="button" class="btn btn-block btn-default btn-sm update-dev" data-id="<?php echo $dev["ID_DEV"]; ?>">FINALIZADA</button>
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
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>


    <?php else:?>
    <section>
      <div class="col-xs-12">
        <div class='restringido' style="text-align: center">
          <span class="label label-primary"><i class="fa fa-warning"></i>  Restringido..!!!  <i class="fa fa-warning"></i></span><br/>
          <label style='color:#1D4FC1'>
                <?php  
                echo "No tienes las credenciales para acceder al contenido";
                ?> 
          </label> 
        </div>
      </div> 
    </section>
    <?php endif ?>

</div>
<!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?php require_once 'footer.php' ?>

<script type="text/javascript">

var id, recojo, usuario, fecha;
$(document).on('click','.update-dev',function(){

    id = $(this).attr('data-id');
    usuario = $("#usuario").val(); //PROCESADO POR
    // recojo = $("#recojo").val();
    recogido = $("#recogido-"+id).val();// RECOGIDO POR
    fecha = $("#fecha-"+id).val();

    if (!recogido) {
        recogido = "0";
    }
// console.log(id);
//     console.log(usuario);
//     console.log(fecha);
// console.log(recogido);
    $.ajax({
        type:'POST',
        url:"controllers/modDevController.php", // sending the request to the same page we're on right now
        data:{'id':id, 'usuario':usuario, 'fecha':fecha, 'recogido':recogido, ID_USER:usuario},
           success: function(result){
                if (result == 'fecha') {
                    $.get("msj_incorrecto.php?msj="+"Programe la fecha", function(result){
                            $("#resp").html(result);
                    });
                    refrescar();
                }
                else{
                    if(result == 'POR PROCESAR'){
                        $.get("msj_correcto.php?msj= Solicitud actualizada a PROGRAMADA", function(result){
                        $("#resp").html(result);
                        });
                        form(id);
                        refrescar();
                    }
                    if(result == 'PROGRAMADA'){
                        $.get("msj_correcto.php?msj= Solicitud actualizada a FINALIZADA", function(result){
                        $("#resp").html(result);
                        });
                        refrescar();
                    }
                    if(result == 'error'){
                        $.get("msj_incorrecto.php?msj="+"No se pudo actualizar la solicitud de devolución", function(result){
                            $("#resp").html(result);
                        });
                        refrescar();
                    }
                }
            }
        }
    )
});
    function form(id_sol) {
        window.open('pdf/form-102.php?id_sol='+id_sol+'&procesado_por='+usuario);
        window.open('pdf/form-106.php?id_sol='+id_sol);
    }

    function refrescar(){
    
        timout=setTimeout(function(){
            location.reload();
        },3000,"JavaScript");//3 segundos
    }

</script>

