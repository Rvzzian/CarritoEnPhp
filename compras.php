<?php
session_start();
//informacion de ola db que traera desde otro php
require_once "db.php";

$conexion = new mysqli(SERVER, USUARIO, CONTRASEÑA, DB);

if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
}
//en esta consulta traeremos los datos de los producto que esten en la db y los reultados seran almacenados en 
//la variable $result
$sql = "SELECT id_producto, nombre, precio, stock, cilindrada, imagen FROM producto";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Area de Compras</title>
    <link rel="stylesheet" href="Compras.css">      
</head>
<body>
    <header>
        <h1>Area de Compras</h1>
        <br>
    </header>
    <nav>
        <ul>
            <li><a href="carrito.php">Carrito</a></li>
            <li><a href="cerrar.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    
    <main class="container-productos">
<!--en el siguiente if  a $result se le adignara la propiedad num_rows para poder contar el numero de filas que se 
 obtuvieron en la consulta $result-->
        <?php if ($result->num_rows > 0): ?>
            <!--si el numero que devuelva el num_rows en el if anterior es mayor a 0 entonces entrara en el if 
            ejecutando un while -->
            <!--el siguiente while funcionara de la siguiente manera los resultados de la consulta almacenados en 
            la variable $ressult seran recorridos por el metodo fetch_assoc este los recorre como un array asociativo 
            hasta que los resultados de result sean false o ninguno en su caso se saldra del bucle y row almacenara 
            los resultados del recorrido del fetch_assoc-->
            <?php while($row = $result->fetch_assoc()): ?>
                <section class="producto">
            <!--esto al ser un bucle podemos poner que muestre cada resultado en este caso 
            mostrara la imagen el producto codificada para que se pueda ver en el html-->
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
            <!--el  htmlspecialchars sirve para evitar las inyecciones sql las cuales si se llegaran hacer los caracteres 
            pasaran a ser caracteres de html 
            $row sera la asignacion de la fila del recorrido del fetch_assoc entonces mostrara los resultados ingresando 
            como array el nombre de la tabla en este caso los nombres de las tablas ya estan en la consulta de arriba asique 
            row mostrara los datos almacenados en esa tabla nombre,precio,stock,cilindrada-->
                    <h2><?php echo htmlspecialchars($row['nombre']); ?></h2>
                    <p>Precio: $<?php echo number_format($row['precio']); ?></p>
                    <p>Stock: <?php echo htmlspecialchars($row['stock']); ?></p>
                    <p>Cilindrada: <?php echo htmlspecialchars($row['cilindrada']); ?> cc</p>
            <!--el siguiente boton mandara infromacion del id_producto ,nombre, y stock por medio de la etiqueta 
            button a un script el cual se ejecutara por medio de onclick este abrira la funcion javascript llamada abrirventana
            esto generara una ventana pequeña donde se podra decirdir la cantidad y confirmar la insercion al carrito de los 
            prodyuctos los datos que se enviaran seran los correspondientes a su producto este tipo de datos almacenados en etiquetas 
            html para despues ser utilizados se llama atributos data al poner la funcion se le tiene que pasar los parametros 
            que en la funcion tendran otro noombre en este caso productid sera igual a id_producto , productname = nombre, cantidad = stock-->       
                    <button class="boton-carrito" 
                       onclick="abrirAgregar('<?php echo htmlspecialchars($row['id_producto']); ?>', 
                       '<?php echo htmlspecialchars($row['nombre']); ?>', 
                       <?php echo htmlspecialchars($row['stock']); ?>)">Agregar al Carrito
                    </button>
                </section>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>
    </main>
    
    <div id="ventanaAgregar" class="ventanaAgregar">
        <div class="ventanaA">
        <!--en la siguiente linea span sirve para agrupar elementos en linea y se le asigna el elemetno onclick que
        ejecutara el script cerrarAgregar el cual se ejecutara cuando precionemos la x -->
            <span class="cerrar" onclick="cerrarAgregar()">x</span>
            <h2 id="tituloV">moto</h2>
            <form id="formularioA" action="agregaCarrito.php" method="post">
                <input type="hidden" id="ProductoId" name="id_producto">
                <label for="cantidad">Cantidad:</label>
        <!--en el siguiente input el min significa que debe de agrgar minimo un valor -->
                <input type="number" id="cantidadA" name="cantidad" min="1" required>
                <div class="BotonesA">
                    <button type="submit" class="btn-confirma">Agregar al Carrito</button>
        <!--este boton sera para cerrar la ventana de agregar ejecutando la funcion cerraragregar-->
                    <button type="button" class="btn-cancela" onclick="cerrarAgregar()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <!--apartir de aca inicia el codigo js-->
<script>
    //creo esta funcion la cual se llama abrirAgregar y producId,productname, stock son los parametros de la funcion 
    // los parametros de la funcion como sus nombres lo dicen son los datos que se van a utilizar en la funcion
    function abrirAgregar(productoId, productName, stock) {
        //el siguiete codigo selecciona el elemeto que este identificado en el html con un id titulov
        //.innettext servira para poder seleccionar y remplazar por lo que va despues del igual , ${productname} es una plantilla 
        //la cual se pondra el nombre de los productos
        document.getElementById('tituloV').innerText = `Agregar ${productName} al Carrito`;
        //la sifuiente linea el .value sirve poder cambiar los elementos de entrada del input
        document.getElementById('ProductoId').value = productoId;
        //el siguiente codigo servira para poder dar un tope maximo de las cantidades disponibles en la db 
        //el set atribute sirve para eso establecer limites de ingreso
        document.getElementById('cantidadA').setAttribute('max', stock);
        //la siguiente linea de codigo servira para hcer visible el div con el id ventanaAgregar con el css esta en display none que es igual a invisible
        //y con el siguiente codigo se vuelve visible
        document.getElementById('ventanaAgregar').style.display = 'block';
    }       
//la siguiente funcion servira para volver a poner la ventanaAgregar invisible
    function cerrarAgregar() {
        document.getElementById('ventanaAgregar').style.display = 'none'; 
    }
    //el siguiente codigo es un menejador de eventos el cual se ejecutara cuando se abra ventanaAgregar 
    //el if verifica que funcione el evento cuando este la ventanaAgregar si es asi se ejecutara el cerrarAgregar   
    window.onclick = function(event) {
        if (event.target == document.getElementById('ventanaAgregar')) {
            cerrarAgregar();
        }
    }
</script>

</body>
</html>
