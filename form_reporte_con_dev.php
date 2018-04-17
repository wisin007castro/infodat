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
      Reporte de Consultas - Devoluciones
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reporte de Consultas - Devoluciones</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
<input type="hidden" name="cliente" id="cliente" value="<?php echo $usuario_session['ID_CLIENTE']; ?>">
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario_session['ID_USER']; ?>">
<input type="hidden" name="regional" id="regional" value="<?php echo $usuario_session['REGIONAL']; ?>">
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <div class="form-group">
              
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

  </div>
  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?php require_once 'footer.php' ?>

<script type="text/javascript">
  $(document).ready(function(){

    $("#buscar").click(function(){

      var id_user = $("#usuario").val();
      var fechas = $("#reservation").val();

      // console.log($("#anio").val());
      // function form(id_cliente, fechas) {
      window.open('pdf/rep_consulta.php?id_user='+id_user+'&fechas='+fechas);
      // }

      });
 });


</script>
 
