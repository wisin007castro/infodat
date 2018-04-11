<?php 
  require_once 'header.php';
  require_once 'conexionClass.php';
  require_once 'stringsClass.php';

  $conexion = new MiConexion();
  // $clientes = $conexion->clientes();
  // $usuarios = $conexion->usuarios($usuario_session['ID_CLIENTE']);
  $usuarios = $conexion->usuarios_reg($usuario_session['ID_CLIENTE'], $usuario_session['REGIONAL']);

  $tipousuarios = $conexion->tipoUsuarios();

  $deptos_access = $conexion->dptos_access($usuario_session['ID_CLIENTE']); 
  // var_dump($deptos_access);
  $MisStrings = new MiStrings();
  $modulos = $MisStrings->modulos();

 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
      <div id="resp" class="col-lg-12">
    </section>
    <section class="content-header">
      <h1>
        Parametrización de usuarios
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Administración</a></li>
        <li class="active">Parametrización de usuarios</li>
      </ol>
    </section>



    <!-- Main content -->
    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Formulario de accesos</h3>
        </div> 

        <form method="POST" id="form_datos_usuario">
        <input type="hidden" name="cliente" id="cliente" value="<?php echo $usuario_session['ID_CLIENTE']; ?>">
        <input type="hidden" name="regional" id="regional" value="<?php echo $usuario_session['REGIONAL']; ?>">
          <div class="box-body">
            <div class="row">
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Usuario</label>
                  <select class="form-control" name="id_user" >
                    <?php foreach ($usuarios as $us) {  ?>
                    <option value="<?php echo $us['ID_USER'] ?>"><?php echo $us['NOMBRE']." ".$us['APELLIDO']?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Tipo</label>
                  <select id="modulos" class="form-control" name="modulos">
                    <option value="0"> --- Seleccione un modulo --- </option>
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
                  <div class="checkbox">
                    <label>
                      <input name="asignacion[]" type="checkbox" value="<?php echo $value['DEPARTAMENTO']?>"><?php echo $value['DEPARTAMENTO']?></label>
                  </div>
                  <?php endforeach ?>
                  <div class="">
                    <label>
                      <input name="asignacion[]" id="ningunoDeptos" value="ninguno" type="checkbox">Ninguno</label>
                  </div>
                </div>

                <div id="div_users"  class="form-group asignacion">
                  <label>Usuarios a los que tendra acceso</label>
                  <div class="">
                    <label>
                      <input name="asignacion[]" id="checkTodoUsers" type="checkbox" value="TODOS">TODOS</label>
                  </div>
                  <?php foreach ($usuarios as $key => $value): ?>
                  <div class="checkbox">
                    <label>
                      <input name="asignacion[]" type="checkbox" value="<?php echo $value['ID_USER'] ?>"><?php echo $value['NOMBRE']."".$value['APELLIDO']?></label>
                  </div>
                  <?php endforeach ?>
                  <div class="">
                    <label>
                      <input name="asignacion[]" id="ningunoUsers" value="ninguno" type="checkbox">Ninguno</label>
                  </div>
                </div>

                <div id="div_ninguno"  class="form-group asignacion">
                  <div class="">
                    <label>
                      <input name="asignacion[]" id="ninguno" value="ninguno" type="checkbox" checked >Ninguno</label>
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

  </div>
  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?php require_once 'footer.php' ?>

<script type="text/javascript">
  $(document).ready(function(){

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
              $("#div_users").show();
              $("#ningunoUsers").prop('checked', true);
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
              $("#div_users").show();
              $("#ningunoUsers").prop('checked', true);
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