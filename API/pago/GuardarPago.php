<?php

header("Content-Type: application/json");
require_once __DIR__ . "/../../includes/config/db.php";
$db = db();

$input = json_decode(file_get_contents("php://input"), true);

if (
    !isset($input["paypal_order_id"]) ||
    !isset($input["estado"]) ||
    !isset($input["create_time"]) ||
    !isset($input["update_time"]) ||
    !isset($input["email_pagador"]) ||
    !isset($input["payer_id"]) ||
    !isset($input["nombre_pagador"]) ||
    !isset($input["monto"]) ||
    !isset($input["Pedidos_Id"]) ||
    !isset($input["json_respuesta"])
) {
    echo json_encode(["success" => false, "errors" => ["Faltan datos necesarios para procesar el pago."]]);
    exit;
}

$paypal_order_id = $input["paypal_order_id"];
$estado = $input["estado"];
$create_time = (new DateTime($input["create_time"]))->format("Y-m-d H:i:s");
$update_time = (new DateTime($input["update_time"]))->format("Y-m-d H:i:s");
$email_pagador = filter_var($input["email_pagador"], FILTER_SANITIZE_EMAIL);
$payer_id = $input["payer_id"];
$nombre_pagador = $input["nombre_pagador"];
$monto = floatval($input["monto"]);
$json_respuesta = json_encode($input["json_respuesta"], JSON_UNESCAPED_UNICODE);
$Pedidos_Id = intval($input["Pedidos_Id"]);
try {
    $stmt = $db->prepare("
        INSERT INTO pagos (

            Pedidos_Id, paypal_order_id, estado, monto, email_pagador, payer_id, fecha_creacion, fecha_actualizacion, json_respuesta
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $Pedidos_Id,
        $paypal_order_id,
        $estado,
        $monto,
        $email_pagador,
        $payer_id,
        $create_time,
        $update_time,
        $json_respuesta
    ]);

    $stmt = $db->prepare("UPDATE pedidos SET estado = 'pagado' WHERE id = ?");
    $stmt->execute([$Pedidos_Id]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "errors" => [$e->getMessage()]]);
}
