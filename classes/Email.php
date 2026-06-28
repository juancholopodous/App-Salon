<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

class Email {

    public $nombre;
    public $email;
    public $token;

    public function __construct($nombre, $email, $token) {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfiramcion(){

        $mensaje = null;
        
        // Crear el objeto de email        
        $mail = new PHPMailer();
        
        try {
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = 'c4a239edee2f7d';
            $mail->Password = '9c0529260c2625';
    
            $mail->setFrom('cuentas@appsalon.com');
            $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
    
            // Definición de el mensaje HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            
            // Contenido
            $mail->Subject = 'Confirma tu cuenta';
            $contenido = "<html>";
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong></p>";
            $contenido .= "<p>Has creado tu cuenta en App Salon, solo debes confirmarla presionando el siguiente enlcace:</p>";
            $contenido .='<a href="http://localhost:3000/confirmar-cuenta?token=' . $this->token . '">Confirmar Cuenta</a></p>';
            $contenido .="<p>Si tú no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
            $contenido .="</html>";

            $mail->Body    = $contenido;
            $mail->AltBody = 'Hola ' . $this->nombre . ', confirma tu cuenta aquí: http://localhost:3000/confirmar-cuenta?token=' . $this->token;
            
            // Envio de Email
            $mail->send();
            $mensaje = 'Mensaje enviado';

        }catch (Exception $e) {
            $mensaje = "Error: {$mail->ErrorInfo}";
        }
    }
}