<?php 

  require_once 'header.php';

  require_once 'conexionClass.php';
  require_once 'stringsClass.php';
  
  $conexion = new MiConexion();
  $usuarios = $conexion->usuarios($usuario_session['ID_CLIENTE']);
  $repAccess = $conexion->repAccesso($usuario_session['ID_CLIENTE']);
  $clientes = $conexion->clientes();

  $modulos = $con->modulos($usuario_session['ID_USER']);
  $modulos = array_column($modulos, 'TIPO');//SOLO LA COLUMNA TIPO
  $modulos = array_unique($modulos);//EQUIVALENTE A UN DISTINCT

  $mistrings = new MiStrings();
  $meses = $mistrings->meses();

 ?>

  <div class="content-wrapper">
    
    <section class="content-header">
      <h1>
      Reporte de Consultas
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reporte de Consultas</li>
      </ol>
    </section>

    <?php if(in_array("emision_reportes", $modulos)  || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>

    <section class="content">

<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario_session['ID_USER']; ?>">
<input type="hidden" name="regional" id="regional" value="<?php echo $usuario_session['REGIONAL']; ?>">
      <div class="row">

        <div class="col-lg-2">
          <div class="form-group">
            <label>Tipo de Reporte</label>
            <div class="radio">
              <label>
                <input type="radio" name="tipo_reporte" id="radio_consulta" value="SI" checked="">
                Consulta&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </label>
              <label>
                <input type="radio" name="tipo_reporte" id="radio_pendiente" value="NO">
                Pendiente
              </label>
            </div>
          </div>
        </div>

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
        <div style="font-size:18px;" class="col-lg-10 col-xs-6">
          <div  id="reporte">
          </div>
          <div align="right" class="footer">
            <a id="btn_pdf" class="btn btn-app">
              <i class="fa fa-save"></i> Guardar
            </a>
          </div>
        </div>
        <div class="col-lg-1 col-xs-6">

        </div>
      </div>
      <div class="row">

      </div>
    </section>

    <?php else:?>
    <section>
      <div class="col-xs-12">
        <div class='restringido' style="text-align: center">
          <span class="label label-primary"><i class="fa fa-warning"></i>  Restringido..!!!  <i class="fa fa-warning"></i></span><br/>
          <label style='color:#1D4FC1'>
                <?php  
                echo "No tienes las credenciales para acceder al contenido"; 
                // echo "Succefully";
                ?> 
          </label> 
        </div>
      </div> 
    </section>
    <?php endif ?>

  </div>

</div>

<?php require_once 'footer.php' ?>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_pdf").hide();

    $("#id_cliente").change(function(){
      buscar();
    });

    $("#radio_consulta").click(function(event) {
      $("#reservation").prop( "disabled", false );
      limpiar();
    });
    $("#radio_pendiente").click(function(event) {
      $("#reservation").prop( "disabled", true );
      limpiar();
    });

    $("#reservation").change(function(){
      buscar();
    });

    $("#buscar").click(function(event) {
      buscar();
    });

    $("#btn_pdf").click(function(){
      genReport();
    });

      $("#limpiar").click(function(){
        limpiar();
      });

    function limpiar(){
      $("#reporte").html("");
      $("#btn_pdf").hide();
    }

    function buscar(){
      var id_cliente = $('#id_cliente').val();
      var id_user = $("#usuario").val();
      var fechas = $("#reservation").val();

      if($('#id_cliente').val() == 'TODOS'){
        if ($('#radio_consulta').is(':checked')) {
          $("#reporte").load("pdf/rep_consulta_admin.php",{'id_user':id_user, 'fechas':fechas, 'id_cliente':id_cliente}, function(response, status, xhr) {
            if (status == "error") {
              var msg = "Error!, algo ha sucedido: ";
              $("#reporte").html(msg + xhr.status + " " + xhr.statusText);
            }
          });
        }
        else{//reporte Pendientes TODOS vista html
          $("#reporte").load("pdf/rep_pendiente_admin.php",{'id_user':id_user, 'fechas':fechas, 'id_cliente':id_cliente}, function(response, status, xhr) {
            if (status == "error") {
              var msg = "Error!, algo ha sucedido: ";
              $("#reporte").html(msg + xhr.status + " " + xhr.statusText);
            }
          });
        }

      }else{
        if ($('#radio_consulta').is(':checked')) {
          $("#reporte").load("pdf/rep_consulta.php",{'id_user':id_user, 'fechas':fechas, 'id_cliente':id_cliente}, function(response, status, xhr) {
            if (status == "error") {
              var msg = "Error!, algo ha sucedido: ";
              $("#reporte").html(msg + xhr.status + " " + xhr.statusText);
            }
          });
        }
        else{//reporte Pendientes por CLIENTE vista html
          $("#reporte").load("pdf/rep_pendiente.php",{'id_user':id_user, 'fechas':fechas, 'id_cliente':id_cliente}, function(response, status, xhr) {
            if (status == "error") {
              var msg = "Error!, algo ha sucedido: ";
              $("#reporte").html(msg + xhr.status + " " + xhr.statusText);
            }
          });
        }
      }
      $("#btn_pdf").show();//mostrando btn Generar
    }

    function genReport(){
      var id_cliente = $('#id_cliente').val();
      var id_user = $("#usuario").val();
      var fechas = $("#reservation").val();

      if ($('#id_cliente').val() == 'TODOS') {
        if ($('#radio_consulta').is(':checked')) {
          window.open('pdf/rep_consulta_admin.php?id_user='+id_user+'&fechas='+fechas+'&id_cliente='+id_cliente);
        }
        else{//Reporte Pendientes TODOS vista PDF
          window.open('pdf/rep_pendiente_admin.php?id_user='+id_user+'&fechas='+fechas+'&id_cliente='+id_cliente);
        }
      }else{
        if ($('#radio_consulta').is(':checked')) {
          window.open('pdf/rep_consulta.php?id_user='+id_user+'&fechas='+fechas+'&id_cliente='+id_cliente);
        }
        else{//Reporte Pendientes por CLIENTE vista PDF
          window.open('pdf/rep_pendiente.php?id_user='+id_user+'&fechas='+fechas+'&id_cliente='+id_cliente);
        }
      }

    }
 });


</script>
 
