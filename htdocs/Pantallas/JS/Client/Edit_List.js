document.addEventListener("DOMContentLoaded", function () {
    const paymentForm2 = document.getElementById("form_List2");

    paymentForm2.addEventListener("submit", function (event) {
        event.preventDefault();

        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../../HTML/Controllers/SP_List.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                        alert("Se ha modificado la Lista")
                        const nuevaPagina = "/Pantallas/HTML/Profiles/Client(Self)/Profile_ClientS.php";
                        window.location.href = nuevaPagina;
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

