<?php
session_start();
require_once "db.php";

$conexion = new mysqli(SERVER, USUARIO, CONTRASEÑA, DB);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id_producto = $_REQUEST['id_producto'];
$cantidad = $_REQUEST['cantidad'];

$consulta = $conexion->query("SELECT stock FROM producto WHERE id_producto = '$id_producto'");
if ($consulta->num_rows > 0) {
    $reg = $consulta->fetch_assoc();
    $stock = $reg['stock'];
} else {
    echo "Producto no encontrado.";
    exit();
}

if ($cantidad > $stock) {
    echo "Cantidad excede el stock disponible.";
    exit();
}

if (isset($_SESSION['carrito'][$id_producto])) {
    $_SESSION['carrito'][$id_producto] += $cantidad;
} else {
    $_SESSION['carrito'][$id_producto] = $cantidad;
}

header("Location: carrito.php");
exit();
?>
