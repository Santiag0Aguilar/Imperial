<?php

include_once '../../includes/function.php';

$auth = ValidarAdmin();
if (!$auth) {
    header("Location: /Perfumeria/index.php");
    exit;
}

include_once '../../includes/config/db.php';
$db = db();

$IdProducto = $_GET['id'];
$formdescuento = isset($_GET['descuento']) ? $_GET['descuento'] : null;

session_start();

if (isset($IdProducto)) {
    $stmtProducto = $db->prepare("SELECT * FROM productos WHERE Id = :IdProducto");
    $stmtProducto->execute(['IdProducto' => $IdProducto]);
    $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        var_dump("Producto no encontrado. Por favor, verifica el ID proporcionado.");
    }

    $stmtImagenes = $db->prepare("SELECT ruta_imagen FROM imagenes_productos WHERE producto_id = :IdProducto");
    $stmtImagenes->execute(['IdProducto' => $IdProducto]);
    $imagenes = $stmtImagenes->fetchAll(PDO::FETCH_COLUMN);
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


incluirTamplate("header");
?>





<div class="main__containerForm">
    <?php if ($producto["Categoria"] == "oferta"): ?>
        <h1 class="centrar-texto">Crear nueva oferta</h1>
    <?php else: ?>
        <h1 class="centrar-texto">Crear nuevo producto</h1>
    <?php endif; ?>

    <form class="Form__CrearProducto content" id="formProducto" enctype="multipart/form-data" method="POST">
        <fieldset>
            <div id="loginErrors" class="error-messages"></div>
            <legend>Crear Productos</legend>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">


            <input type="hidden" name="id" id="id" value="<?php echo $IdProducto; ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $producto['NombreProducto'] ?>" required>

            <?php foreach ($imagenes as $imagen): ?>
                <div class="imagen-preview">
                    <img width="100px" src="<?php echo $imagen ?>" alt="Imagen del producto">
                </div>
            <?php endforeach; ?>

            <label for="precio">Precio <?php echo $producto["Categoria"] == "oferta" ? "sin descuento" : "" ?>:</label>
            <input type="number" step="0.01" id="precio" name="precio" value="<?php echo $producto['Precio'] ?>" required>

            <?php if ($producto["Categoria"] == "oferta") : ?>
                <label for="descuento">Precio con descuento:</label>
                <input type="number" step="0.01" id="descuento" name="descuento" value="<?php echo $producto['PrecioDescuento'] ?>" required>
            <?php endif; ?>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" required><?php echo $producto['Descripcion'] ?></textarea>

            <label for="genero">Género:</label>
            <select id="genero" name="genero" required>
                <option selected disabled value="#">--Seleccionar Genero--</option>
                <option value="masculino" <?php echo $producto["Genero"] == "masculino" ? "selected" : ""; ?>>Masculino</option>
                <option value="femenino" <?php echo $producto["Genero"] == "femenino" ? "selected" : ""; ?>>Femenino</option>
                <option value="unisex" <?php echo $producto["Genero"] == "unisex" ? "selected" : ""; ?>>Unisex</option>
                <option value="niños" <?php echo $producto["Genero"] == "niños" ? "selected" : ""; ?>>Niños</option>
            </select>

            <label for="marca">Marca:</label>
            <input type="text" name="marca" id="marca" value="<?php echo $producto['Marca'] ?>" required>

            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria" required>
                <option selected disabled value="#">--Seleccionar Categoria--</option>
                <option value="perfume" <?php echo $producto["Categoria"] == "perfume" ? "selected" : "" ?>>perfume</option>
                <option value="ropa" <?php echo $producto["Categoria"] == "ropa" ? "selected" : "" ?>>ropa</option>
                <option value="oferta" <?php echo $producto["Categoria"] == "oferta" ? "selected" : "" ?>>oferta</option>
            </select>

            <div id="contenedor-imagenes">
                <div class="imagen-input">
                    <label for="imagen_0">Imagen 1:</label>
                    <input type="file" name="imagenes[]" id="imagen_0" accept="image/jpeg,image/png" onchange="mostrarPreview(event)">
                </div>
            </div>

            <button class="AddImage" type="button" onclick="agregarInputImagen()">Agregar otra imagen</button>

            <div class="preview-container" id="preview"></div>


            <button id="btnActualizarProducto" class="Boton-1" type="submit">Actualizar Producto</button>
        </fieldset>
    </form>
</div>

<script>
    let contadorImagenes = 1;

    function agregarInputImagen() {
        const contenedor = document.getElementById("contenedor-imagenes");

        const div = document.createElement("div");
        div.classList.add("imagen-input");

        const label = document.createElement("label");
        label.textContent = `Imagen ${contadorImagenes + 1}:`;
        label.setAttribute("for", `imagen_${contadorImagenes}`);

        const input = document.createElement("input");
        input.type = "file";
        input.name = "imagenes[]";
        input.id = `imagen_${contadorImagenes}`;
        input.accept = "image/jpeg,image/png";
        input.onchange = mostrarPreview;

        div.appendChild(label);
        div.appendChild(input);
        contenedor.appendChild(div);

        contadorImagenes++;
    }

    function mostrarPreview(event) {
        const archivos = event.target.files;
        const contenedor = document.getElementById("preview");

        Array.from(archivos).forEach(archivo => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement("img");
                img.src = e.target.result;
                contenedor.appendChild(img);
            };
            reader.readAsDataURL(archivo);
        });
    }
</script>


<?php
incluirTamplate("Footer");
?>