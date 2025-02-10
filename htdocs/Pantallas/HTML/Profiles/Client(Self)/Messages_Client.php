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
$NewChat = null;

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
    } else if ($rol == 2) {
        header('Location: /Pantallas/HTML/Profiles/Seller/My_Products.php');
    } else {

    }
    ;

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
        <div class="col-lg-3 col-sm-4">
            <a href="../../../HTML/Profiles/Home.php"><img src="../../../Images/Tilted_Shop_Logo.png"
                    class="Navbar_Logo" alt="Tilted Shop Icon"></a>

        </div>

        <div class="col-lg-6 col-sm-4 ">
            <div class="container Navbar_SearchBar">
                <div class="input-group">
                    <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
                        aria-describedby="search-addon" />
                    <a href="../../../HTML/Profiles/Search_Result.php"
                        class="btn btn-primary Navbar_SearchButton">Buscar</a>
                    <div class="mt-2">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-1 col-sm-4">
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
                            href="../../../HTML/Profiles/Client(Self)/Profile_ClientS.php">Mi Perfil</a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../../HTML/Profiles/Client(Self)/my_orders.php">Mis pedidos</a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../../HTML/Profiles/Client(Self)/Messages_Client.php">Mis mensajes</a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton" id="logout2">Cerrar sesión</a> </li>
                </ul>
            </div>
        </div>


        <div class="col-lg-2 col-sm-4">
            <a href="../../../HTML/Profiles/Client(Self)/Shopping_Cart.php">
                <img src="../../../Images/Edit_Profile_Images/Car_icon.jpg" class="Navbar_CarLogo" alt="Cart icon">
            </a>
            <div class="Navbar_CarText">
                <a class="Navbar_CartHL" href="../../../HTML/Profiles/Client(Self)/Shopping_Cart.php"> Mi Carrito </a>
            </div>
        </div>
    </div>

    <!-- NAVBAR END -->

    <br>
    <br>



    <div class="row mt-3 mb-3">

        <div class="MyChats_ContainerLists col-lg-10 offset-1 p-0">
            <div class=" MyChats_ListDropdown col-lg-4 d-flex flex-column">
                <span class="MyChats_ListText"> Mis chats</span>

                <div class="justify-content-center mt-3">
                </div>

                <hr>
                <div class="list-group">
                    <?php
                    if ($obtainedChats != null) 
                        foreach ($obtainedChats as $chatItems) {
                            $chatID = $chatItems['ID_Chats'];
                            $productName = $chatItems['Nombre_Producto'];
                            $imagen = $chatItems['Imagen'];
                            $encodeImagen = base64_encode($imagen);
                            $sellerName = $chatItems['Username_Vendedor'];
                            $sellerID = $chatItems['ID_Vendedor'];
                            $idproducto = $chatItems['ID_Producto'];
                            $lastMessageDate = $chatItems['Fecha_Ultimo_Mensaje'];
                            echo '<button class="list-group-item list-group-item-action mb-2 MyChats_SelectChatButtons" id="SelectedButton" data-idproduct = '.$idproducto. ' data-idchat='.$chatID.'
                            data-sellerName='.$sellerName.' data-sellerID='.$sellerID.' data-productname= '.$productName.'>
                        <img src="data:image/jpeg;base64, '.$encodeImagen.'" class="chat-image"
                            id="getMesssages" alt="Imagen de Persona 2">
                        <div class="media">
                            <div class="media-body">
                                <small> Vendedor: <b> '.$sellerName.' </b> </small>
                                <br>
                                <small> Fecha Ultimo Mensaje: '.$lastMessageDate.'</small>
                                <br>
                                <small>Producto:<b>'.$productName.'</b></small>
                                <small hidden ><b>'.$sellerID.'</b></small>
                            </div>
                        </div>
                    </button>';
                        }
                    
                    ?>
                    <!-- Puedes agregar más chats aquí -->
                </div>


            </div>



            <!-- CHAT-->
            <div class="col-lg-8">

                <div class="flex-row MyChats_Title ">

                    <div class='flex-row MyChats_Title'>
                        <small id="ActiveNameChat" class='MyChats_InfoText'> Chat con: <b></b> </small>
                        <br>
                        <small class='' id="ActiveItem"> Cotización para: <b></b> </small>
                        <small id="ActiveIDChat"  class='' id='idProducto' hidden value=></small>
                        <small id="ActiveIDSeller"  class='' id='idVendedor' hidden value=></small>
                        <small id="ActiveID" class='' hidden value=> <?php echo $idActiva ?> </small>
                    </div> 


                </div>

                <div class="MyChats_ContainermMaxChat">
                    <div class="flex-row MyChats_ChatContainer">


                       

                    </div>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col-md-12 m-2">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" maxlength="300" placeholder="Escribe tu mensaje"
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
        </div>
    </div>


    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/Client/Messages.js"></script>
    <script src="../../../JS/General/logout.js"></script>

</body>




</html>