<?php
session_start(); // Iniciar sesión si aún no se ha hecho
  include('db.php');

  $id_tipo=$_POST["id_tipo"];
  $NOMBRE = $_POST['nombre'];
  $DESCRIPCCION = $_POST['descripcion'];
  $EXISTENCIAS = $_POST['existencias'];
  $PRECIO = $_POST['precio'];
 
  
// Manejo de la imagen
$imagen = $_FILES['imagen'];
$nombreImagen = $imagen['name'];
$rutaImagenTemporal = $imagen['tmp_name'];
$rutaDestino = '../../../img/productos/' . $nombreImagen; // Cambia 'carpeta_producto' por la ruta correcta

$imagenfinal = '../../img/productos/' . $nombreImagen; // Corregir 'nombreimagen' a 'nombreImagen'

// if (move_uploaded_file($rutaImagenTemporal, $rutaDestino)) {
//   echo "Imagen movida correctamente a: " . $rutaDestino;
// } else {
//   echo "Error al mover la imagen.";
// }

// echo "Valor de \$imagenfinal: " . $imagenfinal;


// Mover la imagen a la carpeta destino
move_uploaded_file($rutaImagenTemporal, $rutaDestino);

  // Preparar la consulta del procedimiento almacenado
  $stmt = $conexion->prepare("CALL validar_RegistroProdcuto(?,?, ?, ?, ?, ?)");
  $stmt->bind_param("issids",$id_tipo,  $NOMBRE, $DESCRIPCCION, $EXISTENCIAS, $PRECIO, $imagenfinal);
  
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
$_SESSION['mensaje_guardar'] = $mensaje_procedimiento;
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