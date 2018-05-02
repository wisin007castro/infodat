<?php

require_once 'header.php';
require_once 'conexionClass.php';
require_once 'stringsClass.php';

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

$pedidos = $conexion->pedidos_auth($usuario_session['ID_CLIENTE']);

$asignacion = $conexion->asignacion($usuario_session['ID_USER'], 'estado_consultas');//Cambiar segun modulo
$asignacion = array_column($asignacion, 'ASIGNACION');//seleccionando una columna


?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<?php   
    // foreach($asignacion as $as){
//   var_dump($asignacion);
    // }
  ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Solicitudes por Aprobar
            <!-- <small>Control panel</small> -->
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Solicitudes por Aprobar</li>
        </ol>
    </section>

    <section>
        <div id="resp" class="col-lg-12">
    </section>

    <!-- Main content -->
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
                                <th>Tipo de Envío</th>
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
                                <?php if ($pedido["ESTADO"]=='POR APROBAR'): ?>
                                    <tr>
                                    <td><a href='javascript:void(0);' onclick='cargar_formulario("<?php echo $pedido["ID_SOLICITUD"]; ?>");'><i style='font-size:14px;' class='fa fa-expand text-blue'></i></a></td>
                                    <td><?php echo $pedido["ID_SOLICITUD"]; ?></td>
                                    <td><?php echo $pedido["NOMBRE"]." ".$pedido["APELLIDO"]; ?></td>
                                    <td><?php echo $pedido["TIPO_ENVIO"]; ?></td>
                                    <td><?php echo $pedido["TIPO_CONSULTA"]; ?></td>
                                    <td><?php echo $pedido["DIRECCION_ENTREGA"]; ?></td>
                                    <td><?php echo $pedido["FECHA_SOLICITUD"]." ".$pedido["HORA_SOLICITUD"]; ?></td>
                                    <td><?php echo $pedido["PROCESADO_POR"]; ?></td>
                                    <td><?php echo $pedido["FECHA_ENTREGA"]." ".$pedido["HORA_ENTREGA"]; ?></td>
                                    <td><?php echo $pedido["ENTREGADO_POR"]; ?></td>
                                    <td><button type="button" class="btn btn-primary" onclick='guardar("<?php echo $pedido["ID_SOLICITUD"]; ?>");'>Guardar</button></td>


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
                                <th>DESCRIPCION 1</th>
                                <th>DESCRIPCION 2</th>
                                <th>DESCRIPCION 3</th>
                                <th>DESCRIPCION 4</th>
                                <th>CANT</th>
                                <th>UNIDAD</th>
                                <th>FECHA INICIAL</th>
                                <th>FECHA FINAL</th>
                                <th>DEPARTAMENTO</th>
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

    function guardar($id_sol){
        var id = $id_sol;
        $.ajax({
            type:'POST',
            url:"controllers/consultaAutorizada.php",
            data:{'id_sol':id},
            success: function(result){
                if (result == 'success') {
                    $.get("msj_correcto.php?msj=Solicitud realizada exitosamente", function(result){
                    $("#resp").html(result);
                    refrescar();
                    });
                }
                else{
                    $.get("msj_incorrecto.php?msj="+"No se pudo realizar la solicitud", function(result){
                    $("#resp").html(result);
                    });
                }
            }
        });
    }


    function cargar_formulario(id_sol){
      // $("#error").html("<div class='modal1'><div class='center1'> <center> <img src='img/gif-load.gif'> Buscando Informacion...</center></div></div>");
      // var id_inv = $("#id_inv").val();
      $("#seleccionados tbody").html("");
        var id_usuario = $("#id_user").val();

      $.getJSON("obtieneItemsAuth.php",{id:id_sol},function(objetosretorna){
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

    function refrescar(){
    
        timout=setTimeout(function(){
            location.reload();
        },3000,"JavaScript");//3 segundos
    }

</script>