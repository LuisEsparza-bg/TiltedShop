<?php

session_start();

include_once '../Conexion/DB_Conection.php';
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Users.php');

$GlobalUserName;

class EditProfileClass extends DB
{
    public function EditProfile()
    {
        if (isset($_SESSION['username'])) {
            $user = new Users();
            $username = $_SESSION['username'];
            $user->setUsername($_POST['Edit_Username']);
            $user->setPassword($_POST['Edit_Password']);
            $user->setName($_POST['Edit_Name']);
            $user->setLastName1($_POST['Edit_LastName1']);
            $user->setLastName2($_POST['Edit_LastName2']);
            $user->setBirthdate($_POST['Edit_Birthday']);
            $user->setEmail($_POST['Edit_Email']);
            $newGender = ($_POST['Edit_Gender'] === 'Hombre') ? 1 : 2;
            $user->setSexID($newGender);
            if (isset($_POST['Edit_privacity'])) {
                $newPrivacity = ($_POST['Edit_privacity']);
                if($newPrivacity == 0){
                    $user->setPrivacy(0);
                }
                else{
                    $user->setPrivacy(1);
                }
            }
            else{
                $user->setPrivacy(1);
            }
            $user->setState($_POST['Edit_Estado']);
            $user->setColony($_POST['Edit_Colonia']);
            $user->setStreet($_POST['Edit_Calle']);
            $user->setHouseNumber($_POST['Edit_NumeroCasa']);
            $db = new DB();
            $pdo = $db->connect();

            if ($pdo) {
                try {
                    $sql = "CALL SP_ActualizarUsuario(:p_Genero, :p_Nombre, :p_lastname1, :p_lastname2, :p_Username, :p_Password, :p_Email, :p_Fecha_Nacimiento, :p_Privacidad, :p_Estado, :p_Colonia, :p_Calle, :p_Numero_Casa, :p_ActualUsername)";

                    $stmt = $pdo->prepare($sql);

                    $newUsername = $user->getUsername();
                    $newPassword = $user->getPassword();
                    $newName = $user->getName();
                    $lastname1 = $user->getLastName1();
                    $lastname2 = $user->getLastName2();
                    $newbirthday = $user->getBirthdate();
                    $newEmail = $user->getEmail();
                    $newGender = $user->getSexID();
                    $newPrivacity = $user->getPrivacy();
                    $newEstado = $user->getState();
                    $newColonia = $user->getColony();
                    $newCalle = $user->getStreet();
                    $newNumeroCasa = $user->getHouseNumber();
            
                    $stmt->bindParam(':p_Username', $newUsername, PDO::PARAM_STR);
                    $stmt->bindParam(':p_Password', $newPassword, PDO::PARAM_STR);
                    $stmt->bindParam(':p_Nombre', $newName, PDO::PARAM_STR);
                    $stmt->bindParam(':p_lastname1', $lastname1, PDO::PARAM_STR);
                    $stmt->bindParam(':p_lastname2', $lastname2, PDO::PARAM_STR);
                    $stmt->bindParam(':p_Fecha_Nacimiento', $newbirthday, PDO::PARAM_STR);
                    $stmt->bindParam(':p_Email', $newEmail, PDO::PARAM_STR);
                    $stmt->bindParam(':p_Genero', $newGender, PDO::PARAM_INT);
                    $stmt->bindParam(':p_Privacidad', $newPrivacity, PDO::PARAM_INT);
                    $stmt->bindParam(':p_Estado', $newEstado, PDO::PARAM_STR);
                    $stmt->bindParam(':p_Colonia', $newColonia, PDO::PARAM_STR);
                    $stmt->bindParam(':p_Calle', $newCalle, PDO::PARAM_STR);
                    $stmt->bindParam(':p_Numero_Casa', $newNumeroCasa, PDO::PARAM_STR);
                    $stmt->bindParam(':p_ActualUsername', $username, PDO::PARAM_STR);

                    $stmt->execute();

                    $pdo = null;

                    $_SESSION['username'] = $user->getUsername();

                    return true;
                } catch (PDOException $e) {
                    // En caso de error, puedes imprimir el mensaje de la excepción o personalizarlo según tu necesidad
                    return "Error: " . $e->getMessage();
                }
            }
        } else {
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