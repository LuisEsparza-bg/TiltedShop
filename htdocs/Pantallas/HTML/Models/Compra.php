<?php
class Compra
{
    private $idCompra;
    private $idUsuarioCliente;
    private $fechaHora;
    private $total;
    private $idMetodoPago;

    public function __construct(
        $idCompra = null,
        $idUsuarioCliente = null,
        $fechaHora = null,
        $total = null,
        $idMetodoPago = null
    ) {
        $this->idCompra = $idCompra;
        $this->idUsuarioCliente = $idUsuarioCliente;
        $this->fechaHora = $fechaHora;
        $this->total = $total;
        $this->idMetodoPago = $idMetodoPago;
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

    public function getIdUsuarioCliente()
    {
        return $this->idUsuarioCliente;
    }

    public function setIdUsuarioCliente($idUsuarioCliente)
    {
        $this->idUsuarioCliente = $idUsuarioCliente;
        return $this;
    }

    public function getFechaHora()
    {
        return $this->fechaHora;
    }

    public function setFechaHora($fechaHora)
    {
        $this->fechaHora = $fechaHora;
        return $this;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    public function getIdMetodoPago()
    {
        return $this->idMetodoPago;
    }

    public function setIdMetodoPago($idMetodoPago)
    {
        $this->idMetodoPago = $idMetodoPago;
        return $this;
    }


    public function realizarCompra($pdo, $opcion)	
	{

		if ($opcion == 1) {
			try {
                
				$sql = "CALL SP_GenerarCompra(:userIdParam, :totalParam, :metodoParam)";
				$stmt = $pdo->prepare($sql);

				$stmt->bindParam(':userIdParam', $this->idUsuarioCliente, PDO::PARAM_INT);
				$stmt->bindParam(':totalParam', $this->total, PDO::PARAM_STR);
				$stmt->bindParam(':metodoParam', $this->idMetodoPago, PDO::PARAM_INT);

				$stmt->execute();

                // Obtener el ID de la última compra
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $lastInsertId = $result['LastInsertId'];

                // Puedes almacenar o utilizar $lastInsertId según tus necesidades
                $this->idCompra = $lastInsertId;

                 // Puedes imprimir o loggear el ID de la última compra aquí
                //echo "ID de la última compra: " . $lastInsertId;

				$pdo = null;

				return $lastInsertId;

			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

	}

    
    public function obtenerPrecioUnitario($pdo, $productoId)	
    {
        try {
            
            $sqlPrecioUnitario = "CALL SP_ObtenerPrecioUnitario(:productoIdParam)";
            $stmt = $pdo->prepare($sqlPrecioUnitario);

            $stmt->bindParam(':productoIdParam', $productoId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $precioUnitario = $result['Precio_Unitario'];

            return $precioUnitario;

        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function obtenerDatosCompra($pdo, $compraId, $productId)	
    {
        try {
            
            $sql = "CALL SP_ObtenerProductosPorCompra(:compraIdParam, :productIdParam)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':compraIdParam', $compraId, PDO::PARAM_INT);
            $stmt->bindParam(':productIdParam', $productId, PDO::PARAM_INT);
            
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

    
    public function obtenerTipoProducto($pdo, $productoId)	
    {
        try {
            
            $sql = "CALL SP_ObtenerTipoProducto(:productoIdParam)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':productoIdParam', $productoId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $tipoProd = $result['Tipo_Producto'];

            return $tipoProd;

        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }


    public function MisCompras($pdo, $idUsuario)	
    {
        try {
            
            $sql = "CALL SP_ObtenerDetallesCompra(:p_idUsuario)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':p_idUsuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();

            
            $productosCompras = array(); 

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $productosCompras[] = $row;
            }
            
            return $productosCompras;

        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    

}


?>