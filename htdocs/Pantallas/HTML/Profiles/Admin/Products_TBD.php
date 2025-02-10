<?php
session_start(); 

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php'); 
    exit; 
}

require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Products.php');

$username = null;
$FindOne = null;

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    // $id = $_SESSION['idUsuario'];

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

    if($rol == 2){
        header('Location: /Pantallas/HTML/Profiles/Seller/My_Products.php');
    }
    else if($rol == 3){
        header('Location: /Pantallas/HTML/Profiles/Home.php'); 
    }

    // Imprimir el script JavaScript con el ID de usuario
    //  echo '<script>var idUsuario = ' . json_encode($id) . ';</script>';

    //Cargar los productos desde el Modelo
        $pdo = $db->connect(); // Obtiene la conexión a la base de datos
        $opcion = 1;

        // Llamar al modelo y pasar la conexión como parámetro
        $productosValClass = new Products();
        $productos = $productosValClass->VerProductosPorValidar($pdo, $opcion);
        $pdo = null;
        
        $FindProducts = null;

        if ($productos) {
          $FindProducts = 1;
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
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsAdmin/products_tbd.css">
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

    <div class="mb-3 col-lg-10 offset-lg-1">
        <div class="row ProfileA_ContainerLists p-0 ">
            <div class="container">
                <p class="ProfileA_HeaderObjects">Productos por validar:</p>
                
                <hr>


                <?php 

                    if ($FindProducts) {
                    foreach ($productos as $producto) {
                        echo'

                            <div class="row  mt-3 mb-3">

                        

                                <div class=" container-fluid col-6">
                                    <div class="row">
                                        <div class="container-fluid col-lg-4">
                                            <img src="../../../Images/Items_Images/cotizado3.png" alt=""
                                                class="img-fluid ProfileA_ContainerImage">
                                        </div>
                                        <div class="container-fluid col-lg-8">
                                            <h5><a class="ProfileA_NameObject" href="../../../HTML/Profiles/Admin/Admin_Products_DetailC.php">' . $producto['NombreP'] . '</a></h5>
                                            <p>Descripción: ' . $producto['DescripcionP'] . ' </p>

                                            <button href="#" onclick="ValidateProduct()"
                                                class="btn Profile_ValidateProductBtn mt-0" id="validateBtn" data-product-id=' . $producto['IdP'] . ' >Validar</button>
                                                
                                            <button href="#" onclick="DevalidateProduct()"
                                                class="btn Profile_DevalidateProductBtn mt-0" id="devalidateBtn" data-product-id=' . $producto['IdP'] . ' >No validar</button>

                                        </div>
                                    </div>
                                </div>



                            </div>
                        
                        ';
                    }
                    }
                    else{
                    echo '<h1><strong class="d-block py-2" style="color: white;">No hay Productos por agregar </strong></h1>';
                    }
                
                ?>


                <!-- Pagination -->
                <!--
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
                -->
                <!-- Pagination -->
            </div>
        </div>
    </div>

    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../JS/Admin/Products.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/General/logout.js"></script>
</body>

</html>