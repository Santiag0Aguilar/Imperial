<?php
@include_once '../includes/function.php';
$auth = ValidarUser(); // Llamada a la funcion de ValidarUser

if (!$auth) {
  header("Location: /Perfumeria/index.php"); // Redirigir a la página de inicio si no está autenticado
  exit;
}

@include_once '../includes/config/db.php';
$db = db(); // Llamada a la funcion de conexion a la base de datos

$CorreoAsociado = $_SESSION['Nombre_Usuario']; // Obtener el correo asociado a la sesión
// Verificar si el correo asociado está definido en la sesión y no está vacío
if (isset($CorreoAsociado) && !empty($CorreoAsociado)) {
  // Si el correo asociado está definido, puedes usarlo para realizar consultas a la base de datos
  $stmt = $db->prepare("SELECT * FROM usuarios WHERE CorreoAsociado = :CorreoAsociado");
  $stmt->bindParam(':CorreoAsociado', $CorreoAsociado);
  $stmt->execute();
  $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener los datos del usuario asociado al correo
} else {
  // Manejar el caso en que el correo asociado no esté definido o esté vacío
  echo "Error: Correo asociado no encontrado.";
  exit;
}


incluirTamplate('header'); // Llamada a la funcion de header
?>

<div class="User">
  <div class="User__Container content">

    <div class="User__Container__Icon">
      <p><?php echo $inicial = strtoupper(substr($_SESSION['Nombre_Usuario'], 0, 2)); ?></p>
      <img
        class="User__Container__IconAccount"
        src="Imagenes/account-svg.svg"
        alt="account-svg" />
    </div>
    <h2>Bienvenido, <?php echo $_SESSION['Rol_Usuario'] == "admin" ? "Admin" : "Usuario" ?></h2>

    <div class="User__Container__Info">
      <p><span class="resaltar">Nombre:</span> <?php echo $usuario['NombreUsuario'] ?></p>
      <p><span class="resaltar">Email:</span> <?php echo $usuario['CorreoAsociado'] ?></p>
      <p><span class="resaltar">Telefono:</span> <?php echo $usuario['NumeroAsociado'] ?></p>
    </div>
    <div class="User__Container__Info__Buttons">
      <a class="Boton-1" href="#">Mis compras</a>
      <a class="Boton-1" href="/Perfumeria/Public/carrito/carrito.php">Mi carrito</a>

      <?php if ($_SESSION['Rol_Usuario'] == "admin"): ?>
        <a class="Boton-1" href="/Perfumeria/Admin/vistas/dashboard.php">Panel de Administracion</a>
      <?php endif; ?>
    </div>
    <a class="Boton-1" href="/Perfumeria/Public/account/CambiarPassword.php">Cambiar contraseña</a>
  </div>
</div>

<div class="content">
  <a class="Boton-2" href="/Perfumeria/Public/account/CerrarSession.php">Cerrar Sesion</a>
</div>

<?php
incluirTamplate('footer'); // Llamada a la funcion de footer
?>