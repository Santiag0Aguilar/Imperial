<?php
header('Content-Type: aplication/json');

file_put_contents("debug.txt", print_r($_POST, true));
file_put_contents("categoria.txt", $_POST["categoria"] ?? "NO LLEGO");
file_put_contents("categoria.txt", isset($_POST["categoria"]) ? $_POST["categoria"] : 'NO RECIBIDA');

session_start();
// Verificar si la sesión está iniciada y obtener el ID del usuario
$idUsuario = isset($_SESSION["idUsuario"]) ? $_SESSION["idUsuario"] : 0;

@include_once "../../includes/config/db.php";
$db = db();

$categoria = $_POST['categoria'] ?? 'perfume';
$generos = isset($_POST['generos']) ? json_decode($_POST['generos'], true) : [];
if (!is_array($generos)) $generos = [];
$marca = $_POST['marca'] ?? '';
$orden = $_POST['orden'] ?? '';
$precio_min = isset($_POST['precio_min']) ? floatval($_POST['precio_min']) : 0;
$precio_max = isset($_POST['precio_max']) ? floatval($_POST['precio_max']) : 999999;


$params = [];

$query = "
  SELECT p.*, MIN(i.ruta_imagen) AS ruta_imagen
  FROM productos p
  LEFT JOIN imagenes_productos i ON p.Id = i.producto_id
  WHERE p.Categoria = ?
";


$params[] = $categoria;
if (!empty($generos)) {
    $placeholders = implode(',', array_fill(0, count($generos), '?'));
    $query .= " AND p.Genero IN ($placeholders)";
    $params = array_merge($params, $generos);
}

if (!empty($marca) && $marca !== '0') {
    $query .= " AND p.Marca = ?";
    $params[] = $marca;
}

$query .= " AND p.Precio BETWEEN ? AND ?";
$params[] = $precio_min;
$params[] = $precio_max;

$query .= " GROUP BY p.Id";

switch ($orden) {
    case "1":
        $query .= " ORDER BY p.Precio ASC";
        break;
    case "2":
        $query .= " ORDER BY p.Precio DESC";
        break;
    case "3":
        $query .= " ORDER BY p.NombreProducto ASC";
        break;
    case "4":
        $query .= " ORDER BY p.NombreProducto DESC";
        break;
}


$stmt = $db->prepare($query);
$stmt->execute($params);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generar el HTML que se reemplazará en productos.php
foreach ($productos as $producto): ?>

    <!-- Producto -->
    <div class="Perfumes__Container__Item">
        <div class="Perfumes__Container__Item__Imagen">
            <a href="/Perfumeria/Public/productos/detalle_producto.php?id=<?php echo $producto["Id"] ?>">
                <img src="<?php echo $producto["ruta_imagen"] ?>" alt="perfume1" />
            </a>
        </div>
        <!-- Contenido del producto -->
        <p class="nombre"><?php echo $producto["NombreProducto"] ?></p>
        <div class="precios">
            <p class="precio"><?php echo $producto["Precio"] ?></p>
        </div>
        <input type="hidden" value="<?php echo $producto["Id"] ?>" name="id_producto" id="id_producto" />
        <input type="hidden" value="<?php echo $producto["Genero"] ?>" name="Genero_producto" id="Genero_producto" />
        <input type="hidden" value="<?php echo $producto["Marca"] ?>" name="Marca_producto" id="Marca_producto" />


        <a class="btnAddCar Boton-1"
            href="#"
            data-id="<?php echo $producto["Id"] ?>"
            data-nombre="<?php echo htmlspecialchars($producto["NombreProducto"]) ?>"
            data-precio="<?php echo $producto["Precio"] ?>"
            data-genero="<?php echo $producto["Genero"] ?>"
            data-marca="<?php echo $producto["Marca"] ?>"
            data-idusuario="<?php echo $idUsuario ?>">
            Agregar al carrito
        </a>

    </div>
    <!-- Fin Producto -->
<?php endforeach;

if (empty($productos)) {
    echo "<p>No se encontraron productos con esos filtros.</p>";
}
?>