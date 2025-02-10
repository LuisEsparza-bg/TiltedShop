document.addEventListener("DOMContentLoaded", function () {
    // Obtener el botón de compra
    var comprarBtn = document.getElementById('comprarBtn');

    // Verificar si el botón existe antes de agregar el manejador de eventos
    if (comprarBtn) {
        // Agregar un manejador de eventos al hacer clic en el botón de compra
        comprarBtn.addEventListener('click', function () {
            // Obtener el valor del input de cantidad
            var cantidad = $('#AddQuantityCar').val();
            var idP = this.getAttribute('data-product-id');

            // Verificar que la cantidad sea mayor que 0
            if (cantidad > 0) {
                // Realizar la petición AJAX 
                $.ajax({
                    type: "POST",
                    url: "../../HTML/Controllers/SP_ProductStockVerification.php", // Reemplaza esto con la URL correcta
                    data: {
                        productId: idP,
                        cantidad: cantidad
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Manejar la respuesta del servidor
                        if (response.success == true) {
                            // Hay suficiente stock, redirigir al usuario a la ventana de pago, el tipo es compra simple o compra multiproductos
                            var url = "../../HTML/Profiles/Client(Self)/Pago_ProductosI.php?productId=" + idP + "&cantidad=" + cantidad + "&tipoC=" + 1 + "&tipoPro=" + 1;
                            
                            window.location.href = url;
                        } else {
                            // Mostrar un mensaje de error al usuario
                            alert('No hay suficiente stock para esa cantidad');
                        }
                    }
                });
            } else {
                // Mostrar un mensaje de error o realizar otra acción si la cantidad es 0
                alert('La cantidad debe ser mayor que 0');
            }
        });
    }
});