<?php
include_once '../includes/function.php';
$auth = ValidarAdmin();

if (!$auth) {
    header("Location: /Perfumeria/index.php?error=3");
    exit;
}


include_once '../includes/config/db.php';
$db = db();

$sql = "SELECT * FROM productos";
$stmt = $db->prepare($sql);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $IdDeProducto = filter_var($_POST["id"], FILTER_VALIDATE_INT);
    if ($IdDeProducto) {
        try {
            // 1. Obtener rutas de imágenes antes de borrar
            $sql2 = "SELECT ruta_imagen FROM imagenes_productos WHERE producto_id = :id";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':id', $IdDeProducto, PDO::PARAM_INT);
            $stmt2->execute();
            $imagenes = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            // 2. Eliminar el producto (esto también borra en cascada las imágenes de la BD)
            $sql = "DELETE FROM productos WHERE Id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $IdDeProducto, PDO::PARAM_INT);
            if ($stmt->execute()) {
                // 3. Eliminar las imágenes del disco
                foreach ($imagenes as $imagen) {
                    $rutaImagen = "../img/productos/" . $imagen['ruta_imagen']; // Asegúrate que 'ruta_imagen' tiene solo el nombre, sin '../'
                    if (file_exists($rutaImagen)) {
                        unlink($rutaImagen);
                    }
                }

                header("Location: /Perfumeria/Admin/index.php?eliminado=1");
                exit;
            } else {
                echo "Error al eliminar el producto.";
            }
        } catch (PDOException $e) {
            error_log("Error en la base de datos: " . $e->getMessage(), 3, "../logs/error.log");
            echo json_encode(["success" => false, "errors" => ["Ocurrió un error al eliminar el producto. Por favor, intenta nuevamente."]]);
            exit;
        }
    } else {
        echo "ID de producto no válido.";
    }
}
