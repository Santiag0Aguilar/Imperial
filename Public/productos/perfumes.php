<?php
session_start();
@include_once "../../includes/config/db.php";
$db = db();

$idUsuario = $_SESSION['idUsuario'] ?? null;
var_dump($_SESSION);

$stmt = $db->prepare("
SELECT p.*, MIN(i.ruta_imagen) AS ruta_imagen
FROM productos p
INNER JOIN imagenes_productos i ON p.Id = i.producto_id
WHERE p.Categoria = 'perfume'
GROUP BY p.Id

");

$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);


@include_once "../../includes/function.php";
incluirTamplate('header');
?>
<div>
  <input type="hidden" id="rol" value="<?php echo !isset($_SESSION["Rol_Usuario"]) ? "NoSessionStart" : $_SESSION['Rol_Usuario'] ?>" id="rol" />
</div>
<main class="Perfumes content" data-categoria="perfume">
  <!-- Filtros -->
  <div class="filtros" id="filtros">
    <!-- Género -->
    <div class="filtros__genero">
      <h4>GÉNERO</h4>


      <label for="genero"><input type="checkbox" name="genero" value="masculino" />masculino</label>

      <label for="genero"><input type="checkbox" name="genero" value="femenino" />femenino</label>

      <label for="genero"><input type="checkbox" name="genero" value="unisex" />unisex</label>

      <label for="genero"><input type="checkbox" name="genero" value="niños" />niños</label>
    </div>

    <!-- Marca -->
    <div class="filtros__marca">
      <h4>MARCA</h4>
      <select name="marca" id="marca">
        <option selected value="0">Todas</option>
        <?php
        $marcasUnicas = array_unique(array_column($productos, "Marca"));
        foreach ($marcasUnicas as $marca) {
          echo "<option value=\"$marca\">$marca</option>";
        }
        ?>

      </select>
    </div>

    <!-- Precio -->
    <div class="filtros__precio">
      <h4>PRECIO</h4>
      <div id="slider-precio"></div>
      <p id="rango-precio"></p>
    </div>

    <!-- Botón para aplicar filtro -->
    <button class="Boton-1" id="aplicar-filtro">Filtrar</button>
  </div>
  <div class="Perfumes__Container__Order">
    <div class="Perfumes__Container__Order__Item">
      <img
        class="filter__active"
        src="/Perfumeria/Public/Imagenes/filtersvg.svg"
        height="10px"
        width="40px"
        alt="filter" />
      <p>Ordenar por</p>
      <select class="Item__Option" name="ordenar" id="ordenar">
        <option value="1">Precio menor a mayor</option>
        <option value="2">Precio mayor a menor</option>
        <option value="3">Nombre A-Z</option>
        <option value="4">Nombre Z-A</option>
      </select>
    </div>
  </div>
  <div class="Perfumes__Container">
    <?php foreach ($productos as $producto): ?>

      <!-- Perfume -->
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
      <!-- Fin perfume -->

    <?php endforeach; ?>
  </div>
</main>

<?php
incluirTamplate('footer');
?>