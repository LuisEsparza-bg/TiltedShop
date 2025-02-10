$(document).ready(function () {

    $('.ListActiveButtons').click(function () {

        var idLista = $(this).data('id');
        var nombreLista = $(this).data('nombre');
        var descripcionLista = $(this).data('descripcion');

        // Actualiza los elementos HTML con los datos de la lista
        $('#Lista_Nombre').text('Lista: ' + nombreLista);
        $('#Lista_Descripcion').text('Descripción: ' + descripcionLista);

        // Modifica el enlace "Editar Lista" para incluir el ID
        var editLink = $('.ProfileC_EditListLink');
        editLink.attr('href', '../../../HTML/Profiles/Client(Self)/edit_list.php?id=' + idLista);

        // Modifica el enlace Boton Lista para incluir el ID
        var editLink2 = $('.ProfileC_DeleteListButton');
        editLink2.attr('data-idLista', idLista);

        const editListButton = document.getElementById('ProfileC_EditListLink');
        const deleteListButton = document.getElementById('ProfileC_DeleteListButton');

        editListButton.hidden = false;
        deleteListButton.hidden = false;

        var itemContainer = $('#ItemContainer').addClass("row")
        itemContainer.empty();


        // LLAMADA A AJAX PARA OBTENER LOS PRODUCTOS.

        $.ajax({
            url: '../../../HTML/Controllers/SP_List.php',
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
                                        href="../../Profiles/Products_Detail.php?id=${idProducto}">${nombreProducto}</a></h5>
                                <p class="card-text">Precio: ${precioUnitario}$</p>
                                <button class="btn ProfileC_ListButtons DeleteListItem" data-idproducto= "${idProducto}" data-idlista="${idLista}"> Eliminar de Lista</button>
                                <a href="#" class="btn ProfileC_ListButtons AddProductToCart" data-idproducto=${idProducto} data-precio=${precioUnitario}>Agregar a Carrito</a>
                            </div>
                        </div>` ;
                        }
                        else {
                            var itemHTML = `
                            <div class="col-lg-2 mb-4">
                            <div class="card ProfileC_CardObjcts">
                                <img src="${img}" class="card-img-top">
                                <h5 class="card-title"><a class="ProfileC_TextObjects"
                                        href="../../Profiles/Products_Detail.php?id=${idProducto}">${nombreProducto}</a></h5>
                                <button class="btn ProfileC_ListButtons DeleteListItem" data-idproducto= "${idProducto}" data-idlista="${idLista}"> Eliminar de Lista</button>
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


    $('.ProfileC_DeleteListButton').click(function () {
        var idLista = document.querySelector('.ProfileC_DeleteListButton').dataset.idlista;

        if (confirm('¿Estás seguro de que deseas eliminar esta lista?')) {
            $.ajax({
                url: '../../../HTML/Controllers/SP_List.php',
                method: 'POST',
                data: { Lista_Type: 3, Lista_ID: idLista },
                success: function (response) {
                    alert("La lista ha sido eliminada");
                    location.reload();
                },
                error: function () {
                    alert("Error al eliminar la lista");
                }
            });
        } else {
            alert("No se ha borrado la lista");
        }
    });


    // BORRAR PORDUCTO DE LISTA
    $(document).on('click', '.DeleteListItem', function () {
        var idProducto = $(this).data('idproducto');
        var idlista = $(this).data('idlista');

        if (confirm('¿Estás seguro de que deseas eliminar esta producto de la lista?')) {
            $.ajax({
                url: '../../../HTML/Controllers/SP_List.php',
                method: 'POST',
                data: { Lista_Type: 5, idLista: idlista, idProducto: idProducto },
                success: function (response) {
                    alert("El producto se ha borrado exitosamente de la lista");
                    location.reload();
                },
                error: function () {
                    alert("Error al eliminar la lista");
                }
            });
        } else {
            alert("No se ha borrado la lista");
        }
    });


    // AGREGAR 1 PRODUCTO A TU CARRITO (OJO SOLO SE AGREGA CANTIDAD 1)
    $(document).on('click', '.AddProductToCart', function () {
        var idProducto = $(this).data('idproducto');
        var precioUnitario = $(this).data('precio');

        $.ajax({
            url: '../../../HTML/Controllers/SP_Carrito.php',
            method: 'POST',
            data: {
                Precio: precioUnitario,
                Descripcion_Cot_Venta: "",
                Cantidad: 1,
                Carrito_Type: 1,
                ID_Producto: idProducto,
                Estatus: 1
            },
            success: function (response) {
                response = response.trim();
                if (response.includes("El producto ya está")) {
                    alert('El producto ya está en el carrito. Por favor, modifique la cantidad en la página de carrito.')
                }
                else if (response.includes("La cantidad solicitada es mayor que el stock disponible")) {
                    alert("La cantidad solicitada es mayor que el stock disponible")
                }
                else {
                    alert("El producto se ha agregado a tu carrito")
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en la solicitud AJAX:', textStatus, errorThrown);
            }
        });



    });



});
