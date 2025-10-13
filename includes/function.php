<?php
require 'app.php';

require_once realpath(__DIR__ . '/../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function ValidarAdmin(): bool
{
    session_start();

    $Auth = $_SESSION["Rol_Usuario"]; // Obtener el rol del usuario desde la sesión


    // Verificar si la sesión está iniciada y si el rol es "admin"
    if ($Auth == "admin") {
        // Si la sesión está iniciada y el rol es "admin", retorna true
        return true;
    }
    return false;
}

function incluirTamplate($nombre, $Vars = [])
{
    extract($Vars); // Extrae las variables del array $Vars para que estén disponibles en el template
    include TEMPLATES_URL . "/${nombre}.php";
};

function ValidarUser(): bool
{
    session_start();

    $Auth = $_SESSION["Rol_Usuario"]; // Obtener el rol del usuario desde la sesión

    // verificar si la sesion esta iniciada y existe el rol de usuario
    if (isset($_SESSION["idUsuario"]) && isset($Auth)) {
        // Si la sesión está iniciada 
        return true;
    }
    return false;
}


function enviarCorreoPrueba($destinatarioEmail, $token)
{
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'santiago.aguilar.dev@gmail.com'; // Correo fijo del remitente
        $mail->Password = 'ypke ouhv otvw mlls'; // Contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente y destinatario
        $mail->setFrom('santiago.aguilar.dev@gmail.com', 'Santiago Aguilar');
        $mail->addAddress($destinatarioEmail);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Prueba desde PHPMailer';
        $mail->Body    = 'Hola, este es un correo de <strong>prueba</strong> enviado desde una app PHP usando Gmail.' . ' Tu token de recuperación es: ' . $token;
        $mail->AltBody = 'Hola, este es un correo de prueba enviado desde una app PHP usando Gmail para enviar.' . ' Tu token de recuperación es: ' . $token;

        $mail->send();
    } catch (Exception $e) {

        echo json_decode("Error al enviar el correo a $destinatarioEmail: {$mail->ErrorInfo}");
    }
}

function ValidarCarrito()
{
    session_start();
    if (!isset($_SESSION['idUsuario'])) {
        header('Location: /Perfumeria/Public/login.php');
        exit;
    }

    $idUsuario = $_SESSION['idUsuario'];
    $db = db();

    try {
        // Verificar si el usuario tiene un pedido pendiente
        $stmt = $db->prepare("SELECT COUNT(*) FROM pedidos WHERE Usuarios_id = ? AND estado = 'pendiente'");
        $stmt->execute([$idUsuario]);
        $existePedidoPendiente = $stmt->fetchColumn() > 0;

        if (!$existePedidoPendiente) {
            header('Location: /Perfumeria/Public/carrito.php');
            exit;
        }
    } catch (PDOException $e) {
        die("Error al verificar el carrito: " . $e->getMessage());
    }
}
