document.addEventListener("DOMContentLoaded", function () {
    const paymentForm = document.getElementById("form1");

    $('#cvv').on('focusout', function () {
        var cvv = $(this).val();
        if (cvv.length !== 4) {
            alert('El CVV debe tener exactamente 4 dígitos.');
            $(this).val(''); // borra el valor del campo de entrada
        }
    });

    $('#PayButton').click(function () {

        var nombre = document.getElementById('nombreTarjeta').value;
        var tarjeta = document.getElementById('numeroTarjeta').value;
        var fecha = document.getElementById('fechaVencimiento').value;
        var cvv = document.getElementById('cvv').value;
        var metodoPago = document.querySelector('input[name="metodoPago"]:checked');
        var metodoPagoValue = metodoPago.value;



        // Verifica que ninguno de los inputs esté vacío
        if (!nombre || !tarjeta || !fecha || !cvv) {
            alert('Todos los campos son obligatorios.');
            return;
        }

        if (!metodoPagoValue) {
            alert('Debes seleccionar un método de pago.');
            return;
        }

        // Verifica que el nombre tenga al menos 3 caracteres
        if (nombre.length < 3) {
            alert('El nombre debe tener al menos 3 caracteres.');
            return;
        }

        // Verifica que la tarjeta tenga exactamente 16 dígitos
        if (!/^\d{16}$/.test(tarjeta)) {
            alert('El número de la tarjeta debe contener exactamente 16 dígitos.');
            return;
        }

        // Verifica que la fecha de vencimiento sea válida
        var fechaIngresada = new Date(fecha);
        var mesIngresado = fechaIngresada.getUTCMonth() + 1; // los meses en JavaScript van de 0 a 11
        var anoIngresado = fechaIngresada.getUTCFullYear();

        var hoy = new Date();
        var mesActual = hoy.getUTCMonth() + 1;
        var anoActual = hoy.getUTCFullYear();

        if (anoIngresado < anoActual || (anoIngresado == anoActual && mesIngresado < mesActual)) {
            alert('La tarjeta está vencida.');
            return;
        }

        $.ajax({
            url: '../../../HTML/Controllers/SP_BuyAll.php',
            method: 'POST',
            data: {
                ID_Metodo: metodoPagoValue,
                CVV: cvv,
                Nombre: nombre,
                NumeroTarjeta: tarjeta,
                FechaVencimiento: fecha
            },
            success: function (response) {
                response = response.trim();
                if (response.includes("El producto")) {
                    alert('El producto ya no existe o su cantidad es menor a la existente')
                }
                else if (response.includes("El carrito")) {
                    alert("El carrito está vacío, es necesario llenar de productos su carrito")
                }
                else {
                    alert("Se ha realizado la compra, muchas gracias")
                    location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en la solicitud AJAX:', textStatus, errorThrown);
            }
        });
    });


});