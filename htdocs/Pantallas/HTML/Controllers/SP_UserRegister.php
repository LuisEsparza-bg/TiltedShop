<?php
include_once '../Conexion/DB_Conection.php';

class RegisterUser extends DB
{
    public function registerNewUser()
    {

        try {


            $revisar = getimagesize($_FILES["Register_imagen"]["tmp_name"]);
            if ($revisar !== false) {
                $image = $_FILES['Register_imagen']['tmp_name'];
                $imgContenido = file_get_contents($image);
            }

            $nombreUsuario = $_POST['Register_nombreUsuario'];
            $correo = $_POST['Register_correo'];
            $password = $_POST['Register_password'];
            $nombre = $_POST['Register_nombre'];
            $apellido1 = $_POST['Register_apellido1'];
            $apellido2 = $_POST['Register_apellido2'];
            $sexo =  $_POST['Register_sexo'];
            if ($sexo == "Masculino"){
                $sexo = 1;
            }
            else{
                $sexo = 2;
            }
            $Roles = $_POST['Register_Rol'];
            if ($Roles == "Vendedor"){
                $Roles = 2;
            }
            else{
                $Roles = 3;
            }
            $fechaNacimiento = $_POST['Register_fechaNacimiento'];
            $estado = $_POST['Register_estado'];
            $colonia = $_POST['Register_colonia'];
            $calle = $_POST['Register_calle'];
            $numeroCasa = $_POST['Register_numeroCasa'];
            $tipoPrivacidad = $_POST['Register_tipoPrivacidad'];

            $pdo = $this->connect();

            $sql = "CALL SP_RegistroUsuario(:p_ID_Sexo, :p_ID_Roles, :p_Nombre, :p_Apellido_Paterno, :p_Apellido_Materno, :p_Username, :p_PassW, :p_Correo, :p_Imagen, :p_Fecha_Nacimiento, :p_Privacidad, :p_Estado, :p_Colonia, :p_Calle, :p_Numero_Casa)";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':p_ID_Sexo', $sexo, PDO::PARAM_INT);
            $stmt->bindParam(':p_ID_Roles', $Roles, PDO::PARAM_INT);
            $stmt->bindParam(':p_Nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':p_Apellido_Paterno', $apellido1, PDO::PARAM_STR);
            $stmt->bindParam(':p_Apellido_Materno', $apellido2, PDO::PARAM_STR);
            $stmt->bindParam(':p_Username', $nombreUsuario, PDO::PARAM_STR);
            $stmt->bindParam(':p_PassW', $password, PDO::PARAM_STR);
            $stmt->bindParam(':p_Correo', $correo, PDO::PARAM_STR);
            $stmt->bindParam(':p_Imagen', $imgContenido, PDO::PARAM_STR);
            $stmt->bindParam(':p_Fecha_Nacimiento', $fechaNacimiento, PDO::PARAM_STR);
            $stmt->bindParam(':p_Privacidad', $tipoPrivacidad, PDO::PARAM_INT);
            $stmt->bindParam(':p_Estado', $estado, PDO::PARAM_STR);
            $stmt->bindParam(':p_Colonia', $colonia, PDO::PARAM_STR);
            $stmt->bindParam(':p_Calle', $calle, PDO::PARAM_STR);
            $stmt->bindParam(':p_Numero_Casa', $numeroCasa, PDO::PARAM_STR);

            $stmt->execute();

            $pdo = null;
            return true;
        } catch (PDOException $e) {
            // En caso de error, puedes imprimir el mensaje de la excepción o personalizarlo según tu necesidad
            return "Error: " . $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new RegisterUser();
    $result = $user->registerNewUser();
    if ($result === true) {
    } else {
        echo $result;
    }
}


?>