<?php
class DetalleCompra
{
    private $idDetallesCompra;
    private $idCompra;
    private $idProducto;
    private $cantidad;
    private $precioUnitario;
    private $descripcionCotVenta;

    public function __construct(
        $idDetallesCompra = null,
        $idCompra = null,
        $idProducto = null,
        $cantidad = null,
        $precioUnitario = null,
        $descripcionCotVenta = null
    ) {
        $this->idDetallesCompra = $idDetallesCompra;
        $this->idCompra = $idCompra;
        $this->idProducto = $idProducto;
        $this->cantidad = $cantidad;
        $this->precioUnitario = $precioUnitario;
        $this->descripcionCotVenta = $descripcionCotVenta;
    }

    public function getIdDetallesCompra()
    {
        return $this->idDetallesCompra;
    }

    public function setIdDetallesCompra($idDetallesCompra)
    {
        $this->idDetallesCompra = $idDetallesCompra;
        return $this;
    }

    public function getIdCompra()
    {
        return $this->idCompra;
    }

    public function setIdCompra($idCompra)
    {
        $this->idCompra = $idCompra;
        return $this;
    }

    public function getIdProducto()
    {
        return $this->idProducto;
    }

    public function setIdProducto($idProducto)
    {
        $this->idProducto = $idProducto;
        return $this;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    public function getPrecioUnitario()
    {
        return $this->precioUnitario;
    }

    public function setPrecioUnitario($precioUnitario)
    {
        $this->precioUnitario = $precioUnitario;
        return $this;
    }

    public function getDescripcionCotVenta()
    {
        return $this->descripcionCotVenta;
    }

    public function setDescripcionCotVenta($descripcionCotVenta)
    {
        $this->descripcionCotVenta = $descripcionCotVenta;
        return $this;
    }

    public function cargarDetalleCompra($pdo, $opcion, $tipoProducto)	
	{

		if ($opcion == 1) {
			try {
				$sql = "CALL SP_GenerarDetalleCompra(:tipoProductoParam, :compraIdParam, :productIdParam,:cantidadParam, :precioUnitParam, :descripcionCotParam )";
				$stmt = $pdo->prepare($sql);

                $stmt->bindParam(':tipoProductoParam', $tipoProducto, PDO::PARAM_INT); // Ajustado el parámetro y el valor
                $stmt->bindParam(':compraIdParam', $this->idCompra, PDO::PARAM_INT); // Ajustado el parámetro y el valor
                $stmt->bindParam(':productIdParam', $this->idProducto, PDO::PARAM_INT);
                $stmt->bindParam(':cantidadParam', $this->cantidad, PDO::PARAM_INT); // Ajustado el parámetro y el valor
                $stmt->bindParam(':precioUnitParam', $this->precioUnitario, PDO::PARAM_STR);
                $stmt->bindParam(':descripcionCotParam', $this->descripcionCotVenta, PDO::PARAM_STR); // Ajustado el parámetro y el valor

				$stmt->execute();

				$pdo = null;

				return true;

			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

	}

}


?>