<?php
@include_once __DIR__ . '/../includes/function.php';

incluirTamplate("header");
?>
<main class="Contacto content">
  <h1>Contacto</h1>

  <img src="Imagenes/ContactoSeccionImage.jpg" alt="contacto" />
  <h3>Formulario de contacto</h3>

  <form class="Formulario__Contacto">
    <fieldset>
      <legend>Información Personal</legend>

      <label for="nombre">Nombre</label>
      <input type="text" placeholder="Tu Nombre" id="nombre" />

      <label for="email">E-mail</label>
      <input type="email" placeholder="Tu Email" id="email" />

      <label for="telefono">Teléfono</label>
      <input type="tel" placeholder="Tu Teléfono" id="telefono" />

      <label for="mensaje">Mensaje:</label>
      <textarea id="mensaje"></textarea>
    </fieldset>
    <input class="Boton-1" type="submit" value="Enviar" />
  </form>
</main>

<?php
incluirTamplate("footer");
?>