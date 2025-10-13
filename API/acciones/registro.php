<?php
session_start();
header("Content-Type: application/json");
// recibir los datos
$input = json_decode(file_get_contents("php://input"), true);

if ($input === null) {
    echo json_encode(["success" => false, "errors" => ["Entrada inválida o vacía."]]);
    exit;
}

if (
    !isset($input["email"]) || !isset($input["password"])
    || !isset($input["csrf_token"]) || !isset($input["rol"]) || !isset($input["telefono"]) || !isset($input["nombre"])
) {
    echo json_encode(["success" => false, "errors" => ["Error al registrar."]]);
    exit;
}

require_once __DIR__ . "/../../includes/config/db.php";  // Conexion a la base de datos

$db = db(); // Llamada a la funcion de conexion a la base de datos
$Errores = [];
$roles_validos = ["admin", "cliente"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $Nombre_Usuario = filter_var($input["nombre"] ?? null, FILTER_SANITIZE_STRING);
    $Numero_Asociado = filter_var($input["telefono"] ?? null, FILTER_SANITIZE_NUMBER_INT);
    $Correo_Asociado = filter_var($input["email"] ?? null, FILTER_SANITIZE_EMAIL);
    $Password_Asociado = htmlspecialchars($input["password"] ?? "", ENT_QUOTES, 'UTF-8');
    $Rol = htmlspecialchars($input["rol"] ?? null);
    $Hash_Password = password_hash($Password_Asociado, PASSWORD_DEFAULT); // Encriptamos la contraseña
    $csrf_token = $input['csrf_token'] ?? null;

    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        echo json_encode(["success" => false, "errors" => ["Error al iniciar sesión. Recarga la página e inténtalo nuevamente."]]);
        exit;
    }
    unset($_SESSION['csrf_token']); // Eliminar token tras uso

    // Validaciones
    if (!in_array($Rol, $roles_validos)) {
        $Errores[] = "Rol no válido.";
    }
    if (!$Nombre_Usuario || strlen($Nombre_Usuario) < 4 || strlen($Nombre_Usuario) > 25) {
        $Errores[] = "El nombre de usuario debe tener entre 4 y 25 caracteres.";
    }
    if (!$Numero_Asociado || strlen($Numero_Asociado) != 10 || !ctype_digit($Numero_Asociado)) {
        $Errores[] = "El número de teléfono debe tener 10 dígitos.";
    }
    if (!$Correo_Asociado || !filter_var($Correo_Asociado, FILTER_VALIDATE_EMAIL)) {
        $Errores[] = "Por favor, ingresa un correo electrónico válido.";
    }
    if (
        !$Password_Asociado || strlen($Password_Asociado) < 8 ||
        !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $Password_Asociado) ||
        !preg_match('/[A-Z]/', $Password_Asociado) ||
        !preg_match('/[a-z]/', $Password_Asociado) ||
        !preg_match('/[0-9]/', $Password_Asociado)
    ) {
        $Errores[] = "La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula, un número y un carácter especial.";
    }

    if (empty($Errores)) {
        // Verificar si el correo ya existe
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE CorreoAsociado = ?");
        $stmt->execute([$Correo_Asociado]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $Errores[] = "El correo electrónico ya está registrado. Por favor, utiliza otro.";
        }
        // Verificar si el numero de telefono ya existe
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE NumeroAsociado = ?");
        $stmt->execute([$Numero_Asociado]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $Errores[] = "El numero de telefono ya está registrado. Por favor, utiliza otro.";
        }
        // Verificar si el nombre de usuario ya existe  
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE NombreUsuario = ?");
        $stmt->execute([$Nombre_Usuario]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $Errores[] = "El nombre de usuario ya está registrado. Por favor, utiliza otro.";
        }

        if (empty($Errores)) {
            // Insertar el nuevo usuario en la base de datos
            try {
                $stmt = $db->prepare("INSERT INTO usuarios (NombreUsuario, NumeroAsociado, CorreoAsociado, contraseña, rol) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$Nombre_Usuario, $Numero_Asociado, $Correo_Asociado, $Hash_Password, $Rol]);
            } catch (PDOException $e) {
                error_log("Error en la base de datos: " . $e->getMessage(), 3, "../logs/error.log");
                $Errores[] = "Ocurrió un error al registrar el usuario. Por favor, intenta nuevamente.";
            }
            if ($stmt->rowCount() > 0) {
                $idUsuario = $db->lastInsertId(); // Obtener el ID del nuevo usuario

                $_SESSION["idUsuario"] = $idUsuario; // Almacenar el ID en la sesión
                $_SESSION["Nombre_Usuario"] = $Nombre_Usuario; // Almacenar el ID en la sesión
                $_SESSION["Rol_Usuario"] = $Rol; // Almacenar el ID en la sesión

                // Alerta de exito
                echo json_encode(["success" => true, "message" => "Usuario registrado con éxito."]);
                exit;
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "No se pudo registrar el usuario. Intenta nuevamente."
                ]);
                exit;
            }
        } else {
            // Si hay errores, devolverlos como respuesta JSON
            echo json_encode(["success" => false, "errors" => $Errores]);
            exit;
        }
    } else {
        // Si hay errores, devolverlos como respuesta JSON
        echo json_encode(["success" => false, "errors" => $Errores]);
        exit;
    }
} else {
    // Si no es una solicitud POST, devolver un error
    echo json_encode(["success" => false, "errors" => ["Método no permitido."]]);
    exit;
}
