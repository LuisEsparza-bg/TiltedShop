<?php

session_start();

include_once '../Conexion/DB_Conection.php';
include '../Models/Users.php';
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\CarProd.php');

$GlobalUserName;

class Carrito extends DB
{
    public function GestionCarrito()
    {

        $opcion = $_POST['Carrito_Type'];


        if ($opcion == '1') {
            $idProducto = $_POST['ID_Producto'];
            $Cantidad = $_POST['Cantidad'];
            $precio = $_POST['Precio'];
            $descripcionCotVenta = $_POST['Descripcion_Cot_Venta'];
            $estatus = $_POST['Estatus'];

            $username2 = $_SESSION['username'];
            $username = $_SESSION['username'];

            $IDCarrito = new Users(
                username: $username
            );

            $db = new DB();
            $pdo = $db->connect();

            if ($pdo) {
                try {
                    $IDCarrito = $IDCarrito->getCart($pdo, $username2);
                    $idCarrito = $IDCarrito;
                    $productoCarrito = new CarProd(
                        idProducto: $idProducto,
                        cantidad: $Cantidad,
                        precio: $precio,
                        descripcion_Cot_Venta: $descripcionCotVenta,
                        estatus: $estatus
                    );
                    $result = $productoCarrito->CrearProducto($pdo, $opcion, $idCarrito);
                    $pdo = null;
                    return $result;
                } catch (PDOException $e) {
                    return "Error: " . $e->getMessage();
                }
            }
        }

        if ($opcion == '2') {
            $idProducto = $_POST['ID_Producto'];
            $Cantidad = $_POST['Cantidad'];
            $username2 = $_SESSION['username'];
            $username = $_SESSION['username'];

            $IDCarrito = new Users(
                username: $username
            );

            $db = new DB();
            $pdo = $db->connect();

            if ($pdo) {
                try {
                    $IDCarrito = $IDCarrito->getCart($pdo, $username2);
                    $idCarrito = $IDCarrito;
                    $productoCarrito = new CarProd(
                        idProducto: $idProducto,
                        cantidad: $Cantidad
                    );
                    $result = $productoCarrito->ModificarProducto($pdo, $opcion, $idCarrito);
                    $pdo = null;
                    return $result;
                } catch (PDOException $e) {
                    return "Error: " . $e->getMessage();
                }
            }
        }

        if ($opcion == '3') {
            $idProducto = $_POST['ID_Producto'];
            $username = $_SESSION['username'];

            $IDCarrito = new Users(
                username: $username
            );

            $db = new DB();
            $pdo = $db->connect();

            if ($pdo) {
                try {
                    $IDCarrito = $IDCarrito->getCart($pdo, $username);
                    $idCarrito = $IDCarrito;
                    $productoCarrito = new CarProd(
                        idProducto: $idProducto,
                    );
                    $result = $productoCarrito->EliminarProducto($pdo, $opcion, $idCarrito);
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
    $carrito = new Carrito();
    $result = $carrito->GestionCarrito();
    if ($result === true) {
    } else {
        echo $result;
    }
}

?>