<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

@include_once __DIR__ . '/../includes/function.php';
incluirTamplate('header')
?>

<main class="content Login">
  <div class="main__container">
    <h2 class="main__title">Registrate</h2>
    <form class="Form__Login" id="formRegistro" method="POST">
      <fieldset>
        <div id="loginErrors" class="error-messages"></div>
        <label for="nombre">Nombre de usuario:</label>
        <input
          type="text"
          name="nombre"
          id="nombre" />


        <label for="email">Email:</label>
        <input
          type="email"
          name="email"
          id="email" />

        <label for="telefono">Numero:</label>
        <input
          type="tel"
          name="telefono"
          id="telefono" />

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" />

        <input
          type="hidden"
          name="csrf_token"

          value="<?php echo $_SESSION['csrf_token']; ?>" />
        <input type="hidden" value="cliente" name="rol" />


        <input
          class="BotonInicioSesion"
          type="submit"
          id="btnRegistro"
          value="Registrarse" />

      </fieldset>
    </form>
  </div>
</main>
<?php

incluirTamplate('footer');
?>