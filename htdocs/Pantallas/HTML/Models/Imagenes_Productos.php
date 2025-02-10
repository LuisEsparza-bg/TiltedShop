<?php
class ImagenProducto
{
    private $idImagenProducto;
    private $imagen;
    private $idProducto;

    // Getter for idImagenProducto
    public function getIdImagenProducto()
    {
        return $this->idImagenProducto;
    }

    // Setter for idImagenProducto
    public function setIdImagenProducto($idImagenProducto)
    {
        $this->idImagenProducto = $idImagenProducto;
    }

    // Getter for imagen
    public function getImagen()
    {
        return $this->imagen;
    }

    // Setter for imagen
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    // Getter for idProducto
    public function getIdProducto()
    {
        return $this->idProducto;
    }

    // Setter for idProducto
    public function setIdProducto($idProducto)
    {
        $this->idProducto = $idProducto;
    }

    public function __construct($idImagenProducto=null, $imagen=null, $idProducto=null)
    {
        $this->idImagenProducto = $idImagenProducto;
        $this->imagen = $imagen;
        $this->idProducto = $idProducto;
    }

    public function CrearProducto($pdo, $IDproducto)
	{
		try {
			$sql = "CALL SP_Img(:p_IDproducto, :p_Imagen)";
			$stmt = $pdo->prepare($sql);

            $stmt->bindParam(':p_IDproducto', $IDproducto, PDO::PARAM_INT);
			$stmt->bindParam(':p_Imagen', $this->imagen, PDO::PARAM_STR);
			$stmt->execute();
			$pdo = null;

			return true;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}

    public function CambiarImagen($pdo)
	{
		try {
			$sql = "CALL SP_CambiarImagen(:p_ID_Imagenes_Producto, :p_Imagen)";
			$stmt = $pdo->prepare($sql);

            $stmt->bindParam(':p_ID_Imagenes_Producto', $this->idImagenProducto, PDO::PARAM_INT);
			$stmt->bindParam(':p_Imagen', $this->imagen, PDO::PARAM_STR);
			$stmt->execute();
			$pdo = null;

			return true;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}

    public function VerTresImagenesProductos($pdo)
	{
		try {
			$sql = "CALL SP_GetImagenesProducto(:p_ID_Producto)";
			$stmt = $pdo->prepare($sql);

            $stmt->bindParam(':p_ID_Producto', $this->idProducto, PDO::PARAM_INT);
			$stmt->execute();
            $imagenes = array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$imagenes[] = $row;
			}

			$pdo = null;

			return $imagenes;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}

}

?>