<?php
class VideosProducto
{
    private $idVideosProducto;
    private $video;
    private $idProducto;

    public function __construct($idVideosProducto = null, $video = null, $idProducto = null)
    {
        $this->idVideosProducto = $idVideosProducto;
        $this->video = $video;
        $this->idProducto = $idProducto;
    }

    public function getIdVideosProducto()
    {
        return $this->idVideosProducto;
    }

    public function setIdVideosProducto($idVideosProducto)
    {
        $this->idVideosProducto = $idVideosProducto;
    }

    public function getVideo()
    {
        return $this->video;
    }

    public function setVideo($video)
    {
        $this->video = $video;
    }

    public function getIdProducto()
    {
        return $this->idProducto;
    }

    public function setIdProducto($idProducto)
    {
        $this->idProducto = $idProducto;
    }

    public function CrearProducto($pdo, $IDproducto)
	{
		try {
			$sql = "CALL Sp_Video(:p_IDproducto, :p_rutaVideo)";
			$stmt = $pdo->prepare($sql);

            $stmt->bindParam(':p_IDproducto', $IDproducto, PDO::PARAM_INT);
			$stmt->bindParam(':p_rutaVideo', $this->video, PDO::PARAM_STR);
			$stmt->execute();
			$pdo = null;

			return true;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}

    public function CambiarVideo($pdo)
	{
		try {
			$sql = "CALL SP_ActualizarRutaVideo(:p_IDproducto, :p_rutaVideo)";
			$stmt = $pdo->prepare($sql);

            $stmt->bindParam(':p_IDproducto', $this->idProducto, PDO::PARAM_INT);
			$stmt->bindParam(':p_rutaVideo', $this->video, PDO::PARAM_STR);
			$stmt->execute();
			$pdo = null;

			return true;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}

    public function VerRutaVideo($pdo)
	{
		try {
			$sql = "CALL Sp_ObtenerVideo(:p_ID_Producto)";
			$stmt = $pdo->prepare($sql);

            $stmt->bindParam(':p_ID_Producto', $this->idProducto, PDO::PARAM_INT);
			$stmt->execute();
            $imagenes = array();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $rutavideo = $row['Ruta_Video'];
        } 
        else{
            $rutavideo = false;
        }

			$pdo = null;

			return $rutavideo;
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		}
	}




}

?>