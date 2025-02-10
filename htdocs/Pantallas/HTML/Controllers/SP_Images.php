<?php

session_start();

include_once '../Conexion/DB_Conection.php';
include '../Models/Categories.php';
include '../Models/Products.php';
include '../Models/CatProd.php';
include '../Models/Imagenes_Productos.php';

$GlobalUserName;

class Imagenes extends DB
{
    public function GestionImagenes()
    {

        $opcion = $_POST["opcion"];

        // CAMBIAR LA IMAGEN DE UN PRODUCTO

        if ($opcion == 1) {

            if (isset($_SESSION['username'])) {
                $idProductoImagen = $_POST["idfotoproducto"];

                if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $blob1 = file_get_contents($_FILES['imagen']['tmp_name']);
                }

                $imagen = new ImagenProducto(
                    idImagenProducto: $idProductoImagen,
                    imagen: $blob1
                );

              

                $db = new DB();
                $pdo = $db->connect();


                if ($pdo) {
                    try {
                        if($blob1)
                        $result = $imagen->CambiarImagen($pdo);
                        return $result;
                    } catch (PDOException $e) {
                        return "Error: " . $e->getMessage();
                    }
                }

            }

        }






    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productoAlta = new Imagenes();
    $result = $productoAlta->GestionImagenes();
    if ($result === true) {
    } else {
        echo $result;
    }
}

?>