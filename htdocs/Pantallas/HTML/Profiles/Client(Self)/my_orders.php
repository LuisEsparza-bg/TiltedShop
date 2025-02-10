<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php');
    exit;
}

require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Categories.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Users.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Compra.php');

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
    $categoriasClass = new Categories();
    $categorias = $categoriasClass->VerCategorias($conn);

    $stmt->closeCursor();
    $IDUser = new Users();
    $idActiva = $IDUser->getIdByUsername($conn, $username);

    $stmt->closeCursor();
    $compras = new Compra();
    $compras = $compras->MisCompras($conn, $idActiva);

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

    <!-- CSS para la Nav Bar-->
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsClient/my_orders.css">
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsSeller/profile_SellerReports.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/footer.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/background.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/Pagination.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/background.css">

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
                    <small> Hola <b>
                            <?php echo $username; ?>
                        </b> </small>
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

    <!-- sidebar y content -->
    <section class="pt-4"> <!-- Este margin es para separar el navbar del contenido-->
        <div class="container">

            <div class="row">

                <!-- Begin sidebar -->
                <div class="col-lg-3 col-sm-4 ">
                    <!-- Agrege el col sm 4 para que al hacer pequeña la pantalla tenga 4 espacios de col y el content tenga 8-->

                    <!-- Collapsible wrapper -->
                    <div class="card d-block mb-5" id="navbarSupportedContent">
                        <!-- Si quiero que el boton de arriba funcione a posterior necesito que esta barra se colapse con collapse y borre el d-lg-block que fuerza el bloque en pantallas lg para que el collpase entre en accion en sm y md-->

                        <div class="card-header mb-4 card-Primer-Titulo">
                            Filtros
                        </div>

                        <div class="card-body m-0 p-0">
                            <!-- Esto no es necesario par aque se mueste la card completa en el card padre
                            <div class="row">
                                <div class="container-fluid">
                                -->

                            <!-- Filtro de Fechas -->
                            <div class="card mb-3">
                                <!-- Encabezado de filtro -->
                                <div class="card-header card-titulo">
                                    Rango de Fechas
                                </div>

                                <!-- Cuerpo del filtro -->
                                <div class="card-body">

                                    <!-- Checked checkbox -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="activarFiltroFecha" />
                                        <label class="form-check-label" for="Activar_Rango">Activar</label>
                                    </div>
                                    <!-- Inputs de fechas -->
                                    <div class="form-group mt-3">
                                        <label for="fechaInicial">Fecha inicial:</label>
                                        <input type="date" class="form-control" id="fechaInicial" name="fechaInicial">
                                    </div>

                                    <div class="form-group">
                                        <label for="fechaFinal">Fecha final:</label>
                                        <input type="date" class="form-control" id="fechaFinal" name="fechaFinal">
                                    </div>

                                </div>
                                <!-- End Cuerpo-->

                            </div>

                            <!--- 
                                </div>
                            </div>
                            -->

                            <!-- Filtro de categorias -->
                            <div class="card mb-3">
                                <!-- Encabezado de filtro -->
                                <div class="card-header card-titulo">
                                    Categorías
                                </div>
                                <!-- Cuerpo del filtro -->
                                <div class="card-body">

                                    <!-- Botones de radio -->

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="filtroCategoria"
                                            id="unaCategoria" checked value="una">
                                        <label class="form-check-label" for="unaCategoria">
                                            1 sola categoría
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="filtroCategoria"
                                            id="todasCategorias" value="todas">
                                        <label class="form-check-label" for="todasCategorias">
                                            Todas las categorías
                                        </label>
                                    </div>
                                    <!-- Combobox de categorías -->
                                    <div class="form-group mt-3">
                                        <label for="seleccionarCategoria">Seleccionar categoría:</label>

                                        <?php
                                        if ($categorias) {
                                            echo '<select class="form-select" id="seleccionarCategoria" name="seleccionarCategoria">';
                                            if (isset($categorias))
                                                foreach ($categorias as $categoria) {
                                                    echo '<option value="' . $categoria['ID_Categoria'] . '"
                                                        data-idCategoria="' . $categoria['ID_Categoria'] . '" title="' . $categoria['Descripcion_Categoria'] . '">' .
                                                        $categoria['Nombre_Categoria'] . '</option>';
                                                }
                                        } else {
                                            echo "No se encontraron categorías.";
                                        }
                                        ?>
                                        </select>
                                    </div>

                                </div>

                            </div>

                            <!--Filtro de busqueda -->


                            <button
                                class="btn btn-primary shadow-0 btn-detalles mb-3 d-flex align-item-center justify-content-center mx-auto"
                                id="botonFiltro" type="button">Filtrar</button>

                        </div>

                    </div>
                </div>
                <!-- End sidebar -->




                <!-- Content -->
                <div class="col-lg-9 col-sm-8"> <!-- Aqui agrege el sm-8 para que funcione con la columna de filtros-->
                    <!-- Contenedor para Consultas -->
                    <div class="container-fluid PayProductsContainer">
                        <div class="row">
                            <div class="col-lg-8">
                                <h3 class="">Mis pedidos:</h3>
                            </div>
                            <!-- Agregar la barra de búsqueda a la derecha -->
                            <div class="col-lg-4 mt-3">

                            </div>
                        </div>


                        <?php
                        if ($compras) {
                            $compraActual = null;
                            foreach ($compras as $compra) {
                                $compraTemporal = $compra['ID_Compra'];
                                if ($compra['ID_Compra'] != $compraActual) {
                                    // Cierra el div del pedido anterior (si existe)
                                    if ($compraActual !== null) {
                                        echo "</div>";
                                    }
                                    $notEqual = false;
                                } else {
                                    $notEqual = true;
                                }

                                $compraActual = $compra['ID_Compra'];

                                            


                                $imagen =  $compra['Imagen'];
                                $encodeImagen = base64_encode($imagen);

                                $fecha = new DateTime($compra['Fecha_Hora']);
                                $fechaFormateada = $fecha->format('j \d\e F \d\e Y, g:i');
                                

                                if ($notEqual == false) {
                                    echo "<div class='pedido' data-idfecha={$compra['Fecha_Hora']}id='pedido_{$compra['ID_Compra']}'>";
                                    echo "<div class='row MyOrders_InfoOrder mb-3' data-idcompra={$compra['ID_Compra']} id={$compra['ID_Compra']} data-idfecha={$compra['Fecha_Hora']}>";
                                    echo "<div class='col-6'>";
                                    echo "<p class='MyOrder_Text'> Pedido realizado: <b>{$fechaFormateada}</b></p>";
                                    echo "</div>";
                                    echo "<div class='col-6'>";
                                    echo "<p class='MyOrder_Text'> Total: <b>{$compra['Total']}$</b></p>";
                                    echo "</div>";
                                    echo "</div>";
                                }

                                echo "<div class='MyOrder_ContainerProducts m-0 p-0' id='compra_{$compra['ID_Compra']}'>";
                                echo "<div class='producto' data-categorias='{$compra['Categorias']}'>";
                                echo "<div class='row'>";
                                echo "<div class='container-fluid  MyOrder_ContainerImages mt-3'>";
                                echo '<img src="data:image/jpeg;base64,' . $encodeImagen . '" alt="" class="img-fluid MyOrder_Images align-middle">';
                                echo "</div>";
                                echo "<div class='container-fluid col-lg-8'>";
                                echo "<h5><a class='MyOrder_Product' href='../../../HTML/Profiles/Products_Detail.php?id={$compra['ID_Producto']}'>{$compra['Nombre_Producto']}</a></h5>";
                                echo "<p>Precio del producto: <b>{$compra['Precio_Unitario']}$</b> </p>";
                                echo "<p>Categorías: <b>{$compra['Categorias']}</b> </p>";
                                echo "<p>Calificación del producto: <b>" . round($compra['Calificacion_Promedio']) . "</b></p>";
                                echo "</div>";
                                echo "</div>";
                                echo "<hr>";
                                echo "</div>";
                                echo "</div>";
                            }
                            // Cierra el último div del pedido
                            if ($compraActual !== null) {
                                echo "</div>";
                            }
                        } else {
                            echo "No se encontraron compras.";
                        }
                        ?>



                    </div>
                </div>
            </div>

        </div>
        </div>
    </section>

    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/Client/Filter.js"></script>
    <script src="../../../JS/General/logout.js"></script>

</body>

</html>