$(document).ready(function () {
    // Agrega la funcionalidad de calificación al hacer clic en las estrellas
    $('.star').on('click', function () {
        const rating = $(this).data('rating');
        $('#rating-value').text(rating);

        // Cambia el aspecto de las estrellas seleccionadas
        $('.star').removeClass('text-warning');
        $(this).prevAll('.star').addBack().addClass('text-warning');
    });
});