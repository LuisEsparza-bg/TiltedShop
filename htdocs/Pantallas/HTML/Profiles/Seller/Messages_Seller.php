<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php');
    exit;
}

require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Users.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Products.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Categories.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\CatProd.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Chats.php');

$username = null;
$FindOne = null;

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $db = new DB();
    $conn = $db->connect();

    $sql = "CALL SP_ProfileData(:username)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $rol = $result['ID_Roles'];
        } else {
        }
    } else {
        echo "Error al llamar al procedimiento almacenado: ";
    }

    if ($rol == 1) {
        header('Location: /Pantallas/HTML/Profiles/Admin/Products_TBD.php');
    } else if ($rol == 3) {
        header('Location: /Pantallas/HTML/Profiles/Home.php');
    } else {

    }

    $stmt->closeCursor();
    $IDUser = new Users();
    $idActiva = $IDUser->getIdByUsername($conn, $username);

    $stmt->closeCursor();
    $chats = new Chat();
    $obtainedChats = $chats->CargarChats($conn, $username);


    $conn = null;
} else {
    $FindOne = null;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsClient/my_messages.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/footer.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/background.css">
</head>

<body>

    <!-- NAVBAR START -->
    <div class="row Navbar_Container">
        <div class="col-lg-3 col-sm-1 ">
            <a href="../../../HTML/Profiles/Seller/My_Products.php">
                <img src="../../../Images/Tilted_Shop_Logo.png" class="Navbar_Logo" alt="Tilted Shop Icon">
            </a>
        </div>

        <div class="col-lg-1 col-sm-4 offset-sm-6 offset-lg-6">
            <div class="dropdown">
                <a class="btn dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <small> Hola <b>
                            <?php echo $username; ?>
                        </b> </small>
                    <br>
                    <b>Cuenta y Más </b>
                </a>

                <ul class="dropdown-menu Navbar_DropdownMenu" aria-labelledby="dropdownMenuLink">
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../../HTML/Profiles/Seller/Profile_SellerS.php">Perfil</a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../../HTML/Profiles/Seller/My_Products.php">Mis productos</a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../../HTML/Profiles/Seller/Products_TBD.php">Mis productos cotizados
                        </a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../../HTML/Profiles/Seller/Products_New.php">Agregar Producto
                        </a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../../HTML/Profiles/Seller/Products_NewC.php">Agregar Producto Cotizado
                        </a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../../HTML/Profiles/Seller/profile_SellerReports.php"> Mis Reportes
                        </a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../../HTML/Profiles/Seller/Messages_Seller.php">Mis mensajes</a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton" id="logout2">Cerrar sesión</a> </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- NAVBAR END -->

    <br>
    <br>



    <div class="row  mb-3">

        <div class="MyChats_ContainerLists col-lg-10 offset-1 p-0">

            <!-- Lista de Chats -->

            <div class=" MyChats_ListDropdown col-lg-3 d-flex flex-column">
                <span class="MyChats_ListText"> Mis chats</span>


                <hr>
                <div class="list-group">
                    <?php if ($obtainedChats != null)
                        foreach ($obtainedChats as $chatItems) {
                            $chatID = $chatItems['ID_Chats'];
                            $productName = $chatItems['Nombre_Producto'];
                            $sellerName = $chatItems['Username_Cliente'];
                            $imagen = $chatItems['Imagen'];
                            $encodeImagen = base64_encode($imagen);
                            $sellerID = $chatItems['Username_Cliente'];
                            $lastMessageDate = $chatItems['Fecha_Ultimo_Mensaje'];
                            $idproducto = $chatItems['ID_Producto'];
                            echo '<button class="list-group-item list-group-item-action mb-2 MyChats_SelectChatButtons" id="SelectedButton" data-idproduct = '.$idproducto. ' data-idchat=' . $chatID . '
                            data-sellerName=' . $sellerName . ' data-sellerID=' . $sellerID . ' data-productname= ' . $productName . '>
                        <img src="data:image/jpeg;base64, '.$encodeImagen.'"  class="chat-image"
                            id="getMesssages" alt="Imagen de Persona 2">
                        <div class="media">
                            <div class="media-body">
                                <small> Cliente: <b> ' . $sellerName . ' </b> </small>
                                <br>
                                <small> Fecha Ultimo Mensaje: ' . $lastMessageDate . '</small>
                                <br>
                                <small>Producto:<b>' . $productName . '</b></small>
                                <small hidden ><b>' . $sellerID . '</b></small>
                            </div>
                        </div>
                    </button>';
                        }

                    ?>
                    </a>



                    <!-- Puedes agregar más chats aquí -->
                </div>


            </div>



            <!-- CHAT-->
            <div class="col-lg-6">

                <div class="flex-row MyChats_Title ">
                    <small id="ActiveNameChat" class='MyChats_InfoText'> Chat con: <b></b> </small>
                    <br>
                    <small class='' id="ActiveItem"> Cotización para: <b></b> </small>
                    <small id="ActiveIDChat" class='' id='idProducto' hidden value=></small>
                    <small id="ActiveIDSeller" class='' id='idVendedor' hidden value=></small>
                    <small id="ActiveID" class='' hidden value=> <?php echo $idActiva ?> </small>
                </div>

                <div class="MyChats_ContainermMaxChat">
                    <div class="flex-row MyChats_ChatContainer">

                        

                       

                    </div>
                </div>



                <div class="container MyChats_SendMessageContainer">
                    <div class="row">
                        <div class="col-md-12 m-2">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Escribe tu mensaje"
                                    aria-label="Escribe tu mensaje" id="mensajeInput" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                <button class="btn MyChats_BotonSend" id="EnviarMensaje"
                                        type="button" disabled>Enviar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>


            <div
                class="container justify-content-center d-flex align-items-center text-center MyChats_NewProductContainer">
                <div>
                    <p class="MyChats_ListText" id="ActiveItem2"> </p>
                    <br>
                    
                    <br>
                    
                        <div class="form-group">
                            <label for="precio">Precio:</label>
                            <input type="number" class="form-control" id="precio" placeholder="Ingrese el precio"
                                value="">
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <textarea class="form-control" id="descripcion" rows="4"
                                placeholder="Ingrese la descripción"></textarea>
                        </div>
                        <button onclick="SendProductChat()" id="EnviarProducto"  disabled class="btn MyChats_BotonSend">Enviar Cotización</button>
                    
                </div>
            </div>


        </div>



    </div>
    </div>



    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/Client/MessagesS.js"></script>
    <script src="../../../JS/General/logout.js"></script>

</body>




</html>