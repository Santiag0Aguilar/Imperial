<?php
header("Content-Type: application/json");
session_start();


include_once '../../includes/function.php';

include_once '../../includes/config/db.php';

$db = db();

$id = $_GET['id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $nombre = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_STRING);
    $precio = filter_input(INPUT_POST, "precio", FILTER_VALIDATE_FLOAT);
    $precioDescuento = filter_input(INPUT_POST, "descuento", FILTER_VALIDATE_FLOAT) ?? 0;
    $descripcion = filter_input(INPUT_POST, "descripcion", FILTER_SANITIZE_STRING);
    $Genero = filter_input(INPUT_POST, "genero", FILTER_SANITIZE_STRING);
    $Marca = filter_input(INPUT_POST, "marca", FILTER_SANITIZE_STRING);
    $Categoria = filter_input(INPUT_POST, "categoria", FILTER_SANITIZE_STRING);
    $ImagenesNuevas = $_FILES['imagenes'];

    // Validar CSRF
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $Errores[] = "Error al subir producto. Por favor, recarga la página e inténtalo nuevamente.";
    }

    // Validaciones
    if (!$nombre) {
        $Errores[] = "El nombre del producto es obligatorio.";
    }
    if (!$precio || $precio <= 0) {
        $Errores[] = "El precio debe ser un número positivo.";
    }
    if (!$descripcion) {
        $Errores[] = "La descripción del producto es obligatoria.";
    }

    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $nombre)) {
        $Errores[] = "El nombre solo puede contener letras y números.";
    }
    if (strlen($nombre) > 100) {
        $Errores[] = "El nombre no puede exceder los 100 caracteres.";
    }
    if (strlen($descripcion) > 500) {
        $Errores[] = "La descripción no puede exceder los 500 caracteres.";
    }
    if (!$Genero) {
        $Errores[] = "El género es obligatorio.";
    }
    if (!$Marca) {
        $Errores[] = "La marca es obligatoria.";
    }
    if (!$Categoria) {
        $Errores[] = "La categoría es obligatoria.";
    }
    /* Validar categoría */
    if (!in_array($Categoria, ['perfume', 'ropa', 'oferta'])) {
        $Errores[] = "La categoría no es válida.";
    }
    /* Validar género */
    if (!in_array($Genero, ['masculino', 'femenino', 'unisex', 'niños'])) {
        $Errores[] = "El género no es válido.";
    }
    // Validar imagenes
    foreach ($ImagenesNuevas['tmp_name'] as $key => $tmp_name) {
        if ($ImagenesNuevas['error'][$key] === 0) {
            $tipoMime = mime_content_type($tmp_name);
            if (!in_array($tipoMime, ['image/jpeg', 'image/png'])) {
                $Errores[] = "Solo se permiten imágenes en formato JPEG o PNG.";
            }
            if ($ImagenesNuevas['size'][$key] > 2 * 1024 * 1024) {
                $Errores[] = "El tamaño de las imágenes no debe exceder los 2 MB.";
            }
        }
    }

    if (empty($Errores)) {
        try {
            // Actualizar datos del producto
            if (isset($_POST['descuento']) && is_numeric($_POST['descuento'])) {
                $precioDescuento = (float) $_POST['descuento'];
                $stmt = $db->prepare("UPDATE productos SET NombreProducto = ?, Precio = ?, Descripcion = ?, Genero = ?, Marca = ?, Categoria = ?, PrecioDescuento = ?  WHERE Id = ?");
                $stmt->execute([$nombre, $precio, $descripcion, $Genero, $Marca, $Categoria, $precioDescuento, $id]);
            } else {
                $stmt = $db->prepare("UPDATE productos SET NombreProducto = ?, Precio = ?, Descripcion = ?, Genero = ?, Marca = ?, Categoria = ? WHERE Id = ?");
                $stmt->execute([$nombre, $precio, $descripcion, $Genero, $Marca, $Categoria, $id]);
            }


            // Revisar si se subieron nuevas imágenes
            $nuevas_imagenes = array_filter($ImagenesNuevas['error'], fn($e) => $e === 0);

            if (!empty($nuevas_imagenes)) {
                // Eliminar imágenes anteriores de la base de datos y del disco
                $stmt = $db->prepare("SELECT ruta_imagen FROM imagenes_productos WHERE producto_id = ?");
                $stmt->execute([$id]);
                $imagenes_anteriores = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($imagenes_anteriores as $img) {
                    if (file_exists($img['ruta_imagen'])) {
                        unlink($img['ruta_imagen']);
                    }
                }

                $stmt = $db->prepare("DELETE FROM imagenes_productos WHERE producto_id = ?");
                $stmt->execute([$id]);

                // Subir nuevas imágenes
                foreach ($ImagenesNuevas['tmp_name'] as $key => $tmp_name) {
                    if ($ImagenesNuevas['error'][$key] === 0) {
                        $directorio = "../../uploads/";
                        if (!is_dir($directorio)) {
                            mkdir($directorio, 0755, true);
                        }

                        $nombre_imagen = uniqid() . "_" . basename($ImagenesNuevas['name'][$key]);
                        $ruta_destino = $directorio . $nombre_imagen;

                        if (move_uploaded_file($tmp_name, $ruta_destino)) {
                            $stmt = $db->prepare("INSERT INTO imagenes_productos (producto_id, ruta_imagen) VALUES (?, ?)");
                            $stmt->execute([$id, $ruta_destino]);
                        }
                    }
                }
            }

            echo json_encode(["success" => true, "message" => "Producto actualizado exitosamente."]);
        } catch (PDOException $e) {
            error_log("Error en la base de datos: " . $e->getMessage(), 3, "../../logs/error.log");
            echo json_encode(["success" => false, "errors" => ["Ocurrió un error al actualizar el producto."]]);
        }
    } else {
        echo json_encode(["success" => false, "errors" => $Errores]);
    }
}
