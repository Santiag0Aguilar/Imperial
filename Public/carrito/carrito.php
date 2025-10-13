<?php
@include_once __DIR__ . '/../../includes/function.php';
incluirTamplate('session');
// Verificar si el usuario está logueado
if (!isset($_SESSION['Rol_Usuario'])) {
  header('Location: /Perfumeria/Public/login.php');
  exit;
}
$idUsuario = $_SESSION['idUsuario'] ?? null;

require_once __DIR__ . "/../../includes/config/db.php";
$db = db();
$usuarioId = $_SESSION['idUsuario'];
$stmt = $db->prepare("
  SELECT 
    p.id AS producto_id,
    p.NombreProducto AS nombre,
    p.Descripcion AS descripcion,
    p.Categoria AS categoria,
    p.Precio AS precio_base,
    p.PrecioDescuento AS precio_descuento,
    dp.cantidad,
    dp.precio_unitario,
    dp.total,
    (
      SELECT ruta_imagen 
      FROM imagenes_productos 
      WHERE producto_id = p.id 
      LIMIT 1
    ) AS ruta_imagen
  FROM 
    pedidos pe
  JOIN 
    detallepedido dp ON pe.Id = dp.Pedidos_Id
  JOIN 
    productos p ON dp.Productos_Id = p.Id
  WHERE 
    pe.Usuarios_id = ? AND pe.estado = 'pendiente'
");


$stmt->execute([$usuarioId]);
$carrito = $stmt->fetchAll();



incluirTamplate("header");

?>

<div class="Carrito content">
  <button class="Boton-1 BtnDeleteCar" data-idusuario="<?php echo $idUsuario ?>">Vaciar carrito</button>
  <div class="Carrito__Container">
    <h2 class="Carrito__Container--titulo centrar-texto">
      Carrito de compras
    </h2>
    <div class="Carrito__Container--productos">
      <?php
      $totalGeneral = 0.00; // ✅ inicializar la variable
      foreach ($carrito as $producto): ?>
        <div class="Carrito__Container--productos--producto" data-idproducto="<?php echo $producto['producto_id'] ?>">
          <?php var_dump($producto) ?>
          <img
            src="<?php echo htmlspecialchars($producto['ruta_imagen'] ?? 'default.jpg'); ?>"
            alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
            class="Carrito__Container--productos--producto--imagen" />
          <div class="Carrito__Container--productos--producto--info">
            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
            <?php if (!empty($producto['precio_descuento'])): ?>
              <p class="precio precio-descuento precioProducto">
                Precio con descuento: $<?php echo number_format($producto['precio_descuento'], 2); ?>
              </p>
            <?php else: ?>
              <p>Precio: <span class="precio">$ <span class="precioProducto"><?php echo number_format($producto['precio_unitario'], 2); ?></span></span></p>
            <?php endif; ?>


            <p>Cantidad: <span class="precio cantidadProducto"><?php echo $producto['cantidad']; ?></span></p>
          </div>
        </div>
        <?php
        if (!empty($producto['precio_descuento'])) {
          // Si tiene precio con descuento, se usa ese * cantidad
          $subtotal = $producto['precio_descuento'] * $producto['cantidad'];
        } else {
          // Si no, se usa el total original del detalle pedido
          $subtotal = $producto['total'];
        }
        $totalGeneral += $subtotal;
        ?>

        <button class="Boton-1 BtnDeleteProducto " data-idusuario="<?php echo $idUsuario ?>" data-idproducto="<?php echo $producto['producto_id'] ?>">Borrar Producto</button>
      <?php endforeach; ?>
    </div>

    <div class=" Carrito__Container--total">
      <h3>Total: <span class="precio ">$<span class="precioTotal"><?php echo number_format($totalGeneral, 2); ?></span></span></h3>
      <a href="pago.php" class="Boton-1 btn btn-pagar">Finalizar compra</a>

    </div>

  </div>
</div>

<?php
incluirTamplate("footer");

?>