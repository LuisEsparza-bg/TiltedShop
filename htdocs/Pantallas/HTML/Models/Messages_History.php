<?php
class MessagesHistory
{
    private $idHistorialMensajes;
    private $idChat;
    private $idUsuarioEmisor;
    private $idUsuarioReceptor;
    private $mensaje;
    private $fechaMensaje;


    public function getIdHistorialMensajes()
    {
        return $this->idHistorialMensajes;
    }

    public function setIdHistorialMensajes($idHistorialMensajes)
    {
        $this->idHistorialMensajes = $idHistorialMensajes;
    }

    public function getIdChat()
    {
        return $this->idChat;
    }

    public function setIdChat($idChat)
    {
        $this->idChat = $idChat;
    }

    public function getIdUsuarioEmisor()
    {
        return $this->idUsuarioEmisor;
    }

    public function setIdUsuarioEmisor($idUsuarioEmisor)
    {
        $this->idUsuarioEmisor = $idUsuarioEmisor;
    }

    public function getIdUsuarioReceptor()
    {
        return $this->idUsuarioReceptor;
    }

    public function setIdUsuarioReceptor($idUsuarioReceptor)
    {
        $this->idUsuarioReceptor = $idUsuarioReceptor;
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    public function getFechaMensaje()
    {
        return $this->fechaMensaje;
    }

    public function setFechaMensaje($fechaMensaje)
    {
        $this->fechaMensaje = $fechaMensaje;
    }



    public function __construct($idHistorialMensajes = null, $idChat = null, $idUsuarioEmisor = null, $idUsuarioReceptor = null, $mensaje = null, $fechaMensaje = null)
    {
        $this->idHistorialMensajes = $idHistorialMensajes;
        $this->idChat = $idChat;
        $this->idUsuarioEmisor = $idUsuarioEmisor;
        $this->idUsuarioReceptor = $idUsuarioReceptor;
        $this->mensaje = $mensaje;
        $this->fechaMensaje = $fechaMensaje;
    }

    public function EnviarMensaje($pdo, $username)
    {
        try {
            $sql = "CALL SP_EnviarMensaje(:p_Username, :p_ID_Chats, :p_Mensaje, :p_ID_Usuario_Receptor)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':p_Username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':p_ID_Chats', $this->idChat, PDO::PARAM_INT);
            $stmt->bindParam(':p_Mensaje', $this->mensaje, PDO::PARAM_STR);
            $stmt->bindParam(':p_ID_Usuario_Receptor', $this->idUsuarioReceptor, PDO::PARAM_INT);
            $stmt->execute();
            $pdo = null;

            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }

    }


    public function EnviarMensajeS($pdo, $username, $UsernameReceptor)
    {
        try {
            $sql = "CALL SP_EnviarMensajeS(:p_Username_Emisor, :p_Username_Receptor, :p_ID_Chats, :p_Mensaje)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':p_Username_Emisor', $username, PDO::PARAM_STR);
            $stmt->bindParam(':p_ID_Chats', $this->idChat, PDO::PARAM_INT);
            $stmt->bindParam(':p_Mensaje', $this->mensaje, PDO::PARAM_STR);
            $stmt->bindParam(':p_Username_Receptor', $UsernameReceptor, PDO::PARAM_STR);
            $stmt->execute();
            $pdo = null;

            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }

    }
}







?>