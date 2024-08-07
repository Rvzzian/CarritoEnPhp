<?php
session_start();
require_once "db.php";

$conexion = new mysqli(SERVER, USUARIO, CONTRASEÑA, DB);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$productos = [];
$total = 0;

if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
        $consulta = $conexion->query("SELECT nombre, precio, imagen FROM producto WHERE id_producto = '$id_producto'");
        
        if ($consulta && $consulta->num_rows > 0) {
            $reg = $consulta->fetch_assoc();
            $nombre = $reg['nombre'];
            $precio = $reg['precio'];
            $imagen = $reg['imagen'];
            
            $productos[] = [
                'id_producto' => $id_producto,
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad,
                'subtotal' => $precio * $cantidad,
                'imagen' => $imagen
            ];

            $total += $precio * $cantidad;
        } else {
            echo "Producto no encontrado: $id_producto";
        }
    }
}

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="carrito.css">
</head>
<body>
    <header>
        <h1>Carrito de Compras</h1>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Imagen</th>
                    <th>Preci0</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($productos) > 0): ?>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']);
                                 ?>" style="width: 100px; height: auto;">
                            </td>
                            <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                            <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                            <td>$<?php echo number_format($producto['subtotal'], 2); ?></td>
                            <td>
                                <form action="actualizarCarrito.php" method="post">
                                    <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['id_producto']); ?>">
                                    <input type="hidden" name="cantidad" value="<?php echo htmlspecialchars($producto['cantidad']); ?>">
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">El carrito está vacío.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <p>Total: $<?php echo number_format($total, 2); ?></p>
    </main>
    <form action="compras.php">
        <input type="submit" value="Volver">
    </form>
    <?php if (count($productos) > 0): ?>
        <form action="procesoCompra.php" method="post">
        <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['id_producto']); ?>">
        <input type="hidden" name="cantidad" value="<?php echo htmlspecialchars($producto['cantidad']); ?>">
            <input type="submit" value="Comprar">
        </form>
    <?php endif; ?>
</body>
</html>
