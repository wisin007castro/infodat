<?php 

  require_once 'header.php';
  require_once 'conexionClass.php';
  require_once 'stringsClass.php';
  
  $conexion = new MiConexion();
  $usuarios = $conexion->usuarios($usuario_session['ID_CLIENTE']);//cliente
  $anios = $conexion->anios();
  // var_dump($usuarios);

  $mistrings = new MiStrings();
  $meses = $mistrings->meses();
 ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reporte de Usuarios
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Administración</a></li>
        <li class="active">Reporte de usuarios</li>
      </ol>
    </section>

    <!-- Main content -->
    <section>
      <!-- Small boxes (Stat box) -->

    </section>
    <!-- /.content -->
    <section class="content">
      <div class="row" style="font-size:11px;">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Reporte</h3>
            </div>
            <div class="box-body table-responsive no-padding">
              <table class="table table-bordered" id="tablajson">
                <thead><tr>
                  <th>#</th>
                  <th>CLIENTE</th>
                  <th>NOMBRE</th>
                  <th>CARGO</th>
                  <th>DIRECCION</th>
                  <th>TELEFONO</th>
                  <!-- <th>INTERNO</th> -->
                  <th>CELULAR</th>
                  <th>CORREO</th>
<!--                   <th>USER</th>
                  <th>PASSWORD</th> 
                  <th>HABILITADO</th>-->
                  <th>TIPO</th>
                  <th>REGIONAL</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($usuarios as $usuario) { ?>
                <tr>
                  <td><?php echo $usuario["ID_USER"]; ?></td> 
                  <td><?php echo $usuario["CLIENTE"]; ?></td>
                  <td><?php echo $usuario["NOMBRE"]; ?></td>
                  <td><?php echo $usuario["CARGO"]; ?></td>
                  <td><?php echo $usuario["DIRECCION"]; ?></td>
                  <td><?php echo $usuario["TELEFONO"]; ?></td>

                  <td><?php echo $usuario["CELULAR"]; ?></td>
                  <td><?php echo $usuario["CORREO"]; ?></td>

                  
                  <td><?php echo $usuario["TIPO"]; ?></td>
                  <td><?php echo $usuario["REGIONAL"]; ?></td>
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


  </div>
  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?php require_once 'footer.php' ?>
 
