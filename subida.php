<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
<!--comparte css con index.php-->
    <title>cargar imagen </title>
</head>
<body> 
    <div class="formulario-container">
        <h1>Agregar Producto</h1>
    <form action="phph.php" method="post" enctype="multipart/form-data" id="subir-Productos">
        <label for="id_categoria">ID Categoría:</label>
        <select id="id_categoria" name="id_categoria" required>
            <option value="1">1:Susuki</option>
            <option value="2">2:Akt</option>
        </select><br><br>
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>
        
        <label for="precio">Precio:</label>
        <input type="number"  id="precio" name="precio" required><br><br>
        
        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required><br><br>
        
        <label for="cilindrada">Cilindrada:</label>
        <input type="number" id="cilindrada" name="cilindrada"><br><br>
        
        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" required><br><br>
        
        <input type="submit" value="Agregar Producto">
    </form>
</div>

<br>
<br>
<!---<form action="index2.php" method="post" id="buscar-producto">
     buscar imagen 
    <input type="submit" value="ir ">
</form>--->

<br>
<br>

<!---<form action="busquedaimg.php" method="post" enctype="multipart/form-data">
    suba la imagen que quiere buscar
    <br> 
    <input type="file" required name="imagen" class="form-control" >
    <br>
    <br>
    <input type="submit" value="buscar">--->


</form>
</body>
</html>