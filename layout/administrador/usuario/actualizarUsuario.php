<?php
// Incluir el archivo de conexión a la base de datos (db.php) si es necesario
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre_actual = $_POST['nombre_actual'];
    $nombre_nuevo = $_POST['nombre_nuevo'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $telefono = $_POST['telefono'];

    // Realizar la conexión a la base de datos si es necesario
    // $conexion = new mysqli('localhost', 'usuario', 'contraseña', 'nombre_base_datos');

    // Ejecutar el procedimiento almacenado
    $stmt = $conexion->prepare("CALL validar_ActualizarUsuario(?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre_actual, $nombre_nuevo, $correo, $password, $telefono);
    $stmt->execute();
   

     // Obtener los resultados del procedimiento almacenado
  $stmt->bind_result($mensaje_procedimiento);
  
  
  
  // Inicializar la variable $mensaje
  $mensaje = '';
  
  // // // Verificar si se obtuvo algún resultado
  // if ($stmt->fetch()) {
  //   // Redirigir al archivo registro.html y pasar el mensaje como parámetro de la URL
  //   echo "Mensaje del procedimiento almacenado: " . $mensaje_procedimiento; // Agregar esta línea para ver el mensaje en la pantalla
  //   header('Location: ../home_administrador.php?mensaje=' . urlencode($mensaje_procedimiento));
  //   exit();
  // }

// Verificar si se obtuvo algún resultado
if ($stmt->fetch()) {
 // Después de guardar el mensaje en la sesión, redireccionamos a home_administrador.php
// Pasamos el parámetro 'opcion' en la URL para indicar la opción seleccionada
header('Location: ../home_administrador.php?mensaje_actualizar=' . urlencode($mensaje_procedimiento) . '&opcion=actualizarUsuario');
exit();
}

// Redirigir a la opción "Mostrar Usuario" para mostrar los mensajes de esa opción
header('Location: ../home_administrador.php?mensaje=Mostrando mensajes de la opción Mostrar Usuario');
exit();

// ... (código existente) ...

    
    $stmt->close();
    $conexion->close();
}

?>
