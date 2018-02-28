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
        Reportes
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reportes</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <div class="form-group">
              <label>Número de caja</label>
              <input type="number" class="form-control" id="bNoCaja" name="" min="0">
              <input type="text" class="form-control" id="bdesc_1" name="" placeholder="Descripción 1">
          </div>
        </div>

        <div class="col-lg-4 col-xs-6">
          <div class="form-group">
              <label>Descripción</label>
              <input type="text" class="form-control" id="bdesc_2" name="" placeholder="Descripción 2">
              <input type="text" class="form-control" id="bdesc_3" name="" placeholder="Descripción 3">
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-xs-6">
          <div class="form-group">
                <label>Rango de fecha:</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <select class="form-control" id="sel_mes">
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
                  <select class="form-control" id="sel_anio">
                      <option class="form-control" value="0">Año</option>
                    <?php foreach ($anios as $anio =>$value) {
                     ?>
                      <option class="form-control" value="<?php echo $value["ANO_I"]; ?>"><?php echo $value["ANO_I"]; ?></option>
                    <?php } ?>
                  </select>
                </div>
          </div>
        </div>
        <div class="col-lg-2 col-xs-6">
          <div class="form-group">
                  <br>
                  <div class="input-group">
                    <!-- <br> -->
                    <a id="buscar" class="btn btn-app">
                      <i class="fa fa-search"></i> Buscar
                    </a>
                    <a id="limpiar" class="btn btn-app">
                        <i class="fa fa-trash"></i> Limpiar
                      </a>
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
              <table class="table table-bordered" id="tablajson">
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
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

    <section class="content">
      <div class="row">
        <div class="col-lg-5 col-xs-6">
          <div class="form-group">
            <label>Dirección de entrega</label>
            <input class="form-control" placeholder="Ingrese su dirección"></input>
          </div>
        </div>
        <div class="col-lg-5 col-xs-6">
          <div class="form-group">
            <label>Observaciones</label>
            <textarea class="form-control" rows="4" placeholder="Ingrese los detalles"></textarea>
          </div>
        </div>
        <div class="col-lg-1 col-xs-6">
          <div class="form-group">
            <div class="radio">
              <label>
                <input type="radio" name="optionsRadios" id="normal" value="Normal" checked="">
                Normal
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="optionsRadios" id="urgente" value="Urgente">
                Urgente
              </label>
            </div>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-1 col-xs-6">
          <div class="form-group">
            <a class="btn btn-app" href='javascript:void(0);' onclick='cargar_formulario();'>
                <i class="fa fa-shopping-cart"></i> Enviar
              </a>
          </div>
        </div>
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
      $("#bdesc_3").val("");
      $("#sel_mes").val("");
      $("#sel_anio").val("");
      $("#tablajson tbody").html("");
    });

    $("#buscar").click(function(){
      $("#tablajson tbody").html("");
        // $("#error").html("<div class='modal1'><div class='center1'> <center> <img src='img/gif-load.gif'> Buscando Informacion...</center></div></div>");
      var bdesc_1 = $("#bdesc_1").val();
      var bdesc_2 = $("#bdesc_2").val();
      var bdesc_3 = $("#bdesc_3").val();
      var sel_mes = $("#sel_mes").val();
      var sel_anio = $("#sel_anio").val();
      var bcaja = $("#bNoCaja").val();
      // var bfecha = $("#reservation").val();//id por defecto de la fecha
      // console.log(bfecha);
      console.log($("#bdesc_1").val());
      console.log($("#sel_anio").val());
      $.getJSON("obtieneConsulta.php",{id:"", desc_1:bdesc_1, desc_2:bdesc_2, desc_3:bdesc_3, caja:bcaja, mes:sel_mes, anio:sel_anio, control:"0"},function(objetosretorna){
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
        // +"<td>"+inventarios.REGIONAL+"</td>"
        +"</tr>";
          $(nuevaFila).appendTo("#tablajson tbody");
        });
        if(TamanoArray==0){
          var nuevaFila =
          "<tr><td colspan=6>No Existe Registros</td>"
          +"</tr>";
          $(nuevaFila).appendTo("#tablajson tbody");
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

      $.getJSON("obtieneConsulta.php",{id:id_inv, desc_1:"", desc_2:"", desc_3:"", caja:"", mes:"0", anio:"0", control:"1"},function(objetosretorna){
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
        // +"<td>"+inventarios.REGIONAL+"</td>"
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
 
