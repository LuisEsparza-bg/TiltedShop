<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php');
    exit;
}

require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Categories.php');



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
    }


    $stmt->closeCursor();
    $categoriasClass = new Categories();
    $categorias = $categoriasClass->VerCategorias($conn);
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
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />

    <!-- CSS para la Pagina-->
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsSeller/Products_New.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/footer.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/background.css">

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

    <!-- Ventana de pago  -->
    <section class="py-5 fondo-color"> <!-- Cambie margin por padding para poder pintar el fondo-->
        <div class="container align-items-center justify-content-center">
            <div class="row ">

                <!-- Card -->
                <div class="col-sm-12">
                    <div class="card border shadow-0 fondo-card">

                        <div class="m-4">
                            <!-- Header de la card-->
                            <h2 class="card-title mb-4 text-center encabezado">Nuevo Producto</h2>

                            <!-- Linea de separacion del header y el body-->
                            <hr class="mb-4 linea-separacion">

                            <!-- Body de la card-->

                            <div class="card-body p-0">

                                <!-- Inica el forms -->
                                <form>

                                    <!-- Datos iniciales del producto-->
                                    <div class="row mb-4 ">
                                        <h3 class="mb-4">Información del producto:</h3>
                                        <div class="col-sm-6 mb-4">
                                            <label for="nombreProducto">Nombre del producto:</label>
                                            <input type="text" class="form-control" id="nombreProducto"
                                                name="nombreProducto" placeholder="Ejemplo: Vaso de plastico" required>
                                        </div>

                                        <div class="col-sm-12 mb-4">
                                            <label for="descripcionProducto">Descripción del producto:</label>
                                            <textarea class="form-control" id="descripcionProducto"
                                                name="descripcionProducto"
                                                placeholder="Ejemplo: Vaso con diseño ergonomico, hecho de plasticos duraderos..."
                                                required></textarea>
                                        </div>

                                        <div class="col-sm-6 mb-4 ">
                                            <label for="precioProducto">Precio del producto (MXN):</label>
                                            <input type="number" class="form-control campos-compartidos"
                                                id="precioProducto" name="precioProducto" placeholder="Ejemplo: 10.99"
                                                required>
                                        </div>

                                        <div class="col-sm-6 mb-4 ">
                                            <label for="inventarioProducto">Inventario del producto:</label>
                                            <input type="number" class="form-control campos-compartidos"
                                                id="inventarioProducto" name="inventarioProducto"
                                                placeholder="Ejemplo: 5000" required>
                                        </div>
                                    </div>

                                    <hr class="mb-4 linea-separacion">

                                    <!-- Categorias -->
                                    <div class="row mb-4">
                                        <h3 class="mb-4">Categorias a las que pertence:</h3>

                                        <div class=" col-sm-12 col-lg-4">
                                            <?php
                                            if ($categorias) {
                                                echo '<select id="categorias" class="form-select mb-2 icon-hover">';
                                                foreach ($categorias as $categoria) {
                                                    echo '<option value="' . $categoria['ID_Categoria'] . '"
                                                        data-idCategoria="' . $categoria['ID_Categoria'] . '" title="' . $categoria['Descripcion_Categoria'] . '">' .
                                                        $categoria['Nombre_Categoria'] . '</option>';
                                                }
                                                echo '</select>';
                                            } else {
                                                echo "No se encontraron categorías.";
                                            }
                                            ?>

                                            <div class="d-flex justify-content-center align-item-center">
                                                <button id="agregarCategoria"
                                                    class="btn btn-success mb-2 me-2">Agregar</button>
                                                <button id="quitarCategoria"
                                                    class="btn btn-danger mb-2 me-2">Quitar</button>
                                                <button id="crearCategoria" class="btn btn-primary mb-2"
                                                    data-bs-toggle="modal" data-bs-target="#crearCategoriaModal">Crear
                                                    Categoría</button>

                                            </div>
                                            <small style="color:#900404"><b>Es necesario reiniciar la pagina de
                                                    creación de
                                                    producto para poder ver las nuevas categorías creadas.</b></small>
                                        </div>

                                        <div class="modal fade" id="crearCategoriaModal" tabindex="-1"
                                            aria-labelledby="crearCategoriaModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="crearCategoriaModalLabel">Crear
                                                            Nueva Categoría</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="nuevaCategoria" class="form-label">Nombre de la
                                                            Categoría:</label>
                                                        <input type="text" class="form-control" id="nuevaCategoria"
                                                            minlength="4" maxlength="35" required>

                                                        <label for="nuevaDescripcion" class="form-label">Descripción de
                                                            la
                                                            Categoría:</label>
                                                        <input type="text" class="form-control" id="nuevaDescripcion"
                                                            minlength="15" maxlength="300" required>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="button"
                                                            class="btn btn-success GuardarNuevaCategoria"
                                                            id="GuardarNuevaCategoria">Guardar</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div id="categoriasSeleccionadas"
                                            class="cuadro-categorias col-sm-12 col-lg-8 mb-2">
                                            <!-- Aquí se mostrarán las categorías seleccionadas -->
                                            <!-- Etiquetas de ejemplo -->
                                        </div>

                                    </div>

                                    <hr class="mb-4 linea-separacion">

                                    <!-- Imagenes y videos -->
                                    <div class="row mb-4">
                                        <h3 class="mb-4">Imagenes y videos:</h3>

                                        <!-- Campo para cargar la primera imagen -->
                                        <!-- d-flex flex-column align-items-center si borro esto de las imagenes queda mas chico el hueco -->
                                        <div class="col-sm-12 col-md-4 mb-3 d-flex flex-column align-items-center">
                                            <label for="imagen1">
                                                <h4> Imagen 1: </h4>
                                            </label>
                                            <input type="file" class="form-control-file" id="imagen1" name="imagen1"
                                                accept="image/*">
                                            <img id="imagenPreview1" class="img-thumbnail mt-2"
                                                src=""
                                                alt="Vista previa de la imagen 1"
                                                style="max-width: 100%; height: 300px;" style="display: none;">
                                        </div>

                                        <!-- Campo para cargar la segunda imagen -->
                                        <div class="col-sm-12 col-md-4 mb-3 d-flex flex-column align-items-center">
                                            <label for="imagen2">
                                                <h4> Imagen 2: </h4>
                                            </label>
                                            <input type="file" class="form-control-file" id="imagen2" name="imagen2"
                                                accept="image/*">
                                            <img id="imagenPreview2" class="img-thumbnail mt-2"
                                                src=""
                                                alt="Vista previa de la imagen 2"
                                                style="max-width: 100%; height: 300px;" style="display: none;">
                                        </div>

                                        <!-- Campo para cargar la tercera imagen -->
                                        <div class="col-sm-12 col-md-4 mb-3 d-flex flex-column align-items-center">
                                            <label for="imagen3">
                                                <h4> Imagen 3: </h4>
                                            </label>
                                            <input type="file" class="form-control-file" id="imagen3" name="imagen3"
                                                accept="image/*">
                                            <img id="imagenPreview3" class="img-thumbnail mt-2"
                                                src=""
                                                alt="Vista previa de la imagen 3"
                                                style="max-width: 100%; height: 300px;" style="display: none;">
                                        </div>

                                        <!-- Campo para cargar el video -->
                                        <div class="col-md-12 mt-4 d-flex flex-column align-items-center">
                                            <label for="video">
                                                <div class="row mb-4 ">
                                            <label for="nombreProducto">Link del video:</label>
                                            <input type="text" class="form-control" id="videoRuta"
                                                name="nombreProducto" placeholder="Ejemplo: youtube.com/video" required>
                                        </div>

                                            </label>
                                            <!-- <input type="file" class="form-control-file" id="video" name="video"
                                                accept="video/*">
                                            <video id="videoPreview" controls class="mt-2"
                                                style="max-width: 100%; height: 300px;" style="display: none;"></video>
                                                -->
                                        </div>


                                    </div>


                                    <hr class="mb-4 linea-separacion">

                                    <!-- Botón para crear producto -->
                                    <div class="d-flex align-item-center justify-content-center">
                                        <button type="submit" class="btn btn-primary btn-comprar" id="Btn_CrearProducto" >Crear
                                            Producto</button>
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
    <script src="../../../JS/General/logout.js"></script>
    <script src="../../../JS/Seller/Creacion_Nuevo_Producto.js"></script>

</body>

</html>