  <?php
 session_start();
// // Redireccionar al archivo registro.php y pasar el mensaje como parámetro de la URL
// echo 'Mensaje: ' . $mensaje; // Agregar esta línea para 
// if ($mensaje !== '') {
//     header('Location: ../layout/registro.php?mensaje=' . urlencode($mensaje));
//     exit();
// }



  // // Redireccionar al archivo HTML
  // header('Location: registro.html');
  // exit();

  include('db.php');

  $NOMBRE = $_POST['nombre'];
  $CORREO = $_POST['correo'];
  $PASSWORD = $_POST['password'];
  $TELEFONO = $_POST['telefono'];
  $id_rol = 3;
  
  // Preparar la consulta del procedimiento almacenado
  $stmt = $conexion->prepare("CALL validar_registro_usuario(?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssi", $NOMBRE, $CORREO, $PASSWORD, $TELEFONO, $id_rol);
  
  // Ejecutar el procedimiento almacenado
  $stmt->execute();
  
  // // Obtener los resultados del procedimiento almacenado
  $stmt->bind_result($mensaje_procedimiento);
  
  // // Verificar si se obtuvo algún resultado
  if ($stmt->fetch()) {
    // Redirigir al archivo registro.html y pasar el mensaje como parámetro de la URL
    header('Location: ../layout/registro.php?mensaje=' . urlencode($mensaje_procedimiento));
    exit();
  }
  
  $stmt->close();
  $conexion->close();
  
  

 
  ?> 
// // Redireccionar al archivo registro.php y pasar el mensaje como parámetro de la URL
// echo 'Mensaje: ' . $mensaje; // Agregar esta línea para 
// if ($mensaje !== '') {
//     header('Location: ../layout/registro.php?mensaje=' . urlencode($mensaje));
//     exit();
// }



  // // Redireccionar al archivo HTML
  // header('Location: registro.html');
  // exit();

  





include('db.php');

$NOMBRE = $_POST['nombre'];
$CORREO = $_POST['correo'];
$PASSWORD = $_POST['password'];
$TELEFONO = $_POST['telefono'];
$id_rol = 3;

// Preparar la consulta del procedimiento almacenado
$stmt = $conexion->prepare("CALL validar_registro_usuario(?, ?, ?, ?, ?)");
$stmt->bind_param("ssisi", $NOMBRE, $CORREO, $PASSWORD, $TELEFONO, $id_rol);

// Ejecutar el procedimiento almacenado
$stmt->execute();

// // Obtener los resultados del procedimiento almacenado
$stmt->bind_result($mensaje);



// Inicializar la variable $mensaje
$mensaje = '';

// Verificar si hay datos en $registro y asignar el mensaje correspondiente
if ($registro) {
  $mensaje = $registro['mensaje'];
}

// // Verificar si se obtuvo algún resultado
if ($stmt->fetch()) {
  // Redirigir al archivo registro.html y pasar el mensaje como parámetro de la URL
  header('Location: ../layout/registro.php?mensaje=' . urlencode($mensaje));
  exit();
}

$stmt->close();
$conexion->close();


// // Redireccionar al archivo registro.php y pasar el mensaje como parámetro de la URL
// echo 'Mensaje: ' . $mensaje; // Agregar esta línea para 
// if ($mensaje !== '') {
//     header('Location: ../layout/registro.php?mensaje=' . urlencode($mensaje));
//     exit();
// }



// // Redireccionar al archivo HTML
// header('Location: registro.html');
// exit();











