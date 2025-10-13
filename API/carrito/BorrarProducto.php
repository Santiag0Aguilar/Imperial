<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once __DIR__ . "/../../includes/config/db.php";

$db = db(); // ✅ importante
$data = json_decode(file_get_contents("php://input"), true);


$Errores = [];
// ✅ Obtener datos del JSON
$usuarioId = $data['usuario_id'] ?? null;
$productoId = $data['producto_id'] ?? null;
// ✅ Validar que el usuario_id y producto_id estén presentes
if (!$usuarioId) {
    http_response_code(400);
    echo json_encode(['error' => 'Usuario no especificado']);
    exit;
}
if (!$productoId) {
    http_response_code(400);
    echo json_encode(['error' => 'Producto no especificado']);
    exit;
}
if (isset($usuarioId)) {
    $stmt = $db->prepare("SELECT * FROM pedidos WHERE Usuarios_id = ? AND estado = 'pendiente'");
    $stmt->execute([$usuarioId]);
    $InformacionPedido = $stmt->fetch();

    if (!$InformacionPedido) {
        echo json_encode(['error' => 'Pedido no encontrado']);
        exit;
    }

    $IdPedido = $InformacionPedido["Id"];

    // Eliminar el producto específico del detallepedido
    $stmt = $db->prepare("DELETE FROM detallepedido WHERE Pedidos_Id = ? AND Productos_Id = ?");
    $stmt->execute([$IdPedido, $productoId]);

    // Verificar si aún hay productos en ese pedido
    $stmt = $db->prepare("SELECT COUNT(*) FROM detallepedido WHERE Pedidos_Id = ?");
    $stmt->execute([$IdPedido]);
    $cantidadRestante = $stmt->fetchColumn();

    if ($cantidadRestante == 0) {
        // Si no quedan productos, eliminar el pedido también
        $stmt = $db->prepare("DELETE FROM pedidos WHERE Id = ?");
        $stmt->execute([$IdPedido]);

        echo json_encode(['success' => true, 'message' => 'Producto eliminado y pedido eliminado porque estaba vacío']);
        exit;
    } else {
        echo json_encode(['success' => true, 'message' => 'Producto eliminado del carrito']);
        exit;
    }
}
