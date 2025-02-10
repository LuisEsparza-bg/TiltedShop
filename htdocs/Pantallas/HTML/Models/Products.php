<?php
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Users.php');
class Products
{

	private $id;
	private $idAdmin;
	private $idVendedor;
	private $nombre;
	private $descripcion;
	private $tipo;
	private $precio;
	private $cantidad;
	private $descripcionC;
	private $estatus;
	private $validacion;
	private $fechaAlta;


	public function __construct(
		$id = null,
		$idAdmin = null,
		$idVendedor = null,
		$nombre = null,
		$descripcion = null,
		$tipo = null,
		$precio = null,
		$cantidad = null,
		$descripcionC = null,
		$estatus = null,
		$validacion = null,
		$fechaAlta = null
	) {
		$this->id = $id;
		$this->idAdmin = $idAdmin;
		$this->idVendedor = $idVendedor;
		$this->nombre = $nombre;
		$this->descripcion = $descripcion;
		$this->tipo = $tipo;
		$this->precio = $precio;
		$this->cantidad = $cantidad;
		$this->descripcionC = $descripcionC;
		$this->estatus = $estatus;
		$this->validacion = $validacion;
		$this->fechaAlta = $fechaAlta;
	}


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
	public function getIdAdmin()
	{
		return $this->idAdmin;
	}

