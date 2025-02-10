<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php');
    exit;
}

require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Products.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\CatProd.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Categories.php');
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

    $FindProducts = null;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $opcion = 2;
        $stmt->closeCursor();
        $productosClass = new Products(
            id: $id
        );

        $categoriaDeProducto = new CatProd(
            producto: $id
        );

        $imagenes = new ImagenProducto(
            idProducto: $id
        );

        $videoProducto = new VideosProducto(
            idProducto: $id
        );

        

        $productosClass = $productosClass->VerMisProductos($conn, $username, $opcion);
        $categorias = new Categories();
        $categorias = $categoriaDeProducto->VerCategoriasProducto($conn);
        $categoriasClass = new Categories();
        $allCategories = $categoriasClass->VerCategorias($conn);
        $imagenes = $imagenes->VerTresImagenesProductos($conn);
        $videoProducto = $videoProducto->VerRutaVideo($conn);
        $pdo = null;
        $conn = null;


        if ($productosClass) {
            $string = $productosClass->getDescripcion();



            $parts = preg_split('/(?=Materiales:|Medidas:|Entrega:)/', $string);

            // Asegura que se hayan extraído tres partes
            if (count($parts) == 4) {   // Esperamos 4 partes porque la primera será un string vacío
                $materiales = str_replace("Materiales:", "", $parts[1]);
                $medidas = str_replace("Medidas:", "", $parts[2]);
                $entrega = str_replace("Entrega:", "", $parts[3]);


            } else {
                echo "Error: no se pudieron extraer tres partes del string de entrada.\n";
            }
        }
        ;

        if ($productosClass) {
            $FindProducts = 1;
        }

    } else {
        $FindProducts = null;
    }

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

    <!-- CSS para la Nav Bar-->
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsSeller/Products_Modify.css">
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

    <!-- Ventana nuevo cotizado  -->
    <?php if ($FindProducts == 1) {

        echo '
    <section class="py-5 fondo-color"> <!-- Cambie margin por padding para poder pintar el fondo-->
        <div class="container align-items-center justify-content-center">
            <div class="row ">

                <!-- Card -->
                <div class="col-sm-12">
                    <div class="card border shadow-0 fondo-card">

                        <div class="m-4">
                            <!-- Header de la card-->
                            <h2 class="card-title mb-4 text-center encabezado">Editar Producto</h2>

                            <!-- Linea de separacion del header y el body-->
                            <hr class="mb-4 linea-separacion">

                            <!-- Body de la card-->

                            <div class="card-body p-0">

                                <!-- Inicia form baja-->
                                <form>
                                    <div
                                        class="form-group d-flex align-items-center justify-content-center flex-column">
                                        <h2 class="mb-4">¿Desea eliminar este producto?:</h2>
                                        <!-- Botón para crear producto -->
                                        <div class="col-sm-4 d-flex align-items-center justify-content-center">
                                            <button id="Btn_EliminarProducto" type="button"
                                                class="btn btn-primary btn-generico mx-auto" data-idProducto="' . $productosClass->getId() . '"><i
                                                    class="fas fa-trash-alt fa-lg px-1 text-secondary"></i>Eliminar
                                                Producto</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- END form baja-->

                                <hr class="mb-4 linea-separacion">

                                <!-- Inica el forms -->
                                <form>

                                    <!-- Datos iniciales del producto-->
                                    <div class="row mb-4 ">
                                        <h2 class="mb-4 text-center">¿Desea actualizar el contenido de este producto?:
                                        </h2>
                                        <h3 class="mb-3">Información del producto:</h3>
                                        <div class="col-sm-6 mb-3">
                                            <label for="nombreProducto">Nombre del producto:</label>
                                            <input type="text" class="form-control" id="nombreProducto"
                                                name="nombreProducto" placeholder="Ejemplo: Vaso de plastico"
                                                value="' . $productosClass->getNombre() . '"required>
                                        </div>

                                        <label for="nombreProducto" hidden id="ID_producto">' . $productosClass->getId() . '</label>
                                        <label for="Validacion" hidden id="Validado">' . $productosClass->getValidacion() . '</label>

                                        <div class="col-sm-12 mb-4">
                                            <label for="materialesProducto">Materiales que puede emplear:</label>
                                            <textarea class="form-control" id="materialesProducto"
                                                name="materialesProducto"
                                                placeholder="Ejemplo: -Caoba / -Roble / -Encino / -Arce / -Cedro ..."
                                                required maxlength="100">' . $materiales . '</textarea>
                                        </div>

                                        <div class="col-sm-12 mb-4">
                                            <label for="medidasProducto">Medidas que manejara:</label>
                                            <textarea class="form-control" id="medidasProducto" name="medidasProducto"
                                                placeholder="Ejemplo: Las dimensiones del producto son: minimo 20cm x 20cm x 20cm hasta un maximo 3m x 3m x 3m ..."
                                                required maxlength="100">' . $medidas . '</textarea>
                                        </div>

                                        <div class="col-sm-12 mb-4">
                                            <label for="entregaProducto">Opciones de entrega:</label>
                                            <textarea class="form-control" id="entregaProducto" name="entregaProducto"
                                                placeholder="Ejemplo: Las opciones de flete son: -DHL / -FEDEX / -Estafeta ..."
                                                required maxlength="100">' . $entrega . '</textarea>
                                        </div>

                                    </div>

                                    <hr class="mb-4 linea-separacion">

                                    <!-- Categorias -->
                                    <div class="row mb-4">
                                        <h3 class="mb-4">Categorias a las que pertence:</h3>

                                        <div class=" col-sm-12 col-lg-4">';

                                        if ($allCategories) {
                                            echo '<select id="categorias" class="form-select mb-2 icon-hover">';
                                            foreach ($allCategories as $categoria) {
                                                echo '<option value="' . $categoria['ID_Categoria'] . '"
                                                                                    data-idCategoria="' . $categoria['ID_Categoria'] . '" title="' . $categoria['Descripcion_Categoria'] . '">' .
                                                    $categoria['Nombre_Categoria'] . '</option>';
                                            }
                                            echo '</select>';
                                        } else {
                                            echo "No se encontraron categorías.";
                                        }

                                        echo '
                                            <div class="d-flex justify-content-center align-item-center">
                                                <button id="agregarCategoria"
                                                    class="btn btn-success mb-2 me-2" type="button" >Agregar</button>
                                                <button id="quitarCategoria" type="button" class="btn btn-danger  mb-2">Quitar</button>
                                            </div>


                                        </div>

                                    
                                        <div id="categoriasSeleccionadas"
                                            class="cuadro-categorias col-sm-12 col-lg-8 mb-2" id="categoriasProductoCuadro">
                                            <!-- Aquí se mostrarán las categorías seleccionadas -->';
                                    foreach ($categorias as $categoria) {
                                        echo '<label class="badge bg-primary me-2" data-idCategoria="' . $categoria['ID_Categoria'] . '"   value="' . $categoria['ID_Categoria'] . '">' . $categoria['Nombre_Categoria'] . '</label>';
                                        // Puedes personalizar la apariencia de las etiquetas según tus necesidades.
                                    }
                                    echo '
                                        </div>

                                    </div>

                                    <hr class="mb-4 linea-separacion">';

                                    echo'<!-- Imagenes y videos -->
                                    <div class="row mb-4">
                                        <h3 class="mb-4">Imagenes y videos:</h3>
                                        <!-- d-flex flex-column align-items-center si borro esto de las imagenes queda mas chico el hueco -->
                                        ';
                                        $numimage = 0;
                                        foreach ($imagenes as $imagen) {
                                            $imagenUsuario = $imagen['Imagen'];
                                            $encodeImagen = base64_encode($imagenUsuario);
                                            $numimage = $numimage + 1;
                                            echo '<div class="col-sm-12 col-md-4 mb-3 d-flex flex-column align-items-center">
                                            <label for="imagen1">
                                            <h4> Imagen '.$numimage.': </h4>
                                            </label>
                                            <input type="file" class="form-control-file" id="imagen'.$numimage.'" name="imagen'.$numimage.'"
                                            accept="image/*">
                                            <img id="imagenPreview'.$numimage.'" class="img-thumbnail mt-2"
                                                src="data:image/jpeg;base64,' . $encodeImagen . '"
                                                alt="Vista previa de la imagen 1"
                                                style="max-width: 100%; height: 300px;" style="display: none;">
                                                <button id="Cambiarfoto'.$numimage.'" data-idfotoproducto= '. $imagen['ID_Imagenes_Producto'].' type="button" class="btn btn-danger  mt-2  mb-2">Cambiar</button>
                                                </div>';

                                        }

                                       echo' <!-- Campo para cargar el video -->
                                       <div class="col-md-12 mt-4 d-flex flex-column align-items-center">
                                                <label for="video">
                                                    <div class="row mb-4 ">
                                                <label for="nombreProducto">Link del video:</label>
                                                <input type="text" class="form-control" id="videoRuta"
                                                    name="nombreProducto" placeholder="Ejemplo: youtube.com/video" value='.$videoProducto.' data-idproducto='.$productosClass->getId().' required>
                                                    <button id="ChangeVideo" data-idproducto= '. $productosClass->getId().' type="button" class="btn btn-danger  mt-2  mb-2">Cambiar</button>
                                           </label>

                                          </div>


                                    </div>


                                    <hr class="mb-4 linea-separacion">

                                    <!-- Botón para crear producto -->
                                    <div class="d-flex align-item-center justify-content-center">
                                        <button type="button"
                                            class="btn btn-primary btn-comprar" id="Btn_ModificarProducto" >Actualizar Cambios</button>
                                    </div>

                                </form>

                            </div>

                        </div>
                    </div>
                </div>

               
                <!-- End Card -->

            </div>
        </div>
    </section>';
    } else {
        echo '<section class="py-5 fondo-color"> <!-- Cambie margin por padding para poder pintar el fondo-->
    <div class="container align-items-center justify-content-center">
        <div class="row ">

            <!-- Card -->
            <div class="col-sm-12">
                <div class="card border shadow-0 fondo-card">

                    <div class="m-4">
                        <!-- Header de la card-->
                        <h2 class="card-title mb-4 text-center encabezado">No tienes permiso para acceder a este producto</h2>

                        </div>

                    </div>
                </div>
            </div>

           
            <!-- End Card -->

        </div>
    </div>
</section>';
    }

    ?>




    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/General/logout.js"></script>
    <script src="../../../JS/Seller/Modificacion_ProductoC.js"></script>


</body>

</html>