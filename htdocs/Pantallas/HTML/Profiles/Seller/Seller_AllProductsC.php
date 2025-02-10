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
    else if($rol == 3){
        header('Location: /Pantallas/HTML/Profiles/Home.php'); 
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
    <link rel="stylesheet" href="../../../CSS/Profiles/profile_sellerproducts.css">
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
                <small> Hola <b> <?php echo $username; ?> </b> </small>
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

    <div class="col-lg-10 offset-lg-1">
        <div class="row ProfileSItems_ContainerLists p-0 ">
            <div class="container">
                <p class="ProfileSItems_HeaderObjects">Productos cotizables de @Vendedor:</p>

                <div class="row">

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../../Images/Items_Images/cotizado5.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../../Profiles/Seller/Products_DetailS.php">Armarios de Madera</a></h5>
                                <p>Descripción: Armarios de Madera, realizados en México. Se puede modificar el color,
                                    el número de cajones, etc.</p>
                                
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../../Images/Items_Images/cotizado3.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../../Profiles/Seller/Products_DetailS.php">Cofres de Madera</a></h5>
                                <p>Descripción: Cofres de Madera, realizados en París. Favor de cotizar el objeto para
                                    más información</p>
                                
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../../Images/Items_Images/cotizado8.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../../Profiles/Seller/Products_DetailS.php">Artilugios de Metal</a></h5>
                                <p>Descripción: Artilugios de metal elaborados artesanalmente, tenemos desde discos
                                    metalicos hasta esferas artesanales</p>
                                
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-3">
                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../../Images/Items_Images/mcotizado1.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../../Profiles/Seller/Products_DetailS.php">Mesa de Comedor Rústica</a></h5>
                                <p>Descripción: Una mesa de comedor de madera maciza con un diseño rústico y encanto
                                    natural. Perfecta para reuniones familiares y cenas elegantes.
                                </p>
                                
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../../Images/Items_Images/mcotizado2.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../../Profiles/Seller/Products_DetailS.php">Mesa de Centro de Roble</a></h5>
                                <p>Descripción: Una mesa de centro de roble sólido con un diseño moderno y minimalista.
                                    Realza la apariencia de tu sala de esta</p>
                                
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../../Images/Items_Images/mcotizado3.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../../Profiles/Seller/Products_DetailS.php">Mesa de Centro de Nogal</a></h5>
                                <p>Descripción: Una mesa de centro de nogal con un diseño contemporáneo y un estante
                                    inferior para guardar revistas y objetos decorativos.</p>
                                
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-3">
                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../../Images/Items_Images/mcotizado4.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../../Profiles/Seller/Products_DetailS.php">Mesa de Bar de Madera Reciclada:</a></h5>
                                <p>Descripción: Una mesa de bar hecha de madera reciclada, con un aspecto único y
                                    sostenible que añade carácter a cualquier espacio.</p>
                                
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../../Images/Items_Images/mcotizado5.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../../Profiles/Seller/Products_DetailS.php">Mesa de Estudio de Roble Claro</a></h5>
                                <p>Descripción: Una mesa de estudio de roble claro, ideal para estudiantes y profesionales, con un diseño simple y funcional.</p>
                                
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-4">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../../Images/Items_Images/mcotizado6.png" alt=""
                                    class="img-fluid ProfileSItems_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileSItems_NameObject" href="../../Profiles/Seller/Products_DetailS.php">Mesa de Café de Caoba:</a></h5>
                                <p>Descripción: Una mesa de café elegante y sofisticada hecha de caoba, con un acabado
                                    brillante que resalta la belleza de la madera.</p>
                                
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
    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/General/logout.js"></script>
</body>../

</html>