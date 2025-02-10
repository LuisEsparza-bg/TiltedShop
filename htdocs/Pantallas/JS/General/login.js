document.addEventListener('DOMContentLoaded', function () {

    const rememberlocal = localStorage.getItem('remember');
    const rememberInput = document.getElementById('Login_RememberMe');
    const loginForm = document.getElementById('Login_Form');
    const emailInput = document.getElementById('Login_Email');
    const passwordInput = document.getElementById('Login_Password');


    if (rememberlocal == 1) {
        // Recuperar el correo electrónico y la contraseña del localStorage
        const emailTemp = localStorage.getItem('email');
        const passwordTemp = localStorage.getItem('password');

        // Rellenar los campos del formulario con los datos recuperados
        document.getElementById('Login_Email').value = emailTemp;
        document.getElementById('Login_Password').value = passwordTemp;

        const rememberInputTemp = document.getElementById('Login_RememberMe');
        rememberInputTemp.checked = true;

        // Puedes incluso enviar automáticamente el formulario si lo deseas
        //document.getElementById('Login_Form').submit();
        performAjax(emailTemp, passwordTemp, rememberInput.checked);
    }

    //Si voy a iniciar sesion

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const email = emailInput.value;
        const password = passwordInput.value;
        

        if (email.length < 3) {
            alert('El nombre de usuario debe tener al menos 3 caracteres.');
            return;
        }

        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])\S{8,}$/;
        if (!password.match(passwordRegex)) {
            alert('La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.');
            return;
        }

        // Realiza la solicitud AJAX
        performAjax(email, password, rememberInput.checked);
        
    });



    function performAjax(email, password, remember) {
        //const remember = rememberInput.checked;
        // Realizar la solicitud AJAX
        $.ajax({
            url: '../../HTML/DB_APIS/API_UserVerification.php',
            type: 'POST',
            data: { Login_Email: email, Login_Password: password },
            dataType: 'json',
            success: function (data) {

                //console.log(data);
                console.log(remember);

                if (data.ref != null && data.rol != -1) {

                    //Guardar los valores de que si inicio sesion                    
                    localStorage.setItem('session', 1); //Uno es true, es que si inicio sesion. Cero es false osea cerro sesion

                    //Siempre guardar su rol y su ID para consultas posteriores
                    localStorage.setItem('rol', data.rol);
                    localStorage.setItem('id_usuario', data.id); //Creo que este se debe conservar despues de cerrar sesion


                    //Si la persona pulso RememberMe entonces guardamos su datos para en una ocasion posterior usar la data para iniciar rapido la sesion
                    if(remember == true){
                        console.log("Fue true");

                        // Guarda los valores en el localStorage
                                                             //Creo que este se debe conservar despues de cerrar sesion
                        localStorage.setItem('remember', 1); //Uno es true, es que si quiere recordar la sesion. Cero es false osea no quiere recordad la sesion 


                        //NOSE SI ESTOS DATOS ES CORRECTO GUARDARLOS O MEJOR HACER UN SP QUE LOS CONSIGA AL CARGAR EL LOGIN Y DETECTE EL REMEMBER EN 1
                        localStorage.setItem('email', data.username);
                        localStorage.setItem('password', data.password);
                    }else{
                        localStorage.removeItem('remember', 0);
                        localStorage.removeItem('email', null);
                        localStorage.removeItem('password', null);
                    }
            
                    window.location.href = data.ref;
                   

                }else if(data.rol == -1){
                    //Como no inicio sesion porque estan mal las credenciales no guardamos sesion, rol ni id_usuario


                    alert('Usuario o contraseña incorrectos. Por favor, inténtelo de nuevo.');
                }


            },
            error: function (error) {
                console.error('Error:', error);
                alert('Se produjo un error al iniciar sesión. Por favor, inténtelo de nuevo.');
            }
        });
    }


});


