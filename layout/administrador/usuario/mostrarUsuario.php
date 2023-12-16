<?php
include('db.php');
// Verificar si el archivo db.php existe antes de incluirlo
if (file_exists('db.php')) {
    // Resto del código para incluir y realizar la búsqueda


    if ($conexion) {
        // Obtener el valor del parámetro de búsqueda
        $parametro_busqueda = isset($_GET['correo']) ? $_GET['correo'] : '';
        // Obtener el valor del parámetro de búsqueda por ID
        $parametro_busqueda_id = isset($_GET['id']) ? $_GET['id'] : '';

        // Verificar si se envió el formulario de búsqueda y si al menos uno de los parámetros no está vacío
        if ((!empty($_GET['correo']) || !empty($_GET['id']))) {
            $sql = "SELECT * FROM vista_UsuarioDetalle WHERE";

            // Verificar si el campo de correo no está vacío y agregar la condición correspondiente
            if (!empty($_GET['correo'])) {
                $sql .= " correo LIKE '%$_GET[correo]%'";
            }

            // Verificar si el campo de ID no está vacío y agregar la condición correspondiente
            if (!empty($_GET['id'])) {
                if (!empty($_GET['correo'])) {
                    $sql .= " OR"; // Si hay correo en la búsqueda, agrega un operador OR
                }
                $sql .= " id_usuario = $_GET[id]";
            }
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
                echo 'No se encontraron usuarios con el correo o nombre especificado.';
            }

            // Cerrar el resultado y la conexión a la base de datos
            $resultado->close();
        } else {
            echo 'Ingresa un correo o nombre para realizar la búsqueda.';
        }
    }
}
