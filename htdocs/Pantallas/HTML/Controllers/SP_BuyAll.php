<?php

session_start();

include_once '../Conexion/DB_Conection.php';
include '../Models/Users.php';
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\CarProd.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\DetallesPago.php');

$GlobalUserName;

class Carrito extends DB
{
    public function GestionCarrito()
    {

        $idMetodo = $_POST['ID_Metodo'];
        $cvv = $_POST['CVV'];
        $nombreTarjeta = $_POST['Nombre'];
        $numeroTarjeta = $_POST['NumeroTarjeta'];
        $fechaVencimiento = $_POST['FechaVencimiento'];

        $username = $_SESSION['username'];

        $pago = new DetallesPago(
            idMetodoPago: $idMetodo,
            cvv: $cvv,
            nombreTarjeta: $nombreTarjeta,
            numeroTarjeta: $numeroTarjeta,
            fechaVencimiento: $fechaVencimiento
        );

        $db = new DB();
        $pdo = $db->connect();

        if ($pdo) {
            try {
                $result = $pago->ComprarTodo($pdo, $username);
                $pdo = null;
                return $result;
            } catch (PDOException $e) {
                return "Error: " . $e->getMessage();
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