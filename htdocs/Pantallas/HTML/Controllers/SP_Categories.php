<?php

session_start();

include_once '../Conexion/DB_Conection.php';
include '../Models/Users.php';
include '../Models/Categories.php';

$GlobalUserName;

class CategoriesA extends DB
{
    public function CrearCategoria()
    {
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $nombreCategoria = $_POST["nombreCategoria"];
                $descripcionLista = $_POST["descripcion"];

                $categorias = new Categories(
                    username: $username,
                    nombre: $nombreCategoria,
                    descripcion: $descripcionLista,
                );

                $db = new DB();
                $pdo = $db->connect();

                if ($pdo) {
                    try {
                        $result = $categorias->CrearCategoria($pdo);
                        $pdo = null;
                        return $result;
                    } catch (PDOException $e) {
                        return "Error: " . $e->getMessage();
                    }
                }
            }
        }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoria = new CategoriesA();
    $result = $categoria->CrearCategoria();
    if ($result === true) {
    } else {
        echo $result;
    }
}

?>