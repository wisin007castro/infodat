<?php 
  require_once 'header.php';
  require_once 'conexionClass.php';

  $conexion = new MiConexion();
  $clientes = $conexion->clientes();
  $tipousuarios = $conexion->tipoUsuarios();
 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registro de usuarios
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Registro de usuarios</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Nuevo usuario</h3>
        </div> 

        <form>
          <div class="box-body">
            <div class="row">
              <div class="col-lg-4">
              <div class="form-group">
                <label>Cliente</label>
                <select class="form-control">
                  <?php foreach ($clientes as $cli) {?>
                  <option value="<?php echo $cli['ID_CLIENTE'] ?>"><?php echo $cli['CLIENTE'] ?></option>
                  <?php } ?>
                </select>
              </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Nombres</label>
                  <input type="text" name="" placeholder="" class="form-control">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Apellidos</label>
                  <input type="text" name="" placeholder="" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Cargo</label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
              <div class="col-lg-8">
                <div class="form-group">
                  <label>Dirección</label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Telefono</label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
              <div class="col-lg-1">
                <div class="form-group">
                  <label>Interno</label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Celular</label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
              <div class="col-lg-7">
                <div class="form-group">
                  <label>Correo</label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Nombre de Usuario</label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" name="" class="form-control">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Habilitado</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                      Si
                    </label>
                    <label>
                      <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                      No
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Tipo de usuario</label>
                  <select class="form-control">
                    <?php foreach ($tipousuarios as $tusuario) { ?>
                    <option value="<?php echo $tusuario['TIPO'] ?>"><?php echo $tusuario['TIPO'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Regional</label>
                  <select class="form-control">
                    <option value="LP">LP</option>
                    <option value="SCZ">SCZ</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>       
      </div>
    </section>

  </div>
  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?php require_once 'footer.php' ?>