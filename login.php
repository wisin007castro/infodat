<?php 
 include "include/class.mysqldb.php";
 include "include/config.inc.php";
 unset($_SESSION['EmpUser']);
 if(isset($_REQUEST['user'])){
     $user = $_REQUEST['user'];
     // $pass = $_REQUEST['pass'];
     $pass = md5($_REQUEST['pass']);//con hash
     $conn = new mysqldb();
     $sql="SELECT * FROM usuarios where USER = '".$user."' and PASS='".$pass."'";
     $query = $conn ->query($sql);
     $data = $conn->fetch($query);
     
     if($conn->num_rows()==0){
         echo "<script language='javascript'>alert('Nombre de Usuario o Password incorrecto..!')</script>";
     }else{
        $_SESSION['loggedin'] = true;
        $_SESSION['EmpUser']=$data->USER;
        $_SESSION['EmpId']=$data->ID_USER;
        $_SESSION['EmpID']=$data->ID_USER;
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start'] + (5);

        unset($_SESSION['APIUser']);
        echo "<meta http-equiv='refresh' content='0;url=index.php' />";
        exit(0);
     }
 }
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  <!-- CSS de Bootstrap -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/login-css.css">
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:100,300,400,500" > -->
  
</head>


<body class="bodylogin">      
  <div class="container" > 
          
    <div class="col-sm-12 cabecera-login" >
      <a class="mybtn-social pull-right" href="">Register</a>
      <a class="mybtn-social pull-right" href="">Login</a>            
    </div>
                
    <div class="row">
            <div class="col-sm-12">
            </div>

            <div class="col-sm-offset-3 col-sm-6">
                <form  role="form" action="" method="post" name ="login" class="formlogin">
                  <input type="hidden" name="_token" value="">
                    <h1>Login</h1>
                  <div class="form-group">
                    <label for="exampleInputEmail1">User</label>
                    <input type="text" class="form-control" value="" placeholder=""  name="user"/>
                    
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" placeholder="" name="pass"/>
                    
                  </div>
                  <br>
                    <button type="submit" class="mybtn"> Ingresar </button>
                </form>

            </div><!-- /.login-box-body -->

        </div><!-- /.login-box -->                

  </div>

    <!-- Enlazamos el js de Bootstrap, y otros plugins que usemos siempre al final antes de cerrar el body -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="dist/js/scriptlogin.js"></script>
  

</body>

</html>



