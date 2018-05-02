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
  // var_dump($usuario);
  $conexion = new MiConexion();
  
  $solicitudes = $conexion->solicitudes($usuario_session['ID_CLIENTE']);
  $asignacion = $conexion->asignacion($usuario_session['ID_USER'], 'solicitud_devoluciones');

  $asignacion = array_column($asignacion, 'ASIGNACION');//seleccionando una columna
  
  // var_dump($solicitudes);
  $mistrings = new MiStrings();
  $meses = $mistrings->meses();
 ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

  <?php   
    // foreach($asignacion as $as){
  // var_dump($asignacion);
    // }
  ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Devolución de documentos
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Devolución de documentos</li>
      </ol>
    </section>

    <!-- Main content -->
    <!-- /.content -->
    <section class="content">
      <div class="row" style="font-size:11px;">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Items pendientes de devolución</h3>
            </div>
            <div class="box-body table-responsive no-padding scrollable">
              <table class="table table-bordered" id="tablajson">
                <thead><tr>
                  <th></th>
                  <th>Nro. SOLICITUD</th>
                  <th>CAJA</th>
<!--                   <th>SOLICITADO POR</th>
                  <th>FECHA SOLICITUD</th>
                  <th>DIRECCION DE ENTREGA</th> -->
                  <!-- <th>ESTADO</th> -->
                    <th>ITEM</th>
                    <th>DESCRIPCION 1</th>
                    <th>DESCRIPCION 2</th>
                    <th>DESCRIPCION 3</th>
                    <th>DESCRIPCION 4</th>
                    <th>CANTIDAD/UNIDAD</th>
                    <th>FECHA INICIO</th>
                    <th>FECHA FIN</th>
                    <th>DEPARTAMENTO</th>
                    <th>ESTADO</th>
                </tr>
              </thead>
              <tbody>
              <?php 
              // foreach($asignacion as $mod){
               foreach ($solicitudes as $sol) {  
                if($sol['ESTADO_INV'] == 'EN CONSULTA' AND in_array('TODOS', $asignacion)){//todos los usuarios
                ?>
                  <tr>
                    <td>
                      <?php //if ($sol['ESTADO_INV'] == 'EN CONSULTA'): ?>
                        <a href='javascript:void(0);' onclick='cargar_formulario("<?php echo $sol["ID_INV"]; ?>");'><i style='font-size:14px;' class='fa fa-cart-arrow-down text-green'></i></a>
                      <?php// else: ?>
                        <!-- <a href='javascript:void(0);'><i style='font-size:14px;' class='fa fa-cart-arrow-down text-muted'></i></a> -->
                      <?php// endif ?>

                    </td>
                    <td><?php echo $sol["ID_SOLICITUD"]; ?></td>
                    <td><?php echo $sol["CAJA"]; ?></td>
                    <td><?php echo $sol["ITEM"]; ?></td>
                    <td><?php echo $sol["DESC_1"]; ?></td>
                    <td><?php echo $sol["DESC_2"]; ?></td>
                    <td><?php echo $sol["DESC_3"]; ?></td>
                    <td><?php echo $sol["DESC_4"]; ?></td>
                    <td><?php echo $sol["CANTIDAD"]." ".$sol["UNIDAD"]; ?></td>
                    <td><?php echo $sol["DIA_I"]."/".$sol["MES_I"]."/".$sol["ANO_I"]; ?></td>
                    <td><?php echo $sol["DIA_F"]."/".$sol["MES_F"]."/".$sol["ANO_F"]; ?></td>
                    <td><?php echo $sol["DEPARTAMENTO"]; ?></td>
                    <td><?php echo $sol["ESTADO_INV"]; ?></td>
                  </tr>  
              <?php }
              elseif($sol['ESTADO_INV'] == 'EN CONSULTA' AND ($sol['ID_USER'] == $usuario_session['ID_USER'] || in_array($sol['ID_USER'], $asignacion)) ){?>
                  <tr>
                    <td>

                      <?php// if ($sol['ESTADO_INV'] == 'EN CONSULTA'): ?>
                        <a href='javascript:void(0);' onclick='cargar_formulario("<?php echo $sol["ID_INV"]; ?>");'><i style='font-size:14px;' class='fa fa-cart-arrow-down text-green'></i></a>
                      <?php //else: ?>
                        <!-- <a href='javascript:void(0);'><i style='font-size:14px;' class='fa fa-cart-arrow-down text-muted'></i></a> -->
                      <?php// endif ?>

                    </td>
                    <td><?php echo $sol["ID_SOLICITUD"]; ?></td>
                    <td><?php echo $sol["CAJA"]; ?></td>
                    <td><?php echo $sol["ITEM"]; ?></td>
                    <td><?php echo $sol["DESC_1"]; ?></td>
                    <td><?php echo $sol["DESC_2"]; ?></td>
                    <td><?php echo $sol["DESC_3"]; ?></td>
                    <td><?php echo $sol["DESC_4"]; ?></td>
                    <td><?php echo $sol["CANTIDAD"]." ".$sol["UNIDAD"]; ?></td>
                    <td><?php echo $sol["DIA_I"]."/".$sol["MES_I"]."/".$sol["ANO_I"]; ?></td>
                    <td><?php echo $sol["DIA_F"]."/".$sol["MES_F"]."/".$sol["ANO_F"]; ?></td>
                    <td><?php echo $sol["DEPARTAMENTO"]; ?></td>
                    <td><?php echo $sol["ESTADO_INV"]; ?></td>
                  </tr> 
              <?php
                  }
                }
              //  } 
               ?>
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

    <section>
      <div id="resp" class="col-lg-12">
    </section>

    <section class="content">
      <form method="POST" id="formulario_dev">
  <input type="hidden" name="cliente" id="cliente" value="<?php echo $usuario_session['ID_CLIENTE']; ?>">
  <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario_session['ID_USER']; ?>">
  <input type="hidden" name="regional" id="regional" value="<?php echo $usuario_session['REGIONAL']; ?>">

        <div class="row" style="font-size:11px;">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">Lista de Items pendientes a devolver</h3>
              </div>
              <div class="box-body table-responsive no-padding scrollable">
                <table class="table table-bordered" id="seleccionados">
                  <thead><tr>
                    <th></th>
