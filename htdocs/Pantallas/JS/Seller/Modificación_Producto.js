function DeleteProduct() {
    if (confirm("¿Estás seguro de que deseas borrar este producto?")) {
        alert("El producto se ha borrado.");
    }
    else{
        alert("El producto no se ha borrado.");
    }
}


function ModifyProduct() {
    if (confirm("¿Estás seguro de que deseas modificar este producto?")) {
        alert("El producto se ha modificado.");
    }
    else{
        alert("El producto no se ha modificado.");
    }
}