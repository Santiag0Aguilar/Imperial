<?php
session_start();
header("Content-Type: application/json");
// recibir los datos
$input = json_decode(file_get_contents("php://input"), true);


if (!isset($input["email"]) || !isset($input["password"])) {
    echo json_encode(["success" => false, "errors" => ["Error al iniciar sesión."]]);
    exit;
}
require_once __DIR__ . "/../../includes/config/db.php";

$db = db(); // Conexión
$Errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $Correo_Asociado = filter_var($input["email"] ?? "", FILTER_SANITIZE_EMAIL);
    $Password_Asociado = htmlspecialchars($input["password"] ?? "", ENT_QUOTES, 'UTF-8');
    $csrf_token = $input["csrf_token"] ?? "";

    // Validar CSRF
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        echo json_encode(["success" => false, "errors" => ["Error al iniciar sesión. Recarga la página e inténtalo nuevamente."]]);
        exit;
    }
    unset($_SESSION['csrf_token']); // Eliminar token tras uso

    // Eliminar intentos viejos
    $db->exec("DELETE FROM intentos_login WHERE fecha < NOW() - INTERVAL 15 MINUTE");

    // Revisar si está bloqueado
    $intentosFallidosStmt = $db->prepare("SELECT COUNT(*) FROM intentos_login WHERE correo = ? AND fecha > NOW() - INTERVAL 15 MINUTE");
    $intentosFallidosStmt->execute([$Correo_Asociado]);
    $cantidadIntentos = $intentosFallidosStmt->fetchColumn();

    if ($cantidadIntentos >= 5) {
        echo json_encode(["success" => false, "errors" => ["Demasiados intentos fallidos. Intenta más tarde."]]);
        exit;
    }

    // Validar email
    if (!$Correo_Asociado || !filter_var($Correo_Asociado, FILTER_VALIDATE_EMAIL)) {
        $Errores[] = "Por favor, ingresa un correo electrónico válido.";
    }

    if (empty($Errores)) {
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE CorreoAsociado = ?");
        $stmt->execute([$Correo_Asociado]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario || !password_verify($Password_Asociado, $usuario['contraseña'])) {
            $db->prepare("INSERT INTO intentos_login (correo, fecha) VALUES (?, NOW())")->execute([$Correo_Asociado]);

            if ($cantidadIntentos + 1 >= 5) {
                $Errores[] = "Demasiados intentos fallidos. Por favor, inténtalo de nuevo más tarde.";
            } else {
                $Errores[] = "Credenciales incorrectas.";
            }
        } else {
            // Login exitoso
            $db->prepare("DELETE FROM intentos_login WHERE correo = ?")->execute([$Correo_Asociado]);

            $_SESSION['idUsuario'] = $usuario['id'];
            $_SESSION['Nombre_Usuario'] = $usuario['CorreoAsociado'];
            $_SESSION['Rol_Usuario'] = $usuario['rol'];

            echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso."]);
            exit;
        }
    }

    echo json_encode(["success" => false, "errors" => $Errores]);
}
