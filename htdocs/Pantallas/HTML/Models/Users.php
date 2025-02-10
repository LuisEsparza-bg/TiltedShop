<?php

require_once('C:\xampp\htdocs\Pantallas\HTML\Models\CarProd.php');

class Users
{
	private  $id;
	private  $sexID;
	private  $roleID;
	private  $name;
	private  $lastName1;
	private  $lastName2;
	private  $username;
	private  $password;
	private  $email;
	private  $imageData;
	private  $birthdate;
	private  $privacy;
	private  $state;
	private  $colony;
	private  $street;
	private  $houseNumber;

	public function __construct(
		$id = null,
		$sexID = null,
		$roleID = null,
		$name = null,
		$lastName1 = null,
		$lastName2 = null,
		$username = null,
		$password = null,
		$email = null,
		$imageData = null,
		$birthdate = null,
		$privacy = null,
		$state = null,
		$colony = null,
		$street = null,
		$houseNumber = null
	) {
		$this->id = $id;
		$this->sexID = $sexID;
		$this->roleID = $roleID;
		$this->name = $name;
		$this->lastName1 = $lastName1;
		$this->lastName2 = $lastName2;
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->imageData = $imageData;
		$this->birthdate = $birthdate;
		$this->privacy = $privacy;
		$this->state = $state;
		$this->colony = $colony;
		$this->street = $street;
		$this->houseNumber = $houseNumber;
	}



	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return self
	 */
	public function setId($id): self {
		$this->id = $id;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getSexID() {
		return $this->sexID;
	}
	
	/**
	 * @param mixed $sexID 
	 * @return self
	 */
	public function setSexID($sexID): self {
		$this->sexID = $sexID;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getRoleID() {
		return $this->roleID;
	}
	
	/**
	 * @param mixed $roleID 
	 * @return self
	 */
	public function setRoleID($roleID): self {
		$this->roleID = $roleID;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @param mixed $name 
	 * @return self
	 */
	public function setName($name): self {
		$this->name = $name;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getLastName1() {
		return $this->lastName1;
	}
	
	/**
	 * @param mixed $lastName1 
	 * @return self
	 */
	public function setLastName1($lastName1): self {
		$this->lastName1 = $lastName1;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getLastName2() {
		return $this->lastName2;
	}
	
	/**
	 * @param mixed $lastName2 
	 * @return self
	 */
	public function setLastName2($lastName2): self {
		$this->lastName2 = $lastName2;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * @param mixed $username 
	 * @return self
	 */
	public function setUsername($username): self {
		$this->username = $username;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * @param mixed $password 
	 * @return self
	 */
	public function setPassword($password): self {
		$this->password = $password;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * @param mixed $email 
	 * @return self
	 */
	public function setEmail($email): self {
		$this->email = $email;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getImageData() {
		return $this->imageData;
	}
	
	/**
	 * @param mixed $imageData 
	 * @return self
	 */
	public function setImageData($imageData): self {
		$this->imageData = $imageData;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getBirthdate() {
		return $this->birthdate;
	}
	
	/**
	 * @param mixed $birthdate 
	 * @return self
	 */
	public function setBirthdate($birthdate): self {
		$this->birthdate = $birthdate;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getPrivacy() {
		return $this->privacy;
	}
	
	/**
	 * @param mixed $privacy 
	 * @return self
	 */
	public function setPrivacy($privacy): self {
		$this->privacy = $privacy;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getState() {
		return $this->state;
	}
	
	/**
	 * @param mixed $state 
	 * @return self
	 */
	public function setState($state): self {
		$this->state = $state;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getColony() {
		return $this->colony;
	}
	
	/**
	 * @param mixed $colony 
	 * @return self
	 */
	public function setColony($colony): self {
		$this->colony = $colony;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getStreet() {
		return $this->street;
	}
	
	/**
	 * @param mixed $street 
	 * @return self
	 */
	public function setStreet($street): self {
		$this->street = $street;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getHouseNumber() {
		return $this->houseNumber;
	}
	
	/**
	 * @param mixed $houseNumber 
	 * @return self
	 */
	public function setHouseNumber($houseNumber): self {
		$this->houseNumber = $houseNumber;
		return $this;
	}

	public function getCart($pdo, $username) {

		try {
			$sql = "CALL SP_ObtenerCarrito(:p_username)";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':p_username', $username, PDO::PARAM_STR);
			$stmt->execute();

			 $row = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if ($row) {
					$idCarrito = $row['ID_Carrito'];
			} 
			else{
				$idCarrito = false;
			}


			return $idCarrito;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}


	}


	public function getIdByUsername($pdo, $username) {

		try {
			$sql = "CALL SP_GetMyID(:p_username)";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':p_username', $username, PDO::PARAM_STR);
			$stmt->execute();

			 $row = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if ($row) {
					$idUsuarioActual = $row['ID_Usuario'];
			} 
			else{
				$idUsuarioActual = false;
			}


			return $idUsuarioActual;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}


	}



}
