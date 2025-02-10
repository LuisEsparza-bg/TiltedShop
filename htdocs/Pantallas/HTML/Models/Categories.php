<?php

class Categories
{
	private $id;
	private $username;
	private $idVendedor;
	private $nombre;
	private $descripcion;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id 
	 * @return self
	 */
	public function setId($id): self
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getIdVendedor()
	{
		return $this->idVendedor;
	}

	/**
	 * @param mixed $idVendedor 
	 * @return self
	 */
	public function setIdVendedor($idVendedor): self
	{
		$this->idVendedor = $idVendedor;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getNombre()
	{
		return $this->nombre;
	}

	/**
	 * @param mixed $nombre 
	 * @return self
	 */
	public function setNombre($nombre): self
	{
		$this->nombre = $nombre;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	 * @param mixed $descripcion 
	 * @return self
	 */
	public function setDescripcion($descripcion): self
	{
		$this->descripcion = $descripcion;
		return $this;
	}


	public function __construct($idCategoria = null, $username = null, $nombre = null, $descripcion = null)
{
    if ($idCategoria !== null) {
        $this->idCategoria = $idCategoria;
    } else {
        $this->username = $username;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }
}


	public function CrearCategoria($pdo)
	{
		try {
			$sql = "CALL SP_CrearCategoria(:p_username, :p_nombre, :p_descripcionCategoria)";
			$stmt = $pdo->prepare($sql);

			$stmt->bindParam(':p_username', $this->username, PDO::PARAM_STR);
			$stmt->bindParam(':p_nombre', $this->nombre, PDO::PARAM_STR);
			$stmt->bindParam(':p_descripcionCategoria', $this->descripcion, PDO::PARAM_STR);

			$stmt->execute();

			$pdo = null;

			return true;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}


	public function VerCategorias($pdo)
	{
		try {
			$sql = "CALL SP_VerCategorias()";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
				$categorias = array();

				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$categorias[] = $row;
				}


			return $categorias;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}

	

}
