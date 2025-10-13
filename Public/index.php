<?php
session_start();
// Verificar si la sesión está iniciada
@include_once '../includes/config/db.php';
$db = db(); // Llamada a la funcion de conexion a la base de datos


var_dump($_SESSION);
$BgHeader = true;
@include_once '../includes/function.php';
incluirTamplate('header', [
  'BgHeader' => $BgHeader,
]);


?>


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

<section class="contacto">
  <div class="contacto-texto content">
    <h2>Encuentra tu estilo</h2>
    <p>
      Llena el formulario de contacto y un asesor se pondrá en contacto
      contigo a la brevedad
    </p>

    <a class="Boton-1" href="#">Contactanos</a>
  </div>
</section>

<section class="Favoritos content">
  <h2>Los favoritos</h2>
  <div class="swiper">
    <div class="swiper-wrapper">
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/perfume1.jpeg" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Perfume 1</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/perfume2.jpeg" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Perfume 2</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/perfume3.jpeg" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Perfume 3</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/perfume4.jpeg" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Perfume 4</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/perfume5.jpeg" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Perfume 5</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/perfume6.jpeg" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Perfume 6</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/perfume7.jpeg" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Perfume 7</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>

      <!-- ... -->
    </div>
    <!-- Controles de navegación -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>
</section>

<section class="publicidad">
  <h2>Aromas y Estilo — Inspiración para Cada Personalidad</h2>
  <div class="anuncios">
    <a href="#"><img src="Imagenes/publicidad/man.png" alt="" /></a>
    <a href="#"><img src="Imagenes/publicidad/girl.png" alt="" /></a>
    <a href="#"><img src="Imagenes/publicidad/clothes.png" alt="" /></a>
  </div>
</section>

<section class="Favoritos content">
  <h2>Lo mas visto</h2>
  <div class="swiper">
    <div class="swiper-wrapper">
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/Ropa1.webp" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Prenda 1</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/ropa2.webp" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Prenda 2</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/ropa3.webp" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Prenda 3</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/ropa4.webp" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Prenda 4</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/ropa5.webp" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Prenda 5</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/ropa6.webp" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Prenda 6</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>
      <!-- Repite este bloque para cada producto -->
      <div class="swiper-slide Favorito">
        <div class="visual__content">
          <div class="descuento">
            <p>30%</p>
          </div>
          <img src="Imagenes/ropa7.webp" alt="perfume1" />
        </div>
        <!-- Contenido del producto -->
        <p class="nombre">Prenda 7</p>
        <div class="precios">
          <p class="precio-normal">$2000</p>
          <p class="precio-descuento">$1300</p>
        </div>

        <a class="Boton-1" href="#">Agregar al carrito</a>
      </div>

      <!-- ... -->
    </div>
    <!-- Controles de navegación -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>
</section>

<?php
incluirTamplate('footer')
?>