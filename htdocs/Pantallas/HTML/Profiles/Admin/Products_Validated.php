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

    if($rol == 2){
        header('Location: /Pantallas/HTML/Profiles/Seller/My_Products.php');
    }
    else if($rol == 3){
        header('Location: /Pantallas/HTML/Profiles/Home.php'); 
    }
    else{

    }

    //Cargar los productos desde el Modelo
    $pdo = $db->connect(); // Obtiene la conexión a la base de datos
    $opcion = 1;

    $Val_IdAdmin = $_SESSION['idUsuario'];

    // Llamar al modelo y pasar la conexión como parámetro
    $productosValClass = new Products();
    $productosValClass -> setIdAdmin($Val_IdAdmin);

    $productos = $productosValClass->VerProductosValidados($pdo, $opcion);
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
                <p class="ProfileA_HeaderObjects">Mis productos validados:</p>
                <hr>
                <!-- TO DO: Anular validación?-->

                <?php 

                    if ($FindProducts) {
                        $contador = 0; // Inicializar un contador

                        foreach ($productos as $producto) {
                            // Verificar si es el primer elemento del par
                            if ($contador % 2 == 0) {
                                echo '<div class="row mb-3">';
                            }

                            echo'

                                <div class=" container-fluid col-lg-6">
                                    <div class="row">
                                        <div class="container-fluid col-lg-4">
                                            
                                        </div>
                                        <div class="container-fluid col-lg-8">
                                            <h5><a class="ProfileA_NameObject" href="../../../HTML/Profiles/Admin/Admin_Products_Detail.php">' . $producto['NombreP'] . '</a></h5>
                                            <p>Descripción: ' . $producto['DescripcionP'] . '</p>
                                            
                                            <p>Producto validado por: <a href="../../Profiles/Admin/Profile_Admin.php?username=' . $producto['NombreAdmin'] . '" class="ProfileA_AdminHL"> ' . $producto['NombreAdmin'] . ' </a></p>
            
                                        </div>
                                    </div>
                                </div>
                                                                       
                            ';

                            // Verificar si es el segundo elemento del par
                            if ($contador % 2 == 1 || $contador == count($productos) - 1) {
                                echo '</div>'; // Cerrar el div de la fila
                            }

                            $contador++;

                        }
                    }
                    else{
                    echo '<h1><strong class="d-block py-2" style="color: white;">No hay productos validados actualmente </strong></h1>';
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
    <script src="../../../JS/Admin/Products.js"></script>
    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/General/logout.js"></script>

</body>

</html>