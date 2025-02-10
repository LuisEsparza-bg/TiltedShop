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


    $('.GuardarNuevaCategoria').click(function () {
        var nombreCategoria = $('#nuevaCategoria').val();
        var descripcion = $('#nuevaDescripcion').val();
        var regex = /^[A-Za-z]+$/;

        if (!regex.test(nombreCategoria)) {
            alert("El nombre de la categoría solo puede contener letras")
            return;
        }

        if (nombreCategoria.length < 4 && nombreCategoria.length > 35 || nombreCategoria == "") {
            alert("La longitud es incorrecta del nombre, debe ser minimo 4 caracteres y máximo 35 ")
            return;
        }

        if (descripcion.length < 15 && descripcion.length > 300 || descripcion == "") {
            alert("La longitud es de la descripcion es incorrecta, debe ser minimo 15 caracteres y máximo 300 ")
            return;
        }

        $.ajax({
            url: '../../../HTML/Controllers/SP_Categories.php',
            method: 'POST',
            data: { nombreCategoria: nombreCategoria, descripcion: descripcion },
            success: function (response) {
                if (response.includes("existe")) {
                    alert("La categoría ya existe, prueba con otro nombre")
                }
                else if (response) {
                    alert("La categoria no ha podido ser creada")
                }
                else {
                    alert("La categoria se ha creado")
                    $('#nuevaDescripcion').val("");
                    $('#nuevaCategoria').val("");

                }
            },
            error: function () {
                alert("Error al eliminar la lista");
            }
        });

    });


    let addedCategories = [];
    const categoriasSelect = document.getElementById('categorias');
    const categoriasLista = document.getElementById('categoriasSeleccionadas');
    const addBtn = document.getElementById('agregarCategoria');
    const removeBtn = document.getElementById('quitarCategoria');

    // Agregar categoría seleccionada
    addBtn.addEventListener('click', () => {
        const selectedOption = categoriasSelect.options[categoriasSelect.selectedIndex];
        if (selectedOption) {
            const categoryId = selectedOption.value;
            const categoryName = selectedOption.text;
            if (!addedCategories.includes(categoryId)) {
                addedCategories.push(categoryId);
                const categoryTag = document.createElement('span');
                categoryTag.className = 'badge me-2';
                categoryTag.style.backgroundColor = getRandomColor();
                categoryTag.dataset.categoryId = categoryId;
                categoryTag.textContent = categoryName;
                categoriasLista.appendChild(categoryTag);
            }
        }
    });

    // Quitar categoría seleccionada
    removeBtn.addEventListener('click', () => {
        const selectedOption = categoriasSelect.options[categoriasSelect.selectedIndex];
        if (selectedOption) {
            const categoryId = selectedOption.value;
            const categoryTag = categoriasLista.querySelector(`span[data-category-id="${categoryId}"]`);
            if (categoryTag) {
                addedCategories = addedCategories.filter((id) => id !== categoryId);
                //SE ELIMNA DEL ARRAY EL ID QUE ES IGUAL CON FILTER
                categoriasLista.removeChild(categoryTag);
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




    $('#Btn_CrearProducto').click(function () {
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
      

        if ($('#imagen1')[0].files.length === 0 || $('#imagen2')[0].files.length === 0 || $('#imagen3')[0].files.length === 0) {
            alert('Debes llenar todos los campos de imagen.');
            return;
        }

        var imagen1 = $('#imagen1')[0].files;

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

        var formData = new FormData();
        formData.append('opcion', 1);
        formData.append('nombreProducto', nombreProducto);
        formData.append('precioProducto', precioProducto);
        formData.append('descripcionProducto', descripcionProducto);
        formData.append('inventarioProducto', inventarioProducto);
        formData.append('Categorias', JSON.stringify(addedCategories));
        formData.append('tipo', 1);
        formData.append('imagen1', $('#imagen1')[0].files[0]);
        formData.append('imagen2', $('#imagen2')[0].files[0]);
        formData.append('imagen3', $('#imagen3')[0].files[0]);
        formData.append('linkvideo', linkvideo);

        $.ajax({
            url: '../../../HTML/Controllers/SP_Products.php',
            method: 'POST',
            data: formData,
            processData: false, // Evita que jQuery transforme la data en una cadena de consulta
            contentType: false, // Indica a jQuery que no establezca un tipo de contenido
            success: function (response) {
                response = response.trim();
                if (response) {
                    alert("El producto no ha podido ser creado")
                }
                else {
                    alert("El producto se ha creado")
                    location.reload();

                }
            }
        });



    });








});