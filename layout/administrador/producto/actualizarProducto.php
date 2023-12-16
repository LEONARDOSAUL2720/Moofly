<?php
// Incluir el archivo de conexión a la base de datos (db.php) si es necesario
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre_actual = $_POST['nombreActual'];
    $nombre_nuevo = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $existencias = $_POST['existencias'];
    $precio = $_POST['precio'];

    // Realizar la conexión a la base de datos si es necesario
    // $conexion = new mysqli('localhost', 'usuario', 'contraseña', 'nombre_base_datos');

    // Ejecutar el procedimiento almacenado
    $stmt = $conexion->prepare("CALL validar_actualizacion_producto(?, ?, ?, ?, ?)");
    $stmt->bind_param("sssid", $nombre_actual, $nombre_nuevo, $descripcion, $existencias, $precio);
    $stmt->execute();
   

     // Obtener los resultados del procedimiento almacenado
  $stmt->bind_result($mensaje_procedimiento);
  
  


// Verificar si se obtuvo algún resultado
if ($stmt->fetch()) {
  // Después de guardar el mensaje en la sesión, redireccionamos a home_administrador.php
// Pasamos el parámetro 'opcion' en la URL para indicar la opción seleccionada
header('Location: ../home_administrador.php?mensaje_registro=' . urlencode($mensaje_procedimiento) . '&opcion=registrarUsuario');
exit();
}
 
    $stmt->close();
    $conexion->close();
}

?>
