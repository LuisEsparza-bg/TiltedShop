<?php
class ListProducts
{
    private $idListaProductos;
    private $idLista;
    private $idProducto;
    private $Estatus;

    public function __construct($idListaProductos = null, $idLista = null, $idProducto = null, $Estatus = null)
    {
        $this->idListaProductos = $idListaProductos;
        $this->idLista = $idLista;
        $this->idProducto = $idProducto;
        $this->Estatus = $Estatus;
    }

    public function GestionProductosLista($pdo, $opcion)
    {

        if ($opcion == 1) {
            try {
                $sql = "CALL SP_ListaProductos(:p_opcion, :p_idLista, :p_idProducto)";
                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
                $stmt->bindParam(':p_idLista', $this->idLista, PDO::PARAM_INT);
                $stmt->bindParam(':p_idProducto', $this->idProducto, PDO::PARAM_INT);
                $stmt->execute();
                $pdo = null;

                return true;
            } catch (PDOException $e) {
                return "Error: " . $e->getMessage();
            }
        } else {
            try {
                $sql = "CALL SP_ListaProductos(:p_opcion, :p_idLista, :p_idProducto)";
                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':p_opcion', $opcion, PDO::PARAM_INT);
                $stmt->bindParam(':p_idLista', $this->idLista, PDO::PARAM_INT);
                $stmt->bindParam(':p_idProducto', $this->idProducto, PDO::PARAM_INT);
                $stmt->execute();
                $pdo = null;

                return true;
            } catch (PDOException $e) {
                return "Error: " . $e->getMessage();
            }
        }




    }


    public function GetListItems($pdo)
    {
        try {
            $sql = "CALL SP_ObtenerProductosLista(:p_idLista)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':p_idLista', $this->idLista, PDO::PARAM_INT);
            $stmt->execute();

            $items = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Codificar la imagen en base64
                $row['Imagen'] = base64_encode($row['Imagen']);
                $items[] = $row;
            }
            if ($items) {
                $itemsJSON = json_encode($items);
                echo $itemsJSON;
            }

            $pdo = null;

            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }

    }



}