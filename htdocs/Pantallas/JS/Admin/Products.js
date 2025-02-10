function ValidateProduct() {
    const productId = event.currentTarget.getAttribute("data-product-id");
    if (confirm("¿Estás seguro de que deseas validar este producto?")) {
        // Realizar la solicitud AJAX para validar el producto
        sendValidationRequest(productId, 1); // 1 representa la acción de validación

        //alert("El producto se ha validado exitosamente.");
    }
    else{
        alert("Se ha cancelado el proceso.");
    }
}

function DevalidateProduct() {
    const productId = event.currentTarget.getAttribute("data-product-id");
    if (confirm("¿Estás seguro de que deseas no validar este producto?")) {
        // Realizar la solicitud AJAX para no validar el producto
        sendValidationRequest(productId, 0); // 0 representa la acción de no validación
        
        //alert("El producto no se ha validado.");
    }
    else{
        alert("Se ha cancelado el proceso.");
    }
}

function sendValidationRequest(productId, action) {
    // Realizar la solicitud AJAX con jQuery
    $.ajax({
        url: "../../Controllers/SP_ProductsAdminValidation.php",
        type: "POST",
        data: { Result: action, IdProduct: productId },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                alert("El producto ha sido validado correctamente.");
                // Recargar la ventana
                location.reload();
            } else {
                alert("Hubo un error al validar el producto.");
            }
        },
        error: function () {
            alert("Hubo un error en la solicitud.");
        }
    });
}
