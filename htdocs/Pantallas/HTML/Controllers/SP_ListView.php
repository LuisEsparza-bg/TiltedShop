<?php
require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idLista'])) {
        $idLista = $_POST['idLista'];
        $listaInfo = obtenerInformacionLista($idLista);
        echo json_encode($listaInfo);
    } else {
        echo json_encode(['error' => 'Falta el ID de la lista en la solicitud.']);
    }
} else {
    echo json_encode(['error' => 'Método de solicitud no válido.']);
}

function obtenerInformacionLista($idLista) {
    
    $nombreLista = "Nombre de la lista"; 
    $descripcionLista = "Descripción de la lista"; 
    
    return ['nombre' => $nombreLista, 'descripcion' => $descripcionLista];
}
?>
