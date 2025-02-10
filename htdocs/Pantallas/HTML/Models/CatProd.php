<?php

require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Categories.php');


class CatProd
{
	private $id;
	private $producto;
	private $idCategoria;
	private $estatus;

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
	public function getProducto()
	{
		return $this->producto;
	}

	/**
	 * @param mixed $producto 
	 * @return self
	 */
	public function setProducto($producto): self
	{
		$this->producto = $producto;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getIdCategoria()
	{
		return $this->idCategoria;
	}

	/**
	 * @param mixed $idCategoria 
	 * @return self
	 */
	public function setIdCategoria($idCategoria): self
	{
		$this->idCategoria = $idCategoria;
		return $this;
	}



	public function __construct($id = null, $producto = null, $idCategoria = null, $estatus = null)
	{
		$this->id = $id;
		$this->producto = $producto;
		$this->idCategoria = $idCategoria;
		$this->estatus = $estatus;
	}

	public function CategoriasProductos($pdo, $opcion = null)
	{
		try {

			if ($opcion == 1) {
				foreach ($this->idCategoria as $idCategoria) {
					$sql = "CALL SP_CategoriasProducto(:p_IdCategoria, :p_IdProducto, :p_EstatusCategoria)";
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':p_IdProducto', $this->producto, PDO::PARAM_INT);
					$stmt->bindParam(':p_IdCategoria', $idCategoria, PDO::PARAM_INT);
					$stmt->bindParam(':p_EstatusCategoria', $this->estatus, PDO::PARAM_INT);
					$stmt->execute();
				}

				$pdo = null;

				return true;
			}
			else if ( $opcion == 2) {
				foreach ($this->idCategoria as $idCategoria) {
					$sql = "CALL Sp_GestionCategoriaProducto(:p_IdCategoria, :p_IdProducto, :p_EstatusCategoria, :p_opcion)";
					$stmt = $pdo->prepare($sql);
					$opcionNueva = 1;
					$stmt->bindParam(':p_IdProducto', $this->producto, PDO::PARAM_INT);
					$stmt->bindParam(':p_IdCategoria', $idCategoria, PDO::PARAM_INT);
					$stmt->bindParam(':p_EstatusCategoria', $this->estatus, PDO::PARAM_INT);
					$stmt->bindParam(':p_opcion', $opcionNueva, PDO::PARAM_INT);
					$stmt->execute();
				}

				$pdo = null;

				return true;
			}
			else if ( $opcion == 3) {
				foreach ($this->idCategoria as $idCategoria) {
					$sql = "CALL Sp_GestionCategoriaProducto(:p_IdCategoria, :p_IdProducto, :p_EstatusCategoria, :p_opcion)";
					$stmt = $pdo->prepare($sql);
					$opcionNueva = 2;
					$stmt->bindParam(':p_IdProducto', $this->producto, PDO::PARAM_INT);
					$stmt->bindParam(':p_IdCategoria', $idCategoria, PDO::PARAM_INT);
					$stmt->bindParam(':p_EstatusCategoria', $this->estatus, PDO::PARAM_INT);
					$stmt->bindParam(':p_opcion', $opcionNueva, PDO::PARAM_INT);
					$stmt->execute();
				}

				$pdo = null;

				return true;

			}



		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}


	public function VerCategoriasProducto($pdo)
	{
		try {
			$sql = "CALL SP_ObtenerCategoriasDeProducto(:p_idProducto)";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':p_idProducto', $this->producto, PDO::PARAM_INT);
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