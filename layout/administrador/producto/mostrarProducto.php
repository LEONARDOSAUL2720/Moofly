
    <?php
            include ('db.php');
            // Verificar si el archivo db.php existe antes de incluirlo
            if (file_exists('db.php')) {
                // Resto del código para incluir y realizar la búsqueda
            
            
                if ($conexion) {
                    // Obtener el valor del parámetro de búsqueda
                    $parametro_busqueda = isset($_GET['nombre']) ? $_GET['nombre'] : '';

                    // Verificar si se envió el formulario de búsqueda y si el parámetro no está vacío
                    if (isset($_GET['nombre']) && !empty($_GET['nombre'])) {
                        // Preparar la consulta para obtener los datos de la vista "vista_UsuarioDetalle" con el filtro de búsqueda
                        $sql = "SELECT * FROM vista_ProductoDetalleIMG WHERE nombre LIKE '%$parametro_busqueda%'";
                        $resultado = $conexion->query($sql);

                        // Verificar si se obtuvieron resultados
                        if ($resultado && $resultado->num_rows > 0) {
                    // Generar la tabla HTML para mostrar los datos
                    echo '<table border="1">';
                    echo '<tr><th>codigo</th><th>Tipo Producto</th><th>Nombre</th><th>Descripcción</th> <th>Existencias</th> <th>Precio</th><th>imagen</th></tr>';

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

                             
                                echo '</tr>';
                            }

                            echo '</table>';
                        } else {
                            echo 'No se encontraron usuarios con el correo o nombre especificado.';
                        }

                        // Cerrar el resultado y la conexión a la base de datos
                        $resultado->close();
                    } else {
                        echo 'Ingresa un correo o nombre para realizar la búsqueda.';
                    }
                }
            }
            ?>