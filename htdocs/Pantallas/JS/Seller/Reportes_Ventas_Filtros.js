document.getElementById('searchInput').addEventListener('click', function() {
    
    // Obtener el checkbox de rango de fechas
    var fechaCheckbox = document.getElementById('Activar_Rango');

    // Verificar si el checkbox está marcado
    var fechaCheckboxMarcado = fechaCheckbox.checked;

    //Bandera
    var fechaFlag = 0;


    // Utilizar la variable fechaCheckboxMarcado en tu lógica
    if (fechaCheckboxMarcado) {
    
        fechaFlag = 1;

        // Obtener el valor de la fecha inicial
        var fechaInicial = document.getElementById('fechaInicial').value;

        // Obtener el valor de la fecha final
        var fechaFinal = document.getElementById('fechaFinal').value;

        if(fechaInicial == "" || fechaFinal == ""){
            alert("No dejes vacio");
            return;
        }


    } else {
        
        fechaFlag = 0;

        // Obtener el valor de la fecha inicial
        var fechaInicial =null;

        // Obtener el valor de la fecha final
        var fechaFinal =null;

    }


    // Obtener el valor del radio button de filtro de categorias
    var categoriaRadio = document.querySelector('input[name="filtroCategoria"]:checked').value;
    
    // Banderas
    var categoriaFlag = 0; // Valor por defecto, se ajustará a continuación
    var idCategoria = ''; // Valor por defecto, se ajustará a continuación
    
    if(categoriaRadio == "todas" || categoriaRadio == "ninguna"){
        categoriaFlag = 0;
        idCategoria = 0; // Valor por defecto, se ajustará a continuación
    } 
    else if(categoriaRadio == "una"){
        categoriaFlag = 1;
        // Obtener el valor del select/combobox de categorias
        var categoriaSelect = document.getElementById('seleccionarCategoria').value; //VALUE DEBE SER EL ID
       
        idCategoria = categoriaSelect;
    }


    // Convertir las fechas a formato ISO
    //var fechaInicioISO = new Date(fechaInicial).toISOString();
    //var fechaFinISO = new Date(fechaFinal).toISOString();

    // Convertir las fechas a formato ISO
    //var fechaInicioISO = fechaCheckbox.checked ? new Date(fechaInicial).toISOString() : '0000-00-00';
    //var fechaFinISO = fechaCheckbox.checked ? new Date(fechaFinal).toISOString() : '0000-00-00';


    // Construir la URL con los parámetros de búsqueda y filtros
    var url = '../../Profiles/Seller/profile_SellerReports.php?idUsuario=' + encodeURIComponent('tu_id_usuario');
    url += '&fechaFlag=' + encodeURIComponent(fechaFlag);
    url += '&fechaInicio=' + encodeURIComponent(fechaInicial);
    url += '&fechaFin=' + encodeURIComponent(fechaFinal);
    url += '&categoriaFlag=' + encodeURIComponent(categoriaFlag);
    url += '&idCategoria=' + encodeURIComponent(idCategoria);

    // Redirigir a la página de resultados con los parámetros
    window.location.href = url;
});