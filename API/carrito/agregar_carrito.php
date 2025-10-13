<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . "/../../includes/config/db.php";

$conn = db(); // ✅ importante

$data = json_decode(file_get_contents("php://input"), true);

// ✅ Obtener datos del JSON
$usuarioId = $data['usuario_id'] ?? null;
$productoId = $data['id'] ?? null;
$cantidad = $data['cantidad'] ?? 1;

if (!$usuarioId || !$productoId) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

// Buscar pedido pendiente
$stmt = $conn->prepare("SELECT Id FROM pedidos WHERE Usuarios_id = ? AND estado = 'pendiente'");
$stmt->execute([$usuarioId]);
$pedido = $stmt->fetch();

if (!$pedido) {
    $stmt = $conn->prepare("INSERT INTO pedidos (FechaPedido, Usuarios_id, estado) VALUES (NOW(), ?, 'pendiente')");
    $stmt->execute([$usuarioId]);
    $pedidoId = $conn->lastInsertId();
} else {
    $pedidoId = $pedido['Id'];
}

// Ver si ya existe el producto en el detalle
$stmt = $conn->prepare("SELECT cantidad FROM detallepedido WHERE Pedidos_Id = ? AND Productos_Id = ?");
$stmt->execute([$pedidoId, $productoId]);
$existe = $stmt->fetch();

if ($existe) {
    $nuevaCantidad = $existe['cantidad'] + $cantidad;
    $stmt = $conn->prepare("UPDATE detallepedido SET cantidad = ?, total = precio_unitario * ? WHERE Pedidos_Id = ? AND Productos_Id = ?");
    $stmt->execute([$nuevaCantidad, $nuevaCantidad, $pedidoId, $productoId]);
} else {
    $stmt = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
    $stmt->execute([$productoId]);
    $producto = $stmt->fetch();
    $precio = $producto['precio'];

    $total = $precio * $cantidad;

    $stmt = $conn->prepare("INSERT INTO detallepedido (cantidad, precio_unitario, total, Pedidos_Id, Productos_Id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$cantidad, $precio, $total, $pedidoId, $productoId]);
}

// Total en carrito
$stmt = $conn->prepare("SELECT SUM(cantidad) as total FROM detallepedido WHERE Pedidos_Id = ?");
$stmt->execute([$pedidoId]);
$row = $stmt->fetch();

echo json_encode(['carritoCantidad' => $row['total']]);
