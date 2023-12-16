<?php
session_start();

include("db.php");
$idUsuario = $_SESSION['id_usuario'];
// Código para obtener los productos desde la base de datos agrupados por tipo
$sql = "SELECT * FROM vista_ProductoDetalleIMG ";
$result = mysqli_query($conexion, $sql);

if (!$result) {
    die('Error al ejecutar la consulta: ' . mysqli_error($conexion));
}
?>
<?php

include("db.php");

$idUsuario = $_SESSION['id_usuario'];
// Lógica para agregar productos al carrito
// ...

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombreProducto"]) && isset($_POST["precioProducto"]) && isset($_POST["cantidadProducto"]) && isset($_POST["codigo"])) {

    // Obtener el código del producto que se intenta agregar
    $codigo = $_POST["codigo"];

    if (!isset($_SESSION["carrito"])) {
        $_SESSION["carrito"] = array();
    }

    // Verificar si el producto ya ha sido agregado al carrito
    $productoYaAgregado = false;
    foreach ($_SESSION["carrito"] as $producto) {
        if ($producto["codigo"] === $codigo) {
            $productoYaAgregado = true;
            break;
        }
    }

    if (!$productoYaAgregado) {
        $nombreProducto = $_POST["nombreProducto"];
        $precioProducto = $_POST["precioProducto"];
        $cantidadProducto = $_POST["cantidadProducto"];
        
        $producto = [
            "nombre" => $nombreProducto,
            "precio" => $precioProducto,
            "cantidad" => $cantidadProducto,
            "codigo" => $codigo
        ];

        $_SESSION["carrito"][] = $producto;
    }
}

// ...

?> 


<!DOCTYPE html>
<html lang="en">

<head class="contenedor-header">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMPLEADO </title>
    <link rel="shortcut icon" href="../../img/favicon (1).ico" type="image/x-icon">
    <link rel="icon" href="../../img/favicon (1).ico" type="image/x-icon">
    <link rel="stylesheet" href="../../css/empleado/mostrarProductos.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        
      function eliminarProducto(index) {
        if (confirm("¿Estás seguro de que deseas eliminar este producto del carrito?")) {
            $.post("eliminar_producto.php", { index: index }, function(data) { 
                alert(data); // Mostrar mensaje del servidor
                // Actualizar la URL para quitar el fragmento #carrito-venta
            history.replaceState({}, document.title, window.location.pathname);
                location.reload(); // Recargar la página
            });
        }
    }

        function vaciarCarrito() {
    console.log("Vaciar Carrito clickeado");
    if (confirm("¿Estás seguro de que deseas vaciar completamente el carrito?")) {
        $.post("vaciar_carrito.php", function(data) {
            // Vaciar el carrito en el lado del cliente
            sessionStorage.removeItem("carrito");
            
            // Actualizar la URL para quitar el fragmento #carrito-venta
            history.replaceState({}, document.title, window.location.pathname);
            
            // Recargar la página
            location.reload();
        });
    }
}


   
$(document).ready(function() {
    $("#btnConfirmarVenta").click(function() {
        $.ajax({
            type: "POST",
            url: "realizar_venta.php",
            data: { id_usuario: <?php echo $idUsuario; ?> }, // Pasar el id_usuario al script
                success: function(response) {
                    alert(response); // Muestra la respuesta del servidor (mensaje de compra)
                // Puedes realizar acciones adicionales después de una compra exitosa, como recargar la página o actualizar el carrito.
                location.href = "comprobante.php"; // Redireccionar al comprobante
            },
            error: function() {
                alert("Error al realizar la venta.");
            }
        });
    });
});
    </script>




</head>

<header class="header-container">
    <div>
        <h1>BIENVENIDO</h1>
        </div>
        <div>
    <a href="#carrito-venta">CARRITO</a> <!-- Corregir el enlace para apuntar a compra.php -->
    <a href="../../index.php" class="salir">SALIR</a>
    <a href="ventas_realizadas.php">Ventas Realizadas</a>
    <a href="compras_realizadas.php">Compras Realizadas</a>
    </div>
</header>

