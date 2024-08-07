<?php 
//el session start sirve para tener un sistema de control de la sesicion la cual servira para al macenar datos y mantenerlos
//en distintos php
session_start();
//este require_once servira para traer la informacion de la base de datos para ser utilizada en este php
require_once "db.php";
$conexion = mysqli_connect(SERVER,USUARIO,CONTRASEÑA,DB);

//esto servira para poder tener un control de errorres en la conexion a la db
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
//traemos los datos del formulario para verificar otra vez que sean los correctos 
$correo = $_REQUEST['correo'];
$contraseña = $_REQUEST['contraseña'];
//verificaremos con otra consulta aun que el correo no es una primary key de momento lo hare con $correo
$consulta = $conexion->query("select cc,nombre,apellido,admin,contraseña FROM usuario WHERE correo = '$correo'");
//este if nos servira para contar los resultados de la consulta por medio de num_rows ayuda a contar los resultados de la
//consulta y si es > (mayor a 0) entrara en el if
if ($consulta->num_rows > 0) {
//el fetch_assoc servira para recorrer los resultados como un array entonces reg almacenara los resultados del recorrido del array
    $reg = $consulta->fetch_assoc();
    //$reg[] al ser lo que almacena el recorrido del array de la consulta se le asignara el la informacion del cc que traiga la consulta
    //asi mismo el resto de la consulta sera almacena en variables 
    $cc = $reg['cc'];   
    $nombre = $reg['nombre'];
    $apellido = $reg['apellido'];
    $admin = $reg['admin'];
    $password = $reg['contraseña'];
    //este if verificara si la contraseña traida de la consulta es igual a la de la ingresada del ingreso si es igual
    //serguira con el if
    if ($contraseña == $password) {
        //al entrar en el if se almacenara los datos del usuario en la session con $_session[] 
        $_SESSION['cc'] = $cc;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['admin'] = $admin;
        
    //en este siguiente if validaremos si es usuario o admin con un 0 o un 1 que son los valores que les puse 
    //en la db si esta almacenado con un 1 era admin si es un 0 sera un usuario la creacion de estos dos perfiles 
    //slos hago desde la db
        if ($admin == 0) {
            //los usuarios con un 0 en la db podran acceder a las compras 
            header("Location: compras.php");
        }elseif ($admin == 1) {
            //los administradores podran subir mas productos a la db de momento 
            header("Location: subida.php");
        }
        //este exit nos sacara del if pues ya no va ver necesidad de que siga pues se espera la debida redireccion
        exit();
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "No se encontró una cuenta con ese correo.";
}
//se cierra la conexion 
$conexion->close();
?>


