<?php

session_start();

include_once '../Conexion/DB_Conection.php';
include '../Models/Categories.php';
include '../Models/Products.php';
include '../Models/CatProd.php';
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Messages_History.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Chats.php');


$GlobalUserName;

class Mensajes extends DB
{
    public function GestionMensajes()
    {

        $opcion = $_POST["Opcion"];

        // opcion 1 PARA CREAR LA CONVERSACION POR PRIMERA VEZ

        if ($opcion == 1) {

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $idProducto = $_POST["idProducto"];
                $idVendedor = $_POST["idVendedor"];
                $mensaje = "";


                $chat = new Chat(
                    idChats: 0,
                    idUsuarioCliente: 0,
                    idUsuarioVendedor:  $idVendedor,
                    idProducto: $idProducto
                );

                $db = new DB();
                $pdo = $db->connect();

                if ($pdo) {
                    try {
                        $result = $chat->GestionMensajes($pdo, $opcion, $username, $mensaje);
                        error_log("Valor de result de sp_messages: " . $result);
                        echo $result;
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                }

            }

        }


        // Opcion 2 para cargar los mensajes.


        if ($opcion == 2) {

                if (isset($_SESSION['username'])) {
                    $idChat = $_POST["idChat"];
    
                    $chat = new Chat(
                        idChats: $idChat,
                    );
    
                    $db = new DB();
                    $pdo = $db->connect();
    
                    if ($pdo) {
                        try {
                            $result = $chat->CargarMensajes($pdo);
                            return $result;
                        } catch (PDOException $e) {
                            return "Error: " . $e->getMessage();
                        }
                    }
    
                }
    
            }

        // OPCION 3 MANDAR MENSAJES

        if ($opcion == 3) {

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $idVendedor = $_POST["idVendedor"];
                $mensaje = $_POST["mensaje"];;
                $idChat = $_POST["idChat"];;


                $mensaje = new MessagesHistory(
                    idChat: $idChat,
                    idUsuarioEmisor: 0,
                    idUsuarioReceptor: $idVendedor,
                    mensaje: $mensaje
                );


                $db = new DB();
                $pdo = $db->connect();

                if ($pdo) {
                    try {
                        $result = $mensaje->EnviarMensaje($pdo, $username);
                        return $result;
                    } catch (PDOException $e) {
                        return "Error: " . $e->getMessage();
                    }
                }

            }

        }


        if ($opcion == 4) {

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $idVendedor = $_POST["idVendedor"];
                $mensaje = $_POST["mensaje"];;
                $idChat = $_POST["idChat"];;


                $mensaje = new MessagesHistory(
                    idChat: $idChat,
                    idUsuarioEmisor: 0,
                    // SE OBTIENE DESDE EL USERNAME
                    idUsuarioReceptor: 1,
                    mensaje: $mensaje
                );


                $db = new DB();
                $pdo = $db->connect();

                if ($pdo) {
                    try {
                        $result = $mensaje->EnviarMensajeS($pdo, $username, $idVendedor);
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
    $productoAlta = new Mensajes();
    $result = $productoAlta->GestionMensajes();
    if ($result === true) {
    } else {
    }
}

?>