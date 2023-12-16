<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/Principal/login.css">
    <link rel="shortcut icon" href="../img/favicon (1).ico" type="image/x-icon">
    <link rel="icon" href="../img/favicon (1).ico" type="image/x-icon">




</head>

<body>

    <header class="header">
        <div>
            <div class="logo-container">
                <img src="../img/imagenes/Moofly_1 (1).png" class="logo">
                <h1 class="encabezado">CAFETERÍA LA CONCHITA</h1>
            </div>
            
                
            <a href="../index.php" class="link">Regresar</a>
        </div>
    </header>

    <main class="animated-bg dynamic-height">
    
        <div class="form-container">
       
            <form action="../php/validar.php" method="post" class="form-group">
           
                <h1 class="titulo-formulario">INICIAR SESIÓN </h1>
                <div>
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" placeholder="tu correo" required pattern="[a-zA-Z]+@gmail\.com">
                </div>

                <div>
                    <label for="contrasena"> Contraseña:</label>
                    <input type="password" id="contrasena" name="password" placeholder="Tu contraseña">
                </div>

                <input type="submit" value="Ingresar" class="boton">
                <a href="registro.php" class="boton">Registro</a>
            </form>
            <?php
            session_start();
            if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
                // Mostrar el mensaje de error
                $mensaje = $_SESSION['error_message'];
                echo "<p class='error-message'>$mensaje</p>";

                // Limpiar la variable de sesión para que no se muestre nuevamente en futuras visitas
                unset($_SESSION['error_message']);
            }
            ?>
            <p id="error" class="error-message"></p>

        </div>
    </main>

    <footer>
        &copy; CATERERIA LA CONCHITA. Todos los derechos reservados. <span id="current-date"></span>

    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/scripts.js"></script>
    <script src="../js/fecha.js"></script>

</body>

</html>