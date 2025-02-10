document.addEventListener("DOMContentLoaded", function () {
    const paymentForm = document.getElementById("form1");

    paymentForm.addEventListener("submit", function (event) {
        event.preventDefault();

        // Obtener el valor de las estrellas seleccionadas
        const ratingValue = document.getElementById("rating-value").innerText;

        if(ratingValue == 0){
            alert("La valoracion tiene que ser mayor a 0");
            return;
        }

        // Obtener la descripción del comentario
        const comentario = document.getElementById("reseña").value;

        if(comentario.length < 10){
            alert("La reseña no puede ser menos a 10 caracteres");
            return;
        }

        // Obtener el valor del atributo data-product-id del botón
        const button = document.querySelector('.btn-enviar');
        const idP = button.dataset.productId;

        // Aquí puedes enviar los datos al servidor mediante AJAX o cualquier otro método que prefieras
        // Realizar la petición AJAX 
        $.ajax({
            type: "POST",
            url: "../../Controllers/SP_RatingSistem.php", // Reemplaza esto con la URL correcta
            data: {
                valoracionProducto: ratingValue,
                comentarioProducto: comentario,
                idProducto: idP
            },
            dataType: 'json',
            success: function(response) {

                console.log(response); // Imprimir la respuesta en la consola

                // Manejar la respuesta del servidor
                if (response.success == true) {
                    // Alerta para propósitos de prueba, puedes eliminarla en la versión final
                    alert(`Gracias por valorar el producto con ${ratingValue} estrellas. Comentario: ${comentario}`);

                    

                    // Una vez terminada la compra redirigir a la ventana de reseña
                    //var url = "../../Profiles/Client(Self)/Rating.php?compraId=" + compra + "&productId=" + idP;
                    //window.location.href = url;

                    
                    // Recargar la página actual
                    location.reload();

                } else {
                    // Mostrar un mensaje de error al usuario
                    alert('No se pudo procesar la valoracion');
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Imprimir detalles del error en la consola
                alert('Error en la solicitud AJAX');
            }
        });


    });

});