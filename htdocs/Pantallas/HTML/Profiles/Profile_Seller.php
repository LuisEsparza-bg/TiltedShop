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
    } else if ($rol == 2) {
        header('Location: /Pantallas/HTML/Profiles/Seller/My_Products.php');
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


    if ($rol == 2 && $result){
    $stmt->closeCursor();
    $IDUser = new Users();
    $idActiva = $IDUser->getIdByUsername($conn, $username2);

    $productosNC = new Products();
    $productosNC = $productosNC->VerProductosVendedor($conn, $idActiva, 1);

    $productosC = new Products();
    $productosC = $productosC->VerProductosVendedor($conn, $idActiva, 2);
    };

    $conn = null;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../CSS/Profiles/profile_seller.css">
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

    <?php
    if ($FindOne != null) {
        if ($rol == 2) {
            echo '
    <div class="row">
        <div class="ProfileS_Container col-lg-10 offset-lg-1 p-0">
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
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Profiles/Products_Detail.php?id='.$productoNC['ID_Producto'].'">' . $productoNC['Nombre_Producto'] . '</a></h5>
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
                            <h5 class="card-title"><a class="ProfileS_TextObjects" href="../Profiles/Products_Detail.php?id='.$productoC['ID_Producto'].'">' . $productoC['Nombre_Producto'] . '</a></h5>
                            <!-- Aquí puedes agregar el precio del producto -->
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
    <script src="../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../Librerias/popper/popper.min.js"></script>
    <script src="../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../JS/General/logout.js"></script>
</body>
</html>
