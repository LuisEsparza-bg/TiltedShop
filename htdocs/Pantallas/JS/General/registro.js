document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        // Obtener los valores de los campos del formulario
        const username = document.querySelector('#nombreUsuario').value;
        const email = document.querySelector('#correo').value;
        const password = document.querySelector('#password').value;
        const firstName = document.querySelector('#nombre').value;
        const lastNameP = document.querySelector('#apellido1').value;
        const lastNameM = document.querySelector('#apellido2').value;
        const estado = document.querySelector('#estado').value;
        const colonia = document.querySelector('#colonia').value;
        const calle = document.querySelector('#calle').value;
        const numeroCasa = document.querySelector('#numeroCasa').value;


        // Verificar que todos los campos estén llenos
        if (!username) {
            alert("Por favor, completa el campo 'Nombre de Usuario'.");
            return;
        }

        if (!email) {
            alert("Por favor, completa el campo 'Correo Electrónico'.");
            return;
        }

        // Verificar el formato de correo electrónico
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.match(emailRegex)) {
            alert("Por favor, ingresa un correo electrónico válido.");
            return;
        }


        if (!password) {
            alert("Por favor, completa el campo 'Contraseña'.");
            return;
        }

        // Verificar la contraseña
        const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
        if (!password.match(passwordRegex)) {
            alert("La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial.");
            return;
        }

        if (!firstName) {
            alert("Por favor, completa el campo 'Nombre'.");
            return;
        }

        if (!lastNameP) {
            alert("Por favor, completa el campo 'Apellido Paterno'.");
            return;
        }

        if (!lastNameM) {
            alert("Por favor, completa el campo 'Apellido Materno'.");
            return;
        }

        // Obtener la fecha de nacimiento
        const fechaNacimientoInput = document.querySelector('#fechaNacimiento');
        const fechaNacimiento = new Date(fechaNacimientoInput.value);
        const fechaActual = new Date();
        // Verificar que la fecha de nacimiento no esté vacía
        if (!fechaNacimientoInput.value) {
            alert("Por favor, ingresa tu fecha de nacimiento.");
            return;
        }
        // Verificar que la fecha de nacimiento sea menor que la fecha actual
        if (fechaNacimiento >= fechaActual) {
            alert("La fecha de nacimiento debe ser menor que la fecha actual.");
            return;
        }


        if (!estado) {
            alert("Por favor, completa el campo 'Estado'.");
            return;
        }

        if (!colonia) {
            alert("Por favor, completa el campo 'Colonia'.");
            return;
        }

        if (!calle) {
            alert("Por favor, completa el campo 'Calle'.");
            return;
        }

        if (!numeroCasa) {
            alert("Por favor, completa el campo 'Número de Casa'.");
            return;
        }



        // Verificar la imagen
        const imagen = document.querySelector('#imagen');
        if (imagen.files.length === 0) {
            alert("Por favor, selecciona una imagen de perfil.");
            return;
        }


        var formData = new FormData(this);

        // Realiza una solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../HTML/Controllers/SP_UserRegister.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Si la solicitud se completó correctamente
                    var responseText = xhr.responseText;
                    if (responseText) {
                        // Hubo un error
                        if (responseText.includes("El usuario ya existe")) {
                            alert("El usuario ya existe");
                        } else if (responseText.includes("El correo ya está registrado")) {
                            alert("El correo ya está registrado");
                        } else {
                            alert("Error: " + responseText);
                        }
                    } else {
                        // Registro exitoso
                        alert("Registro exitoso");
                        window.location.href = '../../HTML/General/index.php';
                    }
                } else {
                    // Error de conexión o servidor
                    alert("Error en la solicitud AJAX. Código de estado: " + xhr.status);
                }
            }
        };

        // Envía los datos
        xhr.send(formData);


    });
});