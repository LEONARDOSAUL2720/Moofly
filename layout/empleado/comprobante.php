<?php
session_start();

// Asegúrate de incluir tu archivo de conexión a la base de datos
include("db.php");

// Verifica si el usuario está autenticado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php"); // Redirecciona a la página de inicio de sesión si el usuario no está autenticado
    exit();
}

// Obtén el ID del usuario autenticado
$idUsuario = $_SESSION["id_usuario"];

$query = "SELECT c.folio, c.establecimiento, c.fecha, c.cantidad, u.id_usuario, u.nombre AS nombre_empleado, p.nombre AS nombre_producto, p.precio
FROM venta c
JOIN usuario u ON c.id_usuario = u.id_usuario
JOIN producto p ON c.codigo = p.codigo
WHERE u.id_usuario = $idUsuario
AND DATE_FORMAT(c.fecha, '%Y-%m-%d %H:%i') = DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i')
ORDER BY c.fecha DESC, c.folio ASC";


$result = mysqli_query($conexion, $query);

if (!$result) {
    die('Error al ejecutar la consulta: ' . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta Actual del Usuario</title>
    <link rel="stylesheet" href="../../css/empleado/comprobante.css ">
</head>
<header>
    <a href="home_empleado.php">REGRESAR</a>
</header>
<body>
   
    <div class="venta-container">
        <div class="venta-info">
        
    
            <div>
                <h1>Venta Actual del Usuario</h1>
                <?php
                $totalVenta=0;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<p class="folio"><strong>Folio:</strong> ' . $row['folio'] . '</p>';
                    echo '<p><strong>Establecimiento:</strong> ' . $row['establecimiento'] . '</p>';
                    echo '<p><strong>Fecha:</strong> ' . $row['fecha'] . '</p>';
                    echo '<p><strong>Cantidad:</strong> ' . $row['cantidad'] . '</p>';
                    echo '<p><strong>ID Usuario:</strong> ' . $row['id_usuario'] . '</p>';
                    echo '<p><strong>Nombre Empleado:</strong> ' . $row['nombre_empleado'] . '</p>';
                    echo '<p><strong>Nombre Producto:</strong> ' . $row['nombre_producto'] . '</p>';
                    echo '<p ><strong>Precio:</strong> ' . $row['precio'] . '</p>';
                    // Sumar el precio al total de la venta
                    $totalVenta += $row['precio'] * $row['cantidad'];
                    
                }
                
                echo '<p class="total"><strong>Total Venta:</strong> ' . $totalVenta . '</p>';    
                ?>
            </div>
        </div>
    </div>
</body>
</html>
