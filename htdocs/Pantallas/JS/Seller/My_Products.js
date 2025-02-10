$(document).ready(function() {
    // Cuando se hace clic en el boton de buscar por categoría...
    $('.categoria').click(function() {
        // Obtenemos la categoría seleccionada del select
        var categoria = $('#seleccionarCategoria option:selected').text();

        // Ocultamos todos los productos
        $('.producto').hide();

        // Mostramos solo los productos que pertenecen a la categoría seleccionada
        $('.producto').each(function() {
            // Obtenemos las categorías del producto
            var categoriasProducto = $(this).attr('data-idcategory').split(',');

            // Verificamos si la categoría seleccionada está en las categorías del producto
            if (categoriasProducto.includes(categoria)) {
                $(this).show();
            }
        });
    });

    // Cuando se hace clic en el botón de limpiar filtro...
    $('.categorianull').click(function() {
        // Mostramos todos los productos
        $('.producto').show();
    });
});