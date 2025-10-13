<?php
header('Content-Type: application/json');
require_once __DIR__ . "/../../includes/config/db.php";

$conn = db(); // ✅ importante
$data = json_decode(file_get_contents("php://input"), true);

// ✅ Obtener datos del JSON
$usuarioId = $data['usuario_id'] ?? null;
if (!$usuarioId) {
    http_response_code(400);
    echo json_encode(['error' => 'Usuario no especificado']);
    exit;
}
// Eliminar todos los productos del carrito del usuario
$stmt = $conn->prepare("DELETE FROM pedidos WHERE Usuarios_id = ? AND estado = 'pendiente'");

$stmt->execute([$usuarioId]);
if ($stmt) {
    echo json_encode(['carrito eliminado correctamente' => true]);
} else {
    echo json_encode(['error' => 'No se pudo vaciar el carrito']);
}
