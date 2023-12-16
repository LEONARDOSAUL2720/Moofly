
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


function mostrarAlerta() {
    alert("Los datos ingresados no son correctos. Inténtalo nuevamente.");
}




// $(document).ready(function() {
//     // Función para mostrar el mensaje de error
//     function mostrarMensajeError() {
//         $("#mensaje-error").removeClass("oculto").addClass("mostrar");
//     }

//     // Función para ocultar el mensaje de error
//     function ocultarMensajeError() {
//         $("#mensaje-error").removeClass("mostrar").addClass("oculto");
//     }

//     // Ejemplo de cómo utilizar las funciones
//     // Puedes llamar a estas funciones en otros eventos, como en la validación del formulario, etc.

//     // Mostrar mensaje de error (ejemplo)
//     $("#boton-submit").on("click", function(event) {
//         event.preventDefault(); // Evita el envío del formulario por defecto
//         // Aquí debes agregar tu lógica de validación y mostrar el mensaje de error si es necesario
//         mostrarMensajeError();
//     });

//     // Ocultar mensaje de error (ejemplo)
//     $("#boton-ocultar").on("click", function(event) {
//         event.preventDefault(); // Evita el envío del formulario por defecto
//         // Aquí debes agregar tu lógica para ocultar el mensaje de error cuando lo desees
//         ocultarMensajeError();
//     });
// });


