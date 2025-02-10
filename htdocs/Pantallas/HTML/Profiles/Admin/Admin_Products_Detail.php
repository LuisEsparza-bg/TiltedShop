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
        echo "Error al llamar al procedimiento almacenado: " ;
    }

    if($rol == 2){
        header('Location: /Pantallas/HTML/Profiles/Seller/My_Products.php');
    }
    else if($rol == 3){
        header('Location: /Pantallas/HTML/Profiles/Home.php'); 
    }
    else{

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
    <!-- CSS para la Pagina-->
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsClient/edit_profile.css">
    <link rel="stylesheet" href="../../../CSS/Profiles/Products_Detail.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">

</head>

<body>

    <!-- Nav bar -->
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
                                <!-- Imágenes -->
                                <div class="carousel-item active">
                                    <img src="../../../Images/Items_Images/mcotizado1.png" style="max-width: 100%; height: 500px;"
                                        class="card-img-top object-fit-cover" alt="Imagen 1">
                                </div>
                                <div class="carousel-item">
                                    <img src="../../../Images/Items_Images/mcotizado3.png" style="max-width: 100%; height: 500px;"
                                        class="card-img-top object-fit-cover" alt="Imagen 2">
                                </div>
                                <div class="carousel-item">
                                    <img src="../../../Images/Items_Images/mcotizado2.png" style="max-width: 100%; height: 500px;"
                                        class="card-img-top object-fit-cover" alt="Imagen 3">
                                </div>
                                <!-- Video -->
                                <div class="carousel-item">
                                    <video controls style="max-width: 100%; height: 500px;">
                                        <source src="Images/Gato.mp4" type="video/mp4">
                                        Tu navegador no admite el elemento de video.
                                    </video>
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
                            <h2 class="titulo-descripcion">Mesas de Madera</h2>
                            <hr>
                        </div>

                        <!-- Inicia card de descripcion -->
                        <div class="card-body d-flex flex-column justify-content-between">
                            <!-- Si solo fuera el card body no se ocupa todo el espacio-->

                            <!-- Descripción -->
                            <p class="card-text">Descripción: Mesas de Madera, realizados en París.</p>
                            <hr>

                            <p class="card-text">Precio: 450.00$</p>

                            <!-- Cantidad disponible -->
                            <p class="card-text">Cantidad Disponible: 50</p>

                            <!-- Categoría -->
                            <p class="card-text">Categoría: Madera, Muebles</p>
                            <hr>

                            <p class="card-text"> Vendido por: <a href="../Admin/Profile_SellerA.php?username=usuario " class="ProfileA_AdminHL">@Vendedor</a></p>

                            

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
                                        <img src="../../../Images/Edit_Profile_Images/Profile_Photo4.png" alt="Foto de Persona 1"
                                            class="img-fluid rounded-circle">
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
                                        <img src="../../../Images/Edit_Profile_Images/Profile_Photo3.png" alt="Foto de Persona 2"
                                            class="img-fluid rounded-circle">
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
                                        <img src="../../../Images/Edit_Profile_Images/Profile_Photo2.jpg" alt="Foto de Persona 3"
                                            class="img-fluid rounded-circle">
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