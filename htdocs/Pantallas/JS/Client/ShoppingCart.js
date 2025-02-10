
document.addEventListener("DOMContentLoaded", function () {
    $('#AddProductToCart').click(function () {
        var inventarioProducto = $('#AddQuantityCar').val();
        var idProducto = document.getElementById('IDProduct').getAttribute('value');
        var precioProducto = document.getElementById('PrecioProducto').getAttribute('value');


        if (inventarioProducto <= 0) {
            alert("La cantidad del producto escogida no puede ser menor que 0 o 0");
            return;
        }

        $.ajax({
            url: '../../HTML/Controllers/SP_Carrito.php',
            method: 'POST',
            data: {
                Precio: precioProducto,
                Descripcion_Cot_Venta: "",
                Cantidad: inventarioProducto,
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

    $('.quantityInput').on({
        focus: function () {
            // Al obtener el foco, guarda la cantidad actual en un atributo data
            $(this).data('oldValue', $(this).val());
        },
        focusout: function () {
            var input = $(this); // Guarda una referencia a $(this)
            var idProducto = input.data('idproducto');
            var inventarioProducto = input.val();
            var oldValue = input.data('oldValue');

            if (inventarioProducto <= 0) {
                alert("La nueva cantidad debe ser mayor que 0")
                input.val(oldValue);
            }

            if (inventarioProducto != oldValue) {
                $.ajax({
                    url: '../../../HTML/Controllers/SP_Carrito.php',
                    method: 'POST',
                    data: {
                        Cantidad: inventarioProducto,
                        Carrito_Type: 2,
                        ID_Producto: idProducto
                    },
                    success: function (response) {
                        response = response.trim();
                        if (response.includes("La cantidad solicitada es mayor que el stock disponible")) {
                            alert("La cantidad solicitada es mayor que el stock disponible");
                            // Usa la referencia guardada a $(this)
                            input.val(oldValue);
                        }
                        else {
                            alert("La cantidad en el carrito se ha actualizado");
                            location.reload();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('Error en la solicitud AJAX:', textStatus, errorThrown);
                    }
                });
            }
        }
    });

    $('.DeleteProduct').click(function () {
        var input = $(this); // Guarda una referencia a $(this)
        var idProducto = input.data('idproducto');


        if (confirm("¿Estás seguro de que quieres eliminar el producto de tu carrito?")) {
            $.ajax({
                url: '../../../HTML/Controllers/SP_Carrito.php',
                method: 'POST',
                data: {
                    Carrito_Type: 3,
                    ID_Producto: idProducto,
                },
                success: function (response) {
                    response = response.trim();
                    if(response){
                        alert("No se ha podido borrar el producto, contacta a un administrador")
                    }
                    else{
                        alert("Se ha borrado el producto de tu carrito")
                        location.reload();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error en la solicitud AJAX:', textStatus, errorThrown);
                }
            });
        } else {
            alert("No se ha borrado el producto de tu carrito")
        }
       



    });


    


    var productosAgotadosElement = document.getElementById('productos-agotados');
    if (productosAgotadosElement) {
        var productosAgotados = JSON.parse(productosAgotadosElement.value);

        if (productosAgotados.length > 0) {
            var nombresProductos = productosAgotados.map(function (producto) {
                return producto.Nombre_Producto;
            });

            alert("Los siguientes productos se han eliminado de tu carrito debido a que ya no hay stock: " + nombresProductos.join(", "));
        }
    }





});