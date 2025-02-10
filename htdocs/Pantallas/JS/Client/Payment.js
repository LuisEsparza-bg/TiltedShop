document.addEventListener("DOMContentLoaded", function () {
    const paymentForm = document.getElementById("form1");

    paymentForm.addEventListener("submit", function (event) {
        event.preventDefault();

        alert("Gracias por su compra, le pedimos que valore el producto")

        // Aquí puedes establecer la URL de la página a la que deseas redirigir al usuario.
        const nuevaPagina = 'http://localhost:8080/Pantallas/HTML/Profiles/Client(Self)/Rating.php';

        // Redirecciona a la nueva página.
        window.location.href = nuevaPagina;
    });
});