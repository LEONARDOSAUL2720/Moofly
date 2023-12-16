<?php
session_start();
include('db.php');

// vinculamos varibales con las de nuestro formulario
$CORREO = $_POST['correo']; 
$PASSWORD = $_POST['password'];



$consulta = "SELECT * FROM usuario WHERE correo=? AND password=?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("ss", $CORREO, $PASSWORD);
$stmt->execute();
$resultado = $stmt->get_result();

$filas = $resultado->num_rows;

if ($filas) {
    $row = $resultado->fetch_assoc();
    $idRol = $row['id_rol'];
    $_SESSION['id_usuario'] = $row['id_usuario']; // Guardar el id_usuario en la sesión
    $_SESSION['nombre'] = $row['nombre'];
   
   
    

    // Resto de tu código para redirigir según el rol

    switch ($idRol) {
        case 1:
            header('Location: ../layout/administrador/home_administrador.php');
            exit(); 
            break;
        case 2:
            header('Location: ../layout/empleado/home_empleado.php');
            exit(); 
            break;
        case 3:
            header('Location: ../layout/usuario/home_usuario.php');
            exit(); 
            break;
       
    }
} else {
    $mensaje = 'Los datos ingresados no son correctos.';
    $_SESSION['error_message'] = $mensaje;
    header('Location: ../layout/inicioSesion.php');
    exit();
}


mysqli_free_result($resultado);

$stmt->close();
$conexion->close();

?>


<!-- 


include('db.php');

// vinculamos varibales con las de nuestro formulario
$CORREO = $_POST['correo']; 
$PASSWORD = $_POST['password'];



$consulta = "SELECT * FROM usuario WHERE correo=? AND password=?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("ss", $CORREO, $PASSWORD);
$stmt->execute();
$resultado = $stmt->get_result();

$filas = $resultado->num_rows;

if ($filas) {
    $row = $resultado->fetch_assoc();
    $idRol = $row['id_rol'];

    switch ($idRol) {
        case 1:
            header('Location: ../layout/home_administrador.php');
            exit(); 
            break;
        case 2:
            header('Location: ../layout/home_empleado.php');
            exit(); 
            break;
        case 3:
            header('Location: ../layout/home.php');
            exit(); 
            break;
       
    }
} else {
    $mensaje = 'Los datos ingresados no son correctos.';
    header('Location: ../layout/inicioSesion.php?error=' . urlencode($mensaje));
    exit();
}


mysqli_free_result($resultado);

$stmt->close();
$conexion->close();

-->



































// include('db.php');


// // vinculamos varibales con las de nuestro formulario
// $CORREO=$_POST['correo']; 
// $PASSWORD=$_POST['password'];

// // $consulta = "SELECT * FROM usuario where correo='$CORREO' and password='$PASSWORD' ";

// // $resultado = mysqli_query($conexion, $consulta);

// $consulta = "SELECT * FROM usuario WHERE correo=? AND password=?";
// $stmt = $conexion->prepare($consulta);
// $stmt->bind_param("ss", $CORREO, $PASSWORD);
// $stmt->execute();
// $resultado = $stmt->get_result();


// $filas = $resultado->num_rows;
// // ...


// if ($filas) {
//     $row = $resultado->fetch_assoc();
//     $idRol = $row['id_rol'];

//     switch ($idRol) {
//         case 1:
//             header('Location: ../layout/home_administrador.php');
//             exit(); 
//             break;
//         case 2:
//             header('Location: ../layout/home_empleado.php');
//             exit(); 
//             break;
//         case 3:
//             header('Location: ../layout/home.php');
//             exit(); 
//             break;
       
//     }
// } else {
//     $mensaje = 'Los datos ingresados no son correctos.';
//     header('Location: ../layout/inicioSesion.php?mensaje=' . urlencode($mensaje));
//     exit();
// }

// mysqli_free_result($resultado);

// $stmt->close();
// $conexion->close();
























?>



// include('db.php');


// // vinculamos varibales con las de nuestro formulario
// $CORREO=$_POST['correo']; 
// $PASSWORD=$_POST['password'];

// // $consulta = "SELECT * FROM usuario where correo='$CORREO' and password='$PASSWORD' ";

// // $resultado = mysqli_query($conexion, $consulta);

// $consulta = "SELECT * FROM usuario WHERE correo=? AND password=?";
// $stmt = $conexion->prepare($consulta);
// $stmt->bind_param("ss", $CORREO, $PASSWORD);
// $stmt->execute();
// $resultado = $stmt->get_result();


// $filas = $resultado->num_rows;
// // ...


// if ($filas) {
//     $row = $resultado->fetch_assoc();
//     $idRol = $row['id_rol'];

//     switch ($idRol) {
//         case 1:
//             header('Location: ../layout/home_administrador.php');
//             exit(); 
//             break;
//         case 2:
//             header('Location: ../layout/home_empleado.php');
//             exit(); 
//             break;
//         case 3:
//             header('Location: ../layout/home.php');
//             exit(); 
//             break;
       
//     }
// } else {
//     $mensaje = 'Los datos ingresados no son correctos.';
//     header('Location: ../layout/inicioSesion.php?mensaje=' . urlencode($mensaje));
//     exit();
// }

// mysqli_free_result($resultado);

// $stmt->close();
// $conexion->close();

