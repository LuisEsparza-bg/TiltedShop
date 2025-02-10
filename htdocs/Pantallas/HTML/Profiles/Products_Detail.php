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

    $hasExistence = true;
    $isCotized = false;

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

$opcion = 1;
$listaid = 0;

$conn = $db->connect();

$sql = "CALL SP_VerListasDeUsuario(:username, :opcion, :listaid)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':opcion', $opcion, PDO::PARAM_INT);
    $stmt->bindParam(':listaid', $listaid, PDO::PARAM_INT);
    $stmt->execute();

    $listas = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $listas[] = $row;
    }


} else {
    echo "Error al llamar al procedimiento almacenado: ";
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />

    <!-- CSS para la Pagina-->
    <link rel="stylesheet" href="../../CSS/Profiles/Products_Detail.css">
    <link rel="stylesheet" href="../../CSS/General Styles/navbar.css">

</head>

<body>

    <!-- Nav bar -->
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

    <!-- Ventana de producto -->

    <?php if ($FindProducts == null): ?>
        <div class="container align-items-center justify-content-center">
            <h1 class="py-5 card mt-4 text-center"> No se ha encontrado el producto o ha sido eliminado </h1>
        </div>

    <?php else: ?>


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
                                        echo '<img src="data:image/jpeg;base64,'. $encodeImagen . '" style="max-width: 100%; height: 500px;" class="card-img-top object-fit-cover" alt="Imagen ' . ($index + 1) . '">';
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
                        <div class="card" style="height:flex;">
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
                                        echo $productosClass->getDescripcion() ?>
                                    </p>
                                    <hr>
                                    <!-- Precio -->

                                <?php if ($FindProducts && $Type == 1) {
                                        echo '<p class="card-text">Precio: ';
                                        echo $productosClass->getPrecio() . "$";
                                    } ?>
                                </p>

                                <?php if ($FindProducts && $Type == 1) {
                                    echo '<p class="card-text">Cantidad Disponible: ';
                                    echo $productosClass->getCantidad();
                                } ?>
                                </p>


                                <p hidden id="IDProduct" value="<?php if ($FindProducts)
                                    echo $productosClass->getid() ?>">
                                    </p>

                                    <p hidden id="idVendedor" value="<?php if ($FindProducts)
                                    echo $productosClass->getIdVendedor() ?>">
                                    </p>

                                    <p hidden id="PrecioProducto" value="<?php if ($FindProducts)
                                    echo $productosClass->getPrecio() ?>">
                                    </p>


                                    <!-- Categoría -->
                                    <p class="card-text">Categorías:
                                        <?php
                                if ($FindProducts)
                                    foreach ($categoriaDeProducto as $categoria) {
                                        echo '<label class=" badge bg-secondary p-2 mt-1 me-2" data-idCategoria="' . $categoria['ID_Categoria'] . '"   value="' . $categoria['ID_Categoria'] . '">' . $categoria['Nombre_Categoria'] . '</label>';
                                    }
                                ?>
                                </p>
                                <hr>

                                <!-- Cantidad deseada -->
                                <div class="row mb-3">

                                    <?php if ($Type == 1) {
                                        echo '
                                <label class="col-sm-4 col-form-label">Cantidad:</label>
                                <div class="col-sm-8">
                                    <input type="number" style="width: 100px;" id="AddQuantityCar" class="form-control me-4" min="1"
                                        value="1">';
                                    } ?>
                                    <?php if ($FindProducts) {
                                        if ($Quantity == 0 && $Type == 1) {
                                            echo '<h3>No hay stock de este producto</h3>';
                                            $hasExistence == false;
                                        } else if ($Quantity == 0 && $Type == 0) {
                                            echo '<h3>Este producto es cotizable</h3>';
                                            $hasExistence == false;
                                        }
                                    } ?>
                                </div>
                            </div>

                            <hr>

                            <!--Vendedor-->
                            <h3 class="text-center">Vendido por:</h3>
                            <a href="../Profiles/Profile_Seller.php?username=<?php if ($FindProducts)
                                echo $User->getUsername(); ?>" class="ProfileA_AdminHL text-center">@
                                <?php if ($FindProducts)
                                    echo $User->getUsername(); ?>
                            </a>


                            <!-- Boton para comprar, guardar en carrito y en lista-->
                            <div class="row  mt-3">
                                <div class="mt-2">
                                    <div class="">
                                        <?php
                                        if ($Quantity != 0 && $Type != 0) {
                                            echo '<button class="btn btn-primary shadow-0 btn-comprar"
                                                id="comprarBtn" data-product-id='.$productosClass->getId().'>Comprar</button>';
                                        } else if ($Type == 0) {
                                            echo '<button class="btn btn-primary shadow-0 btn-comprar m-0" id="CrearChat">Cotizar</button>';
                                        } else if ($Quantity == 0 && $Type == 1) {

                                        }

                                        if ($Type != 0) {
                                            echo '<div class="d-flex justify-content-center align-items-center mt-3 ">
                                                        <!-- Agregar al carrito -->
                                            <a href="#!" class="btn btn-light border px-2 pt-2 icon-hover"><i
                                            class="fas fa-shopping-cart fa-lg px-1" id="AddProductToCart"
                                            data-ItemCar=' . ($FindProducts ? $id : '') . '></i></a>
                                        </div>';
                                        }
                                        ?>
                                        <!-- Agregar a una lista -->
                                        <div class="dropdown justify-content-center align-items-center text-center">
                                            <a class="btn btn-light mt-3 border px-2 pt-2 mb-3  icon-hover dropdown-toggle"
                                                href="#" data-bs-toggle="dropdown">
                                                <i class="fas fa-heart fa-lg px-1"></i>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">


                                                <?php

                                                foreach ($listas as $lista) {
                                                    $nombreListas = $lista['Nombre_Lista'];
                                                    $descripcionListas = $lista['Descripcion'];
                                                    $idListas = $lista['ID_Lista'];
                                                    echo '<li><a class="dropdown-item Listas_DropDownMenu ListaSelected" data-idLista="' . $idListas . '"title="' . $descripcionListas . '" >' . htmlspecialchars($nombreListas) . '</a></li>';
                                                }



                                                ?>




                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>

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
                                        <img src="../../Images/Edit_Profile_Images/Profile_Photo.jpg"
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
                                        <img src="../../Images/Edit_Profile_Images/Profile_Photo3.png"
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
                                        <img src="../../Images/Edit_Profile_Images/Profile_Photo2.jpg"
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

    <?php endif; ?>

    <script src="../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../Librerias/popper/popper.min.js"></script>
    <script src="../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../JS/Client/ShoppingCart.js"></script>
    <script src="../../JS/Client/Messages.js"></script>
    <script src="../../JS/Client/Products_Detail.js"></script>
    <script src="../../JS/Client/AddToList.js"></script>
    <script src="../../JS/General/logout.js"></script>


</body>

</html>