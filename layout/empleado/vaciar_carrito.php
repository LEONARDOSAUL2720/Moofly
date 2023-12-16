<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    unset($_SESSION["carrito"]);
    echo "Carrito vaciado exitosamente.";
}
?>
