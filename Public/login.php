<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
@include_once __DIR__ . '/../includes/function.php';/*  */
var_dump($_SESSION);

incluirTamplate('header')

/* value="<?php echo $Correo_Asociado; ?>" */
/* value="<?php echo $_SESSION['csrf_token']; ?>" */

?>

<main class="content Login">
  <div class="main__container">
    <h2 class="main__title">Iniciar sesión</h2>
    <form class="Form__Login">
      <fieldset>

        <legend>Datos:</legend>
        <div id="loginErrors" class="error-messages"></div>
        <label for="email">Email:</label>
        <input
          type="email"
          name="email"
          id="email"
          placeholder="Tu email" />

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" placeholder="Tu email" />

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

        <input type="hidden" value="cliente" name="rol" />

        <input
          class="BotonInicioSesion"
          type="submit"
          id="btnLogin"
          value="Iniciar sesión" />
        <p>
          ¿Olvidas tu contraseña o tu correo electronico?
          <a href="/Perfumeria/Public/account/recuperar_cuenta.php">Recupera tu cuenta</a>
        </p>
      </fieldset>
      <p>
        ¿No tienes una cuenta?
        <a href="registro.php">Crea una</a>
      </p>
    </form>
  </div>
</main>


<?php

incluirTamplate('footer');
?>