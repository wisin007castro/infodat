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
          <p>Cliente</p>
          <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
        </div>
      </div>
      <!-- search form -->
<!--       <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
          </span>
        </div>
      </form> -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Menú de Navegación</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-search"></i>
            <span>Solicitud</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="reportes.php"><i class="fa fa-circle-o"></i> Solicitud de Documentos</a></li>
            <li><a href="form_sol_dev.php"><i class="fa fa-circle-o"></i> Solicitud de Devolución</a></li>
            <!-- <li><a id="boton" href="#"><i class="fa fa-circle-o"></i> Busqueda</a></li> -->
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
                  <li><a href="estado_pedidos.php"><i class="fa fa-circle-o"></i> Estado de solicitud</a></li>
                  <li><a href="estado_devoluciones.php"><i class="fa fa-circle-o"></i> Estado de Devolución</a></li>
                  <!-- <li><a id="boton" href="#"><i class="fa fa-circle-o"></i> Busqueda</a></li> -->
              </ul>
          </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Reporte</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="form_rep_acceso.php"><i class="fa fa-circle-o"></i> Reporte de Acceso</a></li>
            <li><a href="form_listado_usuario.php"><i class="fa fa-circle-o"></i> Reporte de usuario</a></li>
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
<!--            <li><a href="form_nuevo_usuario.php"><i class="fa fa-circle-o"></i> Agregar Usuarios</a></li>-->
<!--            <li><a href="form_edit_usuario.php"><i class="fa fa-circle-o"></i> Editar Usuarios</a></li>-->
            <li><a href="form_buscar_usuario.php"><i class="fa fa-circle-o"></i> Administración de Usuarios</a></li>
              <li><a href="form_ped_admin.php"><i class="fa fa-circle-o"></i> Administración de Solicitudes</a></li>
              <li><a href="form_dev_admin.php"><i class="fa fa-circle-o"></i> Administración de Devoluciones</a></li>

          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>