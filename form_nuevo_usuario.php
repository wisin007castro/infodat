<?php 
  require_once 'header.php';
  require_once 'conexionClass.php';

  $conexion = new MiConexion();
  $clientes = $conexion->clientes();
  // $usuario = $conexion->usuario($_GET['id']);
  $tipousuarios = $conexion->tipoUsuarios();

  $deptos_access = $conexion->dptos_access($usuario_session['ID_CLIENTE']); 
  // var_dump($deptos_access);

 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
      <div id="resp" class="col-lg-12">
    </section>
    <section class="content-header">
      <h1>
        Registro de usuarios
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Registro de usuarios</li>
      </ol>
    </section>



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
                  <label>Nombres</label>
                  <input type="text" name="nombre" placeholder="" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Apellidos</label>
                  <input type="text" name="apellido" placeholder="" class="form-control" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Cargo</label>
                  <input type="text" name="cargo" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="form-group">
                  <label>Dirección</label>
                  <input type="text" name="direccion" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Telefono</label>
                  <input type="text" name="telefono" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-1">
                <div class="form-group">
                  <label>Interno</label>
                  <input type="text" name="interno" class="form-control">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Celular</label>
                  <input type="text" name="celular" class="form-control">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Correo</label>
                  <input type="text" name="correo" class="form-control" required>
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
                  <label>Nombre de Usuario</label>
                  <input type="text" name="user" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Password</label>
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
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Regional</label>
                  <select class="form-control" name="deptos">
                      <?php foreach ($deptos_access as $key => $value): ?>
                        <option value="<?php echo $value['DEPARTAMENTO'] ?>"><?php echo $value['DEPARTAMENTO']?></option>
                      <?php endforeach ?>

                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="box-footer">
            <button id="btn-guardar" type="submit" class="btn btn-primary">Guardar</button>
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
                    });
                }
                else{
                    if(result == 'vacio'){
                        $.get("msj_incorrecto.php?msj=Complete los datos faltantes", function(result){
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
       });
    });
  });

</script>