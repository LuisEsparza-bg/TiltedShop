document.addEventListener("DOMContentLoaded", function () {
    const paymentForm = document.getElementById("form1");
    const metodoPagoSelect = document.getElementById("metodoPago");
    const datosTarjetaDiv = document.getElementById("datosTarjeta");
    const datosTarjeta2Div = document.getElementById("datosTarjeta2");
    const datosTarjeta3Div = document.getElementById("datosTarjeta3");
    const datosPayPalDiv = document.getElementById("datosPayPal");
    const datosPayPal2Div = document.getElementById("paypal-button");
    
     // Variable para almacenar el método de pago seleccionado
     let metodoPagoSeleccionado = "";
    
    // Evento de cambio en el campo de selección de método de pago
    metodoPagoSelect.addEventListener("change", function () {

         // Almacenar el método de pago seleccionado
         metodoPagoSeleccionado = metodoPagoSelect.value;

        // Mostrar u ocultar campos según el método de pago seleccionado
        if (metodoPagoSelect.value === "TarjetaC" || metodoPagoSelect.value === "TarjetaD") {
            datosTarjetaDiv.style.display = "";
            datosTarjeta2Div.style.display = "";
            datosTarjeta3Div.style.display = "";
            
            datosPayPalDiv.style.display = "none";
            datosPayPal2Div.style.display = "none";

            document.getElementById('nombreTarjeta').disabled = false; // habilitar
            document.getElementById('numeroTarjeta').disabled = false; // habilitar
            document.getElementById('fechaVencimiento').disabled = false; // habilitar
            document.getElementById('cvv').disabled = false; // habilitar
            document.getElementById('datosPayPal2').disabled = true; // deshabilitar

        } else if (metodoPagoSelect.value === "PayPal") {
            datosTarjetaDiv.style.display = "none";
            datosTarjeta2Div.style.display = "none";
            datosTarjeta3Div.style.display = "none";

            datosPayPalDiv.style.display = "block";
            datosPayPal2Div.style.display = "block";
            
            document.getElementById('nombreTarjeta').disabled = true; // deshabilitar
            document.getElementById('numeroTarjeta').disabled = true; // deshabilitar
            document.getElementById('fechaVencimiento').disabled = true; // deshabilitar
            document.getElementById('cvv').disabled = true; // deshabilitar
            document.getElementById('datosPayPal2').disabled = false; // deshabilitar
        }
    });
    

        // Obtener la URL actual
        var url = window.location.href;

        // Obtener la parte de la URL que contiene los parámetros
        var queryString = window.location.search;

        // Crear un objeto URLSearchParams para manipular los parámetros
        var urlParams = new URLSearchParams(queryString);

        // Obtener el valor de un parámetro específico
        var idP = urlParams.get('productId');
        var cantidadP = urlParams.get('cantidad');
        var tipoCom = urlParams.get('tipoC');
        var tipoPro = urlParams.get('tipoPro');

        // Imprimir los valores en la consola
        console.log('Product ID:', idP);
        console.log('Cantidad:', cantidadP);
        console.log('TipoCompra:', tipoCom); //Es el tipo de compra (1 item o muchos)
        console.log('TipoProd:', tipoPro); //Es el tipo de produccto (normal o cotizado)
    
    
    
    
        //Al pulsar el boton de comprar con tarjeta 
        paymentForm.addEventListener("submit", function (event) {
            event.preventDefault();

            if(metodoPagoSelect.value === "TarjetaC" || metodoPagoSelect.value === "TarjetaD"){

                 // Obtener los valores del formulario
                var nombreTarjetaP = document.getElementById('nombreTarjeta').value;
                var numeroTarjetaP = document.getElementById('numeroTarjeta').value;
                var fechaVencimientoP = document.getElementById('fechaVencimiento').value;
                var cvvP = document.getElementById('cvv').value;

                var metodoId = 0;
                if(metodoPagoSeleccionado == "TarjetaC" || metodoPagoSeleccionado == "" ){
                    metodoId =1;
                }else{
                    metodoId=2;
                }

                // Realizar la petición AJAX 
                $.ajax({
                    type: "POST",
                    url: "../../../Controllers/SP_ProductsPayment.php", // Reemplaza esto con la URL correcta
                    data: {
                        productId: idP,
                        cantidad: cantidadP,
                        tipoCom: tipoCom,
                        tipoProd: tipoPro,
                        nombreTarjeta: nombreTarjetaP,
                        numeroTarjeta: numeroTarjetaP,
                        fechaVencimiento: fechaVencimientoP,
                        cvv: cvvP,
                        metodoP: metodoId 
                    },
                    dataType: 'json',
                    success: function(response) {

                        console.log(response); // Imprimir la respuesta en la consola

                        // Manejar la respuesta del servidor
                        if (response.success == true) {
                            alert("Gracias por su compra, le pedimos que valore el producto");

                            var compra = response.idCompra;

                            // Una vez terminada la compra redirigir a la ventana de reseña
                            var url = "../../Profiles/Client(Self)/Rating.php?compraId=" + compra + "&productId=" + idP;
                            
                            window.location.href = url;
                        } else {

                            // Mostrar un mensaje de error al usuario
                            alert('No se pudo procesarl la compra');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText); // Imprimir detalles del error en la consola
                        alert('Error en la solicitud AJAX');
                    }
                });



                // alert("Gracias por su compra, le pedimos que valore el producto");

                // Aquí puedes establecer la URL de la página a la que deseas redirigir al usuario.
                // const nuevaPagina = 'http://localhost:8080/Pantallas/HTML/Profiles/Client(Self)/Rating.php';

                // Redirecciona a la nueva página.
                //window.location.href = nuevaPagina;

            }

            if(metodoPagoSelect.value === "PayPal"){
                alert("Gracias por su compra con paypal");
            }

            return false; // Evita el envío predeterminado para otros casos si es necesario


        });
    
});