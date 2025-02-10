<?php

class Comentario {
    private $id;
    private $idUsuarioCliente;
    private $idProducto;
    private $contenido;

    // Constructor
    public function __construct($id = null, $idUsuarioCliente = null, $idProducto = null, $contenido = null) {
        $this->id = $id;
        $this->idUsuarioCliente = $idUsuarioCliente;
        $this->idProducto = $idProducto;
        $this->contenido = $contenido;
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

    public function getContenido() {
        return $this->contenido;
    }

    public function setContenido($contenido) {
        $this->contenido = $contenido;
    }

    
    public function comentarProducto($pdo, $opcion)	
	{

		if ($opcion == 1) {
			try {
                
				$sql = "CALL SP_InsertarComentario(:usuarioIdParam, :productoIdParam, :comentarioParam)";
				$stmt = $pdo->prepare($sql);

				$stmt->bindParam(':usuarioIdParam', $this->idUsuarioCliente, PDO::PARAM_INT);
				$stmt->bindParam(':productoIdParam', $this->idProducto, PDO::PARAM_INT);
                $stmt->bindParam(':comentarioParam', $this->contenido, PDO::PARAM_STR);

				$result = $stmt->execute();


                $pdo = null;

				return $result;

			} catch (PDOException $e) {
				return "Error: " . $e->getMessage();
			}
		}

	}


}

?>