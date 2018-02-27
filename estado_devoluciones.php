<?php

require_once 'header.php';
require_once 'conexionClass.php';
require_once 'stringsClass.php';

$conexion = new MiConexion();
$anios = $conexion->anios();
$devoluciones = $conexion->devoluciones();
// var_dump($anios);

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
                        <h3 class="box-title">Lista de devoluciones</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered" id="tbEstadoSol">
                            <thead><tr>
                                <th></th>
                                <th>#</th>
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
                                <tr>
                                    <td><a href='javascript:void(0);' onclick='cargar_formulario("<?php echo $dev["ID_DEV"]; ?>");'><i style='font-size:14px;' class='fa fa-shopping-cart text-green'></i></a></td>
                                    <td><?php echo $dev["ID_DEV"]; ?></td>
                                    <td><?php echo $dev["NOMBRE"]." ".$dev["APELLIDO"]; ?></td>
                                    <td><?php echo $dev["DIRECCION"]; ?></td>
                                    <td><?php echo $dev["FECHA_SOLICITUD"]; ?></td>
                                    <td><?php echo $dev["FECHA_PROGRAMADA"]; ?></td>
                                    <td><?php echo $dev["PROCESADO_POR"]; ?></td>
                                    <td><?php echo $dev["RECOGIDO_POR"]; ?></td>
                                    <td><?php echo $dev["ESTADO"]; ?></td>
                                </tr>
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
                        <h4 class="box-title"><strong> Por procesar: 0 &nbsp;&nbsp;&nbsp;&nbsp;  Programada: 1 &nbsp;&nbsp;&nbsp;&nbsp; Finalizada: 5</strong></h4>
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
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered" id="seleccionados">
                            <thead><tr>
                                <th></th>
                                <th>#</th>
                                <th>CLIENTE</th>
                                <th>CAJA</th>
                                <th>ITEM</th>
                                <th>DESC_1</th>
                                <th>DESC_2</th>
                                <th>DESC_3</th>
                                <th>DESC_4</th>
                                <th>CANT</th>
                                <th>UNIDAD</th>
                                <th>FECHA_I</th>
                                <th>FECHA_F</th>
                                <th>DPTO</th>
                                <th>ESTADO</th>
                                <th>REGIONAL</th>
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
    $(document).ready(function(){
        // Limpiamos el cuerpo tbody
        $("#limpiar").click(function(){
            $("#bNoCaja").val("");
            $("#bdesc_1").val("");
            $("#bdesc_2").val("");
            $("#tbEstadoSol tbody").html("");
        });

        $("#buscar").click(function(){
            $("#tbEstadoSol tbody").html("");
            // $("#error").html("<div class='modal1'><div class='center1'> <center> <img src='img/gif-load.gif'> Buscando Informacion...</center></div></div>");
            var bdesc_1 = $("#bdesc_1").val();
            var bdesc_2 = $("#bdesc_2").val();
            var bcaja = $("#bNoCaja").val();
            // var bfecha = $("#reservation").val();//id por defecto de la fecha
            // console.log(bfecha);
            // console.log($("#mes").val());
            // console.log($("#anio").val());
            $.getJSON("obtieneConsulta.php",{id:"", desc_1:bdesc_1, desc_2:bdesc_2, caja:bcaja, control:"0"},function(objetosretorna){
                $("#error").html("");
                var TamanoArray = objetosretorna.length;
                $.each(objetosretorna, function(i,inventarios){
                    var nuevaFila =
                        "<tr>"
                        // +"<td><button type='button' class='btn btn-success' ><i class='fa fa-shopping-cart'></i></button></td>"
                        +"<td><a href='javascript:void(0);' onclick='cargar_formulario("+inventarios.ID_INV+");'><i style='font-size:14px;' class='fa fa-shopping-cart text-green'></i></a></td>"
                        +"<td>"+inventarios.ID_INV+"</td>"
                        +"<td>"+inventarios.CLIENTE+"</td>"
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
                        +"<td>"+inventarios.REGIONAL+"</td>"
                        +"</tr>";
                    $(nuevaFila).appendTo("#tbEstadoSol tbody");
                });
                if(TamanoArray==0){
                    var nuevaFila =
                        "<tr><td colspan=6>No Existe Registros</td>"
                        +"</tr>";
                    $(nuevaFila).appendTo("#tbEstadoSol tbody");
                }
            });
        });

        // $("#agregar").click(function(){

    });

    function cargar_formulario(id_inv){
        // $("#error").html("<div class='modal1'><div class='center1'> <center> <img src='img/gif-load.gif'> Buscando Informacion...</center></div></div>");

        // var id_inv = $("#id_inv").val();

        //Limpiamos campo
        $("#txtNombre").val("");
        $("#txtEmail").val("");

        $.getJSON("obtieneConsulta.php",{id:id_inv, desc_1:"", desc_2:"", caja:"", control:"1"},function(objetosretorna){
            console.log(id_inv);

            $("#error").html("");
            var TamanoArray = objetosretorna.length;
            $.each(objetosretorna, function(i,inventarios){
                var nuevaFila =
                    "<tr>"
                    // +"<td><button type='button' class='btn btn-success' ><i class='fa fa-shopping-cart'></i></button></td>"
                    +"<td><a href='javascript:void(0);' onclick='deleteRow(this)'><i style='font-size:14px;' class='fa fa-trash text-red'></i></a></td>"
                    +"<td id='asd'>"+inventarios.ID_INV+"</td>"
                    +"<td>"+inventarios.CLIENTE+"</td>"
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
                    +"<td>"+inventarios.REGIONAL+"</td>"
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
    function deleteRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }
</script>

