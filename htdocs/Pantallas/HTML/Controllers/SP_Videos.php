<?php

session_start();

include_once '../Conexion/DB_Conection.php';
include '../Models/Categories.php';
include '../Models/Products.php';
include '../Models/CatProd.php';
include '../Models/Videos.php';

class VideosV extends DB
{
    public function GestionVideos()
    {
            if (isset($_SESSION['username'])) {
                $idProducto = $_POST["idproducto"];
                $RutaVideo = $_POST["linkvideo"];

                $video = new VideosProducto(
                    idProducto: $idProducto,
                    video: $RutaVideo
                );


                $db = new DB();
                $pdo = $db->connect();


                if ($pdo) {
                    try {
                        $result = $video->CambiarVideo($pdo);
                        return $result;
                    } catch (PDOException $e) {
                        return "Error: " . $e->getMessage();
                    }
                }

            }

    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $videoAlta = new VideosV();
    $result = $videoAlta->GestionVideos();
    if ($result === true) {
    } else {
        echo $result;
    }
}

?>