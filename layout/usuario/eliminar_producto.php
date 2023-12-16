<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["index"])) {
    $index = $_POST["index"];
    
    if (isset($_SESSION["carrito"][$index])) {
        unset($_SESSION["carrito"][$index]);
        $_SESSION["carrito"] = array_values($_SESSION["carrito"]);
        echo "Producto eliminado del carrito";
    } else {
        echo "Índice de producto no válido";
    }
} else {
    echo "Solicitud no válida";
}
?>