	/**
	 * @param mixed $idAdmin 
	 * @return self
	 */
	public function setIdAdmin($idAdmin): self
	{
		$this->idAdmin = $idAdmin;
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

	/**
	 * @return mixed
	 */
	public function getTipo()
	{
		return $this->tipo;
	}

	/**
	 * @param mixed $tipo 
	 * @return self
	 */
	public function setTipo($tipo): self
	{
		$this->tipo = $tipo;
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
	public function getDescripcionC()
	{
		return $this->descripcionC;
	}

	/**
	 * @param mixed $descripcionC 
	 * @return self
	 */
	public function setDescripcionC($descripcionC): self
	{
		$this->descripcionC = $descripcionC;
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

	/**
	 * @return mixed
	 */
	public function getValidacion()
	{
		return $this->validacion;
	}

	/**
	 * @param mixed $validacion 
	 * @return self
	 */
	public function setValidacion($validacion): self
	{
		$this->validacion = $validacion;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFechaAlta()
	{
		return $this->fechaAlta;
	}

	/**
	 * @param mixed $fechaAlta 
	 * @return self
	 */
	public function setFechaAlta($fechaAlta): self
	{
		$this->fechaAlta = $fechaAlta;
		return $this;
	}





	public function CrearProducto($pdo, $opcion, $username)
	{

		try {
			$sql = "CALL GestionarProductos(:p_opcion, :p_producto_id, :p_nombre_producto, :p_descripcion_producto, :p_tipo_producto, :p_precio, :p_cantidad, 
			:p_descripcionC, :p_estatus, :p_validacion, :p_username)";
			$stmt = $pdo->prepare($sql);

			$stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
			$stmt->bindParam(':p_producto_id', $this->id, PDO::PARAM_INT);
			$stmt->bindParam(':p_nombre_producto', $this->nombre, PDO::PARAM_STR);
			$stmt->bindParam(':p_descripcion_producto', $this->descripcion, PDO::PARAM_STR);
			$stmt->bindParam(':p_tipo_producto', $this->tipo, PDO::PARAM_INT);
			$stmt->bindParam(':p_precio', $this->precio, PDO::PARAM_STR);
			$stmt->bindParam(':p_cantidad', $this->cantidad, PDO::PARAM_INT);
			$stmt->bindParam(':p_descripcionC', $this->descripcionC, PDO::PARAM_STR);
			$stmt->bindParam(':p_estatus', $this->estatus, PDO::PARAM_INT);
			$stmt->bindParam(':p_username', $username, PDO::PARAM_STR);
			$stmt->bindParam(':p_validacion', $this->validacion, PDO::PARAM_INT);

			$stmt->execute();

			$pdo = null;

			return true;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}


	public function VerMisProductos($pdo, $username, $opcion)
	{

		if ($opcion == 1) {
			try {
				$idproducto = 1;
				$sql = "CALL SP_MisProductos(:p_username, :p_opcion, :p_idProducto)";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':p_username', $username, PDO::PARAM_STR);
				$stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
				$stmt->bindParam(':p_idProducto', $idproducto, PDO::PARAM_INT);
				$stmt->execute();

				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$productos[] = $row;
				}

				if (isset($productos)) {
					return $productos;
				} else {
					return false;
				}
			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		} else if ($opcion == 2) {
			try {
				$sql = "CALL SP_MisProductos(:p_username, :p_opcion, :p_idProducto)";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':p_username', $username, PDO::PARAM_STR);
				$stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
				$stmt->bindParam(':p_idProducto', $this->id, PDO::PARAM_INT);
				$stmt->execute();

				$productos = $row = $stmt->fetch(PDO::FETCH_ASSOC);

				if ($productos) {
					$productosAEnviar = new Products(
						id: $productos['ID_Producto'],
						idAdmin: $productos['ID_Usuario_Admin'],
						idVendedor: $productos['ID_Usuario_Vendedor'],
						nombre: $productos['Nombre_Producto'],
						descripcion: $productos['Descripcion_Producto'],
						tipo: $productos['Tipo_Producto'],
						precio: $productos['Precio_Unitario'],
						cantidad: $productos['Cantidad'],
						descripcionC: $productos['Descripcion_Cotizado'],
						estatus: $productos['Estatus'],
						validacion: $productos['Validacion'],
						fechaAlta: $productos['Fecha_Alta'],
					);

				} else {
					$productosAEnviar = false;
				}

				return $productosAEnviar;
			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}
	}




	public function VerProductosHome($pdo, $opcion)
	{

		// OPCION 1 ES PRODUCTOS MÁS RECIENTES
		if ($opcion == 1) {
			try {
				$idproducto = 1;
				$sql = "CALL SP_Productos_Home(:p_opcion)";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
				$stmt->execute();

				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$productos[] = $row;
				}

				if (isset($productos)) {
					return $productos;
				} else {
					return false;
				}
			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
			// OPCION 2 ES PRODUCTOS MÁS POPULARES
		} else if ($opcion == 2) {
			try {
				$sql = "CALL ObtenerProductosTop3()";
				$stmt = $pdo->prepare($sql);
				$stmt->execute();


				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$productos[] = $row;
				}

				if (isset($productos)) {
					return $productos;
				} else {
					return false;
				}

			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

		else if ($opcion == 3){
			try {
				$sql = "CALL ObtenerProductosRecientesC()";
				$stmt = $pdo->prepare($sql);
				$stmt->execute();


				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$productos[] = $row;
				}

				if (isset($productos)) {
					return $productos;
				} else {
					return false;
				}

			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

		else if ($opcion == 4){
			try {
				$sql = "CALL SP_ProductosMasVendidos()";
				$stmt = $pdo->prepare($sql);
				$stmt->execute();


				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$productos[] = $row;
				}

				if (isset($productos)) {
					return $productos;
				} else {
					return false;
				}

			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

	}


	public function VerProducto($pdo, $opcion)
	{
		if ($opcion == 1) {
			try {
				$sql = "CALL SP_ProductoDescriptivo(:p_idProducto, :p_opcion)";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':p_idProducto', $this->id, PDO::PARAM_INT);
				$stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
				$stmt->execute();

				$productos = $row = $stmt->fetch(PDO::FETCH_ASSOC);

				if ($productos) {
					$productosAEnviar = new Products(
						id: $productos['ID_Producto'],
						idAdmin: $productos['ID_Usuario_Admin'],
						idVendedor: $productos['ID_Usuario_Vendedor'],
						nombre: $productos['Nombre_Producto'],
						descripcion: $productos['Descripcion_Producto'],
						tipo: $productos['Tipo_Producto'],
						precio: $productos['Precio_Unitario'],
						cantidad: $productos['Cantidad'],
						descripcionC: $productos['Descripcion_Cotizado'],
						estatus: $productos['Estatus'],
						validacion: $productos['Validacion'],
						fechaAlta: $productos['Fecha_Alta'],
					);

				} else {
					$productosAEnviar = false;
				}

				return $productosAEnviar;
			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		} else {
			try {
				$sql = "CALL SP_ProductoDescriptivo(:p_idProducto, :p_opcion)";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':p_idProducto', $this->id, PDO::PARAM_INT);
				$stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
				$stmt->execute();

				$username = $row = $stmt->fetch(PDO::FETCH_ASSOC);

				if ($username) {
					$usernames = new Users(
						username: $username['Nombre_Vendedor']
					);
				} else {
					$usernames = false;
				}

				return $usernames;
			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}
	}

	public function GetLastProductID($pdo)
	{
		try {
			$sql = "CALL SP_GetUltimoProductoID()";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();

			$idProducto = $stmt->fetch(PDO::FETCH_ASSOC);
			$IDProduct = $idProducto['ID_Producto'];


			return $IDProduct;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}


	public function VerProductosVendedor($pdo, $idUsuario, $opcion)
	{
		if ($opcion == 1) {
			try {
				$sql = "CALL SP_ProductosVendedor(:p_userID, :p_opcion)";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':p_userID', $idUsuario, PDO::PARAM_INT);
				$stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
				$stmt->execute();

				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$productos[] = $row;
				}
				if (isset($productos)) {
					return $productos;
				} else {
					return false;
				}


			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		} else {
			try {
				$sql = "CALL SP_ProductosVendedor(:p_userID, :p_opcion)";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':p_userID', $idUsuario, PDO::PARAM_INT);
				$stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
				$stmt->execute();

				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$productos[] = $row;
				}
				if (isset($productos)) {
					return $productos;
				} else {
					return false;
				}
			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}
	}

	public static function VerProductosPorValidar($pdo, $opcion)
	{

		if ($opcion == 1) {
			try {
				$sql = "CALL SP_ObtenerProductosAValidar()";
				$stmt = $pdo->prepare($sql);

				$stmt->execute();

				$productosV = array();

				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$productosV[] = $row;
				}

				return $productosV;
			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

	}

	public function ValidacionDeProducto($pdo, $opcion)
	{

		if ($opcion == 1) {
			try {
				$sql = "CALL SP_ValidarProducto(:p_idProductoParam, :p_validarParam, :p_idAdminParam)";
				$stmt = $pdo->prepare($sql);

				$stmt->bindParam(':p_idProductoParam', $this->id, PDO::PARAM_INT);
				$stmt->bindParam(':p_validarParam', $this->validacion, PDO::PARAM_BOOL);
				$stmt->bindParam(':p_idAdminParam', $this->idAdmin, PDO::PARAM_INT);

				$stmt->execute();

				# echo var_dump($rol);

				$pdo = null;

				return true;

			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

	}

	public function VerProductosValidados($pdo, $opcion)
	{

		if ($opcion == 1) {
			try {
				$sql = "CALL SP_ObtenerProductosValidados(:p_idAdminParam)";
				$stmt = $pdo->prepare($sql);

				$stmt->bindParam(':p_idAdminParam', $this->idAdmin, PDO::PARAM_INT);

				$stmt->execute();

				$productosV = array();

				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$productosV[] = $row;
				}

				return $productosV;
			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

	}

	public function BusquedaSimpleProductos($pdo, $opcion)
	{

		if ($opcion == 1) {
			try {
				$sql = "CALL SP_BusquedaSimpleProductos(:p_textoParam)";
				$stmt = $pdo->prepare($sql);

				$stmt->bindParam(':p_textoParam', $this->nombre, PDO::PARAM_STR);

				$stmt->execute();

				$productosV = array();

				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$productosV[] = $row;
				}

				return $productosV;
			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

	}

	public function DetallesProducto($pdo, $opcion)
	{

		if ($opcion == 1) {
			try {
				$sql = "CALL SP_DetallesProducto(:p_idProductoParam)";
				$stmt = $pdo->prepare($sql);

				$stmt->bindParam(':p_idProductoParam', $this->id, PDO::PARAM_INT);

				$stmt->execute();

				$productosV = array();

				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$productosV[] = $row;
				}

				return $productosV;
			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

	}


	public function VerificacionStockProducto($pdo, $opcion)
	{

		if ($opcion == 1) {
			try {
				$sql = "CALL SP_VerificarStock(:p_idProductParam, :p_cantidadParam)";
				$stmt = $pdo->prepare($sql);

				$stmt->bindParam(':p_idProductParam', $this->id, PDO::PARAM_INT);
				$stmt->bindParam(':p_cantidadParam', $this->cantidad, PDO::PARAM_INT);

				$stmt->execute();

				// Obtener el resultado del SP
				$result = $stmt->fetch(PDO::FETCH_COLUMN);

				return $result; // Devolver el resultado tal cual
			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

	}

	public function BusquedaAvanzadaProductos($pdo, $nombre, $precioFlag, $precioFilter, $valorFlag, $valorFilter, $ventasFlag, $ventasFilter)
	{


		try {
			$sql = "CALL SP_BusquedaAvanzadaProductos(:searchQuery, :usePriceFilter, :orderByPrice, :useRatingFilter, :orderByRating, :useSalesFilter, :orderBySales)";
			$stmt = $pdo->prepare($sql);

			$stmt->bindParam(':searchQuery', $nombre, PDO::PARAM_STR);
			$stmt->bindParam(':usePriceFilter', $precioFlag, PDO::PARAM_INT);
			$stmt->bindParam(':orderByPrice', $precioFilter, PDO::PARAM_INT);
			$stmt->bindParam(':useRatingFilter', $valorFlag, PDO::PARAM_INT);
			$stmt->bindParam(':orderByRating', $valorFilter, PDO::PARAM_INT);
			$stmt->bindParam(':useSalesFilter', $ventasFlag, PDO::PARAM_INT);
			$stmt->bindParam(':orderBySales', $ventasFilter, PDO::PARAM_INT);


			$stmt->execute();

			$productosV = array();

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$productosV[] = $row;
			}

			return $productosV;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}


	}

	public function ObtenerTop3ProductosVendidos($pdo)
	{
		try {
			$sql = "CALL ObtenerTop3ProductosVendidos()";
			$stmt = $pdo->prepare($sql);

			$stmt->execute();

			$productos = array();

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$productos[] = $row;
			}

			return $productos;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}


	public function ObtenerTop3ProductosMejorValorados($pdo)
	{
		try {
			$sql = "CALL ObtenerTop3ProductosMejorValorados()";
			$stmt = $pdo->prepare($sql);

			$stmt->execute();

			$productos = array();

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$productos[] = $row;
			}

			return $productos;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}



}