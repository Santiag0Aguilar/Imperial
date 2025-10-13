<?php
session_start();
require_once __DIR__ . '/../includes/config/db.php';
require_once __DIR__ . '/../includes/paypalClient.php';

use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

$paypal = paypalClient();
$db = db();
$usuarioId = $_SESSION['idUsuario'] ?? null;

if (!$usuarioId) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$orderID = $input['orderID'] ?? null;

if (!$orderID) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de orden requerido']);
    exit;
}

try {
    $capture = new OrdersCaptureRequest($orderID);
    $response = $paypal->execute($capture);

    // Actualiza pedido a pagado
    $stmt = $db->prepare("UPDATE pedidos SET estado = 'pagado', FechaPedido = NOW() WHERE Usuarios_id = ? AND estado = 'pendiente'");
    $stmt->execute([$usuarioId]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo capturar el pago']);
}
