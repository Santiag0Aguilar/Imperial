<?php
session_start();
header("Content-Type: application/json");

// recibir los datos
$Correo_Asociado = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$Numero_Asociado = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_NUMBER_INT);

if (empty($Correo_Asociado) || empty($Numero_Asociado)) {
    echo json_encode(["success" => false, "errors" => ["Error al recuperar la cuenta, faltan datos."]]);
    exit;
}


require_once __DIR__ . "/../../includes/config/db.php";
require_once __DIR__ . "/../../includes/function.php";

$db = db(); // Conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $Correo_Asociado = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $Numero_Asociado = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_NUMBER_INT);

    $stmt = $db->prepare("SELECT * FROM usuarios WHERE CorreoAsociado = ? AND NumeroAsociado = ?");
    $stmt->execute([$Correo_Asociado, $Numero_Asociado]);
    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $IdUsuario = $usuario['id'];
        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $Correo_Asociado_Confirmado = $usuario['CorreoAsociado'];
        /*         $stmt = $db->prepare("UPDATE usuarios SET token_recuperacion = ? WHERE id = ?");
        $stmt->execute([$token, $usuario['id']]); */

        // Aquí deberías enviar el token al correo electrónico del usuario
        enviarCorreoPrueba($Correo_Asociado_Confirmado, $token); // Función para enviar el correo

        //Aqui guardamos el token y la fecha para su posterior verificación
        $stmt = $db->prepare("UPDATE usuarios SET token_recuperacion = ?, token_generado_en = NOW() WHERE id = ?");
        $stmt->execute([$token, $IdUsuario]);
        // Si el envío del correo fue exitoso, puedes devolver una respuesta de éxito
        echo json_encode(["success" => true, "token" => $token]);
    } else {
        echo json_encode(["success" => false, "errors" => ["No se encontró una cuenta con esos datos."]]);
    }
}
