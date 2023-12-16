<?php
session_start(); // Iniciar sesión si aún no se ha hecho
  include('db.php');

  $NOMBRE = $_POST['nombre'];
  $CORREO = $_POST['correo'];
  $PASSWORD = $_POST['password'];
  $TELEFONO = $_POST['telefono'];
  $id_rol=$_POST["id_rol"];
  
  // Preparar la consulta del procedimiento almacenado
  $stmt = $conexion->prepare("CALL validar_registro_usuario(?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssi", $NOMBRE, $CORREO, $PASSWORD, $TELEFONO, $id_rol);
  
  // Ejecutar el procedimiento almacenado
  $stmt->execute();
  
// Obtener los resultados del procedimiento almacenado
$stmt->bind_result($mensaje_procedimiento);

// Obtener el mensaje
$stmt->fetch();

// Cerrar el statement
$stmt->close();
$conexion->close();

// Guardar el mensaje en una variable de sesión
$_SESSION['mensaje_registro'] = $mensaje_procedimiento;
// Después de guardar el mensaje en la sesión, redireccionamos a home_administrador.php
// Pasamos el parámetro 'opcion' en la URL para indicar la opción seleccionada
header('Location: ../home_administrador.php?mensaje_registro=' . urlencode($mensaje_procedimiento) . '&opcion=registrarUsuario');
exit();
  ?> 

  
<!--   
  // Ejecutar el procedimiento almacenado
  $stmt->execute();
  
  // // Obtener los resultados del procedimiento almacenado
  $stmt->bind_result($mensaje_procedimiento);
  
   
// Redireccionar al archivo registro.php y pasar el mensaje como parámetro de la URL
echo 'Mensaje: ' . $mensaje_procedimiento; // Agregar esta línea para 
if ($mensaje_procedimiento !== '') {
    header('Location: ../home_administrador.php?mensaje=' . urlencode($mensaje_procedimiento) . '&opcion=registrarUsuario');
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
   -->