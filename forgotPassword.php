<?php
require_once 'PHPMailer/PHPMailerAutoload.php';
require_once("conexionClass.php");
$conexion = new MiConexion();

$connection = $conexion->conectarBD();

	if (isset($_POST["forgotPass"])) {
		
		$email = $connection->real_escape_string($_POST["email"]);
		
		$data = $connection->query("SELECT ID_USER FROM usuarios WHERE CORREO='$email'");

		if ($data->num_rows > 0) {
			$str = "0123456789qwertzuioplkjhgfdsayxcvbnm";
			$str = str_shuffle($str);
			$str = substr($str, 0, 10);
            $url = "https://www.infoactiva.com.bo/infodat/resetPassword.php?token=$str&email=$email";

				// Datos para Email Solicitudes
				$destinatario = $email; //DATOS DEL CLIENTE
				$asunto = "Password Reset"; 
				$cuerpo = "
				<html>
				<head>
				<title>SOLICITUD DE RESET PASSWORD INFODAT</title> 
				</head> 
				<body> 
				<p> 
                Para restablecer tu password visita : ".$url."
				</p> 
				</body> 
				</html> 
				"; 

				enviar_email($destinatario, $asunto, $cuerpo);

			$connection->query("UPDATE usuarios SET TOKEN='$str' WHERE CORREO='$email'");

			echo "
            <div class='col-xs-12'>
                <div style='text-align: center; background-color: #E9FCFA;padding: 8px;margin-bottom: 10px;border: 1px dotted #006600;'>
                  <span class='label label-success'>Email enviado <i class='fa fa-check'></i></span><br/>
                  <label style='color:#177F6B'>
                    Por favor revise su correo
                  </label> 
                </div>
            </div>";
		} else {
            echo "
            <div class='col-xs-12'>
                <div style='text-align: center; background-color: #FFEDED;padding: 8px;margin-bottom: 10px;border: 1px dotted #FA206A;'>
                  <span class='label label-danger'>Email no enviado <i class='fa fa-check'></i></span><br/>
                  <label style='color:#EC1010'>
                    Por favor revisa tus datos!
                  </label> 
                </div>
            </div>";
		}
    }
    
    function enviar_email($destinatario, $asunto, $cuerpo){
        $mail = new PHPMailer;
    
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.infoactiva.com.bo';  			  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'wcastro@infoactiva.com.bo';        // SMTP username
        $mail->Password = 'wilTemoral123';                    // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to
    
        $mail->setFrom('sistema.consultas@infoactiva.com.bo', 'INFODAT');
        $mail->addAddress($destinatario);               // Name is optional

        $mail->isHTML(true);                                     // Set email format to HTML
    
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo;
        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            // echo 'Message has been sent';
        }
    
    }
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Password Reset</title>

  <!-- CSS de Bootstrap -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/login-css.css">
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:100,300,400,500" > -->
  
</head>
	<body>
<br><br><br><br>
		<form action="forgotPassword.php" method="post" class="form2">
        <h1>Password Reset</h1>
        <span>Ingrese su email para restablecer su Password</span><br><br>
        <div class="form-group">
            <label for="exampleInputEmail1">Email</label>
			<input type="text" name="email" placeholder="Email"><br>
        </div>
			<button type="submit" name="forgotPass" class="mybtn">Solicitar Password</button>            
		</form>

	</body>
</html>