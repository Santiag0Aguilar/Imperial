<?php


@include_once "../includes/function.php";

incluirTamplate('header', [
  'Leaflet' => $Leftlet = true
]);
?>
<main class="ContactoMain content">
  <h2>Sobre ImperiumParfum</h2>

  <div class="contacto__Page">
    <img src="imagenes/BgHeader.jpg" alt="Contacto">
    <div class="contacto_Page__text">
      <p> En <strong>ImperiumParfum</strong> nos dedicamos a ofrecerte perfumes 100% originales de las marcas más reconocidas a nivel mundial. Nuestra misión es acercarte tus fragancias favoritas de forma rápida, segura y con la mejor atención, estés donde estés en México.</p>
      <p>
        Somos tu mejor opción porque combinamos precios competitivos, atención personalizada y envíos a toda la República. Cada pedido se prepara con cuidado para garantizar que tu experiencia sea tan buena como el aroma que elijas. Compra con confianza, compra con estilo. </p>
    </div>
  </div>
</main>

<main class="Nosotros content">
  <h2>Comprar con nosotros</h2>

  <div class="Nosotros__Informcion">
    <div class="Nosotros_ContenedorInfo">
      <img src="Imagenes/icono1.svg" alt="Icono uno" />
      <h3>Compra segura</h3>
      <p>
        En ImperiumParfum, tu confianza es lo más importante. Por eso, cada
        compra está protegida con métodos de pago seguros y confiables.
        Nuestro compromiso es brindarte una experiencia libre de
        preocupaciones, con atención personalizada y garantía de
        satisfacción.
      </p>
    </div>
    <div class="Nosotros_ContenedorInfo">
      <img src="Imagenes/icono2.svg" alt="Icono dos" />
      <h3>Exlusivos</h3>

      <p>
        En ImperiumParfum combinamos elegancia y buen gusto con precios
        justos. Descubre ropa y perfumes de alta calidad, seleccionados para
        quienes valoran el estilo sin pagar de más. Lujo al alcance de
        todos, sin comprometer la autenticidad.
      </p>
    </div>
    <div class="Nosotros_ContenedorInfo">
      <img src="Imagenes/icono3.svg" alt="Icono tres" />
      <h3>a tiempo</h3>

      <p>
        Valoramos tu tiempo tanto como tú. Por eso, garantizamos entregas
        rápidas y seguras, para que disfrutes tus productos exclusivos justo
        cuando los necesitas. Calidad y cumplimiento van de la mano.
      </p>
    </div>
  </div>
</main>

<section class="content Ubicacion">

  <h2>Nuestra ubicacion</h2>
  <p>¡Estamos en CDMX, pero llegamos hasta donde estés!
    En ImperiumParfum, no importa en qué parte de México te encuentres, llevamos tu fragancia favorita hasta la puerta de tu hogar.
    Calidad, estilo y aroma exclusivo, directo desde la Ciudad de México hasta cualquier rincón de la República.

  </p>


  <div id="map" style="height: 350px;"></div>
</section>

<?php
incluirTamplate('footer');
?>