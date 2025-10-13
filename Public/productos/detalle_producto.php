<?php
@include_once "../../includes/config/db.php";
$db = db();
$Id = $_GET['id'];
$stmt = $db->prepare("
  SELECT p.*, i.ruta_imagen
  FROM productos p
  INNER JOIN imagenes_productos i ON p.Id = i.producto_id
  WHERE p.Id = :id
  LIMIT 4
");
$stmt->bindParam(':id', $Id, PDO::PARAM_INT);
$stmt->execute();
$imagenesProducto = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Extraemos datos generales del producto desde el primer elemento
$producto = $imagenesProducto[0];
@include_once "../../includes/function.php";
incluirTamplate('header');
?>

<main class="Producto content">
  <h2 class="centrar-texto"><?php echo $producto["NombreProducto"] ?></h2>

  <div class="Producto__Imagenes">
    <div class="Producto__Imagenes__Principal">
      <img id="mainImage" src="<?php echo $imagenesProducto[0]["ruta_imagen"]; ?>" alt="Producto" />
    </div>
    <div class="Producto__Imagenes__Secundarias">
      <?php foreach (array_slice($imagenesProducto, 1) as $imagen): ?>
        <img class="thumb" src="<?php echo $imagen["ruta_imagen"]; ?>" alt="Producto" />
      <?php endforeach; ?>
    </div>
  </div>


  <div class="Producto__texto">
    <div class="Producto__texto--Logo">
      <img src="../Imagenes/SvgImperiumPerfume.svg" alt="Svg">
      <p class="Logo">Imperium<span class="LogoSpan">Parfum</span></p>
    </div>
    <div class="Producto__texto__datos">
      <div>
        <p class="precio">$<?php echo $producto["Precio"] ?></p>
        <p class="descripcion">
          <?php echo $producto["Descripcion"] ?>
        </p>
      </div>
      <div>
        <p> <span class="meta-dato">Stock:</span> <span class="resaltar"><?php echo $producto["Stock"] ?></span></p>
      </div>
    </div>
    <div class="Producto__texto--Marca">
      <p><span class="meta-dato">Marca:</span> <span class="resaltar"><?php echo $producto["Marca"] ?></span></p>
    </div>
    <a class="Boton-1" href="#">Agregar al carrito</a>
  </div>
</main>

<?php
incluirTamplate('footer');
?>