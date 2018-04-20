<?php 
include_once 'conexionClass.php';

$con = new MiConexion();
$modulos = $con->modulos($usuario_session['ID_USER']);


$modulos = array_column($modulos, 'TIPO');

$modulos = array_unique($modulos);

?>

  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo "USER: ".$usuario_session['USER']; ?></p>
          <small><?php echo "TIPO: ".$usuario_session['TIPO']; ?></small>
          <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
        </div>
      </div>

      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Menú de Navegación</li>

        <?php 

        // if($usuario_session['TIPO'] == 'IA_CONSULTA' || $usuario_session['TIPO'] == 'VISOR'){
         ?>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-search"></i>
            <span>Requerimientos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          
            <?php if(in_array("solicitud_consultas", $modulos) || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>
            <li><a href="reportes.php"><i class="fa fa-circle-o"></i> Consultas </a></li>
            <?php endif ?>
            <?php if(in_array("solicitud_devoluciones", $modulos) || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>   
            <li><a href="form_sol_dev.php"><i class="fa fa-circle-o"></i> Devoluciones </a></li>
            <?php endif ?>
          </ul>
        </li>
          <li class="treeview">
              <a href="#">
                  <i class="fa fa-search"></i>
                  <span>Estado</span>
                  <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
              </a>
              <ul class="treeview-menu">
                  <?php if(in_array("estado_consultas", $modulos) || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>
                  <li><a href="estado_pedidos.php"><i class="fa fa-circle-o"></i> Consultas</a></li>
                  <?php endif ?>
                  <?php if(in_array("estado_devoluciones", $modulos) || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>
                  <li><a href="estado_devoluciones.php"><i class="fa fa-circle-o"></i> Devoluciones</a></li>
                  <?php endif ?>
              </ul>
          </li>

          <?php //} ?>

        <?php 
        // if($usuario_session['TIPO'] == 'IA_ADMIN' || $usuario_session['TIPO'] == 'ADMIN'){
         ?>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if(in_array("emision_reportes", $modulos)  || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>
            <li><a href="form_reporte_con_dev.php"><i class="fa fa-circle-o"></i> Reporte de Acceso</a></li>
            <?php endif ?>
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-pencil-square-o"></i>
            <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <?php if(in_array("gestion_usuarios", $modulos) || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>
            <li><a href="form_buscar_usuario.php"><i class="fa fa-circle-o"></i> Gestión de Usuarios </a></li>
            <?php endif ?>
            <?php if(in_array("parametricas", $modulos) || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>
            <li><a href="form_param_user.php"><i class="fa fa-circle-o"></i> Parametrización </a></li>
            <?php endif ?>
<!--            <li><a href="form_edit_usuario.php"><i class="fa fa-circle-o"></i> Editar Usuarios</a></li>-->
<!--             <li><a href="form_buscar_usuario.php"><i class="fa fa-circle-o"></i> Administración de Usuarios</a></li> -->

          </ul>
        </li>
        <?php 
          // }
         ?>

        <?php 
        if($usuario_session['TIPO'] == 'ALMACEN'){
         ?>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-pencil-square-o"></i>
            <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             
              <li><a href="form_ped_admin.php"><i class="fa fa-circle-o"></i> Solicitudes</a></li>
              <li><a href="form_dev_admin.php"><i class="fa fa-circle-o"></i> Devoluciones</a></li>

          </ul>
        </li>
        <?php 
          }
         ?>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>