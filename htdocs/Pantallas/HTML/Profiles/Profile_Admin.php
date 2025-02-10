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


    if ($rol == 1) {
        header('Location: /Pantallas/HTML/Profiles/Admin/Products_TBD.php');
    } else if ($rol == 2) {
        header('Location: /Pantallas/HTML/Profiles/Seller/My_Products.php');
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
    <link rel="stylesheet" href="../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../CSS/Profiles/profile_admin.css">
    <link rel="stylesheet" href="../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../CSS/General Styles/footer.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/background.css">

</head>

<body>
    <!-- NAVBAR START -->
    <div class="row Navbar_Container">
        <div class="col-lg-3 col-sm-4">
            <a href="../../HTML/Profiles/Home.php"><img src="../../Images/Tilted_Shop_Logo.png" class="Navbar_Logo"
                    alt="Tilted Shop Icon"></a>

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
                    <small> Hola <b>
                            <?php echo $username; ?>
                        </b> </small>
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
                    <li><a class="dropdown-item Navbar_DropdownButton" id="logout">Cerrar sesión</a> </li>
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
        if ($rol == 1) {
            echo '

            <div class="row">
            <div class="ProfileA_Container col-lg-10 offset-lg-1 p-0">
            <img  src="data:image/jpeg;base64,' . $encodeImagen . '" class="ProfileA_ContainerPhoto" alt="Cart icon">
                <div class="d-flex flex-column">
                <span class="ProfileA_Containertext">@' . $username . '</span>
                <span class="ProfileA_ContainertextSmall">Usuario desde: ' . $fechaCreacion . '</span>
                <span class="ProfileA_ContainertextSmall">Nombre completo: ' . $nombreUsuario . " " . $apellidoPaterno . " " . $apellidoMaterno . '</span>
                </div>
            </div>
        </div>

    <div class="mt-3 col-lg-10 offset-lg-1">
        <div class="row ProfileA_ContainerLists p-0 ">
            <div class="container">
                <p class="ProfileA_HeaderObjects">Productos verificados de @Administrador:</p>

                <div class="row">
                    <div class=" container-fluid col-lg-6">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/cotizado5.png" alt=""
                                    class="img-fluid ProfileA_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileA_NameObject" href="../Profiles/Products_DetailC.php">Armarios de Madera</a></h5>
                                <p>Descripción: Armarios de Madera, realizados en México. Se puede modificar el color, el número de cajones, etc.</p>
                                <p>Fecha de verificación: 02/02/2023</p>
                                <p>Verificado por: <b> ' . $nombreUsuario . " " . $apellidoPaterno . " " . $apellidoMaterno . '</b></p>
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-6">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/cotizado3.png" alt=""
                                    class="img-fluid ProfileA_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileA_NameObject" href="../Profiles/Products_DetailC.php">Cofres de Madera</a></h5>
                                <p>Descripción: Cofres de Madera, realizados en París. Favor de cotizar el objeto para
                                    más información</p>
                                <p>Fecha de verificación: 02/02/2022</p>
                                <p>Verificado por: <b> ' . $nombreUsuario . " " . $apellidoPaterno . " " . $apellidoMaterno . '</b></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class=" container-fluid col-lg-6">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/cotizado8.png" alt=""
                                    class="img-fluid ProfileA_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileA_NameObject" href="../Profiles/Products_DetailC.php">Artilugios de Metal</a></h5>
                                <p>Descripción: Artilugios de metal elaborados artesanalmente, tenemos desde discos metalicos hasta esferas artesanales</p>
                                <p>Fecha de verificación: 01/05/2021</p>
                                <p>Verificado por: <b> ' . $nombreUsuario . " " . $apellidoPaterno . " " . $apellidoMaterno . '</b></p>
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-6">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/cotizado6.png" alt=""
                                    class="img-fluid ProfileA_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileA_NameObject" href="../Profiles/Products_DetailC.php">Mesas para cocina acero inoxidable</a></h5>
                                <div class="">
                                    <p>Descripción: Mesas de cocina, hechas de acero inoxidable, se puede cambiar las medidas, el acabado de metal, etc. </p>
                                    <p>Fecha de verificación: 02/02/2019</p>
                                    <p>Verificado por: <b> ' . $nombreUsuario . " " . $apellidoPaterno . " " . $apellidoMaterno . '</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class=" container-fluid col-lg-6">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/cotizado2.png" alt=""
                                    class="img-fluid ProfileA_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileA_NameObject" href="../Profiles/Products_DetailC.php">Armarios de madera</a></h5>
                                <p>Descripción: Armarios de madera, se puede pedir de una medida especifica y color, favor de comunicarse con el vendedor para cotizar</p>
                                <p>Fecha de verificación: 01/01/2019</p>
                                <p>Verificado por: <b> ' . $nombreUsuario . " " . $apellidoPaterno . " " . $apellidoMaterno . '</b></p>
                            </div>
                        </div>
                    </div>

                    <div class=" container-fluid col-lg-6">
                        <div class="row">
                            <div class="container-fluid col-lg-4">
                                <img src="../../Images/Items_Images/cotizado4.png" alt=""
                                    class="img-fluid ProfileA_ContainerImage">
                            </div>
                            <div class="container-fluid col-lg-8">
                                <h5><a class="ProfileA_NameObject" href="../Profiles/Products_DetailC.php">Muebles estilo japones</a></h5>
                                <div class="">
                                    <p>Descripción: Muebles estilo japones para cotizar, ideales para poner sus zapatos, se pueden cambiar de color y tamaño</p>
                                    <p>Fecha de verificación: 02/02/2001</p>
                                    <p>Verificado por: <b> ' . $nombreUsuario . " " . $apellidoPaterno . " " . $apellidoMaterno . '</b></p>
                                </div>
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
    </div>';
        } else {
            echo '<div class="row mt-3">
    <div class="ProfileA_ContainerLists col-lg-10 offset-lg-1">
        <p class="ProfileA_Containertext">Este perfil no pertenece a un administrador</p>
    </div>';
        }
    } else {
        echo '<div class="row mt-3">
    <div class="ProfileA_ContainerLists col-lg-10 offset-lg-1">
        <p class="ProfileA_Containertext">Este perfil no existe</p>
    </div>';
    }

    ?>

    <script src="../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../Librerias/popper/popper.min.js"></script>
    <script src="../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../JS/General/logout.js"></script>
</body>

</html>