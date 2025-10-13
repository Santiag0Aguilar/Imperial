<?php
@include_once __DIR__ . '../functions.php';
incluirTamplate('session');
@include_once '../includes/config/db.php';

$usuarioId = $_SESSION['idUsuario'] ?? null;
$db = db();

$CantidadCarrito = 0;

if (isset($usuarioId)) {
    $stmt = $db->prepare("SELECT SUM(cantidad) as total FROM detallepedido dp 
                          JOIN pedidos p ON dp.Pedidos_Id = p.Id 
                          WHERE p.Usuarios_id = ? AND p.estado = 'pendiente'");
    $stmt->execute([$usuarioId]);
    $result = $stmt->fetch();

    $CantidadCarrito = $result['total'] ?? 0;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perfumeria</title>
    <!-- Nouislider -->
    <script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.0/dist/nouislider.min.js"></script>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/nouislider@15.7.0/dist/nouislider.min.css" />
    <link rel="stylesheet" href="/Perfumeria/Public/build/css/index.css" />
    <!-- Swiper CSS -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <?php if (isset($Leaflet) && $Leaflet === true) : ?>
        <!-- Leaflet CSS -->
        <link
            rel="stylesheet"
            href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

        <!-- Leaflet JS -->
        <script
            src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            defer></script>
    <?php endif; ?>

    <?php if (isset($Paypal) && $Paypal === true): ?>
        <!-- PayPal JS SDK -->
        <script src="https://www.paypal.com/sdk/js?client-id=AY0229UWPI4ripuI25Dd2F1L4rGhpNztE_su9MjiuhAG6NKiAbrof013wCwrdoCtE-DPkLiw8myfdpeQ&components=buttons,hosted-fields&currency=MXN&intent=capture"></script>
    <?php endif; ?>

    <?php if (isset($Swal) && $Swal === true): ?>
        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <?php endif; ?>
</head>

<body>

    <?php
    var_dump($_SESSION);
    ?>
    <header class="<?php echo isset($BgHeader) && $BgHeader === true ? 'header' : 'HeaderSinBgImage'; ?>">
        <div class="<?php echo isset($BgHeader) && $BgHeader === true ? 'header_Container' : 'HeaderSinBgImage__Container'; ?>">
            <div class="header-top content">
                <a href="/Perfumeria/Public/index.php">
                    <h1 class="Logo">Imperium<span class="LogoSpan">Parfum</span></h1>
                </a>

                <div class="Header__Carrito">
                    <a href="/Perfumeria/Public/carrito/carrito.php">
                        <img src="/Perfumeria/Public/Imagenes/shoppingCar.svg" alt="ShoppingCar" class="shoppingCar" />
                        <div class="shoppingCar__Cantidad cantidadActiva">
                            <p class="shoppingCar__Cantidad--texto"><span id="carritoCantidad"><?php echo $CantidadCarrito ?></span></p>
                        </div>
                    </a>
                </div>

                <div class="mobileversion">
                    <img src="/Perfumeria/Public/Imagenes/barras.svg" alt="Barras" />
                </div>
                <nav class="nav Header__Nav">
                    <ul class="menu DarkModeAndNav">
                        <li>
                            <img
                                class="dark-mode"
                                src="/Perfumeria/Public/Imagenes/dark-mode.svg"
                                alt="dark-mode" />
                        </li>
                        <li><a class="menu__link" href="/Perfumeria/Public/index.php">Inicio</a></li>
                        <li><a class="menu__link" href="/Perfumeria/Public/nosotros.php">Nosotros</a></li>
                        <li><a class="menu__link" href="/Perfumeria/Public/contacto.php">Contacto</a></li>

                        <li class="dropdown">
                            <a class="Enlace__Desplegable menu__link" href="#">Productos ▾</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-menu__item" href="/Perfumeria/Public/productos/perfumes.php">Perfumes</a></li>
                                <li><a class="dropdown-menu__item" href="/Perfumeria/Public/productos/ropa.php">Ropa</a></li>
                                <li><a class="dropdown-menu__item" href="/Perfumeria/Public/productos/ofertas.php">Ofertas</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="Session__Account">
                        <?php if (isset($_SESSION["Rol_Usuario"])): "" ?>
                            <a class="Cuenta__Usuario--Logo" href="/Perfumeria/Public/usuario.php">
                                <p class="iniciales"><?php echo $inicial = strtoupper(substr($_SESSION['Nombre_Usuario'], 0, 2)); ?></p>
                                <p class="correo"><?php echo $_SESSION["Nombre_Usuario"] ?></p>
                            </a>
                        <?php elseif (!isset($_SESSION["idUsuario"])): ?>
                            <a class="Sin__Cuenta" href="/Perfumeria/Public/login.php">
                                <img class="Account-svg" width="30px" src="/Perfumeria/Public/Imagenes/account-svg.svg" alt="#">
                                <p class="Sin__Cuenta--texto">Iniciar sesion</p>
                            </a>
                        <?php endif; ?>
                    </div>

                </nav>
            </div>
        </div>
    </header>