<?php
class Chat
{
    private $idChats;
    private $idUsuarioCliente;
    private $idUsuarioVendedor;
    private $idProducto;

    public function __construct($idChats = null, $idUsuarioCliente = null, $idUsuarioVendedor = null, $idProducto = null)
    {
        $this->idChats = $idChats;
        $this->idUsuarioCliente = $idUsuarioCliente;
        $this->idUsuarioVendedor = $idUsuarioVendedor;
        $this->idProducto = $idProducto;
    }

    public function getIdChats()
    {
        return $this->idChats;
    }

    public function setIdChats($idChats)
    {
        $this->idChats = $idChats;
    }

    public function getIdUsuarioCliente()
    {
        return $this->idUsuarioCliente;
    }

    public function setIdUsuarioCliente($idUsuarioCliente)
    {
        $this->idUsuarioCliente = $idUsuarioCliente;
    }

    public function getIdUsuarioVendedor()
    {
        return $this->idUsuarioVendedor;
    }

    public function setIdUsuarioVendedor($idUsuarioVendedor)
    {
        $this->idUsuarioVendedor = $idUsuarioVendedor;
    }

    public function getIdProducto()
    {
        return $this->idProducto;
    }

    public function setIdProducto($idProducto)
    {
        $this->idProducto = $idProducto;
    }

    public function GestionMensajes($pdo, $opcion, $username, $mensaje)
    {
        if ($opcion == 1) {
            try {
                $sql = "CALL SP_ChatManagement(:p_Opcion, :p_Username, :p_ID_Usuario_Vendedor, :p_ID_Producto, :p_ID_Chat, :p_ID_Usuario_Emisor, :p_ID_Usuario_Receptor, :p_Mensaje)";
                $stmt = $pdo->prepare($sql);
                // se obtiene desde el SP 
                $usuarioEmisor = 1;
                $stmt->bindParam(':p_Opcion', $opcion, PDO::PARAM_INT);
                $stmt->bindParam(':p_Username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':p_ID_Usuario_Vendedor', $this->idUsuarioVendedor, PDO::PARAM_INT);
                $stmt->bindParam(':p_ID_Producto', $this->idProducto, PDO::PARAM_INT);
                $stmt->bindParam(':p_ID_Chat', $this->idChats, PDO::PARAM_INT);
                $stmt->bindParam(':p_ID_Usuario_Emisor', $usuarioEmisor, PDO::PARAM_INT);
                $stmt->bindParam(':p_ID_Usuario_Receptor', $this->idUsuarioVendedor, PDO::PARAM_INT);
                $stmt->bindParam(':p_Mensaje', $mensaje, PDO::PARAM_STR);
                $stmt->execute();
                
                $pdo = null;

                return true;
            } catch (PDOException $e) {
                return "Error: " . $e->getMessage();
            }
        }

        if ($opcion == 2) {
            try {
                $sql = "CALL SP_ChatManagement(:p_Opcion, :p_Username, :p_ID_Usuario_Vendedor, :p_ID_Producto, :p_ID_Chat, :p_ID_Usuario_Emisor, :p_ID_Usuario_Receptor, :p_Mensaje)";
                $stmt = $pdo->prepare($sql);
                // se obtiene desde el SP 
                $usuarioEmisor = 1;
                $stmt->bindParam(':p_Opcion', $opcion, PDO::PARAM_INT);
                $stmt->bindParam(':p_Username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':p_ID_Usuario_Vendedor', $this->idUsuarioVendedor, PDO::PARAM_INT);
                $stmt->bindParam(':p_ID_Producto', $this->idProducto, PDO::PARAM_INT);
                $stmt->bindParam(':p_ID_Chat', $this->idChats, PDO::PARAM_INT);
                $stmt->bindParam(':p_ID_Usuario_Emisor', $usuarioEmisor, PDO::PARAM_INT);
                $stmt->bindParam(':p_ID_Usuario_Receptor', $this->idUsuarioVendedor, PDO::PARAM_INT);
                $stmt->bindParam(':p_Mensaje', $mensaje, PDO::PARAM_STR);
                $stmt->execute();
                $messages = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $messages[] = $row;
                }
                $pdo = null;
                return $messages;
            } catch (PDOException $e) {
                return "Error: " . $e->getMessage();
            }
        }
    }

    public function CargarChats($pdo, $username)
    {
        try {
            $sql = "CALL SP_GetUserChats(:p_Username)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':p_Username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $chats = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $chats[] = $row;
            }


            return $chats;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }


    public function CargarMensajes($pdo)
    {
        try {
            $sql = "CALL SP_GetUserMessages(:p_ID_Chat)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':p_ID_Chat', $this->idChats, PDO::PARAM_STR);
            $stmt->execute();
            $messages = array();


            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $messages[] = $row;
            }
            if ($messages) {
                $messagesJSON = json_encode($messages);
                // Enviar la respuesta JSON
                echo $messagesJSON;
            }


            return $messages;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

}

?>