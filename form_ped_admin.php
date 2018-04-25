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
<input type="hidden" id="usuario" value="<?php echo $usuario_session['ID_USER']; ?>">

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
                                    <th></th>
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

                                        <td>
                                        <?php if ($pedido["ESTADO"] == "DESPACHADA"): ?>
                                        <a href='javascript:void(0);' onclick='form104("<?php echo $pedido["ID_SOLICITUD"]; ?>");'><i style='font-size:20px;' class='fa fa-file-pdf-o text-yellow'></i></a>
                                        <?php else: ?>
                                        <!-- <i style='font-size:14px;' class='fa fa-file-pdf-o text-muted'></i> -->
                                        <?php endif ?>

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
    <section class="content">
    <form method="POST" id="formulario">
        <div class="modal fade" id="modal-default">
          <div class="modal-dialog" style="width:80%;">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Actualización de estado a Despachar</h3>
              </div>
              <div class="modal-body">
                <div >
                    <input type="hidden" name="entrega" id="entrega2">
                    <input type="hidden" name="id" id="id_sol">
                    <input type="hidden" name="usuario" value="<?php echo $usuario_session['ID_USER']; ?>">
                </div>

                
                
                <div class="row" style="font-size:11px;">
                    <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                        <h3 class="box-title">Lista de items a entregar</h3>
                        </div>
                        <div class="box-body table-responsive no-padding">
                        <div class="scrollable">
                            <table class="table table-bordered" id="seleccionados">
                            <thead><tr>
                                <th>QUITAR</th>
                                <!-- <th>ID</th> -->
                                <th>CAJA</th>
                                <th>ITEM</th>
                                <th>DESCRIPCION 1</th>
                                <th>DESCRIPCION 2</th>
                                <th>DESCRIPCION 3</th>
                                <th>CANTIDAD</th>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" onclick="GuardaEntrega()">DESPACHADA</button>
              </div>
                
                
              </div>

            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
    </form>
    </section>
</div>
<!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?php require_once 'footer.php' ?>

<script type="text/javascript">


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
        +"<td><input type='hidden' name=id-"+inventarios.ID_INV+" value="+inventarios.ID_INV+"><a href='javascript:void(0);' onclick='deleteRow(this)'><i style='font-size:14px;' class='fa fa-trash text-red'></i></a></td>"
        // +"<td>"+inventarios.ID_SOLICITUD+"</td>"
        +"<td>"+inventarios.CAJA+"</td>"
        +"<td>"+inventarios.ITEM+"</td>"
        +"<td>"+inventarios.DESC_1+"</td>"
        +"<td>"+inventarios.DESC_2+"</td>"
        +"<td>"+inventarios.DESC_3+"</td>"
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

    $(document).on('click','.update-sol',function(){
        id = $(this).attr('data-id');
        GuardaEstado(id);
    });

    function form101(id_sol) {
        window.open('pdf/form-101.php?id_sol='+id_sol+'&procesado_por='+usuario);
    }

    function form104(id_sol) {
        window.open('pdf/form-104.php?id_sol='+id_sol);
    }

    function modal(valor) {//Valor : id_sol
    // var valor = valor;

    $("#id_sol").val(valor);
    $("#entrega2").val($("#entrega-"+valor).val());
    $('#modal-default').modal('show');
    cargar_formulario(valor);
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
                    form101(id);
                    $.get("msj_correcto.php?msj="+"Solicitud actualizada a EN PROCESO DE BUSQUEDA", function(result){
                    $("#resp").html(result);
                    refrescar();
                    });
                }
                if (result == 'DESPACHADA') {
                    $.get("msj_correcto.php?msj="+"Solicitud actualizada a ATENDIDA/ENTREGADA", function(result){
                    $("#resp").html(result);
                    // $('#modal-default').modal('hidde');
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
    id_sol = $("#id_sol").val();
    $.ajax({
        type:'POST',
        url:"controllers/modSolController.php",
        data:$('#formulario').serialize(),
           success: function(result){

                if (result == 'EN PROCESO DE BUSQUEDA') {
                    $.get("msj_correcto.php?msj=Solicitud actualizada a DESPACHADA", function(result){
                    $("#resp").html(result);
                    });
                    form104(id_sol);
                    refresh_fast();
                    
                }
                if(result == 'error'){
                    $.get("msj_incorrecto.php?msj=No se pudo actualizar la solicitud", function(result){
                        $("#resp").html(result);
                    });
                }
                if(result == 'sin_items'){
                    $.get("msj_incorrecto.php?msj=Debe seleccionar al menos un item", function(result){
                        $("#resp").html(result);
                    });
                }
            }
        }
    )};

    function deleteRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    function refrescar(){
    
        timout=setTimeout(function(){
            location.reload();
        },5000,"JavaScript");//3 segundos
    }
    
    function refresh_fast(){
        
        timout=setTimeout(function(){
            location.reload();
        },2000,"JavaScript");//3 segundos
    }

</script>

