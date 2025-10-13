<?php

header('Content-Type: application/json');
session_start();
require_once __DIR__ . "/../../includes/config/db.php";
$db = db();

// Obtener datos del JSON
$Nombre_Destinatario = filter_input(INPUT_POST, "nombre_destinatario", FILTER_SANITIZE_STRING);
$Telefono = filter_input(INPUT_POST, "telefono", FILTER_SANITIZE_STRING);
$Calle = filter_input(INPUT_POST, "calle", FILTER_SANITIZE_STRING);
$Numero_Exterior = filter_input(INPUT_POST, "numero_exterior", FILTER_SANITIZE_STRING);
$Colonia = filter_input(INPUT_POST, "colonia", FILTER_SANITIZE_STRING);
$Municipio = filter_input(INPUT_POST, "municipio", FILTER_SANITIZE_STRING);
$Estado = filter_input(INPUT_POST, "estado", FILTER_SANITIZE_STRING);
$Codigo_Postal = filter_input(INPUT_POST, "codigo_postal", FILTER_SANITIZE_STRING);
$IdUsuario = filter_input(INPUT_POST, "idUsuario", FILTER_SANITIZE_NUMBER_INT);
$numero_interior = filter_input(INPUT_POST, "numero_interior", FILTER_SANITIZE_STRING) ?? '';
$referencias = filter_input(INPUT_POST, "referencias", FILTER_SANITIZE_STRING) ?? '';
// Obtener pedido pendiente del usuario
$stmt = $db->prepare("SELECT Id FROM pedidos WHERE Usuarios_id = ? AND estado = 'pendiente' LIMIT 1");
$stmt->execute([$IdUsuario]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);
$pedidoId = $pedido['Id'] ?? null;

if (!$pedidoId) {
    echo json_encode(['error' => 'No se encontró un pedido pendiente.']);
    exit;
}

// Validación de campos
if (
    empty($Nombre_Destinatario) || empty($Telefono) || empty($Calle) ||
    empty($Numero_Exterior) || empty($Colonia) || empty($Municipio) ||
    empty($Estado) || empty($Codigo_Postal)
) {
    echo json_encode(['error' => 'Faltan datos.']);
    exit;
}

if (!isset($IdUsuario)) {
    echo json_encode(['error' => 'Usuario no autenticado.']);
    exit;
}

// Verificar si ya existe una dirección para este pedido
$stmt = $db->prepare("SELECT COUNT(*) FROM direcciones_envio WHERE Pedidos_Id = ?");
$stmt->execute([$pedidoId]);
$existe = $stmt->fetchColumn();

if ($existe > 0) {
    echo json_encode(['error' => 'Ya existe una dirección registrada para este pedido.']);
    exit;
}

// Insertar nueva dirección
$stmt = $db->prepare("INSERT INTO direcciones_envio (`Pedidos_Id`, `nombre_destinatario`, `telefono`, `calle`, `numero_ext`, `numero_int`, `colonia`, `municipio`, `estado`, `codigo_postal`, `referencias`, `Usuarios_id`) VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $pedidoId,
    $Nombre_Destinatario,
    $Telefono,
    $Calle,
    $Numero_Exterior,
    $numero_interior,
    $Colonia,
    $Municipio,
    $Estado,
    $Codigo_Postal,
    $referencias,
    $IdUsuario
]);

echo json_encode(['success' => 'Dirección registrada correctamente.']);
