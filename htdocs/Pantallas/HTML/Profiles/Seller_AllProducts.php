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
    <link rel="stylesheet" href="../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../CSS/Profiles/profile_sellerproducts.css">
    <link rel="stylesheet" href="../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../CSS/General Styles/footer.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/background.css">

</head>

<body>
    <!-- NAVBAR START -->
    <div class="row Navbar_Container">
        <div class="col-lg-3 col-sm-4">
            <a href="../../HTML/Profiles/Home.php"><img src="../../Images/Tilted_Shop_Logo.png"
                    class="Navbar_Logo" alt="Tilted Shop Icon"></a>

        </div>

        <div class="col-lg-6 col-sm-4 ">
            <div class="container Navbar_SearchBar">
                <div class="input-group">
                    <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
                        aria-describedby="search-addon" />
                    <a href="../../HTML/Profiles/Search_Result.php"
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
                            href="../../HTML/Profiles/Client(Self)/Profile_ClientS.php">Mi Perfil</a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../HTML/Profiles/Client(Self)/my_orders.php">Mis pedidos</a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../HTML/Profiles/Client(Self)/Messages_Client.php">Mis mensajes</a></li>
                            <li><a class="dropdown-item Navbar_DropdownButton" id="logout">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>


        <div class="col-lg-2 col-sm-4">
            <a href="../../HTML/Profiles/Client(Self)/Shopping_Cart.php">
                <img src="../../Images/Edit_Profile_Images/Car_icon.jpg" class="Navbar_CarLogo" alt="Cart icon">
            </a>
            <div class="Navbar_CarText">
                <a class="Navbar_CartHL" href="../../HTML/Profiles/Client(Self)/Shopping_Cart.php"> Mi Carrito </a>
            </div>
        </div>
    </div>
    <!-- NAVBAR END -->
    <br>
    <br>


    <div class="col-lg-10 offset-lg-1 mb-3">
        <div class="row ProfileSItems_ContainerLists p-0 ">
            <div class="container">
                <p class="ProfileSItems_HeaderObjects">Productos de @Vendedor:</p>

                <div class="row">

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/silla1.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../Profiles/Products_Detail.php">Silla Plástico</a></h5>
                                <p>Descripción: Silla de plástico de alta calidad. 
                                    Ideal para interiores y exteriores.
                                    </p>
                                    <p class="card-text">Precio:  <b>$399.00</b></p>
                                    <a href="#" class="btn ProfileSItems_ItemsButton">Agregar al carrito</a>
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/game5.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../Profiles/Products_Detail.php">Cyber Waifu 2088</a></h5>
                                <p>Descripción: Videojuego "Cyber Waifu 2088".  
                                    Sumérgete en un mundo futurista.
                                    </p>
                                    <p class="card-text">Precio:  <b>$1,399.00 </b></p>
                                    <a href="#" class="btn ProfileSItems_ItemsButton">Agregar al carrito</a>
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/silla5.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../Profiles/Products_Detail.php">Silla Blanca</a></h5>
                                <p>Descripción: Elegante silla blanca para tu hogar.
                                    Diseño moderno y cómodo.            
                                    </p>
                                    <p class="card-text">Precio:  <b>$799.00</b></p>
                                    <a href="#" class="btn ProfileSItems_ItemsButton">Agregar al carrito</a>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-3">

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/game4.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../Profiles/Products_Detail.php">Sallms Diisk PS4 </a></h5>
                                <p>Descripción: Juego de aventuras para PS4.
                                    Experimenta una emocionante historia.
                                    </p>
                                    <p class="card-text">Precio: <b>$1,299.00</b></p>
                                    <a href="#" class="btn ProfileSItems_ItemsButton">Agregar al carrito</a>
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/silla3.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../Profiles/Products_Detail.php">Silla De Madera </a></h5>
                                <p>Descripción: Silla de madera resistente y elegante.
                                    Perfecta para tu comedor.
                                    </p>
                                    <p class="card-text">Precio:  <b>$1,999.00</b></p>
                                    <a href="#" class="btn ProfileSItems_ItemsButton">Agregar al carrito</a>
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/silla4.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../Profiles/Products_Detail.php">Silla Rosa</a></h5>
                                <p>Descripción: Silla rosa con estilo único.       
                                    Ideal para habitaciones juveniles.                                     
                                    </p>
                                    <p class="card-text">Precio:  <b>$999.00</b></p>
                                    <a href="#" class="btn ProfileSItems_ItemsButton">Agregar al carrito</a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row mt-3">

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/Figura1.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../Profiles/Products_Detail.php">Figura Anime Special 3</a></h5>
                                <p>Descripción:  Figuras de anime con diseño exclusivo
                                    Ideal para coleccionistas.     
                                    </p>
                                    <p class="card-text">Precio: <b>$3,299.00</b></p>
                                    <a href="#" class="btn ProfileSItems_ItemsButton">Agregar al carrito</a>
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/Figura2.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../Profiles/Products_Detail.php">Figura de Anime Battle </a></h5>
                                <p>Descripción:Figura de anime de alta calidad.
                                    Detalles increíbles y realistas. 
                                    </p>
                                    <p class="card-text">Precio:  <b>$999.00</b></p>
                                    <a href="#" class="btn ProfileSItems_ItemsButton">Agregar al carrito</a>
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/Figura5.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../Profiles/Products_Detail.php">Figura de Anime Retro</a></h5>
                                <p>Descripción:  Figuras de anime con gran atención a  
                                    los detalles y colores vibrantes.
                                    </p>
                                    <p class="card-text">Precio:  <b>$399.00</b></p>
                                    <a href="#" class="btn ProfileSItems_ItemsButton">Agregar al carrito</a>
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
        </div>
    </div>
    <script src="../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../Librerias/popper/popper.min.js"></script>
    <script src="../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../JS/General/logout.js"></script>
</body>

</html>
