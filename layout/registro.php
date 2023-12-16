<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../css/Principal/registro.css">
    <link rel="shortcut icon" href="../img/favicon (1).ico" type="image/x-icon">
    <link rel="icon" href="../img/favicon (1).ico" type="image/x-icon">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <link rel="stylesheet" href="../css/login.css"> -->
    <!-- este es para esconder mi footer  -->
    <script>
        $(document).ready(function() {
            var prevScrollPos = $(window).scrollTop();
            
            $(window).scroll(function() {
                var currentScrollPos = $(window).scrollTop();
                var footer = $("footer");
                
                if (prevScrollPos > currentScrollPos) {
                    footer.css("bottom", "0");
                } else {
                    footer.css("bottom", "-100px");
                }
                
                prevScrollPos = currentScrollPos;
            });
        });
    </script>


    <!-- este es para mostrar mi mensaje del procedimiento  -->
    <script>
        $(document).ready(function() {
            // Obtener el mensaje de la URL
            var mensaje = getUrlParameter('mensaje');
            if (mensaje) {
                // Mostrar el mensaje en el elemento con id "mensaje-registro"
                $('#mensaje-registro').text(mensaje);
            }
        });
  </script>
   <script>        // Función para obtener un parámetro de la URL
        function getUrlParameter(name) {
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
            var results = regex.exec(window.location.href);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }
    </script>
    <!-- este es para mi gif  -->
    
</head>
<body>
<header>
<div class="logo-container">
                <img src="../img/imagenes/Moofly_1 (1).png" class="logo">
                <h1 class="encabezado">CAFETERÍA LA CONCHITA</h1>
            </div>
</header>


    <main class="animated-bg dynamic-height">
    <form action="../php/controladorRegistro.php" method="post" class="form-group">
        <h1 class="titulo-formulario">Formulario</h1>

        <div >
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
        </div>

        <div >
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" placeholder="tu correo" required pattern="[a-zA-Z0-9]+@gmail\.com"  title="ejemplo@gmail.com"  >
        </div>

        <div>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="password" placeholder="contraseña" required pattern=".{6,8}" title="La contraseña debe tener entre 6 y 8 caracteres">
        </div>

        <div>
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" placeholder="Tu telefono " required pattern="[0-9]{10}" title="Ingresa un número de teléfono válido de 10 dígitos">
        </div>

        <input type="submit" value="Enviar" class="boton">
        <a href="inicioSesion.php" class="boton">Iniciar Sesión</a>
    </form>
      <div class="mensaje-registro" id="mensaje-registro"></div>
    
      
    </main>
    <footer>
    &copy; CATERERIA LA CONCHITA. Todos los derechos reservados. <span id="current-date"></span>

</footer>

     
    <script src="../js/fecha.js"></script>
</body>
</html>
