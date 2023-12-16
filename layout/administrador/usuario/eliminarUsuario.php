<?php
include('db.php');

$id_usuario=$_POST['id_usuario'];
$CORREO = $_POST['correo'];

// Preparar la consulta del procedimiento almacenado
$stmt = $conexion->prepare("CALL validar_EliminarUsuario(?, ?)");
$stmt->bind_param("is", $id_usuario, $CORREO);

// Ejecutar el procedimiento almacenado
$stmt->execute();

// // Obtener los resultados del procedimiento almacenado
$stmt->bind_result($mensaje_procedimiento);



// Inicializar la variable $mensaje
$mensaje = '';




// Verificar si se obtuvo algún resultado
if ($stmt->fetch()) {
  // Después de guardar el mensaje en la sesión, redireccionamos a home_administrador.php
// Pasamos el parámetro 'opcion' en la URL para indicar la opción seleccionada
header('Location: ../home_administrador.php?mensaje_eliminar=' . urlencode($mensaje_procedimiento) . '&opcion=eliminarUsuario');
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