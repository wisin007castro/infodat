<?php
  require_once 'header.php';
  require_once 'conexionClass.php';
  require_once 'stringsClass.php';
  
  $conexion = new MiConexion();
  $anios = $conexion->anios($usuario_session['ID_CLIENTE']);
  // var_dump($usuario);
  $asignacion = $conexion->asignacion($usuario_session['ID_USER'], 'autorizacion');//Cambiar segun modulo
  //$asignacion = array_column($asignacion, 'ASIGNACION');//seleccionando una columna

  $mistrings = new MiStrings();
  $meses = $mistrings->meses();

  $datos_usuario = $conexion->usuario($usuario_session['ID_USER']);

  $modulos = $conexion->modulos($usuario_session['ID_USER']);
  $modulos = array_column($modulos, 'TIPO');//SOLO LA COLUMNA TIPO
  $modulos = array_unique($modulos);//EQUIVALENTE A UN DISTINCT

 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php 
      // var_dump($asignacion);
     ?>
    <section class="content-header">
      <h1>
        Solicitud de documentos
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Solicitud de documentos</li>
      </ol>
    </section>

<?php if(in_array("solicitud_consultas", $modulos) || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-lg-2 col-xs-6">
          <div class="form-group">
              <label>Número de caja:</label>
               <input type="number" class="form-control" id="bNoCaja" name="" min="0" onkeydown="javascript: return event.keyCode == 69 ? false : true"> <!-- press 'e' = false-->
          </div>
        </div>

        <div class="col-lg-6 col-xs-6">
          <div class="form-group tree-fields">
            <label>Descripción:</label>
            <div class="input-group">
              <input style='text-transform:uppercase' type="text" class="form-control" id="bdesc_1" name="" placeholder="Descripción 1">
              <input style='text-transform:uppercase' type="text" class="form-control" id="bdesc_2" name="" placeholder="Descripción 2">
              <input style='text-transform:uppercase' type="text" class="form-control" id="bdesc_3" name="" placeholder="Descripción 3">
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-xs-6">
          <div class="form-group two-fields">
                <label>Fecha inicial:</label>
                <div class="input-group">
                  <!-- <div class="col-lg-7"> -->
                    <!-- <span class="input-group-addon">-</span> -->
                    <select class="form-control" id="sel_mes">
                      <?php foreach ($meses as $mes =>$value) { ?>
                        <option class="form-control" value="<?php echo $mes; ?>"><?php echo $value; ?></option>
                      <?php } ?>
                    </select>
                    
                    <select class="form-control" id="sel_anio">
                        <option class="form-control" value="0">Año</option>
                      <?php foreach ($anios as $anio =>$value) { ?>
                        <option class="form-control" value="<?php echo $value["ANO_I"]; ?>"><?php echo $value["ANO_I"]; ?></option>
                      <?php } ?>
                    </select>
                  <!-- </div> -->
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
      <!-- /.row -->
    </section>
    <!-- /.content -->
    <section class="content">
      <div class="row" style="font-size:11px;">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Lista de archivos encontrados</h3>
            </div>
            <div class="box-body table-responsive no-padding">
              <div class="scrollable">
                <table class="table table-bordered" id="tablajson">
                  <thead><tr>
                    <th></th>
                    <!-- <th>#</th> -->
                    <!-- <th>CLIENTE</th> -->
                    <th>CAJA</th>
                    <th>ITEM</th>
                    <th>DESCRIPCION 1</th>
                    <th>DESCRIPCION 2</th>
                    <th>DESCRIPCION 3</th>
                    <th>DESCRIPCION 4</th>
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
    </section>

    <section>
      <div id="resp" class="col-lg-12">
    </section>

    <section class="content">
      <form method="POST" id="formulario">
<input type="hidden" name="cliente" id="cliente" value="<?php echo $usuario_session['ID_CLIENTE']; ?>">
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario_session['ID_USER']; ?>">
<input type="hidden" name="regional" id="regional" value="<?php echo $usuario_session['REGIONAL']; ?>">
<input type="hidden" name="asignado" id="asignado" value="<?php echo $asignacion[0]['ASIGNACION']; ?>">
        <div class="row" style="font-size:11px;">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">Lista de archivos seleccionados</h3>
              </div>
              <div class="box-body table-responsive no-padding scrollable">
                <table class="table table-bordered" id="seleccionados">
                  <thead><tr>
                    <th></th>
                    <th>CAJA</th>
                    <th>ITEM</th>
                    <th>DESCRIPCION 1</th>
                    <th>DESCRIPCION 2</th>
                    <th>DESCRIPCION 3</th>
                    <th>DESCRIPCION 4</th>
                    <th>CANTIDAD</th>
                    <th>UNIDAD</th>
                    <th>FECHA INICIO</th>
                    <th>FECHA FIN</th>
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

      <?php //if ($usuario_session['TIPO'] == 'CONSULTA') { ?>
      <div class="row"><b><span class="text-yellow pull-right" id="msj_urgente"></span></b></div>
        <div class="row">
          <div class="col-lg-5 col-xs-6">
            <div class="form-group">

              <label>*Dirección de entrega</label>
              <input style='text-transform:uppercase' name="direccion" class="form-control" value="<?php echo $usuario_session['DIRECCION']; ?>"></input>
            </div>
          </div>
          <div class="col-lg-4 col-xs-6">
            <div class="form-group">
              <label>Observaciones</label>
              <textarea style='text-transform:uppercase' name="observacion" class="form-control" rows="3"></textarea>
            </div>
          </div>
          <div class="col-lg-1 col-xs-6">
            <br>
            <div class="form-group">
              <div class="radio">
                <label>
                  <input type="radio" name="tipo_envio" id="fisico" value="FISICO" checked="">
                  Fisico
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="tipo_envio" id="internet" value="INTERNET">
                  Internet
                </label>
              </div>
            </div>
          </div>

          <div class="col-lg-1 col-xs-6">
            <br>
            <div class="form-group">
              <div class="radio">
                <label>
                  <input type="radio" name="tipo_consulta" id="normal" value="NORMAL" checked="">
                  Normal
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="tipo_consulta" id="urgente" value="URGENTE">
                  Urgente
                </label>
              </div>
            </div>
          </div>

          <!-- ./col -->
          <div class="col-lg-1 col-xs-6">
            <div class="form-group">
              <br>
              <a type="button" class="btn btn-app" id="btn-ingresar">
                <i class="fa fa-shopping-cart"></i> Enviar
              </a>
            </div>
          </div>
        </div>
        
        <?php //} ?>

      </form>
      <!-- /.row -->
    </section>

    <?php else:?>
    <section>
      <div class="col-xs-12">
        <div class='restringido' style="text-align: center">
          <span class="label label-primary"><i class="fa fa-warning"></i>  Restringido..!!!  <i class="fa fa-warning"></i></span><br/>
          <label style='color:#1D4FC1'>
                <?php  
                  echo "No tiene el perfil adecuado para ver este contenido, contáctese con su Administrador"; 
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

  $(function(){

    $('#urgente').click(function(){
      // alert('changed');
      $("#msj_urgente").text("* Las solicitudes de tipo 'Urgente' tienen un costo adicional... ");
    });
    $('#normal').click(function(){
      // alert('changed');
       $("#msj_urgente").text('');
    });
    $('#internet').click(function(){
      $("#msj_urgente").text('');
    });

});

//Nueva solicitud 
$(document).ready(function(){


  //Envio de formulario
  $('#btn-ingresar').click(function(){
    var autorizacion = "<?php echo count($asignacion); ?>";
    //console.log(variableJS);
    if (autorizacion > 0) {
      var url = "controllers/consultaAuthController.php";
    }else{
      var url = "controllers/consultaController.php";
    }
    guardar(url);
  });
  function guardar(ruta){
      url = ruta;
      var timout;
      $.ajax({                        
         type: "POST",                 
         url: url,                  
         data: $("#formulario").serialize(), 
         success: function(result){
              if (result == 'success') {
                  $.get("msj_correcto.php?msj=Solicitud realizada exitosamente", function(result){
                  $("#resp").html(result);
                  refrescar();
                  });
              }
              else{
                  if(result == 'vacio'){
                      $.get("msj_incorrecto.php?msj="+"Seleccione al menos un ITEM", function(result){
                          $("#resp").html(result);
                      });
                  }
                  else{
                    if (result == 'vacio_dir') {
                          $.get("msj_incorrecto.php?msj="+"Ingrese la dirección de entrega", function(result){
                          $("#resp").html(result);
                      });
                    }
                    else{
                          $.get("msj_incorrecto.php?msj="+"No se pudo realizar la solicitud", function(result){
                          $("#resp").html(result);
                      });
                    }
                  }
              }
          }
     }); 
  }
  // Limpiamos el cuerpo tbody
  $("#limpiar").click(function(){
    $("#bNoCaja").val("");
    $("#bdesc_1").val("");
    $("#bdesc_2").val("");
    $("#bdesc_3").val("");
    $("#sel_mes").val("0");
    $("#sel_anio").val("0");
    $("#tablajson tbody").html("");
  });

  $("#bNoCaja, #bdesc_1, #bdesc_2, #bdesc_3").keyup(function(event) {
    if (event.keyCode === 13) {//key 'Enter'
        $("#buscar").click();
    }
    if (event.keyCode === 27) {//key 'ESC'
        $("#limpiar").click();
    }
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
    var cliente = $("#cliente").val();
    var usuario = $("#usuario").val();

    var bcaja = $("#bNoCaja").val(); 
    $.getJSON("obtieneConsulta.php",{id:"", desc_1:bdesc_1, desc_2:bdesc_2, desc_3:bdesc_3, caja:bcaja, mes:sel_mes, anio:sel_anio, control:"0", cli:cliente, user:usuario},function(objetosretorna){
      $("#error").html("");
      var TamanoArray = objetosretorna.length;
      $.each(objetosretorna, function(i,inventarios){

        if (inventarios.ESTADO == 'EN CUSTODIA') {
        var nuevaFila =
      "<tr>"
      // +"<td><button type='button' class='btn btn-success' ><i class='fa fa-shopping-cart'></i></button></td>"
      +"<td><a href='javascript:void(0);' onclick='cargar_formulario("+inventarios.ID_INV+");'><i style='font-size:15px;' class='fa fa-shopping-cart text-green'></i></a></td>"
      // +"<td>"+inventarios.ID_INV+"</td>"
      // +"<td>"+inventarios.CLIENTE+"</td>"
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
        }
        else{
        var nuevaFila =
      "<tr>"
      // +"<td><button type='button' class='btn btn-success' ><i class='fa fa-shopping-cart'></i></button></td>"
      +"<td><a href='javascript:void(0);'><i style='font-size:15px;' class='fa fa-shopping-cart text-muted'></i></a></td>"
      // +"<td>"+inventarios.ID_INV+"</td>"
      // +"<td>"+inventarios.CLIENTE+"</td>"
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
        }

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
    var usuario = $("#usuario").val();
    var cliente = $("#cliente").val();
    $.getJSON("obtieneConsulta.php",{id:id_inv, desc_1:"", desc_2:"", desc_3:"", caja:"", mes:"0", anio:"0", control:"1", cli:"", user:usuario},function(objetosretorna){
        

      $("#error").html("");
      var TamanoArray = objetosretorna.length;
      console.log(objetosretorna);
      $.each(objetosretorna, function(i,inventarios){
        var nuevaFila =
      "<tr>"
      // +"<td><button type='button' class='btn btn-success' ><i class='fa fa-shopping-cart'></i></button></td>"

      +"<td><input type='hidden' name=id-"+inventarios.ID_INV+" value="+inventarios.ID_INV+"><a href='javascript:void(0);' onclick='deleteRow(this)'><i style='font-size:14px;' class='fa fa-trash text-red'></i></a></td>"
      // +"<td>"+inventarios.ID_INV+"</td>"
      // +"<td>"+inventarios.CLIENTE+"</td>"
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

  function refrescar(){
    
    timout=setTimeout(function(){
        location.reload();
    },3000,"JavaScript");//3 segundos
  }

</script>
 
