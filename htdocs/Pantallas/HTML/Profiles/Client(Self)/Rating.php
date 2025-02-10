<?php
session_start(); 

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php'); 
    exit; 
}

require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Compra.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Valoracion.php');

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

    // Obtener el texto de búsqueda de la URL
    $searchCompraId = isset($_GET['compraId']) ? $_GET['compraId'] : '';
    $searchProductId = isset($_GET['productId']) ? $_GET['productId'] : '';


    //Revisar si puede valorar el producto o no
    if($searchCompraId != '' && $searchProductId != ''){
        $pdo = $db->connect(); // Obtiene la conexión a la base de datos
        
        // Llamar al modelo y pasar la conexión como parámetro
        $verificacion = new Valoracion();
        
        //Cargar los datos en el modelo
        $username = $_SESSION['idUsuario'];
        $verificacion->setIdUsuarioCliente($username);
        //Cargar el id del producto
        $verificacion->setIdProducto($searchProductId);

        $canOrNot = $verificacion->canReview($pdo);
        $pdo = null;
        
        $FindProducts = null;

        if ($canOrNot == 1) {
            echo '<script>';
            echo 'alert("Ya has valorado este producto anteriormente. Serás redirigido a la página de detalles.");';
            echo 'window.location.href = "/Pantallas/HTML/Profiles/Products_Detail.php?idProduct=' . $searchProductId . '";';
            echo '</script>';
        }
    }


    //Cargar los productos buscados de forma basica desde el Modelo
    if($searchCompraId != '' && $searchProductId != ''){
        $pdo = $db->connect(); // Obtiene la conexión a la base de datos
        
        // Llamar al modelo y pasar la conexión como parámetro
        $productosData = new Compra();
        
        $productos = $productosData->obtenerDatosCompra($pdo, $searchCompraId, $searchProductId);
        $pdo = null;
        
        $FindProducts = null;

        if ($productos) {
            $FindProducts = 1;
        }
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
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    
    <!-- CSS para la Nav Bar-->
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsClient/Rating.css">

</head>
<body>
    
      <!-- Nav bar -->
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

    <!-- Nav bar end-->

    <!-- Valoraciones -->
    <section class="py-5 fondo-color"> <!-- Cambie margin por padding para poder pintar el fondo-->
      <div class="container">
        <div class="row ">

            <!-- Card -->
            <div class="col-lg-12 col-sm-12">
                <div class="card border shadow-0">
                
                    <div class="m-4">
                        <!-- Header de la card-->
                        <h2 class="card-title mb-4">Valoración</h2>
                        
                        <!-- Linea de separacion del header y el body-->
                        <hr class="mb-4 linea-separacion">
                        
                            <!-- Body de la card-->
                            <!-- Datos del producto -->
                            <div class="card-body p-0"> 
                            <div class="row gy-3 mb-4 "> <!-- Checar para que funciona el gy-3 -->

                                <!-- Imagen-->
                                <div class="col-xl-4  col-lg-4 col-md-12  d-flex justify-content-center"> <!-- TODO: Modificar los tamaños de columnas md, lg y sm-->
                                    <div class="bg-image rounded me-md-3 mb-3 mb-md-0"> <!-- A posterior usar la clase hover-zoom, ripple y ripple-surface-->
                                    <img src="../../../Images/Items_Images/game5.png" class="imagenes-carrito" />
                                    </div>
                                </div>

                                <!-- Nombres -->
                                <div class="col-xl-4 col-lg-4 col-sm-12 text-center pe-3"> 
                                    <?php
                                    echo '
                                        <!-- Nombre del producto -->
                                        <a href="#" class="nav-link">' . $productos[0]['nombreP'] . '</a> <!-- Nav link sirve para que un texto funcione como link sin lucir como uno-->
                                        <!-- Nombre del vendedor -->
                                        <p class="text-muted">@' . $productos[0]['vendedor'] . '</p>
                                    ';
                                    ?>
                                </div>
                                
                                <!-- Input de cantidad y texto-->
                                <div class="col-xl-4 col-lg-4 col-sm-12 col-md-12 d-flex flex-row text-nowrap justify-content-sm-center">
                                    <div class="align-item-center justify-content-center">
                                        <p class="mb-0"> Cantidad: </p>
                                        <?php
                                        echo'
                                            <input type="number" class="form-control custom-input" min="1" value="' . $productos[0]['cantidad'] . '" readonly>
                                        ';
                                        ?>
                                    </div>
                                </div>

                            </div>

                            <hr class="mb-4 linea-separacion">

                            <form id="form1">
                                <div class="row mb-4">
                                    <!-- Puntuacion estrellas -->
                                    <div class="container mt-0">
                                        <h3>Califica este producto:</h3>
                                        <div class="rating">
                                            <span class="star" data-rating="1"><i class="fa fa-star"></i></span>
                                            <span class="star" data-rating="2"><i class="fa fa-star"></i></span>
                                            <span class="star" data-rating="3"><i class="fa fa-star"></i></span>
                                            <span class="star" data-rating="4"><i class="fa fa-star"></i></span>
                                            <span class="star" data-rating="5"><i class="fa fa-star"></i></span>
                                        </div>
                                        <p class="mt-2">Tu calificación: <span id="rating-value">0</span></p>
                                    </div>
                                </div>

                                <hr class="mb-4 linea-separacion">

                                <div class="row mb-4"> 
                                    <!-- Reseñas de texto -->
                                    <h3>Deja tu reseña</h3>
                                    
                                    <!-- Espacio para escribir la reseña -->
                                    <div class="form-group">
                                        <label for="reseña" class="mb-2"> Agradeceríamos su apoyo dejando una reseña sobre el producto adquirido, para futuros compradores.</label>
                                        <textarea id="reseña" name="reseña" class="form-control" rows="5" placeholder="Escribe tu reseña aquí..." required></textarea>
                                    </div>
                                </div>

                                <!-- Botón para enviar la reseña -->
                                <div class="d-flex align-item-center justify-content-center"> 
                                    <?php
                                    echo '
                                        <button type="submit" class="btn btn-primary btn-enviar" data-product-id="' . $productos[0]['idProducto'] . '">Enviar Valoración</button>
                                    ';
                                    ?>
                                </div>

                            </form>

                        </div>
                    
                    </div>             
                </div>
             </div>
             <!-- End Card -->
          
        </div>
      </div>
    </section>
    
    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/Client/Valoracion.js"></script>
    <script src="../../../JS/Client/Rating.js"></script>
    <script src="../../../JS/General/logout.js"></script>

    
</body>
</html>