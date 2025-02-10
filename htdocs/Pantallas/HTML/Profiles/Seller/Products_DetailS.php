<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php');
    exit;
}

require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Users.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Products.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Categories.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\CatProd.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Imagenes_Productos.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Videos.php');

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


} else {
    $FindOne = null;
}

$FindProducts = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt->closeCursor();

    $productosClass = new Products(
        id: $id
    );

    // VER CATEGORIAS DE PRODUCTO, EN CASO DE SER NECESARIO IMPRIMIRLAS EN LA DESCRIPCION DEL PRODUCTO.
    $categoriaDeProducto = new CatProd(
        producto: $id
    );

    $imagenesProducto = new ImagenProducto(
        idProducto: $id
    );

    
    $videoProducto = new VideosProducto(
        idProducto: $id
    );


    $opcion = 1;
    // Ver el detalle del producto

    $productosClass = $productosClass->VerProducto($conn, $opcion);

    $opcion = 2;
    $User = new Users();
    // ver el nombre del vendedor
    if ($productosClass) {
        $User = $productosClass->VerProducto($conn, $opcion);
        $categoriaDeProducto = $categoriaDeProducto->VerCategoriasProducto($conn);
        $categoriasClass = new Categories();
        $imagenesProducto = $imagenesProducto->VerTresImagenesProductos($conn);
        $videoProducto = $videoProducto->VerRutaVideo($conn);
        $Quantity = $productosClass->getCantidad();
        $Type = $productosClass->getTipo();
    }

    $pdo = null;
    $conn = null;


    if ($productosClass) {
        $FindProducts = 1;
    }

} else {
    $FindProducts = null;
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
    <!-- CSS para la Pagina-->
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsClient/edit_profile.css">
    <link rel="stylesheet" href="../../../CSS/Profiles/Products_Detail.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">

</head>

<body>

    <!-- Nav bar -->
    <div class="row Navbar_Container">
        <div class="col-lg-3 col-sm-1 ">
            <a href="../../../HTML/Profiles/Seller/My_Products.php">
                <img src="../../../Images/Tilted_Shop_Logo.png" class="Navbar_Logo" alt="Tilted Shop Icon">
            </a>
        </div>

        <div class="col-lg-1 col-sm-4 offset-sm-6 offset-lg-6">
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
    <!-- Nav bar end-->

    <!-- Ventana de producto -->
    <section class="py-5 fondo-color"> <!-- Cambie margin por padding para poder pintar el fondo-->
        <div class="container align-items-center justify-content-center">
            <div class="row ">

                <!-- Carrusel con video -->
                <div class="col-lg-8">
                    <div class="card align-items-center py-3" style="height:550px;">

                        <div id="miCarrusel" class="carousel">
                            <ol class="carousel-indicators ">
                                <li data-bs-target="#miCarrusel" data-bs-slide-to="0" class="active"></li>
                                <li data-bs-target="#miCarrusel" data-bs-slide-to="1"></li>
                                <li data-bs-target="#miCarrusel" data-bs-slide-to="2"></li>
                                <li data-bs-target="#miCarrusel" data-bs-slide-to="3"></li>
                            </ol>
                            <!-- Contenido del carrusel -->
                            <div class="carousel-inner">
                                <?php

                                foreach ($imagenesProducto as $index => $imagen) {
                                    $imagenProducto = $imagen['Imagen'];
                                    $encodeImagen = base64_encode($imagenProducto);
                                    $activeClass = $index === 0 ? 'active' : '';
                                    echo '<div class="carousel-item ' . $activeClass . '">';
                                    echo '<img src="data:image/jpeg;base64,' . $encodeImagen . '" style="max-width: 100%; height: 500px;" class="card-img-top object-fit-cover" alt="Imagen ' . ($index + 1) . '">';
                                    echo '</div>';
                                }

                                ?>
                                 <!-- Video -->
                            <div class="carousel-item">
                                    <iframe width="500" height="500" src="<?php echo$videoProducto?>"></iframe>
                                     </div>
                            </div>

                        </div>
                        <a class="carousel-control-prev bg-dark" href="#miCarrusel" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </a>
                        <a class="carousel-control-next bg-dark" href="#miCarrusel" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </a>

                    </div>
                </div>
                <!-- END Carrusel con video -->

                <!-- Descripcion Producto -->
                <div class="col-lg-4">
                    <div class="card" style="height:550px;">
                        <!-- Nombre del producto -->

                        <div class="card-title text-center mb-0">
                            <h2 class="titulo-descripcion">
                                <?php if ($FindProducts)
                                    echo $productosClass->getNombre() ?>
                                </h2>
                                <hr>
                            </div>

                            <!-- Inicia card de descripcion -->
                            <div class="card-body d-flex flex-column justify-content-between">
                                <!-- Si solo fuera el card body no se ocupa todo el espacio-->

                                <!-- Descripción -->
                                <p class="card-text">
                                <?php if ($FindProducts)
                                    echo 'Descripcion: ' . $productosClass->getDescripcion() ?>
                                </p>
                                <hr>

                                <p class="card-text">
                                <?php if ($FindProducts && $productosClass->getTipo() == 1)
                                    echo 'Precio: ' .$productosClass->getPrecio(). '$';
                                else{
                                    echo 'Este producto es cotizable';
                                }?>
                                </p>

                                <!-- Cantidad disponible -->
                                <p class="card-text"><?php if ($FindProducts && $productosClass->getTipo() == 1)
                                    echo 'Cantidad: ' .$productosClass->getCantidad();
                                else{
                                }?></p>

                                <!-- Categoría -->
                                <?php
                                if ($FindProducts)
                                    foreach ($categoriaDeProducto as $categoria) {
                                        echo '<label class=" badge bg-secondary p-2 mt-1 me-2" data-idCategoria="' . $categoria['ID_Categoria'] . '"   value="' . $categoria['ID_Categoria'] . '">' . $categoria['Nombre_Categoria'] . '</label>';
                                    }
                                ?>
                                <hr>
                                <p class="card-text"> Vendido por: <a href="../Seller/Profile_Seller.php?username=<?php if ($FindProducts)
                                    echo $User->getUsername(); ?>"class="ProfileA_AdminHL text-center">
                                <?php if ($FindProducts)echo $User->getUsername(); ?>
                                </a></p>



                        </div>
                        <!-- END card de descripcion -->
                    </div>
                </div>
                <!-- END Descripcion Producto -->



                <!-- Card -->
                <div class="col-lg-12 col-sm-12">
                    <div class="card border shadow-0 mt-4">

                        <div class="m-4">
                            <!-- Header de la card-->
                            <h2 class="card-title mb-4">Opiniones</h2>

                            <!-- Linea de separacion del header y el body-->
                            <hr class="mb-4 linea-separacion">

                            <!-- Body de la card-->
                            <div class="card-body p-0">

                                <div class="row mb-4">
                                    <!-- Puntuacion estrellas -->
                                    <div class="container mt-0">
                                        <h3>Calificación de este producto: 4</h3>
                                        <div class="rating">
                                            <span class="star text-warning" data-rating="1"><i
                                                    class="fa fa-star"></i></span>
                                            <span class="star text-warning" data-rating="2"><i
                                                    class="fa fa-star"></i></span>
                                            <span class="star text-warning" data-rating="3"><i
                                                    class="fa fa-star"></i></span>
                                            <span class="star text-warning" data-rating="4"><i
                                                    class="fa fa-star"></i></span>
                                            <span class="star" data-rating="5"><i class="fa fa-star"></i></span>
                                        </div>

                                    </div>
                                </div>

                                <hr class="mb-4 linea-separacion">

                                <div class="row mb-4">
                                    <!-- Reseñas de texto -->
                                    <h3>Comentarios</h3>
                                </div>

                                <!-- Primera reseña -->
                                <div class="row mb-4">
                                    <div class="col-md-2">
                                        <img src="../../../Images/Edit_Profile_Images/Profile_Photo4.png"
                                            alt="Foto de Persona 1" class="img-fluid rounded-circle">
                                    </div>
                                    <div class="col-md-10">
                                        <h5>María Rodríguez Pérez</h5>
                                        <p>Tengo que decir que estoy impresionado con mi Amazon Alexa.
                                            La calidad del sonido es asombrosa, y es genial poder controlar mi música y
                                            dispositivos
                                            domésticos con solo pedirlo. Definitivamente, ha mejorado la comodidad en mi
                                            hogar.</p>
                                    </div>
                                </div>

                                <!-- Segunda reseña -->
                                <div class="row mb-4">
                                    <div class="col-md-2">
                                        <img src="../../../Images/Edit_Profile_Images/Profile_Photo3.png"
                                            alt="Foto de Persona 2" class="img-fluid rounded-circle">
                                    </div>
                                    <div class="col-md-10">
                                        <h5>David Smith Johnson</h5>
                                        <p>¡El Amazon Alexa ha cambiado la forma en que hacemos las cosas en casa! Nos
                                            encanta cómo
                                            responde a nuestras preguntas y nos ayuda a mantenernos organizados. Además,
                                            la integración
                                            con otros servicios como Amazon Prime es increíble. ¡Estamos pensando en
                                            comprar otro para
                                            otra habitación!</p>
                                    </div>
                                </div>

                                <!-- Tercera reseña -->
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="../../../Images/Edit_Profile_Images/Profile_Photo2.jpg"
                                            alt="Foto de Persona 3" class="img-fluid rounded-circle">
                                    </div>
                                    <div class="col-md-10">
                                        <h5>Ana García López</h5>
                                        <p>Compré el Amazon Alexa con grandes expectativas, pero me ha decepcionado. A
                                            menudo tiene
                                            dificultades para entender mis comandos y, en ocasiones, responde a
                                            preguntas completamente
                                            diferentes. Además, la calidad del sonido no es tan impresionante como
                                            esperaba. Creo que hay
                                            margen para mejorar en futuras actualizaciones.</p>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- End Card -->



            </div>

        </div>
    </section>

    <script src="../../../JS/Admin/Products.js"></script>
    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/General/logout.js"></script>

</body>

</html>