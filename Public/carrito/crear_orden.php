<?php
session_start();
require_once __DIR__ . '/../includes/config/db.php';
require_once __DIR__ . '/../includes/paypalClient.php';

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

$db = db();
$paypal = paypalClient();
$usuarioId = $_SESSION['idUsuario'] ?? null;

if (!$usuarioId) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$total = 0;
try {
    $stmt = $db->prepare("
        SELECT dp.cantidad, dp.precio_unitario, p.PrecioDescuento
        FROM pedidos pe
        JOIN detallepedido dp ON pe.Id = dp.Pedidos_Id
        JOIN productos p ON dp.Productos_Id = p.Id
        WHERE pe.Usuarios_id = ? AND pe.estado = 'pendiente'
    ");
    $stmt->execute([$usuarioId]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($productos as $producto) {
        $precio = (isset($producto['PrecioDescuento']) && $producto['PrecioDescuento'] > 0)
            ? $producto['PrecioDescuento']
            : $producto['precio_unitario'];

        $total += $precio * $producto['cantidad'];
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al calcular total']);
    exit;
}

$request = new OrdersCreateRequest();
$request->prefer('return=representation');
$request->body = [
    'intent' => 'CAPTURE',
    'purchase_units' => [[
        'amount' => [
            'currency_code' => 'MXN',
            'value' => number_format($total, 2, '.', '')
        ]
    ]]
];

try {
    $response = $paypal->execute($request);
    echo json_encode(['orderID' => $response->result->id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo crear la orden']);
}
