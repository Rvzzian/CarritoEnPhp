<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_REQUEST['id_producto']) && isset($_REQUEST['cantidad'])) {
        $id_producto = $_REQUEST['id_producto'];
        
        if (isset($_SESSION['carrito'])) {
            unset($_SESSION['carrito'][$id_producto]);
            
            header("Location: carrito.php");
            exit();
        } else {
            echo "El carrito está vacío.";
        }
    } 
} else {
    echo "ha ocurrido un error";
}
?>

