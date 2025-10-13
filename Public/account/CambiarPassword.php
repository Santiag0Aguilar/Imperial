<?php
@include_once __DIR__ . '/../../includes/function.php';
incluirTamplate('session');

require_once __DIR__ . "/../../includes/config/db.php";
$db = db();




incluirTamplate("header");


var_dump($_SESSION);

$IdUsuario = $_SESSION['idUsuario'] ?? null;

if (!$IdUsuario) {
    header("Location: /Perfumeria/Public/account/recuperar_cuenta.php");
    exit;
}
?>
<main class="content">
    <h2>Recupera tu cuenta</h2>

    <form id="formChangesPassword" class="Form__Login" method="POST">
        <fieldset>
            <label for="password">Coloca la nueva contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="passwordConfirm">Confirma la contraseña:</label>
            <input type="password" id="passwordConfirm" name="passwordConfirm" required>

            <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo htmlspecialchars($IdUsuario); ?>">


        </fieldset>
        <button id="BtnChangesPassword" type="submit" class="Boton-1">Cambiar contraseña</button>

        <div id="errorContainer"></div>
    </form>
</main>

<?php
incluirTamplate("footer");
?>