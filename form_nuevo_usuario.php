<?php 
  require_once 'header.php';
  require_once 'conexionClass.php';

  $conexion = new MiConexion();
  $clientes = $conexion->clientes();
  // $usuario = $conexion->usuario($_GET['id']);
  $tipousuarios = $conexion->tipoUsuarios();

  $deptos_access = $conexion->dptos_access($usuario_session['ID_CLIENTE']); 
  // var_dump($deptos_access);
  $modulos = $con->modulos($usuario_session['ID_USER']);
  $modulos = array_column($modulos, 'TIPO');//SOLO LA COLUMNA TIPO
  $modulos = array_unique($modulos);//EQUIVALENTE A UN DISTINCT

 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section>
      <div id="resp" class="col-lg-12">
    </section>

    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Registro de usuarios</li>
      </ol>
      <h1>
        Registro de usuarios
      </h1>
    </section>

<?php if(in_array("gestion_usuarios", $modulos) || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>
    <!-- Main content -->
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Nuevo usuario</h3>
        </div> 

        <form method="POST" id="form_datos_usuario">
          <div class="box-body">
            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Cliente</label>
                  <?php if ($usuario_session['TIPO'] == 'IA_ADMIN') {
                  ?>
                  <select class="form-control" name="id_cliente" >
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
                    <select class="form-control" name="id_cliente" >
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
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Nombres *</label>
                  <input style='text-transform:uppercase' type="text" name="nombre" placeholder="" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Apellidos *</label>
                  <input style='text-transform:uppercase' type="text" name="apellido" placeholder="" class="form-control" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Cargo *</label>
                  <input style='text-transform:uppercase' type="text" name="cargo" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="form-group">
                  <label>Dirección *</label>
                  <input style='text-transform:uppercase' type="text" name="direccion" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Telefono *</label>
                  <input type="number" name="telefono" min="2000000" max="4999999" class="form-control" required onkeydown="javascript: return event.keyCode == 69 ? false : true"> <!-- press 'e' = false-->
                </div>
              </div>
              <div class="col-lg-1">
                <div class="form-group">
                  <label>Interno</label>
                  <input type="number" name="interno" min="1" class="form-control" onkeydown="javascript: return event.keyCode == 69 ? false : true"> <!-- press 'e' = false-->
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Celular</label>
                  <input type="number" name="celular" min="6000000" max="7999999" class="form-control" onkeydown="javascript: return event.keyCode == 69 ? false : true"> <!-- press 'e' = false-->
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Correo *</label>
                  <input type="email" name="correo" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Regional</label>
                  <select class="form-control" name="regional">
                    <?php if ($usuario_session['TIPO'] == 'IA_ADMIN') { ?>
                      <option value="LP">LP</option>
                      <option value="SCZ">SCZ</option>
                    <?php
                    }else{?>
                      <option value="<?php echo $usuario_session['REGIONAL'] ?>"><?php echo $usuario_session['REGIONAL']?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Nombre de Usuario *</label>
                  <input style='text-transform:uppercase' type="text" name="user" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Password *</label>
                  <input type="password" name="pass" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Habilitado</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="habilitado" id="habilitado1" value="SI" checked="">
                      Si
                    </label>
                    <label>
                      <input type="radio" name="habilitado" id="habilitado2" value="NO">
                      No
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Tipo de usuario</label>
                  <select class="form-control" name="tipo">
                      <?php if ($usuario_session['TIPO'] == 'IA_ADMIN'): ?>
                        <?php foreach ($tipousuarios as $tusuario) { ?>
                          <option value="<?php echo $tusuario['TIPO'] ?>"><?php echo $tusuario['TIPO'] ?></option>
                        <?php } ?>    
                      <?php else: ?>
                          <option value="CONSULTA">CONSULTA</option>
                          <option value="VISOR">VISOR</option>
                          <option value="ADMIN">ADMIN</option>
                      <?php endif ?>
                    
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
        $('#btn-guardar').click(function(){
        var url = "controllers/addUserController.php";
        $.ajax({                        
           type: "POST",                 
           url: url,                     
           data: $("#form_datos_usuario").serialize(), 
           success: function(result){
                if (result == 'success') {
                    $.get("msj_correcto.php?msj=Usuario agregado correctamente", function(result){
                    $("#resp").html(result);
                    refrescar();
                    });
                }
                else{
                    if(result == 'vacio'){
                      $.get("msj_incorrecto.php?msj=Complete los campos obligatorios (*)", function(result){
                          $("#resp").html(result);
                      });
                    }
                    else{
                      if (result == 'user-repetido') {
                        $.get("msj_incorrecto.php?msj=El nombre de usuario ya fue usado, elija otro", function(result){
                          $("#resp").html(result);
                        });
                      }
                      else{
                        if(result == 'correo-repetido'){
                          $.get("msj_incorrecto.php?msj=El correo ya fue usado, ingrese otro", function(result){
                          $("#resp").html(result);
                        });
                        }
                        else{
                        $.get("msj_incorrecto.php?msj="+"No se pudo agregar usuario", function(result){
                            $("#resp").html(result);
                        });
                      }
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