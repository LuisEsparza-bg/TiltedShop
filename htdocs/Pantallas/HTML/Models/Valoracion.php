<?php

class Valoracion {
    private $id;
    private $idUsuarioCliente;
    private $idProducto;
    private $calificacion;

    // Constructor
    public function __construct($id = null, $idUsuarioCliente = null, $idProducto = null, $calificacion = null) {
        $this->id = $id;
        $this->idUsuarioCliente = $idUsuarioCliente;
        $this->idProducto = $idProducto;
        $this->calificacion = $calificacion;
    }

    // Getters y Setters

    public function getId() {
        return $this->id;
    }

    public function getIdUsuarioCliente() {
        return $this->idUsuarioCliente;
    }

    public function setIdUsuarioCliente($idUsuarioCliente) {
        $this->idUsuarioCliente = $idUsuarioCliente;
    }

    public function getIdProducto() {
        return $this->idProducto;
    }

    public function setIdProducto($idProducto) {
        $this->idProducto = $idProducto;
    }

    public function getCalificacion() {
        return $this->calificacion;
    }

    public function setCalificacion($calificacion) {
        $this->calificacion = $calificacion;
    }

    
    public function valoracionProducto($pdo, $opcion)	
	{

		if ($opcion == 1) {
			try {
                
				$sql = "CALL SP_InsertarValoracion(:usuarioIdParam, :productoIdParam, :calificacionParam)";
				$stmt = $pdo->prepare($sql);

				$stmt->bindParam(':usuarioIdParam', $this->idUsuarioCliente, PDO::PARAM_INT);
				$stmt->bindParam(':productoIdParam', $this->idProducto, PDO::PARAM_INT);
				$stmt->bindParam(':calificacionParam', $this->calificacion, PDO::PARAM_INT);

				//$stmt->execute();

                // Obtener el ID de la última compra
                $result = $stmt->execute();

                
                //echo "Respuesta valoracion: ";
                //var_dump($result);

				$pdo = null;

				return $result;

			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

	}

    public function canReview($pdo)	
    {
        try {
            
            $sqlPrecioUnitario = "CALL SP_VerificarValoracion(:usuarioIdParam, :productoIdParam)";
            $stmt = $pdo->prepare($sqlPrecioUnitario);

            $stmt->bindParam(':usuarioIdParam', $this->idUsuarioCliente, PDO::PARAM_INT);
            $stmt->bindParam(':productoIdParam', $this->idProducto, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $valoracionStatus = $result['valoracionStatus'];

            return $valoracionStatus;

        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

}

?>