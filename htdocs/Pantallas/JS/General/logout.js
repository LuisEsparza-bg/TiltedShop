function cerrarSesion() {
    if (confirm("¿Estás seguro de que deseas cerrar la sesión?")) {
        $.ajax({
            type: 'GET',
            url: '../../HTML/Controllers/Function_Logout.php', 
            success: function(data) {
                // Limpiar items específicos del localStorage
                localStorage.removeItem('remember');
                localStorage.removeItem('email');
                localStorage.removeItem('password');
                localStorage.removeItem('session');
                localStorage.removeItem('rol');
                localStorage.removeItem('id_usuario');

                alert("Usted ha cerrado sesión exitosamente");
                window.location.href = 'http://localhost:8080/Pantallas/HTML/General/index.php';
            },
            error: function() {
                alert("Se ha producido un error, favor de contactar a la administración de Tilted Shop");
            }
        });
    } else {
        alert("La sesión no se ha cerrado.");
    }
}

$(document).ready(function() {
    $('#logout').click(function(e) {
        e.preventDefault(); 
        cerrarSesion(); 
    });
});


function cerrarSesion2() {
    if (confirm("¿Estás seguro de que deseas cerrar la sesión?")) {
        $.ajax({
            type: 'GET',
            url: '../../../HTML/Controllers/Function_Logout.php', 
            success: function(data) {
                // Limpiar items específicos del localStorage
                localStorage.removeItem('remember');
                localStorage.removeItem('email');
                localStorage.removeItem('password');
                localStorage.removeItem('session');
                localStorage.removeItem('rol');
                localStorage.removeItem('id_usuario');

                alert("Usted ha cerrado sesión exitosamente");
                window.location.href = 'http://localhost:8080/Pantallas/HTML/General/index.php';
            },
            error: function() {
                alert("Se ha producido un error, favor de contactar a la administración de Tilted Shop");
            }
        });
    } else {
        alert("La sesión no se ha cerrado.");
    }
}

$(document).ready(function() {
    $('#logout2').click(function(e) {
        e.preventDefault(); 
        cerrarSesion2(); 
    });
});