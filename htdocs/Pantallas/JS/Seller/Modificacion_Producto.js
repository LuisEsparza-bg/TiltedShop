// JavaScript para mostrar la vista previa de las imágenes y el video

document.addEventListener("DOMContentLoaded", function () {

    function mostrarVistaPrevia(input, imagen) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                imagen.attr('src', e.target.result);
                imagen.css('display', 'block');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Asociar eventos de cambio a los campos de carga de archivos
    $('#imagen1').change(function () {
        mostrarVistaPrevia(this, $('#imagenPreview1'));
    });

    $('#imagen2').change(function () {
        mostrarVistaPrevia(this, $('#imagenPreview2'));
    });

    $('#imagen3').change(function () {
        mostrarVistaPrevia(this, $('#imagenPreview3'));
    });

    $('#video').change(function () {
        var video = $('#videoPreview');
        if (this.files && this.files[0]) {
            video.attr('src', URL.createObjectURL(this.files[0]));
            video.css('display', 'block');
        }
    });

    let addedCategories = [];
    let OriginalCategories = [];
    let deletedCategories = [];
    let NewCategories = [];
    const categoriasSelect = document.getElementById('categorias');
    const categoriasLista = document.getElementById('categoriasSeleccionadas');
    const addBtn = document.getElementById('agregarCategoria');
    const removeBtn = document.getElementById('quitarCategoria');
    const idProductoLabel = document.getElementById('ID_producto');
    let idProducto = idProductoLabel.textContent;



    const categoriasSeleccionadas = document.querySelectorAll('.badge');
    categoriasSeleccionadas.forEach((categoria) => {
        const categoryId = categoria.getAttribute('data-idcategoria');
        addedCategories.push(categoryId);
        OriginalCategories.push(categoryId);
    });

    // Agregar categoría seleccionada
    addBtn.addEventListener('click', () => {
        const selectedOption = categoriasSelect.options[categoriasSelect.selectedIndex];
        if (selectedOption) {
            const categoryId = selectedOption.value;
            const categoryName = selectedOption.text;
            if (!addedCategories.includes(categoryId)) {

                if (OriginalCategories.indexOf(categoryId) !== -1) {
                    const indexToRemove = deletedCategories.findIndex(item => item === categoryId);
                    deletedCategories.splice(indexToRemove, 1);
                }

                addedCategories.push(categoryId);
                const categoryTag = document.createElement('label');
                categoryTag.className = 'badge me-2';
                categoryTag.style.backgroundColor = getRandomColor();
                categoryTag.dataset.idcategoria = categoryId;
                categoryTag.textContent = categoryName;
                categoriasLista.appendChild(categoryTag);

                if (!OriginalCategories.includes(categoryId)) {
                    NewCategories.push(categoryId);
                }

            }
        }
    });

    // Quitar categoría seleccionada
    removeBtn.addEventListener('click', () => {
        const selectedOption = categoriasSelect.options[categoriasSelect.selectedIndex];
        if (selectedOption) {
            const categoryId = selectedOption.value;

            if (OriginalCategories.includes(categoryId)) {
                deletedCategories.push(categoryId);
            }
            const categoryTag = categoriasLista.querySelector(`label[data-idcategoria="${categoryId}"]`);
            if (categoryTag) {
                addedCategories = addedCategories.filter((id) => id !== categoryId);
                //SE ELIMNA DEL ARRAY EL ID QUE ES IGUAL CON FILTER
                categoriasLista.removeChild(categoryTag);
            }

            const index = NewCategories.indexOf(categoryId);
            if (index !== -1) {
                NewCategories.splice(index, 1);
            }


        }
    });


    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }




    $('#Btn_ModificarProducto').click(function () {
        var nombreProducto = $('#nombreProducto').val();
        var precioProducto = $('#precioProducto').val();
        var descripcionProducto = $('#descripcionProducto').val();
        var inventarioProducto = $('#inventarioProducto').val();
        
        var linkvideo = $('#videoRuta').val();

        var youtubeEmbedRegex = /^https:\/\/www\.youtube\.com\/embed\/[\w-]+(\?[\w=&]+)?$/;

        // Verificar si la URL cumple con el formato "embed" de YouTube
        if (youtubeEmbedRegex.test(linkvideo)) {
        } else {
          alert("Este link no esta permitido")
          return;
        }


        if (nombreProducto.length < 4 || nombreProducto.length > 35 || nombreProducto === "") {
            alert("La longitud del nombre del producto es incorrecta. Debe tener entre 4 y 35 caracteres.");
            return;
        }

        if (descripcionProducto.length < 15 || descripcionProducto.length > 500 || descripcionProducto === "") {
            alert("La longitud de la descripción del producto es incorrecta. Debe tener entre 30 y 500 caracteres.");
            return;
        }

        if (isNaN(precioProducto) || precioProducto <= 0) {
            alert("El precio del producto es incorrecto. Debe ser un número mayor que cero.");
            return;
        }

        if (isNaN(inventarioProducto) || inventarioProducto < 0) {
            alert("La cantidad de producto disponible es incorrecta. Debe ser un número entero no negativo.");
            return;
        }

        if (addedCategories.length == 0) {
            alert("Se debe minimo de escoger una categoria");
            return;
        }

        $.ajax({
            url: '../../../HTML/Controllers/SP_Products.php',
            method: 'POST',
            data: {
                // OPCION 2 YA QUE ES UNA Modificacion
                opcion: 2,
                nombreProducto: nombreProducto,
                precioProducto: precioProducto,
                descripcionProducto: descripcionProducto,
                inventarioProducto: inventarioProducto,
                NewCategorias: NewCategories,
                DeleteCategorias: deletedCategories,
                // TIPO 1 YA QUE ES UN PRODUCTO NORMAL
                tipo: 1,
                IDProducto: idProducto
            },
            success: function (response) {
                response = response.trim();
                if (response) {
                    alert("El producto no ha podido ser modificado")
                }
                else {
                    alert("El producto se ha modificado")
                    location.reload();
                }
            }
        });



    });


    $('#Btn_EliminarProducto').click(function () {
        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            $.ajax({
                url: '../../../HTML/Controllers/SP_Products.php',
                method: 'POST',
                data: {
                    // OPCION 3 YA QUE ES UNA Eliminacion
                    opcion: 3,
                    IDProducto: idProducto
                },
                success: function (response) {
                    response = response.trim();
                    if (response) {
                        alert("El producto no ha podido ser eliminado")
                    }
                    else {
                        alert("El producto se ha eliminado")
                        location.reload();
                    }
                }

            });
        }
        else {
            alert("No se ha eliminado el producto")
        }

    });



    $('#Cambiarfoto1').click(function () {

        if ($('#imagen1')[0].files.length === 0) {
            alert('Debes llenar la foto de una nueva imagen para cambiarlo.');
            return;
        }

        var formData = new FormData();
        var idFotoProducto = $(this).data("idfotoproducto");


        formData.append('idfotoproducto', idFotoProducto);
        formData.append('opcion', 1);
        formData.append('imagen', $('#imagen1')[0].files[0]);

        if (confirm('¿Estás seguro de que quieres cambiar la imagen?')) {
            $.ajax({
                url: '../../../HTML/Controllers/SP_Images.php',
                method: 'POST',
                data: formData,
                processData: false, // Evita que jQuery transforme la data en una cadena de consulta
                contentType: false, // Indica a jQuery que no establezca un tipo de contenido
                success: function (response) {
                    response = response.trim();
                    if (response) {
                        alert("La foto no ha podido ser cambiada")
                    }
                    else {
                        alert("El foto se ha cambiado")
                        location.reload();

                    }
                }
            });
        }
        else {
            alert("No se ha eliminado el producto")
        }

    });

    $('#Cambiarfoto2').click(function () {

        if ($('#imagen2')[0].files.length === 0) {
            alert('Debes llenar la foto de una nueva imagen para cambiarlo.');
            return;
        }

        var formData = new FormData();
        var idFotoProducto = $(this).data("idfotoproducto");


        formData.append('idfotoproducto', idFotoProducto);
        formData.append('opcion', 1);
        formData.append('imagen', $('#imagen2')[0].files[0]);

        if (confirm('¿Estás seguro de que quieres cambiar la imagen?')) {
            $.ajax({
                url: '../../../HTML/Controllers/SP_Images.php',
                method: 'POST',
                data: formData,
                processData: false, // Evita que jQuery transforme la data en una cadena de consulta
                contentType: false, // Indica a jQuery que no establezca un tipo de contenido
                success: function (response) {
                    response = response.trim();
                    if (response) {
                        alert("La foto no ha podido ser cambiada")
                    }
                    else {
                        alert("El foto se ha cambiado")
                        location.reload();

                    }
                }
            });
        }
        else {
            alert("No se ha eliminado el producto")
        }

    });


    $('#Cambiarfoto3').click(function () {

        if ($('#imagen3')[0].files.length === 0) {
            alert('Debes llenar la foto de una nueva imagen para cambiarlo.');
            return;
        }

        var formData = new FormData();
        var idFotoProducto = $(this).data("idfotoproducto");


        formData.append('idfotoproducto', idFotoProducto);
        formData.append('opcion', 1);
        formData.append('imagen', $('#imagen3')[0].files[0]);

        if (confirm('¿Estás seguro de que quieres cambiar la imagen?')) {
            $.ajax({
                url: '../../../HTML/Controllers/SP_Images.php',
                method: 'POST',
                data: formData,
                processData: false, // Evita que jQuery transforme la data en una cadena de consulta
                contentType: false, // Indica a jQuery que no establezca un tipo de contenido
                success: function (response) {
                    response = response.trim();
                    if (response) {
                        alert("La foto no ha podido ser cambiada")
                    }
                    else {
                        alert("La foto se ha cambiado")
                        location.reload();

                    }
                }
            });
        }
        else {
            alert("No se ha cambiado la foto")
        }

    });


    $('#ChangeVideo').click(function () {
        var formData = new FormData();
        var idproducto = $(this).data("idproducto");

        var linkvideo = $('#videoRuta').val();

        var youtubeEmbedRegex = /^https:\/\/www\.youtube\.com\/embed\/[\w-]+(\?[\w=&]+)?$/;

        // Verificar si la URL cumple con el formato "embed" de YouTube
        if (youtubeEmbedRegex.test(linkvideo)) {
        } else {
          alert("Este link no esta permitido")
          return;
        }

        formData.append('idproducto', idproducto);
        formData.append('linkvideo', linkvideo);

        if (confirm('¿Estás seguro de que quieres cambiar el link del video?')) {
            $.ajax({
                url: '../../../HTML/Controllers/SP_Videos.php',
                method: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                success: function (response) {
                    response = response.trim();
                    if (response) {
                        alert("El Link del video no pudo ser cambiado, conctacta a la administracion")
                    }
                    else {
                        alert("El link del video se cambio")
                        location.reload();

                    }
                }
            });
        }
        else {
            alert("El link del video no ha podido ser cambiado.")
        }

    });










});