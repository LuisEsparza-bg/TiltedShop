document.addEventListener("DOMContentLoaded", function () {
    const form1 = document.getElementById("form1");
    const form2 = document.getElementById("form2");

    form1.addEventListener("submit", function (event) {
        event.preventDefault();

        const inputElement = document.querySelector('#Edit_Photo');
        const imagen = inputElement.files[0]; 
        
        if (!imagen) {
            alert("Por favor, selecciona una imagen de perfil.");
            return false;
        }
        
        const fileExtension = imagen.name.split('.').pop().toLowerCase();
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!allowedExtensions.includes(fileExtension)) {
            alert("La imagen debe estar en formato JPG, JPEG, PNG o GIF.");
            return false;
        }

        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../../HTML/Controllers/SP_EditProfilePhoto.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var responseText = xhr.responseText;
                    if (responseText) {
                        alert("Error: " + responseText);
                    } else {
                        alert("Enhorabuena, haz actualizado tu foto de perfil");
                        location.reload();
                    }
                } else {
                    alert("Error en la solicitud AJAX. Código de estado: " + xhr.status);
                }
            }
        };
        // Envía los datos
        xhr.send(formData);


    });

    form2.addEventListener("submit", function (event) {
        event.preventDefault();

        const username = document.querySelector('#Edit_Username').value;
        const email = document.querySelector('#Edit_Email').value;
        const password = document.querySelector('#Edit_Password').value;
        const firstName = document.querySelector('#Edit_Name').value;
        const lastNameP = document.querySelector('#Edit_LastName1').value;
        const lastNameM = document.querySelector('#Edit_LastName2').value;
        const estado = document.querySelector('#Edit_Estado').value;
        const colonia = document.querySelector('#Edit_Colonia').value;
        const calle = document.querySelector('#Edit_Calle').value;
        const numeroCasa = document.querySelector('#Edit_NumeroCasa').value;
        const fechaNacimientoInput = document.querySelector('#Edit_Birthday');

        // Agregar las validaciones aquí (similares a las del registro)
        if (!username || !email || !password || !firstName || !lastNameP || !lastNameM || !estado || !colonia || !calle || !numeroCasa) {
            alert("Por favor, completa todos los campos.");
            return ;
        }

        const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
        if (!password.match(passwordRegex)) {
            alert("La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial.");
            return ;
        }

        // Validar formato de correo electrónico
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.match(emailRegex)) {
            alert("Por favor, ingresa un correo electrónico válido.");
            return ;
        }

        // Validar la fecha de nacimiento (puedes ajustar esta validación)
        const currentDate = new Date();
        const fechaNacimiento = new Date(fechaNacimientoInput.value);
        if (fechaNacimiento  >= currentDate) {
            alert("La fecha de nacimiento debe ser anterior a la fecha actual.");
            return ;
        }
        
            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../../../HTML/Controllers/SP_EditProfile.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        // Si la solicitud se completó correctamente
                        var responseText = xhr.responseText;
                        if (responseText) {
                            // Hubo un error
                            if (responseText.includes("El nombre de usuario")) {
                                alert("El usuario ya existe");
                            } else if (responseText.includes("El correo ya está registrado")) {
                                alert("El correo ya está registrado");
                            } else {
                                alert("Error: " + responseText);
                            }
                        } else {
                            // Registro exitoso
                            alert("Edicion de perfil exitosa");
                            location.reload();
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
