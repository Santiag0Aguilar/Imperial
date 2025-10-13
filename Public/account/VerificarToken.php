<?php
@include_once __DIR__ . '/../../includes/function.php';
incluirTamplate('session');

require_once __DIR__ . "/../../includes/config/db.php";
$db = db();


var_dump($_SESSION);

incluirTamplate("header");
?>

<main class="content formConfirmarToken">
    <h2>Codigo de confirmacion</h2>

    <form id="FormVerificarToken" class="formConfirmarToken__Inputs Form__Login" method="POST">
        <fieldset>
            <label for="email">Coloca el correo asociado:</label>
            <input type="email" id="email" name="email" required>

            <label for="token">Coloca el token:</label>
            <input type="number" id="token" name="token" required maxlength="6" min="6">

        </fieldset>

        <div id="errorContainer"></div>
        <button id="BtnVerificarToken" type="submit" class="Boton-1">Verificar codigo</button>
    </form>
</main>

<?php
incluirTamplate("footer");
?>