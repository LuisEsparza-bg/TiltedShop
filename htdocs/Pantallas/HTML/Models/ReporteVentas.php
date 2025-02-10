<?php

class Reporte
{
    private $idVendedorParam;
    private $usarFiltroFecha;
    private $fechaInicioParam;
    private $fechaFinParam;
    private $usarFiltroCategoria;
    private $idCategoriaParam;

    public function __construct(
        $idVendedorParam = null,
        $usarFiltroFecha = null,
        $fechaInicioParam = null,
        $fechaFinParam = null,
        $usarFiltroCategoria = null,
        $idCategoriaParam = null
    ) {
        $this->idVendedorParam = $idVendedorParam;
        $this->usarFiltroFecha = $usarFiltroFecha;
        $this->fechaInicioParam = $fechaInicioParam;
        $this->fechaFinParam = $fechaFinParam;
        $this->usarFiltroCategoria = $usarFiltroCategoria;
        $this->idCategoriaParam = $idCategoriaParam;
    }

    public function getIdVendedorParam()
    {
        return $this->idVendedorParam;
    }

    public function setIdVendedorParam($idVendedorParam)
    {
        $this->idVendedorParam = $idVendedorParam;
        return $this;
    }

    public function getUsarFiltroFecha()
    {
        return $this->usarFiltroFecha;
    }

    public function setUsarFiltroFecha($usarFiltroFecha)
    {
        $this->usarFiltroFecha = $usarFiltroFecha;
        return $this;
    }

    public function getFechaInicioParam()
    {
        return $this->fechaInicioParam;
    }

    public function setFechaInicioParam($fechaInicioParam)
    {
        $this->fechaInicioParam = $fechaInicioParam;
        return $this;
    }

    public function getFechaFinParam()
    {
        return $this->fechaFinParam;
    }

    public function setFechaFinParam($fechaFinParam)
    {
        $this->fechaFinParam = $fechaFinParam;
        return $this;
    }

    public function getUsarFiltroCategoria()
    {
        return $this->usarFiltroCategoria;
    }

    public function setUsarFiltroCategoria($usarFiltroCategoria)
    {
        $this->usarFiltroCategoria = $usarFiltroCategoria;
        return $this;
    }

    public function getIdCategoriaParam()
    {
        return $this->idCategoriaParam;
    }

    public function setIdCategoriaParam($idCategoriaParam)
    {
        $this->idCategoriaParam = $idCategoriaParam;
        return $this;
    }


    public function reporteVentasDetallada($pdo, $idVendedor, $fechaFlag, $fechaInicio, $fechaFin, $categoriaFlag, $idCategoria)	
	{

		
		try {
			$sql = "CALL SP_ConsultarVentasDetalladas(:idVendedorParam, :usarFiltroFecha, :fechaInicioParam, :fechaFinParam, :usarFiltroCategoria, :idCategoriaParam)";
			$stmt = $pdo->prepare($sql);

			$stmt->bindParam(':idVendedorParam', $idVendedor, PDO::PARAM_INT);
            $stmt->bindParam(':usarFiltroFecha', $fechaFlag, PDO::PARAM_BOOL);
            $stmt->bindParam(':fechaInicioParam', $fechaInicio, PDO::PARAM_STR);
            $stmt->bindParam(':fechaFinParam', $fechaFin, PDO::PARAM_STR);
            $stmt->bindParam(':usarFiltroCategoria', $categoriaFlag, PDO::PARAM_BOOL);
            $stmt->bindParam(':idCategoriaParam', $idCategoria, PDO::PARAM_INT);
			
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


    
    public function reporteVentasAgrupada($pdo, $idVendedor, $fechaFlag, $fechaInicio, $fechaFin, $categoriaFlag, $idCategoria)	
	{

		
		try {
			$sql = "CALL SP_ConsultarVentasAgrupadas(:idVendedorParam, :usarFiltroFecha, :fechaInicioParam, :fechaFinParam, :usarFiltroCategoria, :idCategoriaParam)";
			$stmt = $pdo->prepare($sql);

			$stmt->bindParam(':idVendedorParam', $idVendedor, PDO::PARAM_INT);
            $stmt->bindParam(':usarFiltroFecha', $fechaFlag, PDO::PARAM_BOOL);
            $stmt->bindParam(':fechaInicioParam', $fechaInicio, PDO::PARAM_STR);
            $stmt->bindParam(':fechaFinParam', $fechaFin, PDO::PARAM_STR);
            $stmt->bindParam(':usarFiltroCategoria', $categoriaFlag, PDO::PARAM_BOOL);
            $stmt->bindParam(':idCategoriaParam', $idCategoria, PDO::PARAM_INT);
			
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

?>