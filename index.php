<?php
// Start the session
session_start();
$Rol = $_SESSION["Rol_Usuario"] ?? null;
echo $Rol;

if (!isset($_SESSION["idUsuario"])) {
    echo "Session not started";
} elseif ($_SESSION["Rol_Usuario"] == "admin") {
    echo "Session started as admin";
} else {
    echo "Session started white not admin";
}
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";
