document.getElementById('botonFiltro').addEventListener('click', function () {
    aplicarFiltros();
});


function aplicarFiltros() {
    // Obtiene las fechas de los filtros
    var fechaInicial = document.getElementById('fechaInicial').value;
    var fechaFinal = document.getElementById('fechaFinal').value;



    // Obtiene la opción seleccionada en el filtro de categoría
    var selectElement = document.getElementById('seleccionarCategoria');
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    var textContent = selectedOption.textContent;

    // Verifica si el filtro de fecha está activado
    var activarFiltroFecha = document.getElementById('activarFiltroFecha').checked;
    var filtroFechaActivado = activarFiltroFecha && (fechaInicial || fechaFinal);

    var filtroCategoria = document.querySelector('input[name="filtroCategoria"]:checked').value;

    // Convierte las fechas a objetos Date para facilitar la comparación
    var fechaInicialDate = fechaInicial ? new Date(fechaInicial) : null;
    var fechaFinalDate = fechaFinal ? new Date(fechaFinal) : null;


    if(activarFiltroFecha){
        if(!fechaInicialDate || !fechaFinalDate){
            alert("Se debe de seleccionar las dos fechas")
            return;
        }
    };



    var pedidos = document.getElementsByClassName('pedido');
    for (var i = 0; i < pedidos.length; i++) {
        var pedido = pedidos[i];
        var mostrarPedido = false;

        var productos = pedido.getElementsByClassName('producto');
        for (var j = 0; j < productos.length; j++) {
            var producto = productos[j];
            var categoriasProducto = producto.getAttribute('data-categorias').split(',');

            // Aquí deberías obtener la fecha de cada compra. Como no se especificó cómo
            // se almacenan estos datos, usaré una variable dummy. Reemplázala con la tuya.
            var fechaCompraString = pedido.getAttribute('data-idfecha').replace(' ', 'T');
            var fechaCompra = new Date(fechaCompraString);
            // Comprueba los filtros de fecha
            if (filtroFechaActivado) {
                if (fechaInicialDate && fechaCompra < fechaInicialDate) continue;
                if (fechaFinalDate && fechaCompra > fechaFinalDate) continue;
            }

            // Comprueba el filtro de categoría
            if (categoriasProducto.indexOf(textContent) != -1 || filtroCategoria === "todas") {
                mostrarPedido = true;
                break;
            }
        }

        if (mostrarPedido) {
            // Si al menos un producto de la compra cumple con los filtros, muestra todos los productos de la compra
            for (var j = 0; j < productos.length; j++) {
                var producto = productos[j];
                producto.style.display = 'block';
            }
        } else {
            // Si no hay productos que cumplan con los filtros, oculta todos los productos de la compra
            for (var j = 0; j < productos.length; j++) {
                var producto = productos[j];
                producto.style.display = 'none';
            }
        }

        pedido.style.display = mostrarPedido ? 'block' : 'none';
    }
}


