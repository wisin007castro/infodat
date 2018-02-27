<?php 

  require_once 'header.php';
  require_once 'conexionClass.php';
  require_once 'stringsClass.php';
  
  $conexion = new MiConexion();
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
        Gestión de Usuarios
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Gestión de Usuarios</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <div class="form-group">
              <label>Descripción</label>
              <select name="" id=""class="form-control">
                <option value="1">Usuario1</option>
                <option value="1">Usuario1</option>
              </select>
                <!-- <button type="button" class="btn btn-default" id="buscar"><i class="fa fa-search"> </i> </button>
                <button type="button" class="btn btn-default pull-right" id="limpiar"><i class="fa fa-trash"></i> </button> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <div class="form-group">
          </div>
        </div>

        <div class="col-lg-4 col-xs-6">
            <div class="form-group">
                <label>Rango de fecha:</label>
                <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <select class="form-control" id="mes">
                    <?php foreach ($meses as $mes =>$value) {
                    ?>
                    <option class="form-control" value="<?php echo $mes; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                </select>
                </div>
                <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <select class="form-control" id="anio">
                    <?php foreach ($anios as $anio =>$value) {
                    ?>
                    <option class="form-control" value="<?php echo $value["ANO_I"]; ?>"><?php echo $value["ANO_I"]; ?></option>
                    <?php } ?>
                </select>
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
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Lista de archivos</h3>
            </div>
            <div class="box-body table-responsive no-padding">
              <table class="table table-bordered" id="tb_busc_usuario">
              <thead><tr>
                  <th>#</th>
                  <th>CLIENTE</th>
                  <th>NOMBRE</th>
                  <th>CARGO</th>
                  <th>DIRECCION</th>
                  <th>TELEFONO</th>
                  <!-- <th>INTERNO</th> -->
                  <th>CELULAR</th>
                  <th>CORREO</th>
<!--                   <th>USER</th>
                  <th>PASSWORD</th> 
                  <th>HABILITADO</th>-->
                  <th>TIPO</th>
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
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-11 col-xs-6">
        </div>
        <div class="col-lg-1 col-xs-6">
          <div class="form-group">
          </br>
          </br>
          <a href="#" class="btn btn-app">
                <i class="fa fa-file-excel-o"></i> Exportar
              </a>
          </div>
        </div>
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
      $("#tb_busc_usuario tbody").html("");
    });

    $("#buscar").click(function(){
      $("#tb_busc_usuario tbody").html("");
        // $("#error").html("<div class='modal1'><div class='center1'> <center> <img src='img/gif-load.gif'> Buscando Informacion...</center></div></div>");
      var bdesc_1 = $("#bdesc_1").val();
      // var bfecha = $("#reservation").val();//id por defecto de la fecha
      // console.log(bfecha);
      // console.log($("#mes").val());
      // console.log($("#anio").val());
      $.getJSON("consultaUsuario.php",{nombre:bdesc_1},function(objetosretorna){
        $("#error").html("");
        var TamanoArray = objetosretorna.length;
        $.each(objetosretorna, function(i,usuarios){
          var nuevaFila =
        "<tr>"
        // +"<td><button type='button' class='btn btn-success' ><i class='fa fa-shopping-cart'></i></button></td>"
        +"<td><a href='form_edit_usuario.php'><i style='font-size:14px;' class='fa fa-edit text-blue'></i></a></td>"
        +"<td>"+usuarios.ID_USER+"</td>"
        +"<td>"+usuarios.ID_CLIENTE+"</td>"
        +"<td>"+usuarios.NOMBRE+"-"+usuarios.APELLIDO+"</td>"
        +"<td>"+usuarios.CARGO+"</td>"
        +"<td>"+usuarios.DIRECCION+"</td>"
        +"<td>"+usuarios.TELEFONO+"</td>"
        +"<td>"+usuarios.CELULAR+"</td>"
        +"<td>"+usuarios.CORREO+"</td>"
        +"<td>"+usuarios.TIPO+"</td>"
        +"<td>"+usuarios.REGIONAL+"</td>"
        +"</tr>";
          $(nuevaFila).appendTo("#tb_busc_usuario tbody");
        });
        if(TamanoArray==0){
          var nuevaFila =
          "<tr><td colspan=6>No Existe Registros</td>"
          +"</tr>";
          $(nuevaFila).appendTo("#tb_busc_usuario tbody");
        }
      });
    });

    // $("#agregar").click(function(){

  });


</script>
 
