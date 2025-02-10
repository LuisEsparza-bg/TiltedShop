<?php

session_start(); 

include_once '../Conexion/DB_Conection.php';

class EditProfileClass extends DB
{
    public function EditProfile()
    {
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

            $revisar = getimagesize($_FILES["Edit_Photo"]["tmp_name"]);
            if ($revisar !== false) {
                $image = $_FILES['Edit_Photo']['tmp_name'];
                $imgContenido = file_get_contents($image);
            }


            $db = new DB();
            $pdo = $db->connect();

            if ($pdo) {
                try {
                    $sql = "CALL SP_ActualizarImagenUsuario(:p_ActualUsername, :p_Imagen)";
    
                    $stmt = $pdo->prepare($sql);
    
                    $stmt->bindParam(':p_Imagen', $imgContenido, PDO::PARAM_LOB);
                    $stmt->bindParam(':p_ActualUsername', $username, PDO::PARAM_STR);
    
                    $stmt->execute();
    
                    $pdo = null;
                    return true;
                } catch (PDOException $e) {
                    // En caso de error, puedes imprimir el mensaje de la excepción o personalizarlo según tu necesidad
                    return "Error: " . $e->getMessage();
                }
            }
        }
        else{
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new EditProfileClass();
    $result = $user->EditProfile();
    if ($result === true) {
    } else {
        echo $result;
    }
}
?>