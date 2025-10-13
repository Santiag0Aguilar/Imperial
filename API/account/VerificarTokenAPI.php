<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/config/db.php';
$db = db();

$input = json_decode(file_get_contents("php://input"), true);

$email = filter_var($input["email"] ?? "", FILTER_SANITIZE_EMAIL);
$token = filter_var($input["token"] ?? "", FILTER_SANITIZE_STRING);

if (empty($email) || empty($token)) {
    echo json_encode(["success" => false, "errors" => ["Correo y token son obligatorios."]]);
    exit;
}

$stmt = $db->prepare("
    SELECT id 
    FROM usuarios 
    WHERE CorreoAsociado = ? 
      AND token_recuperacion = ?
      AND token_generado_en >= (NOW() - INTERVAL 5 MINUTE)
");
$stmt->execute([$email, $token]);

if ($stmt->rowCount() === 0) {
    echo json_encode(["success" => false, "errors" => ["Token inválido, expirado o correo incorrecto."]]);
    exit;
}

$stmt = $db->prepare("
    SELECT id, CorreoAsociado, rol 
    FROM usuarios 
    WHERE CorreoAsociado = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
$_SESSION['idUsuario'] = $usuario['id'];
$_SESSION['Nombre_Usuario'] = $usuario['CorreoAsociado'];
$_SESSION['Rol_Usuario'] = $usuario['rol'];

echo json_encode(["success" => true]);
