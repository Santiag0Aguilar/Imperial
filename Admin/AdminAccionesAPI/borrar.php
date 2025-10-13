<?php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);


include_once '../../includes/function.php';
include_once '../../includes/config/db.php';
$db = db();

$Errores = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    $id = isset($input['id']) ? filter_var($input['id'], FILTER_VALIDATE_INT) : null;
    $csrf_token = $input["csrf_token"] ?? "";
    // Validar ID
    if (!$id) {
        echo json_encode(["success" => false, "errors" => ["ID de producto no válido."]]);
        exit;
    }

    // Validar CSRF
    if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        echo json_encode(["success" => false, "errors" => ["Token CSRF inválido. Por favor, recarga la página e inténtalo nuevamente."]]);
        exit;
    }

    // Verificar existencia del producto
    $stmt = $db->prepare("SELECT COUNT(*) FROM productos WHERE Id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() == 0) {
        echo json_encode(["success" => false, "errors" => ["El producto no existe."]]);
        exit;
    }

    try {
        $db->beginTransaction();

        // Eliminar imágenes relacionadas
        $stmt = $db->prepare("SELECT ruta_imagen FROM imagenes_productos WHERE producto_id = ?");
        $stmt->execute([$id]);
        $imagenes_anteriores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($imagenes_anteriores as $img) {
            if (file_exists($img['ruta_imagen'])) {
                if (!unlink($img['ruta_imagen'])) {
                    error_log("Error al eliminar la imagen: " . $img['ruta_imagen']);
                }
            }
        }

        $stmt = $db->prepare("DELETE FROM imagenes_productos WHERE producto_id = ?");
        $stmt->execute([$id]);

        // Eliminar el producto
        $stmt = $db->prepare("DELETE FROM productos WHERE Id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $db->commit();
        echo json_encode(["success" => true, "message" => "Producto eliminado correctamente."]);
    } catch (PDOException $e) {
        $db->rollBack();
        error_log("Error en la base de datos: " . $e->getMessage());
        echo json_encode(["success" => false, "errors" => ["Error al eliminar el producto."]]);
    }
} else {
    echo json_encode(["success" => false, "errors" => ["Método no permitido."]]);
}
