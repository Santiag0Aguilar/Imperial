<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP de Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'santiago.aguilar.dev@gmail.com'; // Cambia esto por tu correo de Gmail
    $mail->Password = 'ypke ouhv otvw mlls'; // La contraseña de aplicación generada
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Remitente y destinatario
    $mail->setFrom('santiago.aguilar.dev@gmail.com', 'Santiago Aguilar'); // Cambia esto por tu correo y nombre
    $mail->addAddress('aguilarsantiagoguil2007@gmail.com'); // A quién va el correo

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Prueba desde PHPMailer';
    $mail->Body    = 'Hola, este es un correo de <strong>prueba</strong> enviado desde una app PHP usando Gmail.';
    $mail->AltBody = 'Hola, este es un correo de prueba enviado desde una app PHP usando Gmail.';

    $mail->send();
    echo 'Correo enviado correctamente';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
