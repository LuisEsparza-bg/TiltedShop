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

    if ($rol == 1) {
        header('Location: /Pantallas/HTML/Profiles/Admin/Products_TBD.php');
    } else if ($rol == 2) {
        header('Location: /Pantallas/HTML/Profiles/Seller/My_Products.php');
    } else {

    }

    $stmt->closeCursor();
    $productosClassRecientes = new Products();
    $productosRecientes = $productosClassRecientes->VerProductosHome($conn, 1);

    $productosTop3 = new Products();
    $productosTop3 = $productosTop3->VerProductosHome($conn, 2);

    $productosClassRecientesC = new Products();
    $productosClassRecientesC = $productosClassRecientesC->VerProductosHome($conn, 3);

    $productosMasVendidos = new Products();
    $productosMasVendidos = $productosMasVendidos->VerProductosHome($conn, 4);


    $pdo = null;
    $conn = null;

} else {
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

    <!-- Libreria para iconos de boostrap Font Awesome-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="../../CSS/Profiles/Home.css">
    <link rel="stylesheet" href="../../CSS/General Styles/navbar.css">

</head>

<body>

    <!-- Nav bar -->
    <div class="row Navbar_Container">
        <div class="col-lg-3 col-sm-4">
            <a href="../../HTML/Profiles/Home.php"><img src="../../Images/Tilted_Shop_Logo.png"
                    class="Navbar_Logo" alt="Tilted Shop Icon"></a>

        </div>

        <div class="col-lg-6 col-sm-4 ">
            <div class="container Navbar_SearchBar">
                <div class="input-group">
                    <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
                        aria-describedby="search-addon" id="searchInput" /> <!-- Agrege un ID-->
                    <a href="#"
                        class="btn btn-primary Navbar_SearchButton" id="searchButton">Buscar</a>
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
    <!-- Nav bar end-->


    <!-- Carrusel de imagenes -->
    <!-- data-bs-ride="carousel" -->

    <div id="carouselCaptions" class="carousel slide color-carrusel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">

            <div class="carousel-item active">
                <div class="container-fluid">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img src="../../Images/Items_Images/eva1.png" class="img-fluid" alt="imagen 1">
                        </div>

                        <div class="col-lg-6 mb-0 d-flex align-items-center info-banner">
                            <div class="text-align-left align-self-center">
                                <h1 class="h1 text-success"><b>Nuevo</b> kit</h1>
                                <h3 class="h2">Muy pronto estara a la venta!</h3>
                                <p>
                                    Nos complace anunciar que pronto estara a la venta nuestro kit de piezas
                                    para PC con tematica del Eva 01
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="carousel-item">
                <div class="container-fluid">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img src="../../Images/Items_Images/eva2.png" class="img-fluid" alt="imagen 1">
                        </div>

                        <div class="col-lg-6 mb-0 d-flex align-items-center info-banner">
                            <div class="text-align-left align-self-center">
                                <h1 class="h1 text-success"><b>Nuevo</b> kit</h1>
                                <h3 class="h2">Muy pronto estara a la venta!</h3>
                                <p>
                                    Nos complace anunciar que pronto estara a la venta nuestro kit de piezas
                                    para PC con tematica del Eva 02
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="carousel-item">
                <div class="container-fluid">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img src="../../Images/Items_Images/utiles4.png" class="img-fluid" alt="imagen 1">
                        </div>

                        <div class="col-lg-6 mb-0 d-flex align-items-center info-banner">
                            <div class="text-align-left align-self-center">
                                <h1 class="h1 text-success"><b>Nuevo</b> kit</h1>
                                <h3 class="h2">Muy pronto estara a la venta!</h3>
                                <p>
                                    Nos complace anunciar que pronto estara a la venta nuestro kit de piezas
                                    para utiles escolares
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="carousel-controls-container d-flex justify-content-between">

            <button class="carousel-control-prev  w-auto ps-3" type="button" data-bs-target="#carouselCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next  w-auto ps-3" type="button" data-bs-target="#carouselCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>

        </div>

    </div>

    <!-- End carrusel -->


    <!-- Elementos recientes -->
    <!-- antes la class del section era bg-dark -->
    <section class="bg-light">
        <div class="container py-5">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="">Productos populares!</h1>
                    <p class="">
                        Disfruta de los productos con mejor calificación de nuestra tienda!
                    </p>
                </div>
            </div>

            <div class="row">

            <?php if ($productosTop3)
                    foreach ($productosTop3 as $producto):
                        $imagen = $producto['Imagen'];
                        $encodeImagen = base64_encode($imagen);
                        ?>
                        <div class="col-12 col-md-4 mb-4">
                            <div class="card h-100">
                                <a href="../../HTML/Profiles/Products_Detail.php?id=<?php echo $producto['ID_Producto']; ?>">
                                    <div class="container equal-image-container">
                                        <img src="data:image/jpeg;base64,<?php echo $encodeImagen ?>"
                                            class="card-img-top img-fluid equal-image" alt="...">
                                    </div>
                                </a>
                                <div class="card-body">
                                    <ul class="list-unstyled d-flex justify-content-between">
                                        <li>
                                            <?php // for ($i = 0; $i < $producto['Valoracion']; $i++) : ?>
                                            <i class="text-warning fa fa-star"></i>
                                            <?php // endfor; ?>
                                            <?php // for ($i = 0; $i < (5 - $producto['Valoracion']); $i++) : ?>
                                            <i class="text-muted fa fa-star"></i>
                                            <?php // endfor; ?>
                                        </li>
                                        <li class="text-muted text-right">
                                            <?php echo'Precio: $'; echo number_format($producto['Precio_Unitario'], 2); ?>
                                        </li>
                                        <br>
                                        <li class="text-muted text-right">
                                            <?php echo'Promedio Califiación: '; echo number_format($producto['PromedioCalificacion'], 2); ?>
                                        </li>
                                        <li class="text-muted text-right">
                                        </li>
                                    </ul>
                                    <a href="../../HTML/Profiles/Products_Detail.php?id=<?php echo $producto['ID_Producto'];?>" class="h2 text-decoration-none text-dark">
                                        <?php echo $producto['Nombre_Producto']; ?>
                                    </a>
                                    <p class="card-text">
                                        <?php echo $producto['Descripcion_Producto']; ?>
                                    </p>
                                    <p class="text-muted">Reviews (
                                        <?php // echo $producto['Reviews']; ?>)
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

            </div>
        </div>
    </section>
    <!-- End recientes -->

    <!-- Elementos recientes -->
    <!-- antes la class del section era bg-dark -->
    <section class="fondo-menu-inicio">
        <div class="container py-5">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="titulos-menu">Productos más vendidos</h1>
                    <p class="parrafos-menu">
                        Disfruta de los productos mas vendidos en nuestro catalogo,
                        no dejes pasar la oportunidad de comprar lo que esta en boca
                        de todos aqui en "Tilted Shop"
                    </p>
                </div>
            </div>

            <div class="row">

                <?php if ($productosMasVendidos)
                    foreach ($productosMasVendidos as $producto):
                        $imagen = $producto['Imagen'];
                        $encodeImagen = base64_encode($imagen);
                        ?>
                        <div class="col-12 col-md-4 mb-4">
                            <div class="card h-100">
                                <a href="../../HTML/Profiles/Products_Detail.php?id=<?php echo $producto['ID_Producto']; ?>">
                                    <div class="container equal-image-container">
                                        <img src="data:image/jpeg;base64,<?php echo $encodeImagen ?>"
                                            class="card-img-top img-fluid equal-image" alt="...">
                                    </div>
                                </a>
                                <div class="card-body">
                                    <ul class="list-unstyled d-flex justify-content-between">
                                        <li class="text-muted text-right text-center"> <b>Ventas totales del producto:
                                            <?php echo number_format($producto['CantidadVendida'], 2); ?> </b>
                                        </li>
                                    </ul>
                                    <a href="../../HTML/Profiles/Products_Detail.php?id=<?php echo $producto['ID_Producto'];?>" class="h2 text-center text-decoration-none text-dark">
                                        <?php echo $producto['Nombre_Producto']; ?>
                                    </a>
                                    <p class="card-text">
                                    </p>
                                   
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

            </div>
        </div>

        </div>
        </div>
    </section>



    <section class="bg-light">
        <div class="container py-5">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="">Productos nuevos cotizables!</h1>
                    <p class="">
                        Haz una cotización en nuestros ultimos productos cotizables! 
                    </p>
                </div>
            </div>

            <div class="row">

            <?php if ($productosClassRecientesC)
                    foreach ($productosClassRecientesC as $producto):
                        $imagen = $producto['Imagen'];
                        $encodeImagen = base64_encode($imagen);
                        ?>
                        <div class="col-12 col-md-4 mb-4">
                            <div class="card h-100">
                                <a href="../../HTML/Profiles/Products_Detail.php?id=<?php echo $producto['ID_Producto']; ?>">
                                    <div class="container equal-image-container">
                                        <img src="data:image/jpeg;base64,<?php echo $encodeImagen ?>"
                                            class="card-img-top img-fluid equal-image" alt="...">
                                    </div>
                                </a>
                                <div class="card-body">
                                    <ul class="list-unstyled d-flex justify-content-between">
                                        <li>
                                            <?php // for ($i = 0; $i < $producto['Valoracion']; $i++) : ?>
                                            <i class="text-warning fa fa-star"></i>
                                            <?php // endfor; ?>
                                            <?php // for ($i = 0; $i < (5 - $producto['Valoracion']); $i++) : ?>
                                            <i class="text-muted fa fa-star"></i>
                                            <?php // endfor; ?>
                                        </li>
                                        <br>
                                        
                                        <li class="text-muted text-right">
                                        </li>
                                    </ul>
                                    <a href="../../HTML/Profiles/Products_Detail.php?id=<?php echo $producto['ID_Producto'];?>" class="h2 text-decoration-none text-dark">
                                        <?php echo $producto['Nombre_Producto']; ?>
                                    </a>
                                    <p class="card-text">
                                        <?php echo $producto['Descripcion_Producto']; ?>
                                    </p>
                                    <p class="text-muted">Reviews (
                                        <?php // echo $producto['Reviews']; ?>)
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

            </div>
        </div>
    </section>



    <!-- End recientes -->

    <!-- Scripts -->

    <script src="../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../Librerias/popper/popper.min.js"></script>
    <script src="../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../JS/General/logout.js"></script>
    <script src="../../JS/Client/Search_Bar_Client.js"></script>
</body>

<!-- Footer -->

<!-- End footer-->


</html>