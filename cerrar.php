<?php
//este apartado cerrara todo desde sessiones y eliminara todo lo del carrito cancelando todo
session_start();
session_unset();
session_destroy();

header("Location: index.php"); 
exit();
?>
