
    // Función para formatear un número con dos dígitos (agrega un cero si el número es menor que 10)
    function formatNumberWithTwoDigits(number) {
        return number < 10 ? `0${number}` : number;
    }

    // Obtiene la fecha y hora actual
    const currentDate = new Date();

    // Obtiene el día, mes, año y hora
    const day = formatNumberWithTwoDigits(currentDate.getDate());
    const month = formatNumberWithTwoDigits(currentDate.getMonth() + 1); // Los meses en JavaScript son indexados desde 0 (enero es 0)
    const year = currentDate.getFullYear();
    const hours = formatNumberWithTwoDigits(currentDate.getHours());
    const minutes = formatNumberWithTwoDigits(currentDate.getMinutes());

    // Crea una cadena con el formato deseado: "dd/mm/aaaa hh:mm"
    const formattedDate = `Fecha: ${day}  /${month} /${year}    Hora  ${hours}:${minutes}`;

    // Actualiza el contenido del elemento con la fecha y hora actual
    document.getElementById("current-date").textContent = formattedDate;
