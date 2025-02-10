
document.addEventListener("DOMContentLoaded", function () {
    $('.ListaSelected').click(function () {

        var idLista = $(this).data('idlista');
        var idProducto = $('#IDProduct').attr('value');


        $.ajax({
            url: '../../HTML/Controllers/SP_List.php',
            method: 'POST',
            data: {
                Lista_Type: 4,
                idlista: idLista,
                idProducto: idProducto
            },
            success: function (response) {
                response = response.trim();
                if (response.includes("'La lista que se quiere agregar ya ha sido eliminada")) {
                    alert('La lista que se quiere agregar ya ha sido eliminada')
                }
                else if (response.includes("El producto ha sido eliminado o no esta disponible")) {
                    alert("El producto ha sido eliminado o no esta disponible")
                }
                else if (response.includes("El producto ya existe en la lista")) {
                    alert("Este producto ya existe en la lista")
                }
                else{
                    alert("Se ha agregado exitosamente el producto a la lista")
                }
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en la solicitud AJAX:', textStatus, errorThrown);
            }
        });



    });




});