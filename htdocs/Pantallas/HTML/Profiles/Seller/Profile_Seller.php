<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php');
    exit;
}

require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Users.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Products.php');

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

    $conn = null;
}

if (isset($_GET['username'])) {
    $username2 = $_GET['username'];

    $db = new DB();
    $conn = $db->connect();

    $sql = "CALL SP_ProfileData(:username)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(':username', $username2, PDO::PARAM_STR);
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

    $stmt->closeCursor();

    if ($rol == 2 && $result){
    $IDUser = new Users();
    $idActiva = $IDUser->getIdByUsername($conn, $username2);

    $productosNC = new Products();
    $productosNC = $productosNC->VerProductosVendedor($conn, $idActiva, 1);

    $productosC = new Products();
    $productosC = $productosC->VerProductosVendedor($conn, $idActiva, 2);
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

    <?php
    if ($FindOne != null) {
        if ($rol == 2) {
            echo '
            <div class="row">
            <div class="ProfileS_Container col-lg-10 offset-lg-1">
            <img  src="data:image/jpeg;base64,' . $encodeImagen . '" class="ProfileS_ContainerPhoto" alt="Cart icon">
            <div class="d-flex flex-column">
                <span class="ProfileS_Containertext">@' . $username2 . '</span>
                <span class="ProfileS_ContainertextSmall">Usuario desde: ' . $fechaCreacion . '</span>
                <span class="ProfileS_ContainertextSmall">Nombre completo: ' . $nombreUsuario . " " . $apellidoPaterno . " " . $apellidoMaterno . '</span>
            </div>
            </div>
            </div>

    <div class="mt-3 col-lg-10 offset-lg-1">
        <div class=" row ProfileS_ContainerLists p-0 ">
            <div class="container">
                <p class="ProfileS_HeaderObjects">Productos agregados recientemente:</p>
                <div class="row text-lg-center">';

                if ($productosNC)
                foreach ($productosNC as $productoNC) {
                    $imagenProducto = $productoNC['Imagen'];
                    $encodeImagen = base64_encode($imagenProducto);
                    echo '
                    <div class="col-lg-2 mb-4">
                        <div class="card ProfileS_CardObjcts">
                            <!-- Aquí puedes agregar la imagen a partir de la información del producto -->
                            <img src="data:image/jpeg;base64,' . $encodeImagen . ' " class="card-img-top ProfileS_ListImages" />
                            <!-- Aquí puedes agregar el nombre del producto -->
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../../Profiles/Seller/Products_DetailS.php?id='.$productoNC['ID_Producto'].'">' . $productoNC['Nombre_Producto'] . '</a></h5>
                            <!-- Aquí puedes agregar el precio del producto -->
                            <p class="card-text">Precio: $' . $productoNC['Precio_Unitario'] . '</p>
                        </div>
                    </div>';
                }
                echo'<hr>';
                echo'</div>
            </div>
        </div>

        

        <div class="row ProfileS_ContainerLists mb-4">
            <div class="container">
                <p class="ProfileS_HeaderObjects">Productos cotizables agregados recientemente:</p>
                <div class="row text-lg-center">';

                if ($productosC)
                foreach ($productosC as $productoC) {
                    $imagenProducto = $productoC['Imagen'];
                    $encodeImagen = base64_encode($imagenProducto);
                    echo '
                    <div class="col-lg-2 mb-4">
                        <div class="card ProfileS_CardObjcts">
                            <!-- Aquí puedes agregar la imagen a partir de la información del producto -->
                            <img src="data:image/jpeg;base64,' . $encodeImagen . ' " class="card-img-top ProfileS_ListImages" />
                            <!-- Aquí puedes agregar el nombre del producto -->
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../../Profiles/Seller/Products_DetailS.php?id='.$productoC['ID_Producto'].'">' . $productoC['Nombre_Producto'] . '</a></h5>
                            <!-- Aquí puedes agregar el precio del producto -->
                            <p class="card-text">Precio: $' . $productoC['Precio_Unitario'] . '</p>
                        </div>
                    </div>';
                }
                    
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
    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/General/logout.js"></script>
</body>

</html>