<?php
include_once '../../includes/function.php';

$auth = ValidarAdmin();
if (!$auth) {
    header("Location: /Perfumeria/index.php");
    exit;
}

include_once '../../includes/config/db.php';
$db = db();

$formdescuento = isset($_GET['descuento']) ? $_GET['descuento'] : null;

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}



incluirTamplate("header");
?>





<div class="main__containerForm">
    <?php if ($formdescuento): ?>
        <h1 class="centrar-texto">Crear nueva oferta</h1>
    <?php else: ?>
        <h1 class="centrar-texto">Crear nuevo producto</h1>
    <?php endif; ?>

    <form class="Form__CrearProducto content" id="formProducto" action="crear.php" enctype="multipart/form-data" method="POST">
        <fieldset class="fieldset-crearProducto">
            <div id="loginErrors" class="error-messages"></div>
            <legend>Crear Productos</legend>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="precio">Precio:</label>
            <input type="number" step="0.01" id="precio" name="precio" required>

            <label for="stock">Stock del producto</label>
            <input type="number" id="stock" name="stock" required>

            <?php if ($formdescuento) : ?>
                <label for="descuento">Precio con descuento:</label>
                <input type="number" step="0.01" id="descuento" name="descuento" required>
            <?php endif; ?>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" required></textarea>

            <label for="genero">Género:</label>
            <select id="genero" name="genero" required>
                <option selected disabled value="#">--Seleccionar Genero--</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="unisex">Unisex</option>
                <option value="niños">Niños</option>
            </select>

            <label for="marca">Marca:</label>
            <input type="text" name="marca" id="marca" required>


            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria" class="CategoriaInput" required>
                <option selected disabled value="#">--Seleccionar Categoria--</option>
                <option value="perfume">perfume</option>
                <option value="ropa">ropa</option>
                <option value="oferta">oferta</option>
            </select>

            <div id="contenedor-imagenes">
                <div class="imagen-input">
                    <label for="imagen_0">Imagen 1:</label>
                    <input type="file" name="imagenes[]" id="imagen_0" accept="image/jpeg,image/png" onchange="mostrarPreview(event)">
                </div>
            </div>

            <button class="AddImage" type="button" onclick="agregarInputImagen()">Agregar otra imagen</button>

            <div class="preview-container" id="preview"></div>


            <button id="btnCrearProducto" class="Boton-1" type="submit">Crear Producto</button>
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