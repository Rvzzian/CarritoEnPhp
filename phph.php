<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
$conexion = mysqli_connect("localhost", "root", "", "tienda") or die("Problemas en la conexión");

$imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));

$id_categoria=$_REQUEST['id_categoria'];
$nombre=$_REQUEST['nombre'];
$precio=$_REQUEST['precio'];
$stock=$_REQUEST['stock'];
$cilindrada=$_REQUEST['cilindrada'];

$busqueda = mysqli_query($conexion,"select * from producto where imagen='$imagen'") 
or die("problemas con la query1" .mysqli_error($conexion));
if ($busqueda->num_rows > 0) {
    ?>
    <div class="notificacion" style=" margin: 20px;">
        <p ><?php
    echo"ya esta ese producto";?>
        </p>
    </div>
    <?php
     mysqli_close($conexion);
}else {
    mysqli_query($conexion, "INSERT INTO producto (id_categoria,nombre, precio, stock, cilindrada, imagen)
    VALUES ('$id_categoria','$nombre','$precio','$stock','$cilindrada','$imagen')") 
    or die("Problemas en la sentencia SQL: " . mysqli_error($conexion));
    
    mysqli_close($conexion);
    ?>
    <div class="notificacion">
        <p><?php
        echo "La imagen fue cargada exitosamente";
            ?>
        </p>
    </div>
    <?php
}

?>
<form action="index.html" method="post" class="volver">
    <input type="submit" value="volver">
</form>

</body>
</html>