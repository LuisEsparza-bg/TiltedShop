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
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsSeller/products_tbd.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/footer.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/Pagination.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/background.css">

</head>

<body class="">
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

    <div class=" col-lg-8 offset-lg-2 mb-4">
        <div class="row ProductsTBD_OrdersContainer p-0 ">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <h3 class="">Mis productos cotizados:</h3>
                    </div>
                    <!-- Agregar la barra de búsqueda a la derecha -->
                    <div class="col-lg-4 mt-3">
                        <div class="input-group">
                            <input type="search" class="form-control rounded" placeholder="Buscar productos por nombre"
                                aria-label="Search" aria-describedby="search-addon" />
                            <button type="button" class="btn Navbar_SearchButton">Buscar</button>
                        </div>
                    </div>
                </div>

                <div class="ProductsTBD_ContainerProducts p-0">

                    <div class="row">
                        <div class="container-fluid col-lg-4 ProductsTBD_ContainerImages mt-3">
                            <img src="../../../Images/Items_Images/mcotizado3.png" alt=""
                                class="img-fluid ProductsTBD_Images align-middle">
                        </div>
                        <div class="container-fluid col-lg-8 mt-2">
                            <h5 class="ProductsTBD_Text">Mesas de madera cotizables</h5>
                            <p>Descripción: El producto cuenta con diferentes colores, tamaños y formas. </p>
                            <p>Fecha de alta: 10/01/2021</p>
                            <p>Estatus: <b>En espera</b></p>
                        </div>
                    </div>

                    <hr>
                </div>
                <hr>

                <div class="ProductsTBD_ContainerProducts p-0">
                    <div class="row">
                        <div class="container-fluid col-lg-4 ProductsTBD_ContainerImages mt-3">
                            <img src="../../../Images/Items_Images/mcotizado1.png" alt=""
                                class="img-fluid ProductsTBD_Images align-middle">
                        </div>
                        <div class="container-fluid col-lg-8 mt-2">
                            <h5><a class="ProductsTBD_Validated" href="../../Profiles/Seller/Products_DetailS.php">Mesa de caoba</a></h5>
                            <p>Descripción: El producto cuenta con diferentes colores, tamaños y formas. </p>
                            <p>Fecha de alta: 10/01/2020</p>
                            <p>Estatus: <b>Validado</b></p>
                            <p>Validado por:  <a href="../Seller/Profile_AdminS.php?username=ssiul" class="ProductsTBD_Validated"><b>@Admin</b></a></p>
                        </div>
                    </div>

                    <hr>
                </div>

                <hr>
                <div class="ProductsTBD_ContainerProducts p-0">
                    <div class="row">
                        <div class="container-fluid col-lg-4 ProductsTBD_ContainerImages mt-3">
                            <img src="../../../Images/Items_Images/mcotizado5.png" alt=""
                                class="img-fluid ProductsTBD_Images align-middle">
                        </div>
                        <div class="container-fluid col-lg-8 mt-2">
                            <h5 class="ProductsTBD_Text">Mesa de madera loca</h5>
                            <p>Descripción: El producto cuenta con diferentes colores, tamaños y formas. </p>
                            <p>Fecha de alta: 10/01/2020</p>
                            <p>Estatus: <b>Anulado</b></p>
                            <p>Validado por:  <a href="../Seller/Profile_AdminS.php?username=ssiul" class="ProductsTBD_Validated"><b>@Admin</b></a></p>
                        </div>
                    </div>

                    <hr>
                </div>
                </div>
                <hr>

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

    <script src="../../../JS/Admin/Products.js"></script>
    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/General/logout.js"></script>
</body>

</html>