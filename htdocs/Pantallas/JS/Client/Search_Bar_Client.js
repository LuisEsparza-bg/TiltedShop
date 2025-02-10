document.getElementById('searchButton').addEventListener('click', function() {
    // Obtener el texto del input de búsqueda
    var searchText = document.getElementById('searchInput').value;

    // Redirigir a la página de resultados con el texto como parámetro
    window.location.href = '../../HTML/Profiles/Search_Result.php?search=' + encodeURIComponent(searchText);
});