<?php 

	class DB{
		private $host;
		private $db;
		private $user;
		private $password;
		private $charset;

		public function __construct()
		{
			$this->host = 'localhost';
			$this->db = 'dbTiltedShop';
			$this->user = 'root';
			$this->password = 'password';
			$this->charset = 'utf8mb4';
		}

		function connect(){

			try{
				$conn = "mysql:host=".$this->host.";dbname=".$this->db.";charset=".$this->charset;
				$options = [
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_EMULATE_PREPARES => false
				];
				$pdo = new PDO($conn, $this->user, $this->password);

				#print_r('Me Contecte');

				return $pdo;

			}catch(PDOException $e){
				print_r('Error de conexion: ' . $e->getMessage());
			}
		}
	}

?>