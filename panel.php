<?php 
include_once 'conexionClass.php';

$con = new MiConexion();
$modulos = $con->modulos($usuario_session['ID_USER']);


$modulos = array_column($modulos, 'TIPO');//SOLO LA COLUMNA TIPO

$modulos = array_unique($modulos);//EQUIVALENTE A UN DISTINCT

//
$asignacion = $con->asign_auth($usuario_session['ID_USER'], 'autorizacion');//ASIGNADO PARA APROBAR SOLICITUD

$asignacion = array_column($asignacion, 'ASIGNACION');//seleccionando una columna

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
        // var_dump(($asignacion));
        // if($usuario_session['TIPO'] == 'IA_CONSULTA' || $usuario_session['TIPO'] == 'VISOR'){
         ?>
        <?php if(in_array("solicitud_consultas", $modulos)
         || in_array("solicitud_devoluciones", $modulos)
         || $usuario_session['TIPO'] == 'IA_ADMIN'
         || in_array($usuario_session['ID_USER'], $asignacion) ):?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-search"></i>
            <span>Requerimientos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if(in_array($usuario_session['ID_USER'], $asignacion)): ?>
            <li><a href="form_consulta_auth.php"><i class="fa fa-circle-o"></i>Aprobación Consultas </a></li>
            <?php endif ?>
            <?php if(in_array("solicitud_consultas", $modulos) || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>
            <li><a href="reportes.php"><i class="fa fa-circle-o"></i> Consultas </a></li>
            <?php endif ?>
            <?php if(in_array("solicitud_devoluciones", $modulos) || $usuario_session['TIPO'] == 'IA_ADMIN'): ?>   
            <li><a href="form_sol_dev.php"><i class="fa fa-circle-o"></i> Devoluciones </a></li>
            <?php endif ?>
          </ul>
        </li>
        <?php endif ?>
        <?php if(in_array("estado_consultas", $modulos)
         || in_array("estado_devoluciones", $modulos)
         || $usuario_session['TIPO'] == 'IA_ADMIN'):?>
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
        <?php endif ?>
        <?php if(in_array("emision_reportes", $modulos) || $usuario_session['TIPO'] == 'IA_ADMIN'):?>
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
            <li><a href="form_reporte_con_dev.php"><i class="fa fa-circle-o"></i> Reporte de Solicitudes</a></li>
            <?php endif ?>
          </ul>
        </li>
        <?php endif ?>
        <?php if(in_array("gestion_usuarios", $modulos) || in_array("parametricas", $modulos) || $usuario_session['TIPO'] == 'IA_ADMIN'):?>
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
        <?php endif ?>
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