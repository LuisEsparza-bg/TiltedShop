<?php

session_start();

include_once '../Conexion/DB_Conection.php';
include '../Models/Users.php';
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\List.php');


$GlobalUserName;

class ListP extends DB
{
    public function GestionLista()
    {

        $opcion = $_POST['Lista_Type'];

        if ($opcion == '1') {

            // CREACION 
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];

                $newPrivacity = $_POST["Lista_Privacidad"];

                $idLista = 0;

                $nombreLista = $_POST['Lista_Nombre'];
                $descripcionLista = $_POST['Lista_Descripcion'];
                // 1 ES CREACION, 2 ES MODIFICACION, 3 ELIMINACION DE LA LISTA (ESTATUS -> 0 )


                $db = new DB();
                $pdo = $db->connect();

                if ($pdo) {
                    try {
                        $sql = "CALL SP_GestionLista(:p_opcion, :p_username, :p_nombreLista, :p_descripcionLista, :p_privacidadLista, :p_idLista)";

                        $stmt = $pdo->prepare($sql);

                        $stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_STR);
                        // desde el username se consigue el ID del usuario desde el sp.
                        $stmt->bindParam(':p_username', $username, PDO::PARAM_STR);
                        $stmt->bindParam(':p_nombreLista', $nombreLista, PDO::PARAM_STR);
                        $stmt->bindParam(':p_descripcionLista', $descripcionLista, PDO::PARAM_STR);
                        $stmt->bindParam(':p_privacidadLista', $newPrivacity, PDO::PARAM_INT);
                        $stmt->bindParam(':p_idLista', $idLista, PDO::PARAM_STR);

                        $stmt->execute();

                        $pdo = null;

                        return true;
                    } catch (PDOException $e) {
                        return "Error: " . $e->getMessage();
                    }
                }
            }
        }
        // MODIFICACION
        else if ($opcion == "2") {
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];

                if (isset($_POST['Lista_Privacidad'])) {
                    $newPrivacity = ($_POST['Lista_Privacidad']);
                    if ($newPrivacity == "1")
                        $newPrivacity = 1;
                } else {
                    $newPrivacity = 0;
                }

                $nombreLista = $_POST['Lista_Nombre'];
                $descripcionLista = $_POST['Lista_Descripcion'];
                $idLista = $_POST['Lista_ID'];
                // 1 ES CREACION, 2 ES MODIFICACION, 3 ELIMINACION DE LA LISTA (ESTATUS -> 0 )
                $opcion = $_POST['Lista_Type'];
                

                $db = new DB();
                $pdo = $db->connect();

                if ($pdo) {
                    try {
                        $sql = "CALL SP_GestionLista(:p_opcion, :p_username, :p_nombreLista, :p_descripcionLista, :p_privacidadLista, :p_idLista)";

                        $stmt = $pdo->prepare($sql);

                        $stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_STR);
                        // desde el username se consigue el ID del usuario desde el sp.
                        $stmt->bindParam(':p_username', $username, PDO::PARAM_STR);
                        $stmt->bindParam(':p_nombreLista', $nombreLista, PDO::PARAM_STR);
                        $stmt->bindParam(':p_descripcionLista', $descripcionLista, PDO::PARAM_STR);
                        $stmt->bindParam(':p_privacidadLista', $newPrivacity, PDO::PARAM_INT);
                        $stmt->bindParam(':p_idLista', $idLista, PDO::PARAM_INT);

                        $stmt->execute();

                        $pdo = null;

                        return true;
                    } catch (PDOException $e) {
                        return "Error: " . $e->getMessage();
                    }
                }
            }

        }


        // ELIMINACION LOGICA
        else if ($opcion == "3") {
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];

                $newPrivacity = 0;
                $nombreLista = "a";
                $descripcionLista = "a";
                // 1 ES CREACION, 2 ES MODIFICACION, 3 ELIMINACION DE LA LISTA (ESTATUS -> 0 )
                $opcion = 3;
                $idLista = $_POST['Lista_ID'];
                

                $db = new DB();
                $pdo = $db->connect();

                if ($pdo) {
                    try {
                        $sql = "CALL SP_GestionLista(:p_opcion, :p_username, :p_nombreLista, :p_descripcionLista, :p_privacidadLista, :p_idLista)";

                        $stmt = $pdo->prepare($sql);

                        $stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
                        // desde el username se consigue el ID del usuario desde el sp.
                        $stmt->bindParam(':p_username', $username, PDO::PARAM_STR);
                        $stmt->bindParam(':p_nombreLista', $nombreLista, PDO::PARAM_STR);
                        $stmt->bindParam(':p_descripcionLista', $descripcionLista, PDO::PARAM_STR);
                        $stmt->bindParam(':p_privacidadLista', $newPrivacity, PDO::PARAM_INT);
                        $stmt->bindParam(':p_idLista', $idLista, PDO::PARAM_INT);

                        $stmt->execute();

                        $pdo = null;

                        return true;
                    } catch (PDOException $e) {
                        return "Error: " . $e->getMessage();
                    }
                }
            }

        }

// AGREGAR PRODUCTO A LA LISTA
        else if ($opcion == "4") {

            if (isset($_SESSION['username'])) {
                $idLista =  $_POST['idlista'];
                 $idProducto = $_POST["idProducto"];;

                 $opcion = 1;

               $mensaje = new ListProducts(
                    idLista: $idLista,
                    idProducto: $idProducto
               );

               $db = new DB();
               $pdo = $db->connect();

               if ($pdo) {
                   try {
                       $result = $mensaje->GestionProductosLista($pdo, $opcion);
                       return $result;
                   } catch (PDOException $e) {
                       return "Error: " . $e->getMessage();
                   }
               }
            }

        }

        // ELIMINAR PRODUCTO DE LA LISTA

        else if ($opcion == "5") {

            if (isset($_SESSION['username'])) {
                $idLista =  $_POST['idLista'];
                 $idProducto = $_POST["idProducto"];;

                 $opcion = 2;

               $mensaje = new ListProducts(
                    idLista: $idLista,
                    idProducto: $idProducto
               );

               $db = new DB();
               $pdo = $db->connect();

               if ($pdo) {
                   try {
                       $result = $mensaje->GestionProductosLista($pdo, $opcion);
                       return $result;
                   } catch (PDOException $e) {
                       return "Error: " . $e->getMessage();
                   }
               }
            }

        }

        // CARGAR LAS LISTAS
        else if ($opcion == "6") {


            if (isset($_SESSION['username'])) {
                $idLista =  $_POST['idlista'];


               $mensaje = new ListProducts(
                    idLista: $idLista,
               );

               $db = new DB();
               $pdo = $db->connect();

               if ($pdo) {
                   try {
                       $result = $mensaje->GetListItems($pdo);
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
    $list = new ListP();
    $result = $list->GestionLista();
    if ($result === true) {
    } else {
        echo $result;
    }
}

?>