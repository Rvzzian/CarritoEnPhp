<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="logeo-container">
        <h1>Ingreso</h1>
        <form action="redireccion.php" method="post">
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            
            <input type="submit" value="Ingresar">
        </form>
    </div>
</body>
</html>
