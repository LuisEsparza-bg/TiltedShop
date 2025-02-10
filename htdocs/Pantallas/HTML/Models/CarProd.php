<?php
class CarProd
{
	private $idProductosCarrito;
	private $idCarrito;
	private $idProducto;
	private $cantidad;
	private $precio;
	private $descripcion_Cot_Venta;
	private $estatus;


	/**
	 * @return mixed
	 */
	public function getIdProductosCarrito()
	{
		return $this->idProductosCarrito;
	}

	/**
	 * @param mixed $idProductosCarrito 
	 * @return self
	 */
	public function setIdProductosCarrito($idProductosCarrito): self
	{
		$this->idProductosCarrito = $idProductosCarrito;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getIdCarrito()
	{
		return $this->idCarrito;
	}

	/**
	 * @param mixed $idCarrito 
	 * @return self
	 */
	public function setIdCarrito($idCarrito): self
	{
		$this->idCarrito = $idCarrito;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getIdProducto()
	{
		return $this->idProducto;
	}

	/**
	 * @param mixed $idProducto 
	 * @return self
	 */
	public function setIdProducto($idProducto): self
	{
		$this->idProducto = $idProducto;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	 * @param mixed $cantidad 
	 * @return self
	 */
	public function setCantidad($cantidad): self
	{
		$this->cantidad = $cantidad;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPrecio()
	{
		return $this->precio;
	}

	/**
	 * @param mixed $precio 
	 * @return self
	 */
	public function setPrecio($precio): self
	{
		$this->precio = $precio;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDescripcion_Cot_Venta()
	{
		return $this->descripcion_Cot_Venta;
	}

	/**
	 * @param mixed $descripcion_Cot_Venta 
	 * @return self
	 */
	public function setDescripcion_Cot_Venta($descripcion_Cot_Venta): self
	{
		$this->descripcion_Cot_Venta = $descripcion_Cot_Venta;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEstatus()
	{
		return $this->estatus;
	}

	/**
	 * @param mixed $estatus 
	 * @return self
	 */
	public function setEstatus($estatus): self
	{
		$this->estatus = $estatus;
		return $this;
	}

	public function __construct(
		$idProductosCarrito = null,
		$idCarrito = null,
		$idProducto = null,
		$cantidad = null,
		$precio = null,
		$descripcion_Cot_Venta = null,
		$estatus = null
	) {
		$this->idProductosCarrito = $idProductosCarrito;
		$this->idCarrito = $idCarrito;
		$this->idProducto = $idProducto;
		$this->cantidad = $cantidad;
		$this->precio = $precio;
		$this->descripcion_Cot_Venta = $descripcion_Cot_Venta;
		$this->estatus = $estatus;
	}

	public function CrearProducto($pdo, $opcion, $idCarritoS)
	{
		try {
			$sql = "CALL SP_GestionCarrito(:p_opcion, :p_ID_Carrito, :p_ID_Producto, :p_Cantidad, :p_Precio, :p_Descripcion_Cot_Venta, :p_Estatus)";
			$stmt = $pdo->prepare($sql);

			$stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
			$stmt->bindParam(':p_ID_Carrito', $idCarritoS, PDO::PARAM_INT);
			$stmt->bindParam(':p_ID_Producto', $this->idProducto, PDO::PARAM_INT);
			$stmt->bindParam(':p_Cantidad', $this->cantidad, PDO::PARAM_INT);
			$stmt->bindParam(':p_Precio', $this->precio, PDO::PARAM_INT);
			$stmt->bindParam(':p_Descripcion_Cot_Venta', $this->descripcion_Cot_Venta, PDO::PARAM_STR);
			$stmt->bindParam(':p_Estatus', $this->estatus, PDO::PARAM_INT);
			$stmt->execute();
			$pdo = null;

			return true;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}

	public function ModificarProducto($pdo, $opcion, $idCarritoS)
	{
		try {
			$sql = "CALL SP_GestionCarrito(:p_opcion, :p_ID_Carrito, :p_ID_Producto, :p_Cantidad, :p_Precio, :p_Descripcion_Cot_Venta, :p_Estatus)";
			$stmt = $pdo->prepare($sql);

			$precio = 100;
			$estatus = 1; 
			$descr = "";

			$stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
			$stmt->bindParam(':p_ID_Carrito', $idCarritoS, PDO::PARAM_INT);
			$stmt->bindParam(':p_ID_Producto', $this->idProducto, PDO::PARAM_INT);
			$stmt->bindParam(':p_Cantidad', $this->cantidad, PDO::PARAM_INT);
			$stmt->bindParam(':p_Precio', $precio, PDO::PARAM_INT);
			$stmt->bindParam(':p_Descripcion_Cot_Venta', $descr, PDO::PARAM_STR);
			$stmt->bindParam(':p_Estatus', $estatus, PDO::PARAM_INT);
			$stmt->execute();
			$pdo = null;

			return true;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}

	public function EliminarProducto($pdo, $opcion, $idCarritoS)
	{
		try {
			$sql = "CALL SP_GestionCarrito(:p_opcion, :p_ID_Carrito, :p_ID_Producto, :p_Cantidad, :p_Precio, :p_Descripcion_Cot_Venta, :p_Estatus)";
			$stmt = $pdo->prepare($sql);

			$precio = 100;
			$estatus = 1; 
			$descr = "";
			$cantidad = 1;

			$stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
			$stmt->bindParam(':p_ID_Carrito', $idCarritoS, PDO::PARAM_INT);
			$stmt->bindParam(':p_ID_Producto', $this->idProducto, PDO::PARAM_INT);
			$stmt->bindParam(':p_Cantidad', $cantidad, PDO::PARAM_INT);
			$stmt->bindParam(':p_Precio', $precio, PDO::PARAM_INT);
			$stmt->bindParam(':p_Descripcion_Cot_Venta', $descr, PDO::PARAM_STR);
			$stmt->bindParam(':p_Estatus', $estatus, PDO::PARAM_INT);
			$stmt->execute();
			$pdo = null;

			return true;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}

	public function ObtenerProductosActivos($pdo, $idCarritoS)
	{
		try {
			$stmt = $pdo->prepare("CALL SP_ObtenerProductosCarrito(:id_carrito)");
			$stmt->bindParam(':id_carrito', $idCarritoS, PDO::PARAM_INT);
			$stmt->execute(); 
			$productosCarrito = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $productosCarrito; 
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}
}



?>