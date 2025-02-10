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
        echo "Error al llamar al procedimiento almacenado: ";
    }

    if ($rol == 2) {
        header('Location: /Pantallas/HTML/Profiles/Seller/My_Products.php');
    } else if ($rol == 3) {
        header('Location: /Pantallas/HTML/Profiles/Home.php');
    } else {

    }

    $conn = null;
}

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    $db = new DB();
    $conn = $db->connect();

    $sql = "CALL SP_ProfileData(:username)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $FindOne = 1;
            $imagenUsuario = $result['Imagen'];
            $encodeImagen = base64_encode($imagenUsuario);
            $nombreUsuario = $result['Nombre'];
            $apellidoPaterno = $result['Apellido_Paterno'];
            $apellidoMaterno = $result['Apellido_Materno'];
            $fechaCreacion = $result['Fecha_Registro'];
            $rol = $result['ID_Roles'];
            $privacidad = $result['Privacidad'];
        } else {
        }
    } else {
        echo "Error al llamar al procedimiento almacenado: ";
    }

    // Cierra la conexión
    $conn = null;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../CSS/Profiles/profile_seller.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/footer.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/background.css">

</head>

<body>
    <!-- NAVBAR START -->
    <div class="row Navbar_Container">
        <div class="col-lg-3 col-sm-4">
            <a href="../../../HTML/Profiles/Admin/Products_TBD.php">
                <img src="../../../Images/Tilted_Shop_Logo.png" class="Navbar_Logo" alt="Tilted Shop Icon">
            </a>
        </div>

        <div class="col-lg-6 col-sm-4 ">
            <div class="container Navbar_SearchBar">
                <div class="input-group">
                    <input type="search" class="form-control rounded" placeholder="Buscar Productos que se han validado"
                        aria-label="Search" aria-describedby="search-addon" />
                    <a href="../../../HTML/Profiles/Admin/Admin_Products_Search.php"
                        class="btn Navbar_SearchButton">Buscar</a>
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
                            href="../../../HTML/Profiles/Admin/Profile_AdminS.php">Perfil</a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../../HTML/Profiles/Admin/Products_TBD.php">Productos por verificar</a></li>
                    <li><a class="dropdown-item Navbar_DropdownButton"
                            href="../../../HTML/Profiles/Admin/Products_Validated.php">Produtos verificados</a></li>
                            <li><a class="dropdown-item Navbar_DropdownButton" id="logout2">Cerrar sesión</a> </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- NAVBAR END -->
    <br>
    <br>


    <?php

    if ($FindOne != null) {
        if ($rol == 2) {
            echo '
            <div class="row">
            <div class="ProfileS_Container col-lg-10 offset-lg-1">
            <img  src="data:image/jpeg;base64,' . $encodeImagen . '" class="ProfileS_ContainerPhoto" alt="Cart icon">
            <div class="d-flex flex-column">
                <span class="ProfileS_Containertext">@' . $username . '</span>
                <span class="ProfileS_ContainertextSmall">Usuario desde: ' . $fechaCreacion . '</span>
                <span class="ProfileS_ContainertextSmall">Nombre completo: ' . $nombreUsuario . " " . $apellidoPaterno . " " . $apellidoMaterno . '</span>
            </div>
            </div>
            </div>

    <div class="mt-3 col-lg-10 offset-lg-1">
        <div class=" row ProfileS_ContainerLists p-0 ">
            <div class="container">
                <p class="ProfileS_HeaderObjects">Productos agregados recientemente:</p>
                <div class="row text-lg-center">
                    <div class="col-lg-2 mb-4">
                        <div class="card ProfileS_CardObjcts">
                            <img src="../../../Images/Items_Images/silla1.png" class="card-img-top ProfileS_ListImages">
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Admin/Admin_Products_Detail.php">Silla Plástico</a></h5>
                            <p class="card-text">Precio: $399.00</p>
                        </div>
                    </div>

                    <div class="col-lg-2 mb-4">
                        <div class="card ProfileS_CardObjcts">
                            <img src="../../../Images/Items_Images/silla5.png" class="card-img-top ProfileS_ListImages">
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Admin/Admin_Products_Detail.php">Silla Blanca</a></h5>
                            <p class="card-text">Precio: $799.00</p>
                        </div>
                    </div>

                    <div class="col-lg-2 mb-4">
                        <div class="card ProfileS_CardObjcts">
                            <img src="../../../Images/Items_Images/game5.png" class="card-img-top ProfileS_ListImages">
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Admin/Admin_Products_Detail.php">Cyber Waifu 2088</a></h5>
                            <p class="card-text">Precio: $1,399.00</p>
                        </div>
                    </div>

                    <div class="col-lg-2 mb-4">
                        <div class="card ProfileS_CardObjcts">
                            <img src="../../../Images/Items_Images/game4.png" class="card-img-top ProfileS_ListImages">
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Admin/Admin_Products_Detail.php">Sallms Diisk PS4</a></h5>
                            <p class="card-text">Precio: $1,299.00</p>
                        </div>
                    </div>

                    <div class="col-lg-2 mb-4">
                        <div class="card ProfileS_CardObjcts">
                            <img src="../../../Images/Items_Images/silla3.png" class="card-img-top ProfileS_ListImages">
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Admin/Admin_Products_Detail.php">Silla De Madera</a></h5>
                            <p class="card-text">Precio: $1,999.00</p>
                        </div>
                    </div>

                    <div class="col-lg-2 mb-4">
                        <div class="card ProfileS_CardObjcts">
                            <img src="../../../Images/Items_Images/silla4.png" class="card-img-top ProfileS_ListImages">
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Admin/Admin_Products_Detail.php">Silla Rosa</a></h5>
                            <p class="card-text">Precio: $999.00</p>
                        </div>
                    </div>

                    
                    
                </div>
            </div>
            <div class="d-flex justify-content-center mb-2">
                <a href="../Admin/Profile_SellerAProducts.php" class="btn ProfileS_ViewMoreButton">Ver todos los productos de @Vendedor</a>
            </div>
        </div>

        <div class="row ProfileS_ContainerLists mb-4">
            <div class="container">
                <p class="ProfileS_HeaderObjects">Productos cotizables agregados recientemente:</p>
                <div class="row text-lg-center">

                    <div class="col-lg-2 mb-4 ">
                        <div class="card ProfileS_CardObjcts">
                            <img src="../../../Images/Items_Images/cotizado1.png" class="card-img-top ProfileS_ListImages">
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Admin/Admin_Products_DetailC.php">Muebles de Madera</a></h5>
                            <p class="card-text fw-bold">Producto Cotizable</p>
                            
                        </div>
                    </div>

                    <div class="col-lg-2 mb-4 ">
                        <div class="card ProfileS_CardObjcts">
                            <img src="../../../Images/Items_Images/cotizado3.png" class="card-img-top ProfileS_ListImages">
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Admin/Admin_Products_DetailC.php">Cofres de Madera</a></h5>
                            <p class="card-text fw-bold">Producto Cotizable</p>
                            
                        </div>
                    </div>

                    <div class="col-lg-2 mb-4 ">
                        <div class="card ProfileS_CardObjcts">
                            <img src="../../../Images/Items_Images/cotizado5.png" class="card-img-top ProfileS_ListImages">
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Admin/Admin_Products_DetailC.php">Armarios de Madera</a></h5>
                            <p class="card-text fw-bold">Producto Cotizable</p>
                            
                        </div>
                    </div>

                    <div class="col-lg-2 mb-4 ">
                        <div class="card ProfileS_CardObjcts">
                            <img src="../../../Images/Items_Images/cotizado1.png" class="card-img-top ProfileS_ListImages">
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Admin/Admin_Products_DetailC.php">Muebles de Madera</a></h5>
                            <p class="card-text fw-bold">Producto Cotizable</p>
                            
                        </div>
                    </div>

                    <div class="col-lg-2 mb-4 ">
                        <div class="card ProfileS_CardObjcts">
                            <img src="../../../Images/Items_Images/cotizado8.png" class="card-img-top ProfileS_ListImages">
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Admin/Admin_Products_DetailC.php">Arreglos Metalicos</a></h5>
                            <p class="card-text fw-bold">Producto Cotizable</p>
                            
                        </div>
                    </div>
                    
                    </div>
                    <div class="d-flex justify-content-center p-2">
                        <a href="../Admin/Profile_SellerAProductsC.php" class="btn ProfileS_ViewMoreButton">Ver todos los productos
                            cotizables de @Vendedor</a>
                    </div>
                    
                    ';

        } else {
            echo '<div class="row mt-3">
    <div class="ProfileS_ContainerLists col-lg-10 offset-lg-1">
        <p class="ProfileS_Containertext">Este perfil no pertenece a un vendedor</p>
    </div>';
        }
    } else {
        echo '<div class="row mt-3">
        <div class="ProfileS_ContainerLists col-lg-10 offset-lg-1">
            <p class="ProfileS_Containertext">Este perfil no existe</p>
        </div>';
    }

    ?>

    </div>
    </div>
    </div>
    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/General/logout.js"></script>
</body>

</html>