<?php
session_start();
header("Content-Type: application/json");
require_once __DIR__ . "/../../includes/config/db.php";

$db = db(); // Conexión

$input = json_decode(file_get_contents("php://input"), true);
$IdUsuario = filter_var($input["idUsuario"] ?? "", FILTER_SANITIZE_NUMBER_INT);
$Password = filter_var($input["password"] ?? "", FILTER_SANITIZE_STRING);
$PasswordConfirm = filter_var($input["passwordConfirm"] ?? "", FILTER_SANITIZE_STRING);


if (empty($IdUsuario) || empty($Password) || empty($PasswordConfirm)) {
    echo json_encode(["success" => false, "errors" => ["Todos los campos son obligatorios."]]);
    exit;
}


if ($Password !== $PasswordConfirm) {
    echo json_encode(["success" => false, "errors" => ["Las contraseñas no coinciden."]]);
    exit;
}

if (
    !$Password || strlen($Password) < 8 ||
    !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $Password) ||
    !preg_match('/[A-Z]/', $Password) ||
    !preg_match('/[a-z]/', $Password) ||
    !preg_match('/[0-9]/', $Password)
) {
    echo json_decode("La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula, un número y un carácter especial.");
    exit;
}

$PasswordHash = password_hash($Password, PASSWORD_DEFAULT);

$stmt = $db->prepare("UPDATE usuarios SET contraseña = ? WHERE id = ?");
$stmt->execute([$PasswordHash, $IdUsuario]);
if ($stmt->rowCount() === 0) {
    echo json_encode(["success" => false, "errors" => ["No se pudo actualizar la contraseña."]]);
    exit;
}
echo json_encode(["success" => true, "message" => "Contraseña actualizada correctamente."]);
$stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$IdUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
if ($usuario) {
    $_SESSION['idUsuario'] = $usuario['id'];
    $_SESSION['Nombre_Usuario'] = $usuario['CorreoAsociado'];
    $_SESSION['Rol_Usuario'] = $usuario['rol'];
} else {
    echo json_encode(["success" => false, "errors" => ["Usuario no encontrado."]]);
    exit;
}
