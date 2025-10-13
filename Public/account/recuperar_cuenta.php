<?php
@include_once __DIR__ . '/../../includes/function.php';
incluirTamplate('session');

require_once __DIR__ . "/../../includes/config/db.php";
$db = db();



var_dump($_SESSION);

incluirTamplate("header");
?>

<main class="content">
    <h2>Recupera tu cuenta</h2>

    <form id="formRecuperarCuenta" class="Form__Login" method="POST">
        <fieldset>
            <label for="email">Coloca el correo asociado:</label>
            <input type="email" id="email" name="email" required>

            <label for="telefono">Coloca el numero asociado:</label>
            <input type="tel" id="telefono" name="telefono" required>

        </fieldset>
        <button id="btnRecuperarCuenta" type="submit" class="Boton-1">recuperar cuenta</button>

        <div id="errorContainer"></div>
    </form>
</main>

<?php
incluirTamplate("footer");
?>