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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:100,300,400,500" >
  
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
                <form class="formlogin" action="" method="post">
                  <input type="hidden" name="_token" value="">
                    <h1>Login</h1>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" value="" placeholder="" name="email"/>
                    
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" placeholder="" name="password"/>
                    
                  </div>
                  <br>
                    <button type="submit" class="mybtn"> Ingresar </button>
                </form>

            </div><!-- /.login-box-body -->

        </div><!-- /.login-box -->                

  </div>

    <!-- Enlazamos el js de Bootstrap, y otros plugins que usemos siempre al final antes de cerrar el body -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>