<!--                     <th>#</th>
                    <th>CLIENTE</th> -->
                    <th>CAJA</th>
                    <th>ITEM</th>
                    <th>DESCRIPCION 1</th>
                    <th>DESCRIPCION 2</th>
                    <th>DESCRIPCION 3</th>
                    <th>DESCRIPCION 4</th>
                    <th>CANTIDAD</th>
                    <th>UNIDAD</th>
                    <th>FECHA ICIO</th>
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

    <?php if ($usuario_session['TIPO'] == 'CONSULTA') { ?>
      <div class="row">
        <div class="col-lg-6 col-xs-6">
          <div class="form-group">
            <label>Dirección de recojo</label>
            <input style='text-transform:uppercase' class="form-control" name="direccion" value="<?php echo $usuario_session['DIRECCION']; ?>"></input>
          </div>
        </div>
        <div class="col-lg-5 col-xs-6">
          <div class="form-group">
            <label>Observaciones</label>
            <textarea style='text-transform:uppercase' class="form-control" rows="4" name="observacion" placevaholder="Ingrese los detalles"></textarea>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-1 col-xs-6">
          <div class="form-group">
              <a type="button" class="btn btn-app" id="btn-ingresar">
                <i class="fa fa-shopping-cart"></i> Enviar
              </a>
          </div>
        </div>
      </div>
      <?php } ?>
      <!-- /.row -->
      </form>
    </section>

    <section>
      <div class="col-lg-12">
        <div class="div_contenido" style=" text-align: center">
          <label id="resp" style='color:#177F6B'></label>
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
    $('#btn-ingresar').click(function(){
        var url = "controllers/devolucionController.php";
        $.ajax({                        
           type: "POST",                 
           url: url,                     
           data: $("#formulario_dev").serialize(), 
           success: function(result){
                if (result == 'success') {
                    $.get("msj_correcto.php?msj=Solicitud realizada exitosamente", function(result){
                    $("#resp").html(result);
                    refrescar();
                    });
                }
                else{
                    if(result == 'vacio'){
                        $.get("msj_incorrecto.php?msj="+"Seleccione al menos un ITEM e ingrese los detalles", function(result){
                            $("#resp").html(result);
                            refrescar();
                        });
                    }
                    else{
                        $.get("msj_incorrecto.php?msj="+"No se pudo realizar la solicitud", function(result){
                            $("#resp").html(result);
                            refrescar();
                        });
                    }
                }
            }
       });
    });
  });

  function cargar_formulario(id_inv){

      var usuario = $("#usuario").val();
      $.getJSON("obtieneConsulta.php",{id:id_inv, desc_1:"", desc_2:"", desc_3:"", caja:"",  anio:"0",  mes:"0",control:"1", cli:"", user:usuario},function(objetosretorna){
          // console.log(id_inv);
          
        $("#error").html("");
        var TamanoArray = objetosretorna.length;
        $.each(objetosretorna, function(i,inventarios){
          // console.log(inventarios);
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
 
