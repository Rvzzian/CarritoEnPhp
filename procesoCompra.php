<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['cc'])) {
    die("Acceso denegado. Por favor, inicia sesión.");
}

$conexion = new mysqli(SERVER, USUARIO, CONTRASEÑA, DB);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['cc'];
$fecha = date("Y-m-d");
$total = 0;
$productos = [];

if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
        $consulta = "SELECT precio, stock, nombre FROM producto WHERE id_producto = '$id_producto'";
        $resultado = $conexion->query($consulta);
        
        if ($resultado->num_rows > 0) {
            $reg = $resultado->fetch_assoc();
            $precio = $reg['precio'];
            $stock = $reg['stock'];
            $nombre = $reg['nombre'];

            if ($stock >= $cantidad) {
                $productos[] = [
                    'id_producto' => $id_producto,
                    'nombre' => $nombre,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $precio * $cantidad
                ];
                $total += $precio * $cantidad;
            } else {
                echo "Stock insuficiente para el producto: $id_producto";
                exit();
            }
        } else {
            echo "Producto no encontrado: $id_producto";
        }
    }
}

$busquedaPedidos = mysqli_query($conexion, "SELECT MAX(id_pedido) as id_pedido FROM pedidos");


if ($busquedaPedidos->num_rows > 0) {
    $reg = mysqli_fetch_assoc($busquedaPedidos);
    $id_pedido = $reg['id_pedido'] + 1;
}

$insercionPedidos = "INSERT INTO pedidos (id_pedido, id_usuario, fecha, total) VALUES ('$id_pedido', '$id_usuario', '$fecha', '$total')";
if ($conexion->query($insercionPedidos) === TRUE) {
    foreach ($productos as $producto) {
        $id_producto = $producto['id_producto'];
        $cantidad = $producto['cantidad'];
        $precio_unitario = $producto['precio_unitario'];
        $subtotal = $producto['subtotal'];
        
        $insercionDetalle = "INSERT INTO detalles_pedidos (id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES ('$id_pedido', '$id_producto', '$cantidad', '$precio_unitario', '$subtotal')";
        if ($conexion->query($insercionDetalle) !== TRUE) {
            echo "Error al insertar detalle del pedido: " . $conexion->error;
        } else {
            $actualizarStock = "UPDATE producto SET stock = stock - $cantidad WHERE id_producto = '$id_producto'";
            if ($conexion->query($actualizarStock) !== TRUE) {
                echo "Error al actualizar el stock del producto: " . $conexion->error;
            }
        }
    }

    echo "factura guardada con éxito";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_REQUEST['id_producto']) && isset($_REQUEST['cantidad'])) {
            $id_producto = $_REQUEST['id_producto'];
            
            if (isset($_SESSION['carrito'])) {
                unset($_SESSION['carrito'][$id_producto]);
                
                $factura = fopen("factura_$id_pedido.txt", "w");
                if ($factura) {
                    fputs($factura, "Factura No: $id_pedido\n");
                    fputs($factura, "Fecha: $fecha\n");
                    fputs($factura, "Usuario ID: $id_usuario\n\n");
                    fputs($factura, "Productos:\n");
                    fputs($factura, "-------------------------------------------\n");

                    foreach ($productos as $producto) {
                        fputs($factura, "Producto: " . $producto['nombre'] . "\n");
                        fputs($factura, "Cantidad: " . $producto['cantidad'] . "\n");
                        fputs($factura, "Precio Unitario: $" . number_format($producto['precio_unitario'], 2) . "\n");
                        fputs($factura, "Subtotal: $" . number_format($producto['subtotal'], 2) . "\n");
                        fputs($factura, "-------------------------------------------\n");
                    }
                    fputs($factura, "Total: $" . number_format($total, 2) . "\n");
                    fclose($factura);
                } else {
                    echo "Error al crear el archivo de la factura";
                }
            } else {
                echo "El carrito está vacío.";
            }
        } 
    } else {
        echo "Ha ocurrido un error";
    }
} else {
    echo "Error al insertar el pedido: " . $conexion->error;
}

$conexion->close();
?>