<body>
    <main>
        <div class="productos-container">
            <!-- Contenedor para los productos -->
            <div class="productos-izquierda">
                <h2>Pan</h2>
                <!-- Loop para mostrar los productos de tipo 'panel' -->
                <?php
                while ($producto = mysqli_fetch_assoc($result)) {
                    if ($producto['tipo_producto'] === 'PAN') {
                        echo '<div class="producto panel">';
                        echo '<h2>' . $producto['nombre'] . '</h2>';
                        echo '<img src="' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '">';
                        echo '<p>' . $producto['descripccion'] . '</p>';
                        echo '<p>Existencias: ' . $producto['existencias'] . '</p>';
                        echo '<p>Precio: ' . $producto['precio'] . '</p>';
                        echo '<h2>' . $producto['codigo'] . '</h2>';


                            
                      
                        // Formulario para agregar al carrito
                        echo '<form method="post" action="home_empleado.php">';
                        // Campos ocultos para enviar información del producto
                        echo '<input type="hidden" name="nombreProducto" value="' . $producto['nombre'] . '">';
                        echo '<input type="hidden" name="precioProducto" value="' . $producto['precio'] . '">';
                        echo '<input type="hidden" name="codigo" value="' . $producto['codigo'] . '">';
                        echo '<input type="number" class="cantidad-input" name="cantidadProducto" min="0" value="0">';
                        echo '<button type="submit" class="carrito-btn">Agregar al Carrito</button>';
                        echo '</form>';
                            
                        

                        echo '</div>';
                    }
                }
                ?>
            </div>

            <!-- Contenedor para las bebidas -->
            <div class="productos-derecha">
                <h2 class="titulo-de-carrito">Bebidas</h2>
                <!-- Loop para mostrar los productos de tipo 'bebida' -->
                <?php
                mysqli_data_seek($result, 0); // Reiniciar el puntero del resultado para comenzar desde el principio
                while ($producto = mysqli_fetch_assoc($result)) {
                    if ($producto['tipo_producto'] === 'BEBIDA') {
                        echo '<div class="producto bebida">';
                        echo '<h2>' . $producto['nombre'] . '</h2>';
                        echo '<img src="' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '">';
                        echo '<p>' . $producto['descripccion'] . '</p>';
                        echo '<p>Existencias: ' . $producto['existencias'] . '</p>';
                        echo '<p>Precio: ' . $producto['precio'] . '</p>';
                        echo '<h2>' . $producto['codigo'] . '</h2>';

                        
                    
                        // Formulario para agregar al carrito
                        echo '<form method="post" action="home_empleado.php">';
                        // Campos ocultos para enviar información del producto
                        echo '<input type="hidden" name="nombreProducto" value="' . $producto['nombre'] . '">';
                        echo '<input type="hidden" name="precioProducto" value="' . $producto['precio'] . '">';
                        echo '<input type="hidden" name="codigo" value="' . $producto['codigo'] . '">';
                        
                        echo '<input type="number" class="cantidad-input" name="cantidadProducto" min="0" value="0">';
                        echo '<button type="submit" class="carrito-btn">Agregar al Carrito</button>';
                        echo '</form>';
                            
                        

                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>

        <div class="carrito-venta" id="carrito-venta">
            <h2>Carrito de Compras</h2>
            <?php
            if (isset($_SESSION["carrito"]) && is_array($_SESSION["carrito"]) && count($_SESSION["carrito"]) > 0) {
                echo '<table>';
                echo '<tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Código</th><th>Funciones</th></tr>';
                foreach ($_SESSION["carrito"] as $index => $producto) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($producto["nombre"]) . '</td>';
                    echo '<td>' . $producto["cantidad"] . '</td>';
                    echo '<td>$' . $producto["precio"] . '</td>';
                    echo '<td>' . htmlspecialchars($producto["codigo"]) . '</td>';  
                   
                    echo '<td><button onclick="eliminarProducto(' . $index . ')">Eliminar</button></td>';
                    echo '</tr>';
                }
                if (isset($_SESSION["carrito"]) && is_array($_SESSION["carrito"]) && count($_SESSION["carrito"]) > 0) {

                echo '<tr><td colspan="4"><button onclick="vaciarCarrito()">Vaciar Carrito</button></td></tr>';
                }
                echo '<tr><td colspan="4"><button id="btnConfirmarVenta"onclick="confirmarVenta()">Confirmar Venta</button></td></tr>';
                echo '</table>';
            } else {
                echo '<p class="carrito-vacio" id="carrito-vacio">No hay productos en el carrito.</p>';
            }
            ?>

        </div>


    </main>
  


    <!-- VALIDACIÓN PARA ENVIAR EL FORMULARIO  -->
<script>
    function validarCantidad(event) {
        const cantidadInput = event.target.querySelector('.cantidad-input');
        const cantidad = parseInt(cantidadInput.value);

        if (cantidad <= 0) {
            event.preventDefault(); // Evita el envío del formulario
            alert('La cantidad debe ser mayor a 0 para agregar al carrito.');
        }
    }

    // Agrega un evento onsubmit al formulario para llamar a la función de validación
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', validarCantidad);
    });
</script>
   
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const carritoVacio = document.getElementById('carrito-vacio');

    function mostrarCarritoVacio() {
        console.log("Mostrando mensaje de carrito vacío");
        carritoVacio.style.display = 'block';
        setTimeout(() => {
            console.log("Ocultando mensaje de carrito vacío");
            carritoVacio.style.display = 'none';
        }, 8000); // Ocultar después de 3 segundos (3000 ms)
    }

    // Llamamos a esta función si el carrito está vacío
    mostrarCarritoVacio();
});

</script>
</body>

</html>