<?php
session_start();

include("db.php"); // Asegúrate de incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["carrito"]) && count($_SESSION["carrito"]) > 0 && isset($_POST["id_usuario"]) ) {
    foreach ($_SESSION["carrito"] as $producto) {
        $idUsuario = $_POST["id_usuario"];
        
        $nombreProducto = mysqli_real_escape_string($conexion, $producto["nombre"]);
        $precioProducto = floatval($producto["precio"]);
        $cantidadProducto = intval($producto["cantidad"]);
        $codigoProducto = intval($producto["codigo"]);

        // Consulta para obtener el código del producto por su nombre
        $queryCodigoProducto = "SELECT codigo FROM producto WHERE nombre = '$nombreProducto'";
        $resultCodigoProducto = mysqli_query($conexion, $queryCodigoProducto);

        if ($resultCodigoProducto && mysqli_num_rows($resultCodigoProducto) > 0) {
            $rowCodigoProducto = mysqli_fetch_assoc($resultCodigoProducto);
          

            // Verificar existencias del producto
            $queryExistencias = "SELECT existencias FROM producto WHERE codigo = $codigoProducto";
            $resultExistencias = mysqli_query($conexion, $queryExistencias);
            $rowExistencias = mysqli_fetch_assoc($resultExistencias);

            if ($rowExistencias["existencias"] > $cantidadProducto) {
                // Realizar la compra
                if ($cantidadProducto >0){
                $queryCompra = "UPDATE producto SET existencias = existencias - $cantidadProducto WHERE codigo = $codigoProducto";
                $resultCompra = mysqli_query($conexion, $queryCompra);
            

                if ($resultCompra) {
                    $id_usuario = 2;
                    // Insertar registro en la tabla de ventas
                    $queryInsertVenta = "INSERT INTO compra (codigo, cantidad, id_usuario) VALUES ($codigoProducto, $cantidadProducto, $idUsuario)";
                    mysqli_query($conexion, $queryInsertVenta);
                }
                // Vaciar el carrito después de realizar la compra
                $_SESSION["carrito"] = array();
                echo "Compra realizada exitosamente.";
            } else {
                $_SESSION["carrito"] = array();
                echo "las existencias deben ser mayores a 0 .";
            } 
            } else {
                // Vaciar el carrito después de realizar la compra
                $_SESSION["carrito"] = array();
                echo "No hay existencias disponibles.";
         
           
        }
        }
    }
} else {
    echo "No se pudo realizar la compra. El carrito está vacío.";
}

?>
