<?php
session_start();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../../img/favicon (1).ico" type="image/x-icon">
    <link rel="icon" href="../../img/favicon (1).ico" type="image/x-icon">

    <link rel="stylesheet" href="../../css/adminEstilos/estilosAdmin.css">
    <link rel="stylesheet" href="../../css/adminEstilos/formularioRegistroUsu.css">

    <link rel="stylesheet" href="../../css/adminEstilos/actualizarUsuario.css">

    <link rel="stylesheet" href="../../css/adminEstilos/eliminarUsuario.css">

    <script src="../../js/admin.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>


    <title>ADMINISTRADOR </title>
</head>

<body>


    <nav>
        <ul>
            <li><a href="#" onclick="mostrarContenido('usuario')">Usuario</a>
                <ul class="subopciones">
                    <li><a href="#" onclick="mostrarContenido('mostrarUsuario')">Mostrar</a></li>
                    <li><a href="#" onclick="mostrarContenido('registrarUsuario')">Registrar</a></li>
                    <li><a href="#" onclick="mostrarContenido('actualizarUsuario')">Actualizar</a></li>
                    <li><a href="#" onclick="mostrarContenido('eliminarUsuario')">Eliminar</a></li>

                </ul>
            </li>
            <li><a href="#" onclick="mostrarContenido('producto')">Producto</a>
                <ul>
                    <li><a href="#" onclick="mostrarContenido('mostrarProducto')">Mostrar</a></li>
                    <li><a href="#" onclick="mostrarContenido('registrarProducto')">Registrar</a></li>
                    <li><a href="#" onclick="mostrarContenido('actualizarProducto')">Actualizar</a></li>
                    <li><a href="#" onclick="mostrarContenido('eliminarProducto')">Eliminar</a></li>

                </ul>
            </li>

            <li><a href="#" onclick="mostrarContenido('movimientos')">Movimientos</a>
                <ul>
                    <li><a href="#" onclick="mostrarContenido('ventas')">Ventas</a> </li>
                    <li><a href="#" onclick="mostrarContenido('compras')">compras</a> </li>

                    <li><a href="#" onclick="mostrarContenido('movimientosUsuarios')">Usuarios</a> </li>

                    <li><a href="#" onclick="mostrarContenido('movimientosProductos')">Productos</a> </li>
                </ul>
            </li>

            <li><a href="logout.php">Salir</a></li>


        </ul>
    </nav>


    <?php
    // Mostrar el mensaje según la opción seleccionada
    if (isset($_GET['mensaje_registro']) && $_GET['opcion'] === 'registrarUsuario') {
        echo '<div class="mensaje">' . $_GET['mensaje_registro'] . '</div>';
    } elseif (isset($_GET['mensaje_actualizar']) && $_GET['opcion'] === 'actualizarUsuario') {
        echo '<div class="mensaje">' . $_GET['mensaje_actualizar'] . '</div>';
    } elseif (isset($_GET['mensaje_eliminar']) && $_GET['opcion'] === 'eliminarUsuario') {
        echo '<div class="mensaje">' . $_GET['mensaje_eliminar'] . '</div>';
    } elseif (isset($_GET['mensaje_guardar_producto']) && $_GET['opcion'] === 'registrarProducto') {
        echo '<div class="mensaje">' . $_GET['mensaje_guardar_producto'] . '</div>';
    } elseif (isset($_GET['mensaje_actualizar_producto']) && $_GET['opcion'] === 'actualizarProducto') {
        echo '<div class="mensaje">' . $_GET['mensaje_actualizar_producto'] . '</div>';
    } elseif (isset($_GET['mensaje_eliminar_producto']) && $_GET['opcion'] === 'eliminarProducto') {
        echo '<div class="mensaje">' . $_GET['mensaje_eliminar_producto'] . '</div>';
    }
    ?>


    <div id="contenido">


        <!-- USUARIOS -->


        <!-- -------------------------------Mostrar Usuarios  ------------------------------------->

        <div id="mostrarUsuario" class="contenido-opcion">

            <h1> Buscar Usuario por CORREO o por ID</h1>
            <form id="formBuscarUsuario">
                <label for="buscarUsuario">Ingresa el correo:</label>
                <input type="email" id="buscarUsuario" name="correo" placeholder="Ingrese un correo">

                
                <label for="buscarUsuarioID">Ingresa el id:</label>
                <input type="number" id="buscarUsuarioID" name="id" placeholder="Ingrese el id del usuario ">
                <input type="submit" value="Buscar">
            </form>

            <div id="resultadoBusqueda">
                <script>
                    // Esperar a que el documento esté listo
                    $(document).ready(function() {
                        // Manejar el evento submit del formulario
                        $('#formBuscarUsuario').submit(function(event) {
                            // Evitar que el formulario se envíe por el método tradicional
                            event.preventDefault();

                            // Obtener el valor del campo de búsqueda
                            var parametroBusqueda = $('#buscarUsuario').val();
                            
                            var parametroBusquedaID = $('#buscarUsuarioID').val();

                            // Realizar la solicitud AJAX
                            $.ajax({
                                url: 'usuario/mostrarUsuario.php', // URL del script PHP que procesará la búsqueda
                                method: 'GET', // Método HTTP (GET en este caso)
                                data: {
                                    correo: parametroBusqueda, 
                                    id: parametroBusquedaID
                                }, // Datos a enviar al servidor (parámetro 'correo')
                                success: function(response) {
                                    // Éxito: mostrar la respuesta en el contenedor de resultados
                                    $('#resultadoBusqueda').html(response);
                                },
                                error: function() {
                                    // Error: mostrar un mensaje de error
                                    $('#resultadoBusqueda').html('<p>Error al realizar la búsqueda.</p>');
                                }
                            });
                        });
                    });
                </script>
            </div>


            <h1> Usuarios Registrados</h1>

            <?php
            // Verificar si el archivo db.php existe antes de incluirlo
            if (file_exists('usuario/db.php')) {
                include('usuario/db.php');

                if ($conexion) {
                    // Realizar la consulta para obtener los datos de la vista "vista_UsuarioDetalle"
                    $sql = "SELECT * FROM vista_UsuarioDetalle";
                    $resultado = $conexion->query($sql);

                    // Verificar si se obtuvieron resultados
                    if ($resultado && $resultado->num_rows > 0) {
                        // Generar la tabla HTML para mostrar los datos
                        echo '<table border="1">';
                        echo '<tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th></tr>';

                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $fila['id_usuario'] . '</td>';
                            echo '<td>' . $fila['nombre'] . '</td>';
                            echo '<td>' . $fila['correo'] . '</td>';

                            // Verificar si la clave "roles" existe en el arreglo $fila antes de imprimir
                            if (isset($fila['roles'])) {
                                echo '<td>' . $fila['roles'] . '</td>';
                            } else {
                                // Si la clave "roles" no existe, imprimir un espacio en blanco
                                echo '<td></td>';
                            }

                            echo '</tr>';
                        }

                        echo '</table>';
                    } else {
                        echo 'No hay usuarios registrados.';
                    }

                    // Cerrar el resultado y la conexión a la base de datos
                    $resultado->close();
                    $conexion->close();
                } else {
                    echo 'Error al conectar con la base de datos.';
                }
            } else {
                echo 'El archivo db.php no existe.';
            }
            ?>
        </div>



        <!-- -------------------------------Registrar Usuario  ------------------------------------->

        <div id="registrarUsuario" class="contenido-opcion">

            <h1>Registro de Usuario</h1>
            <form action="usuario/registrarUsuario.php" method="POST">
                <div>
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre">
                </div>

                <div>
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" required placeholder="Tu correo" pattern="[a-zA-Z0-9]+@gmail\.com" title="ejemplo@gmail.com">
                </div>

                <div>
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required placeholder="Tu contraseña" pattern=".{6,8}" title="La contraseña debe tener entre 6 y 8 caracteres">
                </div>

                <div>
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" pattern="[0-9]{10}" title="Ingresa un número de teléfono válido de 10 dígitos" required placeholder="Tu telefono">
                </div>

                <div>
                    <label for="rol">Rol:</label>
                    <select id="rol" name="id_rol" required>
                        <option value="" disabled selected>Selecciona un rol</option>
                        <option value="1">Administrador</option>
                        <option value="2">Empleado</option>
                        <option value="3">Usuario</option>
                    </select>
                </div>

                <input type="submit" value="Registrar">
            </form>


        </div>


        <!-- -------------------------------Actualizar Usuario  ------------------------------------->

        <div id="actualizarUsuario" class="contenido-opcion">
            <h1>Actualizar Usuario</h1>

            <form action="usuario/actualizarUsuario.php" method="POST">
                <div>
                    <label for="nombre_actual">Correo para validar:</label>
                    <input type="email" id="nombre_actual" name="nombre_actual" required placeholder="Nombre actual">
                </div>

                <div>
                    <label for="nombre_nuevo">Nombre Nuevo:</label>
                    <input type="text" id="nombre_nuevo" name="nombre_nuevo" required placeholder="Nombre nuevo">
                </div>

                <div>
                    <label for="correo">Correo Nuevo:</label>
                    <input type="email" id="correo" name="correo" required placeholder="Correo">
                </div>

                <div>
                    <label for="password">Contraseña Nueva:</label>
                    <input type="password" id="password" name="password" required placeholder="Contraseña">
                </div>

                <div>
                    <label for="telefono">Teléfono Nuevo:</label>
                    <input type="tel" id="telefono" name="telefono" required placeholder="Teléfono">
                </div>

                <input type="submit" value="Actualizar">
            </form>

        </div>


        <!-- -------------------------------eliminarUsuario  ------------------------------------->



        <div id="eliminarUsuario" class="contenido-opcion">

            <h1>Eliminar Usuario</h1>
            <form action="usuario/eliminarUsuario.php" method="POST">
                <label for="userId">ID del Usuario:</label>
                <input type="text" id="userId" name="id_usuario" required>

                <label for="userEmail">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" placeholder="correo eléctrionico" pattern="[a-zA-Z0-9]+@gmail\.com" title="ejemplo@gmail.com">

                <input type="submit" value="ELIMINAR">
            </form>

        </div>

        <!-- -------------------------------VENTAS  ------------------------------------->

        <div id="ventas" class="contenido-opcion">
            <h1>Ventas realizadas </h1>
            <?php
            // Verificar si el archivo db.php existe antes de incluirlo
            if (file_exists('usuario/db.php')) {
                include('usuario/db.php');

                if ($conexion) {
                    // Realizar la consulta para obtener los datos de la vista "vista_UsuarioDetalle"
                    $sql = "SELECT * FROM vista_venta_detalle";
                    $resultado = $conexion->query($sql);

                    // Verificar si se obtuvieron resultados
                    if ($resultado && $resultado->num_rows > 0) {
                        // Generar la tabla HTML para mostrar los datos
                        echo '<table border="1">';
                        echo '<tr><th>Folio</th><th>Establecimiento</th><th>Fecha</th><th>id_usuario</th> <th>Nombre Empleado</th> <th>Nombre Producto</th><th>cantidad</th> <th>Precio</th></tr>';

                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $fila['folio'] . '</td>';
                            echo '<td>' . $fila['establecimiento'] . '</td>';
                            echo '<td>' . $fila['fecha'] . '</td>';
                            echo '<td>' . $fila['id_usuario'] . '</td>';
                            echo '<td>' . $fila['nombre_empleado'] . '</td>';
                            echo '<td>' . $fila['nombre_producto'] . '</td>';
                            echo '<td>' . $fila['cantidad'] . '</td>';
                            echo '<td>' . $fila['precio'] . '</td>';


                            // // Verificar si la clave "roles" existe en el arreglo $fila antes de imprimir
                            // if (isset($fila['roles'])) {
                            //     echo '<td>' . $fila['roles'] . '</td>';
                            // } else {
                            //     // Si la clave "roles" no existe, imprimir un espacio en blanco
                            //     echo '<td></td>';
                            // }

                            echo '</tr>';
                        }

                        echo '</table>';
                    } else {
                        echo 'No hay productos registrados.';
                    }

                    // Cerrar el resultado y la conexión a la base de datos
                    $resultado->close();
                    $conexion->close();
                } else {
                    echo 'Error al conectar con la base de datos.';
                }
            } else {
                echo 'El archivo db.php no existe.';
            }
            ?>
        </div>


        <!-- ------------------------------- COMPRAS ------------------------------------->


        <div id="compras" class="contenido-opcion">
            <h1>Compras realizadas </h1>
            <?php
            // Verificar si el archivo db.php existe antes de incluirlo
            if (file_exists('usuario/db.php')) {
                include('usuario/db.php');

                if ($conexion) {
                    // Realizar la consulta para obtener los datos de la vista "vista_UsuarioDetalle"
                    $sql = "SELECT * FROM vista_compra_detalle";
                    $resultado = $conexion->query($sql);

                    // Verificar si se obtuvieron resultados
                    if ($resultado && $resultado->num_rows > 0) {
                        // Generar la tabla HTML para mostrar los datos
                        echo '<table border="1">';
                        echo '<tr><th>Folio</th><th>Establecimiento</th><th>Fecha</th> <th>Cantidad</th><th>id_usuario</th> <th>Nombre Usuario</th> <th>Nombre Producto</th> <th>Precio</th></tr>';

                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $fila['folio'] . '</td>';
                            echo '<td>' . $fila['establecimiento'] . '</td>';
                            echo '<td>' . $fila['fecha'] . '</td>';
                            echo '<td>' . $fila['cantidad'] . '</td>';
                            echo '<td>' . $fila['id_usuario'] . '</td>';
                            echo '<td>' . $fila['nombre_usuario'] . '</td>';
                            echo '<td>' . $fila['nombre_producto'] . '</td>';
                            echo '<td>' . $fila['precio'] . '</td>';



                            echo '</tr>';
                        }

                        echo '</table>';
                    } else {
                        echo 'No hay compras registradas.';
                    }

                    // Cerrar el resultado y la conexión a la base de datos
                    $resultado->close();
                    $conexion->close();
                } else {
                    echo 'Error al conectar con la base de datos.';
                }
            } else {
                echo 'El archivo db.php no existe.';
            }
            ?>
        </div>
        <!-----------------------------------------   MOVIMIENTOS USUARIOS  -------------------------------------------- -->

        <div id="movimientosUsuarios" class="contenido-opcion">
            <h1>MOVIMIENTOS DE USUARIOS </h1>
            <?php
            // Verificar si el archivo db.php existe antes de incluirlo
            if (file_exists('usuario/db.php')) {
                include('usuario/db.php');

                if ($conexion) {
                    // Realizar la consulta para obtener los datos de la vista "vista_UsuarioDetalle"
                    $sql = "SELECT mensaje, fecha_registro
                    FROM aviso_usuario
                    ORDER BY fecha_registro ASC;
                    ";
                    $resultado = $conexion->query($sql);

                    // Verificar si se obtuvieron resultados
                    if ($resultado && $resultado->num_rows > 0) {
                        // Generar la tabla HTML para mostrar los datos
                        echo '<table border="1">';
                        echo '<tr><th>Mensaje </th><th>Fecha</th></tr>';

                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $fila['mensaje'] . '</td>';
                            echo '<td>' . $fila['fecha_registro'] . '</td>';


                            echo '</tr>';
                        }

                        echo '</table>';
                    } else {
                        echo 'No hay movimientos registrados.';
                    }

                    // Cerrar el resultado y la conexión a la base de datos
                    $resultado->close();
                    $conexion->close();
                } else {
                    echo 'Error al conectar con la base de datos.';
                }
            } else {
                echo 'El archivo db.php no existe.';
            }
            ?>
        </div>


        <!-----------------------------------------   MOVIMIENTOS PRODUCTO  -------------------------------------------- -->

        <div id="movimientosProductos" class="contenido-opcion">
            <h1>MOVIMIENTOS DE PRODUCTOS </h1>
            <?php
            // Verificar si el archivo db.php existe antes de incluirlo
            if (file_exists('db.php')) {
                include('db.php');

                if ($conexion) {
                    // Realizar la consulta para obtener los datos de la vista "vista_UsuarioDetalle"
                    $sql = "SELECT mensaje, fecha_registro
                    FROM aviso_producto
                    ORDER BY fecha_registro ASC;
                    ";
                    $resultado = $conexion->query($sql);

                    // Verificar si se obtuvieron resultados
                    if ($resultado && $resultado->num_rows > 0) {
                        // Generar la tabla HTML para mostrar los datos
                        echo '<table border="1">';
                        echo '<tr><th>Mensaje </th><th>Fecha</th></tr>';

                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $fila['mensaje'] . '</td>';
                            echo '<td>' . $fila['fecha_registro'] . '</td>';


                            echo '</tr>';
                        }

                        echo '</table>';
                    } else {
                        echo 'No hay movimientos registrados.';
                    }

                    // Cerrar el resultado y la conexión a la base de datos
                    $resultado->close();
                    $conexion->close();
                } else {
                    echo 'Error al conectar con la base de datos.';
                }
            } else {
                echo 'El archivo db.php no existe.';
            }
            ?>
        </div>




        <!-- PRODUCTOS -->


        <!-- ------------------------------- MOSTRAR PRODUCTO  ------------------------------------->

        <div id="mostrarProducto" class="contenido-opcion">
            <!-- Formulario de búsqueda -->

            <h1> Buscar Producto</h1>
            <form id="formBuscarProducto">
                <label for="buscarProducto">Ingresa el nombre:</label>
                <input type="text" id="buscarProducto" name="nombre" placeholder="Ingrese un nombre de producto">
                <input type="submit" value="Buscar">
            </form>

            <div id="resultadoBusquedas">
                <script>
                    // Esperar a que el documento esté listo
                    $(document).ready(function() {
                        // Manejar el evento submit del formulario
                        $('#formBuscarProducto').submit(function(event) {
                            // Evitar que el formulario se envíe por el método tradicional
                            event.preventDefault();

                            // Obtener el valor del campo de búsqueda
                            var parametroBusqueda = $('#buscarProducto').val();

                            // Realizar la solicitud AJAX
                            $.ajax({
                                url: 'producto/mostrarProducto.php', // URL del script PHP que procesará la búsqueda
                                method: 'GET', // Método HTTP (GET en este caso)
                                data: {
                                    nombre: parametroBusqueda
                                }, // Datos a enviar al servidor (parámetro 'correo')
                                success: function(response) {
                                    // Éxito: mostrar la respuesta en el contenedor de resultados
                                    $('#resultadoBusquedas').html(response);
                                },
                                    
                                
                                error: function() {
                                    // Error: mostrar un mensaje de error
                                    $('#resultadoBusquedas').html('<p>Error al realizar la búsqueda.</p>');
                                }
                            });
                        });
                    });
                </script>



            </div>


            <h1> Productos Registrados</h1>

            <?php
            // Verificar si el archivo db.php existe antes de incluirlo
            if (file_exists('producto/db.php')) {
                include('producto/db.php');

                if ($conexion) {
                    // Realizar la consulta para obtener los datos de la vista "vista_UsuarioDetalle"
                    $sql = "SELECT * FROM vista_ProductoDetalleIMG";
                    $resultado = $conexion->query($sql);

                    // Verificar si se obtuvieron resultados
                    if ($resultado && $resultado->num_rows > 0) {
                        // Generar la tabla HTML para mostrar los datos
                        echo '<table border="1">';
                        echo '<tr><th>codigo</th><th>Tipo Producto</th><th>Nombre</th><th>Descripcción</th> <th>Existencias</th> <th>Precio</th>  <th>Imagen</th></tr>';

                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $fila['codigo'] . '</td>';
                            echo '<td>' . $fila['tipo_producto'] . '</td>';
                            echo '<td>' . $fila['nombre'] . '</td>';
                            echo '<td>' . $fila['descripccion'] . '</td>';
                            echo '<td>' . $fila['existencias'] . '</td>';
                            echo '<td>' . $fila['precio'] . '</td>';
                            echo '<td>' . $fila['imagen'] . '</td>';



                            echo '</tr>';
                        }

                        echo '</table>';
                    } else {
                        echo 'No hay usuarios registrados.';
                    }

                    // Cerrar el resultado y la conexión a la base de datos
                    $resultado->close();
                    $conexion->close();
                } else {
                    echo 'Error al conectar con la base de datos.';
                }
            } else {
                echo 'El archivo db.php no existe.';
            }
            ?>
            <!-- crerramos mostrar producto -->
        </div>


        <!-- -------------------------------REGISTRAR  PRODUCTO  ------------------------------------->


        <div id="registrarProducto" class="contenido-opcion">

            <!-- Aquí va el contenido de la opción "Registrar Usuario" -->
            <h1>Registro de Producto</h1>
            <form action="producto/registrarProducto.php" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="rol">Tipo Producto:</label>
                    <select id="rol" name="id_tipo" required>
                        <option value="" disabled selected>Selecciona un tipo </option>
                        <option value="1">Bebida</option>
                        <option value="2">Pan</option>
                    </select>
                </div>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required><br>

                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion"><br>

                <label for="existencias">Existencias:</label>
                <input type="number" id="existencias" name="existencias" required><br>

                <label for="precio">Precio:</label>
                <input type="number" step="0.01" id="precio" name="precio" required><br>


                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen"><br>
                <input type="submit" value="Guardar">

            </form>

        </div>




        <!-- -------------------------------ACTUALIZAR  PRODUCTO  ------------------------------------->


        <div id="actualizarProducto" class="contenido-opcion">
            <h1>Actualizar Producto</h1>

            <form action="producto/actualizarProducto.php" method="POST">
                <label for="nombreActual">Nombre Actual :</label>
                <input type="text" id="nombreActual" name="nombreActual" required><br>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required><br>

                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion"><br>

                <label for="existencias">Existencias:</label>
                <input type="number" id="existencias" name="existencias" required><br>

                <label for="precio">Precio:</label>
                <input type="number" step="0.01" id="precio" name="precio" required><br>

                <input type="submit" value="Actualizar">
            </form>

        </div>



        <!-- -------------------------------ELIMINAR  PRODUCTO  ------------------------------------->


        <div id="eliminarProducto" class="contenido-opcion">

            <h1>Eliminar Producto</h1>

            <form action="producto/eliminarProducto.php" method="POST">
                <label for="codigo">Codigo:</label>
                <input type="number" id="codigo" name="codigo" required>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <input type="submit" value="ELIMINAR">
            </form>

        </div>




    </div> <!-- -------------------------------CERRAMOS NUESTRO DIV CONTENIDO ------------------------------------->
    <script>
    // Verificar si hay un mensaje en la URL
    var mensajeEnURL = <?php echo isset($_GET['mensaje_registro']) ? 'true' : 'false'; ?>;

    // Si hay un mensaje en la URL, esperar 3 segundos y luego recargar la página
    if (mensajeEnURL) {
        setTimeout(function() {
            var urlWithoutMessage = window.location.pathname;
            history.replaceState({}, document.title, urlWithoutMessage);
            location.reload(); // Recargar la página
        }, 3000); // 3000 milisegundos = 3 segundos
    }
</script>



</body>

</html>