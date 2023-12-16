    function mostrarContenido(opcion) {
        // Ocultar todos los contenidos
        var contenidos = document.getElementsByClassName('contenido-opcion');
        for (var i = 0; i < contenidos.length; i++) {
            contenidos[i].style.display = 'none';
           
        }
        

        // Mostrar el contenido de la opción seleccionada
        var contenidoMostrar = document.getElementById(opcion);
        contenidoMostrar.style.display = 'block';
         
    }


    // function mostrarContenido(opcion) {
    //     // Ocultar todos los contenidos
    //     var contenidos = document.getElementsByClassName('contenido-opcion');

    //     for (var i = 0; i < contenidos.length; i++) {
    //         contenidos[i].style.display = 'none';
    //     }

    //     // Mostrar el contenido seleccionado
    //     var contenidoMostrar = document.getElementById(opcion);
    //     if (contenidoMostrar) {
    //         contenidoMostrar.style.display = 'block';
    //     } else {
    //         // Si no se proporciona un ID de opción válido, mostrar por defecto "mostrarUsuario"
    //         document.getElementById('mostrarUsuario').style.display = 'block';
    //     }
    // }

