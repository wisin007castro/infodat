<?php 
  require_once 'header.php';
  require_once 'conexionClass.php';
  require_once 'stringsClass.php';

  $conexion = new MiConexion();

  $clientes = $conexion->clientes();
  $tipousuarios = $conexion->tipoUsuarios();
  $usuario = $conexion->usuario($_GET['id']);
  // var_dump($usuario);

  $strings = new MiStrings();
  $estadoUser = $strings->estadoUsuario();
  $regional = $strings->regional();

  $modulos = $conexion->modulos($usuario_session['ID_USER']);
  $modulos = array_column($modulos, 'TIPO');//SOLO LA COLUMNA TIPO
  $modulos = array_unique($modulos);//EQUIVALENTE A UN DISTINCT
 ?>



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section>
    <div id="resp" class="col-lg-12"></div>
    </section>
    <!-- MOSTRAR SI ES CLIENTE -->

    <section class="content-header">
    
      <h1>
        Registro de usuarios
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Registro de usuarios</li>
      </ol>
    </section>


<?php if ($usuario[0]['ID_CLIENTE'] == $usuario_session['ID_CLIENTE'] && in_array("gestion_usuarios", $modulos) ) { ?>
    <!-- Main content -->
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Nuevo usuario</h3>
        </div> 

        <form method="POST" id="form_datos_usuario">
          <input type="hidden" name="id_user" id="id_user" value="<?php echo $_GET['id']; ?>">
          <div class="box-body">
            <div class="row">
              <div class="col-lg-4">
              <div class="form-group">
                <label>Cliente</label>
                <select class="form-control" name="id_cliente" >
                  <?php foreach ($clientes as $cli) {
                    if($usuario[0]['ID_CLIENTE'] == $cli['ID_CLIENTE']){
                    ?>
                  <option selected="<?php echo $usuario[0]['ID_CLIENTE'] ?>" value="<?php echo $cli['ID_CLIENTE'] ?>"><?php echo $cli['CLIENTE']?></option>
                  <?php 
                  }
                } ?>
                </select>
              </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Nombres *</label>
                  <input style='text-transform:uppercase' type="text" name="nombre" value="<?php echo $usuario[0]['NOMBRE'] ?>" placeholder="" class="form-control">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Apellidos *</label>
                  <input style='text-transform:uppercase' type="text" name="apellido" value="<?php echo $usuario[0]['APELLIDO'] ?>" placeholder="" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Cargo *</label>
                  <input style='text-transform:uppercase' type="text" name="cargo" value="<?php echo $usuario[0]['CARGO'] ?>" class="form-control">
                </div>
              </div>
              <div class="col-lg-8">
                <div class="form-group">
                  <label>Dirección *</label>
                  <input style='text-transform:uppercase' type="text" name="direccion" value="<?php echo $usuario[0]['DIRECCION'] ?>" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Telefono *</label>
                  <input type="number" name="telefono" min="2000000" max="4999999" value="<?php echo $usuario[0]['TELEFONO'] ?>" class="form-control" required onkeydown="javascript: return event.keyCode == 69 ? false : true"> <!-- press 'e' = false-->
                </div>
              </div>
              <div class="col-lg-1">
                <div class="form-group">
                  <label>Interno</label>
                  <input type="number" name="interno" min="1" value="<?php echo $usuario[0]['INTERNO'] ?>" class="form-control" onkeydown="javascript: return event.keyCode == 69 ? false : true"> <!-- press 'e' = false-->
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Celular</label>
                  <input type="number" name="celular" min="6000000" max="7999999" value="<?php echo $usuario[0]['CELULAR'] ?>" class="form-control" onkeydown="javascript: return event.keyCode == 69 ? false : true"> <!-- press 'e' = false-->
                </div>
              </div>
              <div class="col-lg-7">
                <div class="form-group">
                  <label>Correo *</label>
                  <input type="text" name="correo" value="<?php echo $usuario[0]['CORREO'] ?>" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Nombre de Usuario *</label>
                  <input style='text-transform:uppercase' type="text" name="user" value="<?php echo $usuario[0]['USER'] ?>" class="form-control" disabled>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Password *</label>
                  <input type="password" name="pass" value="<?php echo $usuario[0]['PASS'] ?>" class="form-control">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Habilitado</label>
                  <select class="form-control" name="habilitado">
                    <?php foreach ($estadoUser as $eu) { 
                        if($usuario[0]['HABILITADO'] == $eu){
                        ?>
                        <option selected="<?php echo $usuario[0]['HABILITADO'] ?>" value="<?php echo $eu ?>"><?php echo $eu ?></option>
                        <?php
                        }
                        else{
                        ?>
                        <option value="<?php echo $eu ?>"><?php echo $eu ?></option>
                        <?php
                        }
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Tipo de usuario</label>
                  <select class="form-control" name="tipo">
                    <?php if ($usuario_session['TIPO'] == 'IA_ADMIN'): ?>
                      <?php foreach ($tipousuarios as $tusuario) { 
                          if($usuario[0]['TIPO'] == $tusuario['TIPO']){
                          ?>
                          <option selected="<?php echo $usuario[0]['TIPO'] ?>" value="<?php echo $tusuario['TIPO'] ?>"><?php echo $tusuario['TIPO'] ?></option>
                          <?php
                          }
                          else{
                          ?>
                          <option value="<?php echo $tusuario['TIPO'] ?>"><?php echo $tusuario['TIPO'] ?></option>
                          <?php
                          }
                      } ?>
                      <?php else: ?>
                          <?php foreach ($tipousuarios as $tusuario) { 
                          if($usuario[0]['TIPO'] == $tusuario['TIPO']){
                          ?>
                          <option selected="<?php echo $usuario[0]['TIPO'] ?>" value="<?php echo $tusuario['TIPO'] ?>">* <?php echo $tusuario['TIPO'] ?></option>
                          <?php
                          }
                      } ?>
                          <option value="CONSULTA">CONSULTA</option>
                          <option value="VISOR">VISOR</option>
                          <option value="ADMIN">ADMIN</option>
                      <?php endif ?>
                    
                  </select>

                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Regional</label>
                  <select class="form-control" name="regional">
                    <?php foreach ($regional as $reg) { 
                        if($usuario[0]['REGIONAL'] == $reg){
                        ?>
                        <option selected="<?php echo $usuario[0]['TIPO'] ?>" value="<?php echo $reg ?>"><?php echo $reg ?></option>
                        <?php
                        }
                        else{
                        ?>
                        <option value="<?php echo $reg ?>"><?php echo $reg ?></option>
                        <?php
                        }
                    } ?>
                  </select>
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
<?php } 
else{
?>

<section>
  <div class="col-xs-12">
    <div class='restringido' style="text-align: center">
      <span class="label label-primary"><i class="fa fa-warning"></i>  Restringido..!!!  <i class="fa fa-warning"></i></span><br/>
      <label style='color:#1D4FC1'>
            <?php  
            echo "No tienes Acceso a este contenido"; 
            // echo "Succefully";
            ?> 
      </label> 
    </div>
  </div> 
</section>

<?php } ?>
</div>
<!-- ./wrapper -->


<!-- FIN MOSTRAR -->

<?php require_once 'footer.php';
// var_dump($_POST);
 ?>

<script type="text/javascript">
  $(document).ready(function(){
        $('#btn-guardar').click(function(){
        var url = "controllers/editUserController.php";
        $.ajax({                        
           type: "POST",                 
           url: url,                     
           data: $("#form_datos_usuario").serialize(), 
           success: function(result){
                if (result == 'success') {
                  $.get("msj_correcto.php?msj=Usuario actualizado correctamente", function(result){
                    $("#resp").html(result);
                    refrescar();
                  });
                }
                else{
                  if(result == 'vacio'){
                      $.get("msj_incorrecto.php?msj=Complete los datos faltantes", function(result){
                        $("#resp").html(result);
                      });
                  }
                  else{
                    if(result == 'repetido'){
                      $.get("msj_incorrecto.php?msj=Correo repetido", function(result){
                        $("#resp").html(result);
                      });
                    }
                    else{
                    $.get("msj_incorrecto.php?msj="+"No se pudo actualizar usuario", function(result){
                      $("#resp").html(result);
                    });
                    }
                  }
                }
            }
       });
    });
  });

  function refrescar(){
    timout=setTimeout(function(){
        location.reload();
    },2000,"JavaScript");//2 segundos
  }

</script>