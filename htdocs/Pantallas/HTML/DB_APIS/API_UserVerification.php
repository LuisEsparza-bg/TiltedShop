<?php
# echo "API_UserVerification.php se está ejecutando.";

include_once 'SP_UserVerification.php';

class UserLogIn
{

	function validateUser()
	{
		$user = new consultUser();
		$username = $_POST['Login_Email']; # Reemplaza 'username' con el nombre de tu campo de entrada de nombre de usuario en el formulario
		$pass = $_POST['Login_Password']; # Reemplaza 'contraseña' con el nombre de tu campo de entrada de contraseña en el formulario
		$usernameR = '';
		$data = $user->getUserRol($username, $pass);

		# echo var_dump($rol);


		if ($data['ID_Roles']) {

			$rol = (int) $data['ID_Roles'];
			$id = (int) $data['ID_Usuario'];


			if ($rol == 1) {
				// Usuario es administrador, prepara los datos que deseas almacenar
				$userData = $userData = array('username' => $username, 'password' => $pass, 'rol' => $rol, 'id' => $id, 'ref' => '../../HTML/Profiles/Admin/Products_TBD.php');
				$usernameR = $data['Usuario'];
				
			} elseif ($rol == 2) {
				// Usuario es vendedor, prepara los datos correspondientes
				$userData = $userData = array('username' => $username, 'password' => $pass, 'rol' => $rol, 'id' => $id, 'ref' => '../../HTML/Profiles/Seller/My_Products.php');
					$usernameR = $data['Usuario'];
				
			} elseif ($rol == 3) {
				// Usuario es comprador, prepara los datos correspondientes
				$userData = $userData = array('username' => $username, 'password' => $pass, 'rol' => $rol, 'id' => $id, 'ref' => '../../HTML/Profiles/Home.php');
					$usernameR = $data['Usuario'];

			} else {
				$userData = $userData = array('username' => NULL, 'password' => NULL, 'rol' => $rol, 'id' => $id, 'ref' => NULL);
				$usernameR = NULL;
			}

		}

		// Inicia la sesión
		session_start();

		// Almacena el nombre de usuario en la sesión
		if($usernameR != NULL){
			$_SESSION['username'] = $usernameR;
			$_SESSION['idUsuario'] = $id;
		}
		// Convierte los datos en formato JSON
		$userDataJSON = json_encode($userData);

		// Establece la cabecera de respuesta como JSON
		header('Content-Type: application/json');

		// Envía el JSON como respuesta a la página web
		echo $userDataJSON;


	}

}

if (isset($_POST['Login_Email']) && isset($_POST['Login_Password'])) {
	$obj = new UserLogIn();
	$obj->validateUser();

} else {
	// Enviar una respuesta de error si no se proporcionaron credenciales
	$userData = array('username' => NULL, 'password' => NULL, 'rol' => NULL);
	$userDataJSON = json_encode($userData);
	header('Content-Type: application/json');
	echo $userDataJSON;


}

?>