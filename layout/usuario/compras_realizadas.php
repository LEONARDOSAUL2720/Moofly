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
FROM compra c
JOIN usuario u ON c.id_usuario = u.id_usuario
JOIN producto p ON c.codigo = p.codigo
WHERE u.id_usuario = $idUsuario
ORDER BY c.fecha DESC, c.folio DESC";

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
    <title>Compras Realizadas</title>
    <link rel="stylesheet" href="../../css/empleado/ventas_realizadas.css">
    <link rel="shortcut icon" href="../../img/favicon (1).ico" type="image/x-icon">
    <link rel="icon" href="../../img/favicon (1).ico" type="image/x-icon">
    
    <!-- Agrega tus estilos CSS aquí -->
</head>
<body>
    <header>
        <a href="home_usuario.php">REGRESAR</a>
    </header>
    <main>
        <h1>Compras Realizadas</h1>
        <table>
            <tr>
                <th>Folio</th>
                <th>Establecimiento</th>
                <th>Fecha</th>
                <th>Cantidad</th>
                <th>ID Usuario</th>
                <th>Nombre Usuario</th>
                <th>Nombre Producto</th>
                <th>Precio</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['folio']) . '</td>';
                echo '<td>' . htmlspecialchars($row['establecimiento']) . '</td>';
                echo '<td>' . htmlspecialchars($row['fecha']) . '</td>';
                echo '<td>' . htmlspecialchars($row['cantidad']) . '</td>';
                echo '<td>' . htmlspecialchars($row['id_usuario']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nombre_empleado']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nombre_producto']) . '</td>';
                echo '<td>' . htmlspecialchars($row['precio']) . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </main>
</body>
</html>
