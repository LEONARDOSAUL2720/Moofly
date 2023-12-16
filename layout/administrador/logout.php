<?php
// Iniciar sesión (si no se ha iniciado)
session_start();

// Destruir todas las variables de sesión
session_destroy();

// Redirigir al usuario a "index.php"
header('Location:  ../../index.php');
exit();
?>
