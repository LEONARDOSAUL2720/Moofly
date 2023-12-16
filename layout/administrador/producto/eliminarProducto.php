<?php
include('db.php');

$codigo=$_POST['codigo'];
$nombre = $_POST['nombre'];

// Preparar la consulta del procedimiento almacenado
$stmt = $conexion->prepare("CALL validar_EliminarProducto(?, ?)");
$stmt->bind_param("is", $codigo, $nombre);

// Ejecutar el procedimiento almacenado
$stmt->execute();

// // Obtener los resultados del procedimiento almacenado
$stmt->bind_result($mensaje_procedimiento);
var_dump($mensaje_procedimiento); 

// Verificar si se obtuvo algún resultado
if ($stmt->fetch()) {
  // Después de guardar el mensaje en la sesión, redireccionamos a home_administrador.php
// Pasamos el parámetro 'opcion' en la URL para indicar la opción seleccionada
header('Location: ../home_administrador.php?mensaje_registro=' . urlencode($mensaje_procedimiento) . '&opcion=registrarUsuario');
exit();
}




// // // Verificar si se obtuvo algún resultado
// if ($stmt->fetch()) {
//   // Redirigir al archivo registro.html y pasar el mensaje como parámetro de la URL
//   header('Location: ../home_administrador.php?mensaje=' . urlencode($mensaje_procedimiento));
//   exit();
// }

$stmt->close();
$conexion->close();




?> 