<?php
include_once '../modelo/usuario.php';
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

$usuario = new usuario();

if($_POST['funcion']=='verificar'){
  $email=$_POST['email'];
  $dni=$_POST['dni'];
  $usuario->verificar($email, $dni);
}

if($_POST['funcion']=='recuperar'){
  $email=$_POST['email'];
  $dni=$_POST['dni'];
  $codigo=generarCodigo(10);
  $usuario->reemplazar($email, $dni, $codigo);

  //Create an instance; passing `true` enables exceptions
  $mail = new PHPMailer(true);

  try {
      //Server settings
      $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = 'smtp-mail.outlook.com';                     //Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
      $mail->Username   = 'correoOutlook@hotmail.com';                     //SMTP username
      $mail->Password   = 'xxxxx';                               //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
      $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

      //Recipients
      $mail->setFrom('correoOutlook@hotmail.com', 'Sistema administrativo');
      $mail->addAddress($email);
      //$mail->addAddress($email, 'Joe User');     //Add a recipient
      //$mail->addAddress('ellen@example.com');               //Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = 'Restablecer contraseña';
      $mail->Body    = 'La nueva contraseña es: <b>'.$codigo.'</b>';
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->SMTPDebug=false;
      $mail->do_debug=false;
      $mail->send();
      echo 'enviado';
  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}

function generarCodigo($longitud){
  $key='';
  $patron="1234567890abcdefghijklmnopqrstuvwyz";
  $max=strlen($patron)-1;
  for ($i=0; $i < $longitud; $i++) { 
    $key.=$patron[mt_rand(0, $max)];
  }
  return $key;
}
?>