<?php
include_once '../../includes/function.php';

$auth = ValidarAdmin();
if (!$auth) {
  header("Location: /Perfumeria/index.php");
  exit;
}

include_once '../../includes/config/db.php';
$db = db();


$stmt = $db->prepare("
SELECT p.*, MIN(i.ruta_imagen) AS ruta_imagen
FROM productos p
INNER JOIN imagenes_productos i ON p.Id = i.producto_id
WHERE p.Categoria = 'perfume'
GROUP BY p.Id

");

$stmt->execute();
$perfumes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("
SELECT p.*, MIN(i.ruta_imagen) AS ruta_imagen
FROM productos p
INNER JOIN imagenes_productos i ON p.Id = i.producto_id
WHERE p.Categoria = 'ropa'
GROUP BY p.Id

");

$stmt->execute();
$ropas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("
SELECT p.*, MIN(i.ruta_imagen) AS ruta_imagen
FROM productos p
INNER JOIN imagenes_productos i ON p.Id = i.producto_id
WHERE p.Categoria = 'oferta'
GROUP BY p.Id

");

$stmt->execute();
$ofertas = $stmt->fetchAll(PDO::FETCH_ASSOC);



incluirTamplate("header");
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>



<main class="content Tablas">
  <div class="Botones-Admin">
    <a class="Boton-1" href="CrearProducto.php">Crear producto</a>
    <a class="Boton-1" href="CrearProducto.php?descuento=1">Crear producto con descuento</a>
  </div>

  <h2 class="centrar-texto">Pandel de administrador</h2>

  <h4>Perfumes</h4>
  <table class="table-Perfumes">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Precio/s</th>
        <th>Imagen</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($perfumes as $perfume): ?>
        <tr>
          <td><?php echo $perfume["NombreProducto"] ?></td>
          <td>$<?php echo $perfume["Precio"] ?></td>
          <td>
            <img
              src="<?php echo $perfume["ruta_imagen"] ?>"
              alt="perfume1" />
          </td>
          <td>
            <form class="formEliminar">
              <input type="hidden" id="id" name="id" value="<?php echo $perfume['Id']; ?>" />
              <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
              <input type="submit" value="Eliminar" class="BotonDelete" />
            </form>

            <a class="BotonUpdate" href="ActualizarProducto.php?id=<?php echo $perfume['Id'] ?>">Actualizar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <h4>Ropa</h4>
  <table class="table-ropa">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Precio/s</th>
        <th>Imagen</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($ropas as $ropa): ?>
        <tr>
          <td><?php echo $ropa["NombreProducto"] ?></td>
          <td>$<?php echo $ropa["Precio"] ?></td>
          <td>
            <img
              src="<?php echo $ropa["ruta_imagen"] ?>"
              alt="ropa1" />
          </td>
          <td>
            <form class="formEliminar">
              <input type="hidden" id="id" name="id" value="<?php echo $ropa['Id']; ?>" />
              <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
              <input type="submit" value="Eliminar" class="BotonDelete" />
            </form>

            <a class="BotonUpdate" href="ActualizarProducto.php?id=<?php echo $ropa['Id'] ?>">Actualizar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <h4>Ofertas</h4>
  <table class="table-Perfumes">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Precio/s</th>
        <th>Imagen</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($ofertas as $oferta): ?>
        <tr>
          <td><?php echo $oferta["NombreProducto"] ?></td>
          <td>
            <p class="precio-normal">$<?php echo $oferta["Precio"] ?></p>
            <p>$<?php echo $oferta["PrecioDescuento"] ?></p>
          </td>
          <td>
            <img
              src="<?php echo $oferta["ruta_imagen"] ?>"
              alt="ropa1" />
          </td>
          <td>
            <form class="formEliminar">
              <input type="hidden" id="id" name="id" value="<?php echo $oferta['Id']; ?>" />
              <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
              <input type="submit" value="Eliminar" class="BotonDelete" />
            </form>
            <a class="BotonUpdate" href="ActualizarProducto.php?id=<?php echo $oferta['Id'] ?>">Actualizar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>

<?php
incluirTamplate("footer");
?>