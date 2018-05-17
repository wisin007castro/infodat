<?php
session_start();
ob_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

    include("conexionClass.php");
    $con = new MiConexion();
    $conexion = $con->conectarBD();
    $usuario = mysqli_query($conexion, "SELECT * FROM usuarios AS u 
                            JOIN clientes AS c ON u.ID_CLIENTE = c.ID_CLIENTE
                            WHERE ID_USER='".$_SESSION['EmpID']."' 
                          ");
    $usuario_session = mysqli_fetch_array($usuario);

} else {

   echo "<script language='javascript'>alert('Debes iniciar sesion')</script>";
   echo "<meta http-equiv='refresh' content='0;url=login.php' />";

   exit(0);
}

// $now = time();
// if($now > $_SESSION['expire']) {
//   echo "<script language='javascript'>alert('Debes iniciar sesion')</script>";
//   echo "<meta http-equiv='refresh' content='0;url=login.php' />";
//   session_destroy();
// }


?>

<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Infoactiva</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
    <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <link rel="stylesheet" href="dist/css/estilos.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-red-light sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IA</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Infodat</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
          <input type="hidden" name="id_user" id="id_user" value="<?php echo $usuario_session['ID_USER']; ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $usuario_session['NOMBRE']." ".$usuario_session['APELLIDO']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  Cliente <?php echo $usuario_session['CLIENTE']; ?> <br> Usuario: <?php echo $usuario_session['NOMBRE']." ".$usuario_session['APELLIDO']; ?>
                  <!-- <small>Member since Nov. 2012</small> -->
                </p>
              </li>

              <li class="user-footer">
                <div class="pull-left">
                  <a onclick="editar()" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Salir</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  

<?php 
  include "panel.php"; 
  // include "conexionClass.php";
  
  // require_once 'footer.php';
?>
<div id="perfil"></div>
<script  type="text/javascript">
  function editar(){//CON CONFIRMACION DESPACHADO
  id_user = $("#id_user").val();
  $.ajax({
    type:'POST',
    url:"form_edit_perfil.php",
    data:{'id_user':id_user},
      success: function(data){
        $("#contenidos").html(data);
      }
    }
  )};
</script>