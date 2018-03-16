<?php 

  require_once 'header.php';

  require_once 'conexionClass.php';
  require_once 'stringsClass.php';
  
  $conexion = new MiConexion();
  $usuarios = $conexion->usuarios($usuario_session['ID_CLIENTE']);
  $repAccess = $conexion->repAccesso($usuario_session['ID_CLIENTE']);

  $anios = $conexion->anios();
  // var_dump($anios);

  $mistrings = new MiStrings();
  $meses = $mistrings->meses();

 ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reporte de Usuarios
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reporte de Usuarios</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <div class="form-group">
              <label>Usuario:</label>
              <select name="user" id="user" class="form-control">
                <option value="0"> - - - TODOS LOS USUARIOS - - - </option>
                <?php foreach ($usuarios as $usu => $value) { ?>
                  <option value="<?php echo $value['ID_USER']; ?>"><?php echo $value['NOMBRE']." ".$value['APELLIDO'] ?></option>
                <?php } ?>
              </select>
                <!-- <button type="button" class="btn btn-default" id="buscar"><i class="fa fa-search"> </i> </button>
                <button type="button" class="btn btn-default pull-right" id="limpiar"><i class="fa fa-trash"></i> </button> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <div class="form-group two-fields">
            <label>Fecha inicial:</label>
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" class="form-control" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask="" id="sel_fecha">
            </div>
          </div>
        </div>

        <div class="col-lg-2 col-xs-6">
          <div class="form-group">
            <br>
            <div class="btn-group">
                <button  id="buscar" type="button" class="btn btn-default">
                  <i class="fa fa-search"></i></button>
                <button id="limpiar" type="button" class="btn btn-default">
                  <i class="fa fa-trash"></i></button>
              </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
    <section class="content">
        <div class="row" style="font-size:11px;">
            <div class="col-xs-12">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">Reporte de Acciones de Usuario</h3>
                    </div>
  <input type="hidden" name="cliente" id="cliente" value="<?php echo $usuario_session['ID_CLIENTE']; ?>">
  <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario_session['ID_USER']; ?>">
  <input type="hidden" name="regional" id="regional" value="<?php echo $usuario_session['REGIONAL']; ?>">

                    <div class="box-body table-responsive no-padding scrollable">
                        <table class="table table-bordered" id="tb_busc_acceso">
                            <thead><tr>
                                <th>No</th>
                                <th>Nombre</th>
                                <th>Tipo de Consulta</th>
                                <th>Fecha Solicitud</th>
                                <th>Caja</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($repAccess as $ra) { ?>
                                <tr>
                                    <td><?php echo $ra["ID_CLIENTE"]; ?></td>
                                    <td><?php echo $ra["NOMBRE"]." ".$ra["APELLIDO"]; ?></td>
                                    <td><?php echo $ra["TIPO"]; ?></td>
                                    <td><?php echo $ra["FECHA"]; ?></td>
                                    <td><?php echo $ra["CAJA"]; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="box-footer" align="right">
                      <form action="excel.php" method="post" target="_blank" id="frmExport">
                      <a class="btn btn-app botonExcel">
                        <i class="fa fa-file-excel-o"></i> Exportar
                      </a>
                      <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
                      </form>
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

    //Exportar a Excel
    $(".botonExcel").click(function(event) {
    $("#datos_a_enviar").val( $("<div>").append( $("#tb_busc_acceso").eq(0).clone()).html());
    $("#frmExport").submit();
    });

    // Limpiamos el cuerpo tbody
    $("#limpiar").click(function(){
      $("#sel_fecha").val("");
      $("#user").val("0");
      $("#tb_busc_acceso tbody").html("");
    });

    $("#buscar").click(function(){
      $("#tb_busc_acceso tbody").html("");
        // $("#error").html("<div class='modal1'><div class='center1'> <center> <img src='img/gif-load.gif'> Buscando Informacion...</center></div></div>");
      var sel_user = $("#user").val();
      var sel_fecha = $("#sel_fecha").val();

      var cliente = $("#cliente").val();

      // console.log($("#anio").val());
      $.getJSON("consultaAcceso.php",{user:sel_user, fecha:sel_fecha, cli:cliente},function(objetosretorna){
        $("#error").html("");
        var TamanoArray = objetosretorna.length;
        $.each(objetosretorna, function(i,accesos){
          var nuevaFila =
        "<tr>"
        +"<td>"+accesos.ID_CLIENTE+"</td>"
        +"<td>"+accesos.NOMBRE+" "+accesos.APELLIDO+"</td>"
        +"<td>"+accesos.TIPO+"</td>"
        +"<td>"+accesos.FECHA+"</td>"
        +"<td>"+accesos.CAJA+"</td>"
        +"</tr>";
          $(nuevaFila).appendTo("#tb_busc_acceso tbody");
        });
        if(TamanoArray==0){
          var nuevaFila =
          "<tr><td colspan=6>No Existe Registros</td>"
          +"</tr>";
          $(nuevaFila).appendTo("#tb_busc_acceso tbody");
        }
      });
    });

    // $("#agregar").click(function(){

  });


</script>
 
