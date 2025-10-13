<?php
header("Content-Type: application/json; charset=UTF-8");

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include_once "../../includes/config/db.php";  // Conexion a la base de datos
$db = db();
$nombre = "";
$precio = "";
$descripcion = "";
$Errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $nombre = trim($_POST["nombre"] ?? '');
    $descripcion = trim($_POST["descripcion"] ?? '');
    $Genero = trim($_POST["genero"] ?? '');
    $Marca = trim($_POST["marca"] ?? '');
    $Categoria = trim($_POST["categoria"] ?? '');
    $precio = filter_var($_POST["precio"], FILTER_VALIDATE_FLOAT);
    $precioDescuento = isset($_POST["descuento"]) ? filter_var($_POST["descuento"], FILTER_VALIDATE_FLOAT) : 0;
    $imagenes = $_FILES['imagenes'];
    $stock = isset($_POST["stock"]) ? filter_var($_POST["stock"], FILTER_VALIDATE_INT) : 0;
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
    if (!$stock || $stock < 0) {
        $Errores[] = "El stock debe ser un número entero positivo.";
    }
    if (!$descripcion) {
        $Errores[] = "La descripción del producto es obligatoria.";
    }
    if (!$imagenes || empty($imagenes['name'][0])) {
        $Errores[] = "Las imágenes del producto son obligatorias.";
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

    if (!$Marca) {
        $Errores[] = "La marca es obligatoria.";
    }
    /* Validar nombre de marca */
    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $Marca)) {
        $Errores[] = "El nombre de la marca solo puede contener letras y números.";
    }
    if (strlen($Marca) > 50) {
        $Errores[] = "El nombre de la marca no puede exceder los 50 caracteres.";
    }
    /* Validar categoría */
    if (!in_array($Categoria, ['perfume', 'ropa', 'oferta'])) {
        $Errores[] = "La categoría no es válida.";
    }
    /* Validar género */
    if (!in_array($Genero, ['masculino', 'femenino', 'unisex', 'niños'])) {
        $Errores[] = "El género no es válido.";
    }
    /* Validar precio */
    if (!is_numeric($precio) || $precio <= 0) {
        $Errores[] = "El precio debe ser un número positivo.";
    }


    // Validar imágenes
    foreach ($imagenes['tmp_name'] as $key => $tmp_name) {
        if ($imagenes['error'][$key] === 0) {
            $tipoMime = mime_content_type($tmp_name);
            if (!in_array($tipoMime, ['image/jpeg', 'image/png'])) {
                $Errores[] = "Solo se permiten imágenes en formato JPEG o PNG.";
            }
            if ($imagenes['size'][$key] > 2 * 1024 * 1024) { // 2 MB
                $Errores[] = "El tamaño de las imágenes no debe exceder los 2 MB.";
            }
        } else {
            $Errores[] = "Error al subir la imagen " . $imagenes["name"][$key];
        }
    }

    if (empty($Errores)) {
        try {
            // Insertar el producto
            if (isset($_POST['descuento']) && is_numeric($_POST['descuento'])) {
                $stmt = $db->prepare("INSERT INTO productos (NombreProducto, Precio, Descripcion, Genero, Marca, Categoria, PrecioDescuento, Stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $precio, $descripcion, $Genero, $Marca, $Categoria, $precioDescuento, $stock]);
            } else {
                $stmt = $db->prepare("INSERT INTO productos (NombreProducto, Precio, Descripcion, Genero, Marca, Categoria, Stock) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $precio, $descripcion, $Genero, $Marca, $Categoria, $stock]);
            }

            // Obtener el ID del nuevo producto
            $producto_id = $db->lastInsertId();

            // Procesar imágenes
            foreach ($imagenes['tmp_name'] as $key => $tmp_name) {
                if ($imagenes['error'][$key] === 0) {
                    $directorio = "../../uploads/";
                    if (!is_dir($directorio)) {
                        mkdir($directorio, 0755, true);
                    }

                    $nombre_imagen = uniqid() . "_" . basename($imagenes['name'][$key]);
                    $ruta_destino = $directorio . $nombre_imagen;

                    if (move_uploaded_file($tmp_name, $ruta_destino)) {
                        // Guardar en la tabla de imágenes
                        $stmt = $db->prepare("INSERT INTO imagenes_productos (producto_id, ruta_imagen) VALUES (?, ?)");
                        $stmt->execute([$producto_id, $ruta_destino]);
                    }
                }
            }

            echo json_encode(["success" => true, "message" => "Producto creado exitosamente."]);
        } catch (PDOException $e) {
            error_log("Error en la base de datos: " . $e->getMessage(), 3, "../../logs/error.log");
            echo json_encode(["success" => false, "errors" => ["Ocurrió un error al guardar el producto. Por favor, inténtalo nuevamente."]]);
        }
    } else {
        echo json_encode(["success" => false, "errors" => $Errores]);
    }
}
