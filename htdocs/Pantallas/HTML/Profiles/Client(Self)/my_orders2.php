<?php
session_start(); 

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php'); 
    exit; 
}

require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');

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
        echo "Error al llamar al procedimiento almacenado: " ;
    }

    if($rol == 1){
    header('Location: /Pantallas/HTML/Profiles/Admin/Products_TBD.php'); 
    }
    else if($rol == 2){
        header('Location: /Pantallas/HTML/Profiles/Seller/My_Products.php');
    }
    else{

    }

    $conn = null; 
}
else{
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
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsClient/my_orders.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/footer.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/Pagination.css">
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
                <small> Hola <b> <?php echo $username; ?> </b> </small>
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

    <div class=" col-lg-8 offset-lg-2 mb-4">
        <div class="row MyOrder_OrdersContainer p-0 ">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <h3 class="">Mis pedidos:</h3>
                    </div>
                    <!-- Agregar la barra de búsqueda a la derecha -->
                    <div class="col-lg-4 mt-3">
                        <div class="input-group">
                            <input type="search" class="form-control rounded" placeholder="Buscar pedidos por producto"
                                aria-label="Search" aria-describedby="search-addon" />
                            <button type="button" class="btn Navbar_SearchButton">Buscar</button>
                        </div>
                    </div>
                </div>

                <div class="row MyOrders_InfoOrder mb-3">
                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Pedido realizado: <b>20 de Noviembre de 2022</b></p>
                    </div>

                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Total: <b>3,345.00$</b></p>
                    </div>

                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Enviado a: <b>@Domicilio</b></p>
                    </div>

                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Pedido n# <b>3048501</b></p>
                    </div>

                </div>

                <div class="MyOrder_ContainerProducts p-0">

                    <div class="row">
                        <div class="container-fluid col-lg-4 MyOrder_ContainerImages mt-3">
                            <img src="../../../Images/Items_Images/Figura4.png" alt=""
                                class="img-fluid MyOrder_Images align-middle">
                        </div>
                        <div class="container-fluid col-lg-8">
                            <h5><a class="MyOrder_Product" href="../../../HTML/Profiles/Products_Detail.php">Figura Anime Super</a></h5>
                            <p>Precio del producto: <b>199.99$</b> </p>
                            <p>Vendido por: <a class="MyOrder_SellerText" href="../../../HTML/Profiles/Profile_Seller.php">@Usuario</a></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="container-fluid col-lg-4 MyOrder_ContainerImages">
                            <img src="../../../Images/Items_Images/Figura3.png" alt=""
                                class="img-fluid MyOrder_Images align-middle">
                        </div>
                        <div class="container-fluid col-lg-8">
                            <h5><a class="MyOrder_Product" href="../../../HTML/Profiles/Products_Detail.php">Figura Anime</a></h5>
                            <p>Precio del producto: <b>399.99$</b> </p>
                            <p>Vendido por: <a class="MyOrder_SellerText" href="../../../HTML/Profiles/Profile_Seller.php">@Usuario</a></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="container-fluid col-lg-4 MyOrder_ContainerImages">
                            <img src="../../../Images/Items_Images/game5.png" alt=""
                                class="img-fluid MyOrder_Images align-middle">
                        </div>
                        <div class="container-fluid col-lg-8">
                            <h5><a class="MyOrder_Product" href="../../../HTML/Profiles/Products_Detail.php">Videojuego</a></h5>
                            <p>Precio del producto: <b>1,399.99$</b> </p>
                            <p>Vendido por: <a class="MyOrder_SellerText" href="../../../HTML/Profiles/Profile_Seller.php">@Usuario</a></p>
                        </div>
                    </div>

                    <hr>

                </div>


                <div class="row MyOrders_InfoOrderFinish mb-3">
                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Gracias por tu pedido <b> :D </b></p>
                    </div>
                </div>

                <hr>

                <div class="row MyOrders_InfoOrder mb-3">
                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Pedido realizado: <b>01 de Noviembre de 2022</b></p>
                    </div>

                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Total: <b>2,345.00$</b></p>
                    </div>

                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Enviado a: <b>@Domicilio</b></p>
                    </div>

                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Pedido n# <b>3142513</b></p>
                    </div>

                </div>

                <div class="MyOrder_ContainerProducts p-0">

                    <div class="row">
                        <div class="container-fluid col-lg-4 MyOrder_ContainerImages mt-3">
                            <img src="../../../Images/Items_Images/mcotizado4.png" alt=""
                                class="img-fluid MyOrder_Images align-middle">
                        </div>
                        <div class="container-fluid col-lg-8">
                            <h5><a class="MyOrder_Product" href="../../../HTML/Profiles/Products_Detail.php">Mesa de Madera Caoba</a></h5>
                            <p>Precio del producto: <b>1,199.99$</b> </p>
                            <p><b>Este producto fue cotizado</b> </p>
                            <p>Mesa de Caoba con toques modernos y el nombre "Lisa" enmarcado en Oro, 100cm de ancho y
                                400cm de altura</p>
                            <p>Vendido por: <a class="MyOrder_SellerText" href="../../../HTML/Profiles/Profile_Seller.php">@Usuario</a></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="container-fluid col-lg-4 MyOrder_ContainerImages">
                            <img src="../../../Images/Items_Images/Figura1.png" alt=""
                                class="img-fluid MyOrder_Images align-middle">
                        </div>
                        <div class="container-fluid col-lg-8">
                            <h5><a class="MyOrder_Product" href="../../../HTML/Profiles/Products_Detail.php">Figura Anime</a></h5>
                            <p>Precio del producto: <b>459.99$</b> </p>
                            <p>Vendido por: <a class="MyOrder_SellerText" href="../../../HTML/Profiles/Profile_Seller.php">@Usuario</a></p>
                        </div>
                    </div>

                    <hr>

                    <hr>

                </div>

                <div class="row MyOrders_InfoOrderFinish mb-3">
                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Gracias por tu pedido <b> :D </b></p>
                    </div>
                </div>

                <hr>

                <div class="row MyOrders_InfoOrder mb-3">
                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Pedido realizado: <b>26 de Noviembre de 2022</b></p>
                    </div>

                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Total: <b>1,199.00$</b></p>
                    </div>

                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Enviado a: <b>@Domicilio</b></p>
                    </div>

                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Pedido n# <b>31203</b></p>
                    </div>

                </div>

                <div class="MyOrder_ContainerProducts p-0">

                    <div class="row">
                        <div class="container-fluid col-lg-4 MyOrder_ContainerImages mt-3">
                            <img src="../../../Images/Items_Images/mcotizado4.png" alt=""
                                class="img-fluid MyOrder_Images align-middle">
                        </div>
                        <div class="container-fluid col-lg-8">
                            <h5><a class="MyOrder_Product" href="../../../HTML/Profiles/Products_Detail.php">Mesa de Madera Caoba</a></h5>
                            <p>Precio del producto: <b>1,199.99$</b> </p>
                            <p><b>Este producto fue regalado a @Usuario</b> </p>
                            <p>Vendido por: <a class="MyOrder_SellerText" href="../../../HTML/Profiles/Profile_Seller.php">@Usuario</a></p>
                        </div>
                    </div>

                    <hr>

                </div>

                <div class="row MyOrders_InfoOrderFinish mb-3">
                    <div class="col-lg-3">
                        <p class="MyOrder_Text"> Gracias por tu pedido <b> :D </b></p>
                    </div>
                </div>



            </div>





        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation example" class="d-flex justify-content-center mt-3">
            <ul class="pagination">
                <li class="page-item disabled">
                    <a class="page-link btn-paginacion-color" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link btn-paginacion-color" href="#">1</a></li>
                <li class="page-item"><a class="page-link btn-paginacion-color" href="#">2</a></li>
                <li class="page-item"><a class="page-link btn-paginacion-color" href="#">3</a></li>
                <li class="page-item"><a class="page-link btn-paginacion-color" href="#">4</a></li>
                <li class="page-item"><a class="page-link btn-paginacion-color" href="#">5</a></li>
                <li class="page-item">
                    <a class="page-link btn-paginacion-color" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Pagination -->
    </div>

    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/General/logout.js"></script>
</body>

</html>