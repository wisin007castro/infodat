<?php 
  require_once 'header.php';
  require_once 'conexionClass.php';
  require_once 'stringsClass.php';

  $conexion = new MiConexion();
  $clientes = $conexion->clientes();
  // $usuarios = $conexion->usuarios($usuario_session['ID_CLIENTE']);
  $usuarios = $conexion->usuarios_reg($usuario_session['ID_CLIENTE'], $usuario_session['REGIONAL']);

  $tipousuarios = $conexion->tipoUsuarios();

  $deptos_access = $conexion->dptos_access($usuario_session['ID_CLIENTE']);
  // var_dump($deptos_access);
  $MisStrings = new MiStrings();
  $modulos = $MisStrings->modulos();

  $mod_auth = $conexion->modulos($usuario_session['ID_USER']);
  $mod_auth = array_column($mod_auth, 'TIPO');//SOLO LA COLUMNA TIPO
  $mod_auth = array_unique($mod_auth);//EQUIVALENTE A UN DISTINCT

 ?>
    <section>
      <div id="resp" class="col-lg-12">
    </section>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
      <h1>
        Parametrización de usuarios
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Parametrización de usuarios</li>
      </ol>
    </section>

    <?php if(in_array("parametricas", $mod_auth) || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>
    <!-- Main content -->
    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Formulario de Parametrización de accesos</h3>
        </div> 
        <?php //echo $usuario_session['ID_CLIENTE']; ?>
        <form method="POST" id="form_datos_usuario">
        <!-- <input type="hidden" name="cliente" id="cliente" value="<?php //echo $usuario_session['ID_CLIENTE']; ?>"> -->
        <input type="hidden" name="regional" id="regional" value="<?php echo $usuario_session['REGIONAL']; ?>">

          <div class="box-body">
            <div class="row">
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Cliente</label>
                  <?php if ($usuario_session['TIPO'] == 'IA_ADMIN') {
                  ?>
                  <select class="form-control" name="cliente" id="id_cliente">
                  <!-- <option value="0">--- SELECCIONE UN CLIENTE ---</option> -->
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
                  <select class="form-control" name="cliente" id="id_cliente">
                    <!-- <option value="0">--- SELECCIONE UN CLIENTE ---</option> -->
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
            </div>

            <div class="row">
              <div class="col-lg-3">
                <div class="form-group con-json">
                  <label>Usuario</label>
                  <select id="id_user" class="form-control" name="id_user" >
                  </select>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Tipo</label>
                  <select id="modulos" class="form-control" name="modulos">
                    <option value="0"> --- SELECCIONE UN MODULO --- </option>
                  <?php foreach ($modulos as $key => $value): ?>
                    <option value="<?php echo $value ?>"><?php echo $value ?></option>
                  <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Acceso</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="acceso" id="acceso1" value="SI">
                      Si
                    </label>
                    <label>
                      <input type="radio" name="acceso" id="acceso2" value="NO" checked="">
                      No
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div id="div_deptos"  class="form-group asignacion">
                  <label>Departamento</label>
                  <div class="">
                    <label>
                      <input name="asignacion[]" id="checkTodoDeptos" value="TODOS" type="checkbox">TODOS</label>
                  </div>
                  <?php foreach ($deptos_access as $key => $value): ?>
                  <?php if ($usuario_session['TIPO'] != 'IA_ADMIN') :?>
                  <div class="checkbox">
                    <label>
                      <input name="asignacion[]" type="checkbox" value="<?php echo $value['DEPARTAMENTO']?>"><?php echo $value['DEPARTAMENTO']?></label>
                  </div>
                  <?php endif ?>
                  <?php endforeach ?>
                  <div class="">
                    <label>
                      <input name="asignacion[]" id="ningunoDeptos" value="NINGUNO" type="checkbox">Ninguno</label>
                  </div>
                </div>

                <div id="div_users"  class="form-group asignacion">
                  <label>Usuarios a los que tendra acceso</label>
                  <div class="">
                    <label>
                      <input name="asignacion[]" id="checkTodoUsers" type="checkbox" value="TODOS">TODOS</label>
                  </div>
                  <?php foreach ($usuarios as $key => $value): ?>
                  <?php if ($usuario_session['TIPO'] != 'IA_ADMIN') :?>
                  <div class="checkbox">
                    <label>
                      <input name="asignacion[]" type="checkbox" value="<?php echo $value['ID_USER'] ?>"><?php echo $value['NOMBRE']." ".$value['APELLIDO']?></label>
                  </div>
                  <?php endif ?>
                  <?php endforeach ?>
                  <div class="">
                    <label>
                      <input name="asignacion[]" id="ningunoUsers" value="NINGUNO" type="checkbox">Ninguno</label>
                  </div>
                </div>

                <div id="div_ninguno"  class="form-group asignacion">
                <label>Departamento</label>
                  <div class="">
                    <label>
                      <input name="asignacion[]" id="checkTodo" value="TODOS" type="checkbox">TODOS</label>
                  </div>
                  <div class="">
                    <label>
                      <input name="asignacion[]" id="ninguno" value="NINGUNO" type="checkbox" checked >Ninguno</label>
                  </div>
                </div>

              </div>

            </div>
          </div>

          <div class="box-footer">
            <button id="btn-guardar" type="button" class="btn btn-primary">Guardar</button>
          </div>
        </form>       
      </div>
    </section>

<section class="content">
        <div class="row" style="font-size:11px;">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Usuarios Parametrizados</h3>
                    </div>
                    <div class="box-body table-responsive no-padding scrollable">
                        <table class="table table-bordered" id="tb_busc_acceso">
                            <thead><tr>
                                <!-- <th>ID Usuario</th> -->
                                <th>Nombre</th>
                                <th>Modulo</th>
                                <th>Acceso</th>
                                <th>Asiganción</th>
                                <th>Habilitado</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        
                    </div>
                    <div class="box-footer" align="right">
                      <!-- <form action="excel.php" method="post" target="_blank" id="frmExport">
                      <a class="btn btn-app botonExcel">
                        <i class="fa fa-file-excel-o"></i> Exportar
                      </a>
                      <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
                      </form> -->
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>

    <?php else:?>
    <section>
      <div class="col-xs-12">
        <div class='restringido' style="text-align: center">
          <span class="label label-primary"><i class="fa fa-warning"></i>  Restringido..!!!  <i class="fa fa-warning"></i></span><br/>
          <label style='color:#1D4FC1'>
                <?php  
                echo "No tienes las credenciales para acceder al contenido"; 
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
  $(document).ready(function(){
    cargaUsuarios();//Datos de usuario de entrada
    
    $("#id_cliente").change(function(){
      cargaUsuarios();
    });

    $(".con-json select").change(function(){
      cargaParametrizados();
    });

    function cargaUsuarios(){
      $(".con-json select").html("");
      var sel_cliente = $("#id_cliente").val();

      // console.log($("#anio").val());
      $.getJSON("consultaUsuarios.php",{id_cliente:sel_cliente},function(objetosretorna){
        $("#error").html("");
        var TamanoArray = objetosretorna.length;
        $(".con-json select").append('<option value="0"> --- SELECCIONE UN USUARIO --- </option>');
        $.each(objetosretorna, function(i,value){
          $(".con-json select").append('<option value="'+value.ID_USER+'">'+value.NOMBRE+" "+value.APELLIDO+'</option>');
        });
      });
      
    };


    function cargaParametrizados(){
      $("#tb_busc_acceso tbody").html("");
        // $("#error").html("<div class='modal1'><div class='center1'> <center> <img src='img/gif-load.gif'> Buscando Informacion...</center></div></div>");
      var sel_user = $(".con-json select").val();
      var sel_modulo = $("#modulos").val();

      // console.log($("#anio").val());
      $.getJSON("consultaParam.php",{id_user:sel_user, modulo:sel_modulo},function(objetosretorna){
        $("#error").html("");
        var TamanoArray = objetosretorna.length;
        $.each(objetosretorna, function(i,accesos){
          
          ASIGNACION = accesos.NASIGNACION+accesos.ASIGNACION;;
          ASIGNACION = ASIGNACION.replace("null", "");
          ASIGNACION = ASIGNACION.replace(/\d/g, "");

          if (accesos.HABILITADO == 'SI') {
            var nuevaFila =
            "<tr>"
            // +"<td>"+accesos.ID_USER+"</td>"
            +"<td>"+accesos.NOMBRE+" "+accesos.APELLIDO+"</td>"
            +"<td>"+accesos.TIPO+"</td>"
            +"<td>"+accesos.ACCESO+"</td>"
            +"<td>"+ASIGNACION+"</td>"
            +"<td><i class='fa fa-check text-green'></i> "+accesos.HABILITADO+"</td>"
            +"</tr>";
          }
          else{
            var nuevaFila =
            "<tr>"
            // +"<td>"+accesos.ID_USER+"</td>"
            +"<td>"+accesos.NOMBRE+" "+accesos.APELLIDO+"</td>"
            +"<td>"+accesos.TIPO+"</td>"
            +"<td>"+accesos.ACCESO+"</td>"
            +"<td>"+ASIGNACION+"</td>"
            +"<td><i class='fa fa-remove text-muted'></i> "+accesos.HABILITADO+"</td>"
            +"</tr>";
          }
          $(nuevaFila).appendTo("#tb_busc_acceso tbody");
        });
        if(TamanoArray==0){
          var nuevaFila =
          "<tr><td colspan=6>Sin parametros registrados</td>"
          +"</tr>";
          $(nuevaFila).appendTo("#tb_busc_acceso tbody");
        }
      });
    };

        $('#acceso2').click(function(){//Radio button NO
          $(".checkbox input[type=checkbox]").prop('checked', false);
          $("#checkTodoDeptos").prop('checked', false);
          $("#checkTodoUsers").prop('checked', false);
          $("#ningunoDeptos").prop('checked', false);
          $("#ningunoUsers").prop('checked', false);
          $(".asignacion").hide();
          $("#ninguno").prop('checked', true);
        });

        $('#acceso1').click(function(){
            
          // console.log($('#modulos').val());
        //limpiando los check al cambiar de seleccion o acceso
        $(".checkbox input[type=checkbox]").prop('checked', false);
        $("#checkTodoDeptos").prop('checked', false);
        $("#checkTodoUsers").prop('checked', false);
        $("#checkTodo").prop('checked', false);
        $("#ningunoDeptos").prop('checked', false);
        $("#ningunoUsers").prop('checked', false);
        $("#ninguno").prop('checked', false);
        
        $(".asignacion").hide();

          if ($('#modulos').val() != '0' && $('#acceso1').is(':checked')) {
            if ($('#modulos').val() == 'solicitud_consultas') {
              $("#div_deptos").show();
              $("#ningunoDeptos").prop('checked', true);
            }
            else{
              if($('#modulos').val() == 'emision_reportes'){
                $("#div_ninguno").show();
                $("#ninguno").prop('checked', true);
              }
              else{
                $("#div_users").show();
                $("#ningunoUsers").prop('checked', true);
              }
            }
          }
          else{
            $(".asignacion").hide();
            $("#ninguno").prop('checked', true);
          }
        });

        //Ocultar/Mostrar lista segun los modulos selecionados
        $(".asignacion").hide();
        $("#modulos").change(function(){
          cargaParametrizados();
          // console.log($('#modulos').val());
          //limpiando los check al cambiar de seleccion o acceso
        $(".checkbox input[type=checkbox]").prop('checked', false);
        $("#checkTodoDeptos").prop('checked', false);
        $("#checkTodoUsers").prop('checked', false);
        $("#ningunoDeptos").prop('checked', false);
        $("#ningunoUsers").prop('checked', false);
        $("#ninguno").prop('checked', false);

        $(".asignacion").hide();

          if ($('#modulos').val() != '0' && $('#acceso1').is(':checked')) {
            if ($('#modulos').val() == 'solicitud_consultas') {
              $("#div_deptos").show();
              $("#ningunoDeptos").prop('checked', true);
            }
            else{
              if($('#modulos').val() == 'emision_reportes'){
                $("#div_ninguno").show();
                $("#ninguno").prop('checked', true);
              }
              else{
                $("#div_users").show();
                $("#ningunoUsers").prop('checked', true);
              }
            }
          }
          else{
            $(".asignacion").hide();
            $("#ninguno").prop('checked', true);
          }
        });

        $("#ningunoDeptos").change(function () {
          if ($(this).is(':checked')) {
              $(".checkbox input[type=checkbox]").prop('checked', false); 
              $("#checkTodoDeptos").prop('checked', false);
          }
        });

        $("#ningunoUsers").change(function () {
          if ($(this).is(':checked')) {
              $(".checkbox input[type=checkbox]").prop('checked', false); 
              $("#checkTodoUsers").prop('checked', false);
          }
        });

        $("#ninguno").change(function () {
          if ($(this).is(':checked')) {
              $(".checkbox input[type=checkbox]").prop('checked', false); 
              $("#checkTodo").prop('checked', false);
          }
        });

        $("#checkTodoDeptos").change(function () {
          if ($(this).is(':checked')) {
              $(".checkbox input[type=checkbox]").prop('checked', false); 
              $("#ningunoDeptos").prop('checked', false);
          }
        });

        $("#checkTodoUsers").change(function () {
          if ($(this).is(':checked')) {
              $(".checkbox input[type=checkbox]").prop('checked', false);
              $("#ningunoUsers").prop('checked', false);
          }
        });

        $("#checkTodo").change(function () {
          if ($(this).is(':checked')) {
              $(".checkbox input[type=checkbox]").prop('checked', false);
              $("#ninguno").prop('checked', false);
          }
        });

        $(".checkbox input[type=checkbox]").change(function () {
          if ($(this).is(':checked')) {
              $("#checkTodoDeptos").prop('checked', false);
              $("#checkTodoUsers").prop('checked', false);
              $("#ningunoDeptos").prop('checked', false);
              $("#ningunoUsers").prop('checked', false);
          }

        });

        $('#btn-guardar').click(function(){
        var url = "controllers/paramController.php";
        $.ajax({                        
           type: "POST",
           url: url,                     
           data: $("#form_datos_usuario").serialize(), 
           success: function(data){
            //  alert(data);
            cargaParametrizados();
             return data;
                // if (result == 'success') {
                //     $.get("msj_correcto.php?msj=Usuario agregado correctamente", function(result){
                //     $("#resp").html(result);
                //     });
                // }
                // else{
                //     if(result == 'vacio'){
                //         $.get("msj_incorrecto.php?msj=Complete los datos faltantes", function(result){
                //             $("#resp").html(result);
                //         });
                //     }
                //     else{
                //         $.get("msj_incorrecto.php?msj="+"No se pudo agregar usuario", function(result){
                //             $("#resp").html(result);
                //         });
                //     }
                // }
            }
       });
    });
  });

</script>