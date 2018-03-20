<?php 
  require_once 'header.php';
  require_once 'conexionClass.php';
  require_once 'stringsClass.php';
  // var_dump($usuario);
  $conexion = new MiConexion();
  $anios = $conexion->anios();
  $solicitudes = $conexion->solicitudes($usuario_session['ID_CLIENTE']);
  // var_dump($solicitudes);
  $mistrings = new MiStrings();
  $meses = $mistrings->meses();
 ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
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
              <h3 class="box-title">Solicitudes pendientes de devolución</h3>
            </div>
            <div class="box-body table-responsive no-padding scrollable">
              <table class="table table-bordered" id="tablajson">
                <thead><tr>
                  <th></th>
                  <th>#</th>
                  <th>SOLICITADO POR</th>
                  <th>FECHA SOLICITUD</th>
                  <th>DIRECCION DE ENTREGA</th>
                  <th>ESTADO</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($solicitudes as $sol) {  ?>
                  <tr>
                    <td><a href='javascript:void(0);' onclick='cargar_formulario("<?php echo $sol["ID_INV"]; ?>");'><i style='font-size:14px;' class='fa fa-cart-arrow-down text-green'></i></a></td>
                    <td><?php echo $sol["ID_SOLICITUD"]; ?></td>
                    <td><?php echo $sol["NOMBRE"]." ".$sol["APELLIDO"]; ?></td>
                    <td><?php echo $sol["FECHA_SOLICITUD"]; ?></td>
                    <td><?php echo $sol["DIRECCION_ENTREGA"]; ?></td>
                    <td><?php echo $sol["ESTADO"]; ?></td>
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
      <form method="POST" id="formulario_dev">
  <input type="hidden" name="cliente" id="cliente" value="<?php echo $usuario_session['ID_CLIENTE']; ?>">
  <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario_session['ID_USER']; ?>">
  <input type="hidden" name="regional" id="regional" value="<?php echo $usuario_session['REGIONAL']; ?>">

        <div class="row" style="font-size:11px;">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">Lista Items pendientes de devolución</h3>
              </div>
              <div class="box-body table-responsive no-padding scrollable">
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

      <div class="row">
        <div class="col-lg-6 col-xs-6">
          <div class="form-group">
            <label>Dirección de recojo</label>
            <input class="form-control" name="direccion" placeholder="Ingrese su dirección"></input>
          </div>
        </div>
        <div class="col-lg-5 col-xs-6">
          <div class="form-group">
            <label>Observaciones</label>
            <textarea class="form-control" rows="4" name="observacion" placeholder="Ingrese los detalles"></textarea>
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
           success: function(data)             
           {
             $('#resp').html(data);           
           }
       });
    });
  });

  function cargar_formulario(id_inv){


      $.getJSON("obtieneConsulta.php",{id:id_inv, desc_1:"", desc_2:"", desc_3:"", caja:"",  anio:"0",  mes:"0",control:"1", cli:"", user:""},function(objetosretorna){
          // console.log(id_inv);
          
        $("#error").html("");
        var TamanoArray = objetosretorna.length;
        $.each(objetosretorna, function(i,inventarios){
          // console.log(inventarios);
          var nuevaFila =
        "<tr>"
        // +"<td><button type='button' class='btn btn-success' ><i class='fa fa-shopping-cart'></i></button></td>"
        +"<td><input type='hidden' name=id-"+inventarios.ID_INV+" value="+inventarios.ID_INV+"><a href='javascript:void(0);' onclick='deleteRow(this)'><i style='font-size:14px;' class='fa fa-trash text-red'></i></a></td>"
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
 
