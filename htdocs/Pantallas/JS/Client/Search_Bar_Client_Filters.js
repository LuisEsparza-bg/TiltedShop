document.getElementById('searchButton').addEventListener('click', function() {
    // Obtener el texto del input de búsqueda
    var searchText = document.getElementById('searchInput').value;

    // Obtener el valor del radio button de filtro de precio
    var precioFilter = document.querySelector('input[name="filtroPrecio"]:checked');
    var precioFilterValue = precioFilter ? precioFilter.value : '';

    // Obtener el valor del radio button de filtro de valoración
    var valoracionFilter = document.querySelector('input[name="filtroValoracion"]:checked');
    var valoracionFilterValue = valoracionFilter ? valoracionFilter.value : '';

    // Obtener el valor del radio button de filtro de ventas
    var ventasFilter = document.querySelector('input[name="filtroVentas"]:checked');
    var ventasFilterValue = ventasFilter ? ventasFilter.value : '';


    //Banderas
    var precioFlag = 0;
    var valoracionFlag = 0;
    var ventasFlag = 0;


    //Definir que pasa si estan o no vacios los inputs
    if(precioFilterValue == ""){
        precioFlag = 0;
        precioFilterValue == 0;
    }else{
        precioFlag = 1;
    }

    if(valoracionFilterValue == ""){
        valoracionFlag = 0;
        valoracionFilterValue = 0;
    }else{
        valoracionFlag = 1;
    }

    if(ventasFilterValue == ""){
        ventasFlag = 0;
        ventasFilterValue = 0;
    }else{
        ventasFlag = 1;
    }

    
    // Construir la URL con los parámetros de búsqueda y filtros
    var url = '../../HTML/Profiles/Search_Result.php?search=' + encodeURIComponent(searchText);
    url += '&precioFlag=' + encodeURIComponent(precioFlag);
    url += '&precioFilterValue=' + encodeURIComponent(precioFilterValue);
    url += '&valoracionFlag=' + encodeURIComponent(valoracionFlag);
    url += '&valoracionFilterValue=' + encodeURIComponent(valoracionFilterValue);
    url += '&ventasFlag=' + encodeURIComponent(ventasFlag);
    url += '&ventasFilterValue=' + encodeURIComponent(ventasFilterValue);


    // Redirigir a la página de resultados con los parámetros
    window.location.href = url;
});