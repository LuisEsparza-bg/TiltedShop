<?php

class DetallesPago
{
    private $idDetallePago;
    private $idCompra;
    private $idMetodoPago;
    private $transaccionPaypal;
    private $cvv;
    private $nombreTarjeta;
    private $numeroTarjeta;
    private $fechaVencimiento;

    public function __construct(
        $idDetallePago = null,
        $idCompra = null,
        $idMetodoPago = null,
        $transaccionPaypal = null,
        $cvv = null,
        $nombreTarjeta = null,
        $numeroTarjeta = null,
        $fechaVencimiento = null
    ) {
        $this->idDetallePago = $idDetallePago;
        $this->idCompra = $idCompra;
        $this->idMetodoPago = $idMetodoPago;
        $this->transaccionPaypal = $transaccionPaypal;
        $this->cvv = $cvv;
        $this->nombreTarjeta = $nombreTarjeta;
        $this->numeroTarjeta = $numeroTarjeta;
        $this->fechaVencimiento = $fechaVencimiento;
    }

    public function getIdDetallePago()
    {
        return $this->idDetallePago;
    }

    public function setIdDetallePago($idDetallePago)
    {
        $this->idDetallePago = $idDetallePago;
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

    public function getIdMetodoPago()
    {
        return $this->idMetodoPago;
    }

    public function setIdMetodoPago($idMetodoPago)
    {
        $this->idMetodoPago = $idMetodoPago;
        return $this;
    }

    public function getTransaccionPaypal()
    {
        return $this->transaccionPaypal;
    }

    public function setTransaccionPaypal($transaccionPaypal)
    {
        $this->transaccionPaypal = $transaccionPaypal;
        return $this;
    }

    public function getCvv()
    {
        return $this->cvv;
    }

    public function setCvv($cvv)
    {
        $this->cvv = $cvv;
        return $this;
    }

    public function getNombreTarjeta()
    {
        return $this->nombreTarjeta;
    }

    public function setNombreTarjeta($nombreTarjeta)
    {
        $this->nombreTarjeta = $nombreTarjeta;
        return $this;
    }

    public function getNumeroTarjeta()
    {
        return $this->numeroTarjeta;
    }

    public function setNumeroTarjeta($numeroTarjeta)
    {
        $this->numeroTarjeta = $numeroTarjeta;
        return $this;
    }

    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;
        return $this;
    }

    public function cargarDetallePago($pdo, $opcion, $tipoPago)	
	{

		if ($opcion == 1) {
			try {
				$sql = "CALL SP_GenerarDetallesPago(:tipoPagoParam, :compraIdParam, :metodoPagoIdParam, :transaccionPaypalParam,
                 :cvvParam, :nombreTarjetaParam, :numeroTarjetaParam, :fechaVencimientoParam)";
				$stmt = $pdo->prepare($sql);

                $stmt->bindParam(':tipoPagoParam', $tipoPago, PDO::PARAM_INT);
                $stmt->bindParam(':compraIdParam', $this->idCompra, PDO::PARAM_INT);
                $stmt->bindParam(':metodoPagoIdParam', $this->idMetodoPago, PDO::PARAM_INT);
                $stmt->bindParam(':transaccionPaypalParam', $this->transaccionPaypal, PDO::PARAM_STR);
                $stmt->bindParam(':cvvParam', $this->cvv, PDO::PARAM_STR);
                $stmt->bindParam(':nombreTarjetaParam', $this->nombreTarjeta, PDO::PARAM_STR);
                $stmt->bindParam(':numeroTarjetaParam', $this->numeroTarjeta, PDO::PARAM_STR);
                $stmt->bindParam(':fechaVencimientoParam', $this->fechaVencimiento, PDO::PARAM_STR);

				$stmt->execute();

				$pdo = null;

				return true;

			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

	}

    public function ComprarTodo($pdo, $username)	
	{

			try {
				$sql = "CALL SP_CompletarCompra(:p_username, :p_metodoPago, :p_cvv, :p_nombreTarjeta,
                 :p_numeroTarjeta, :p_FechaVencimiento)";
				$stmt = $pdo->prepare($sql);

                $stmt->bindParam(':p_username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':p_metodoPago', $this->idMetodoPago, PDO::PARAM_INT);
                $stmt->bindParam(':p_cvv', $this->cvv, PDO::PARAM_STR);
                $stmt->bindParam(':p_nombreTarjeta', $this->nombreTarjeta, PDO::PARAM_STR);
                $stmt->bindParam(':p_numeroTarjeta', $this->numeroTarjeta, PDO::PARAM_STR);
                $stmt->bindParam(':p_FechaVencimiento', $this->fechaVencimiento, PDO::PARAM_STR);

				$stmt->execute();

				$pdo = null;

				return true;

			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}

	}


    

}

?>