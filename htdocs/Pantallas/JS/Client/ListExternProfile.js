$(document).ready(function () {

    var urlParams = new URLSearchParams(window.location.search);
    var username = urlParams.get('username');
    var idExterna = document.getElementById('idExterna').innerHTML;


    $('.ListActiveButtons').click(function () {

        var idLista = $(this).data('id');
        var nombreLista = $(this).data('nombre');
        var descripcionLista = $(this).data('descripcion');

        // Actualiza los elementos HTML con los datos de la lista
        $('#Lista_Nombre').text('Lista: ' + nombreLista);
        $('#Lista_Descripcion').text('Descripción: ' + descripcionLista);

        var itemContainer = $('#ItemContainer').addClass("row")
        itemContainer.empty();

        // LLAMADA A AJAX PARA OBTENER LOS PRODUCTOS.

        $.ajax({
            url: '../../HTML/Controllers/SP_List.php',
            method: 'POST',
            data: {
                Lista_Type: 6,
                idlista: idLista,
            },
            success: function (response) {
                if (response) {
                    // Convertir la respuesta JSON a objeto JavaScript
                    var items = JSON.parse(response);
                    

                    itemContainer.append('<p class="ProfileC_HeaderObjects">Objetos en la lista:</p>')

                    for (var i = 0; i < items.length; i++) {
                        var item = items[i];
                        var idLista = item.ID_Lista;
                        var idProducto = item.ID_Producto;
                        var nombreProducto = item.Nombre_Producto;
                        var precioUnitario = item.Precio_Unitario;
                        var tipoProducto = item.Tipo_Producto;
                        var Imagen = item.Imagen;

                        if (tipoProducto == 1) {
                            var itemHTML = `
                            <div class="col-lg-2 mb-4">
                            <div class="card ProfileC_CardObjcts">
                            <img src="data:image/jpeg;base64,${Imagen}" class="card-img-top" />
                                <h5 class="card-title"><a class="ProfileC_TextObjects"
                                        href="../../HTML/Profiles/Products_Detail.php?id=${idProducto}">${nombreProducto}</a></h5>
                                <p class="card-text">Precio: ${precioUnitario}$</p>
                                <a href="../../HTML/Profiles/Client(Self)/Pago_ProductosI.php?type=regalo&productId=${idProducto}&idExterna=${idExterna}&precio=${precioUnitario}&cantidad=1&tipoC=1&tipoPro=1"  class="btn ProfileC_ListButtons AddProductToCart" data-username=${username} data-idproducto=${idProducto} data-precio=${precioUnitario}>Regalar</a>
                            </div>
                        </div>` ;
                        }
                        else {
                            var itemHTML = `
                            <div class="col-lg-2 mb-4">
                            <div class="card ProfileC_CardObjcts">
                            <img src="data:image/jpeg;base64,${Imagen}" class="card-img-top" />
                                <h5 class="card-title"><a class="ProfileC_TextObjects"
                                        href="../../HTML/Profiles/Products_Detail.php?id=${idProducto}">${nombreProducto}</a></h5>
                                        <p class="card-text"><b>Este producto es cotizable solamente</b></p>
                            </div>
                        </div>` ;
                        }
                        itemContainer.append(itemHTML);
                    }
                }
            },
            error: function (xhr, status, error) {
                // Manejar el error de la llamada AJAX
                console.log(error);
            }
        });


    });



});
