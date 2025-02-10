<?php

include_once '../Conexion/DB_Conection.php';

class consultUser extends DB{

    function getUserRol(string $username, string $pass){
        // Conectar a la base de datos
        $pdo = $this->connect();

        // Definir el llamado al procedimiento almacenado
        $sql = "CALL SP_VerificarUsuario(:p_Username, :p_PassW)";

        // Preparar la consulta
        $stmt = $pdo->prepare($sql);

    
    	#print_r($username);
    	#print_r($pass);

        $stmt->bindParam(':p_Username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':p_PassW', $pass, PDO::PARAM_STR);

        

        // Ejecutar el SP
        $stmt->execute();


		// Obtener el resultado del SP
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Otra opción podría ser fetch(PDO::FETCH_OBJ)

        return $result;
        
    }



    function selectGenerico(){
    	$query = $this->connect()->query('SELECT * FROM tb_Usuario');

			return $query;
    }

}

?>