<?php 

  require_once 'header.php';

  require_once 'conexionClass.php';
  require_once 'stringsClass.php';
  
  $conexion = new MiConexion();
  $usuarios = $conexion->usuarios($usuario_session['ID_CLIENTE']);
  $repAccess = $conexion->repAccesso($usuario_session['ID_CLIENTE']);
  $clientes = $conexion->clientes();
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
      Reporte de Consultas
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reporte de Consultas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->

<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario_session['ID_USER']; ?>">
<input type="hidden" name="regional" id="regional" value="<?php echo $usuario_session['REGIONAL']; ?>">
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <div class="form-group">
            <label>Cliente</label>
              <?php if ($usuario_session['TIPO'] == 'IA_ADMIN') {
                ?>
              <select class="form-control" name="id_cliente" id="id_cliente" >
                <option value="TODOS"> --- TODOS LOS CLIENTES --- </option>
                <?php foreach ($clientes as $cli) {
                  if($usuario_session['ID_CLIENTE'] == $cli['ID_CLIENTE']){
                  ?>
                <option selected="<?php echo $usuario_session['ID_CLIENTE'] ?>" value="<?php echo $cli['ID_CLIENTE'] ?>"><?php echo $cli['CLIENTE']?></option>
                <?php }
                else{ ?>
                <option value="<?php echo $cli['ID_CLIENTE'] ?>"><?php echo $cli['CLIENTE']?></option>
                <?php 
                }
              } ?>
              </select>
              <?php }
              else{ ?>
                <select class="form-control" name="id_cliente" id="id_cliente" >
                <?php foreach ($clientes as $cli) {
                  if($usuario_session['ID_CLIENTE'] == $cli['ID_CLIENTE']){
                  ?>
                <option selected="<?php echo $usuario_session['ID_CLIENTE'] ?>" value="<?php echo $cli['ID_CLIENTE'] ?>"><?php echo $cli['CLIENTE']?></option>
                <?php 
                }
              } ?>
              </select>
              <?php } ?>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="form-group">
            <label>Date range:</label>
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" class="form-control pull-right" id="reservation">
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
      <div class="row">
        <div class="col-lg-1 col-xs-6">

        </div>
        <div style="font-size:18px;" id="reporte" class="col-lg-10 col-xs-6">

        </div>
        <div class="col-lg-1 col-xs-6">

        </div>
      </div>
      <div class="row">
        <div align="right">
          <a id="btn_pdf" class="btn btn-app">
            <i class="fa fa-save"></i> Guardar
          </a>
        </div>
      </div>
    </section>

  </div>
  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?php require_once 'footer.php' ?>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_pdf").hide();

    $("#buscar").click(function(event) {
        var id_cliente = $('#id_cliente').val();
        var id_user = $("#usuario").val();
        var fechas = $("#reservation").val();

        if($('#id_cliente').val() == 'TODOS'){
          $("#reporte").load("pdf/rep_consulta_admin.php",{'id_user':id_user, 'fechas':fechas, 'id_cliente':id_cliente}, function(response, status, xhr) {
          if (status == "error") {
            var msg = "Error!, algo ha sucedido: ";
            $("#reporte").html(msg + xhr.status + " " + xhr.statusText);
          }
        });
        }else{
          $("#reporte").load("pdf/rep_consulta.php",{'id_user':id_user, 'fechas':fechas, 'id_cliente':id_cliente}, function(response, status, xhr) {
          if (status == "error") {
            var msg = "Error!, algo ha sucedido: ";
            $("#reporte").html(msg + xhr.status + " " + xhr.statusText);
          }
        });
        }

        // $("#reporte").load('pdf/rep_consulta.php?id_user='+id_user+'&fechas='+fechas+' #con_rep');
        $("#btn_pdf").show();//mostrando btn Generar
    });

    $("#btn_pdf").click(function(){
      genReport();
    });

      function genReport(){
        var id_cliente = $('#id_cliente').val();
        var id_user = $("#usuario").val();
        var fechas = $("#reservation").val();

        if ($('#id_cliente').val() == 'TODOS') {
          window.open('pdf/rep_consulta_admin.php?id_user='+id_user+'&fechas='+fechas+'&id_cliente='+id_cliente);
        }else{
          window.open('pdf/rep_consulta.php?id_user='+id_user+'&fechas='+fechas+'&id_cliente='+id_cliente);
        }

      }
 });


</script>
 
