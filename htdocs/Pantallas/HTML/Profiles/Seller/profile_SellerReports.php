<?php
session_start(); 

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php'); 
    exit; 
}

require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\ReporteVentas.php');
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
        echo "Error al llamar al procedimiento almacenado: " ;
    }

    if($rol == 1){
    header('Location: /Pantallas/HTML/Profiles/Admin/Products_TBD.php'); 
    }
    else if($rol == 3){
        header('Location: /Pantallas/HTML/Profiles/Home.php'); 
    }
    else{

    }


    //Variable para ver saber si encontro mas de 0 productos
    $FindProducts = null;
    $FindProducts2 = null;

    //Usuario
    $idUsuarioVendedor = $_SESSION['idUsuario'];


    // Obtener los parámetros de filtro de la URL
    //Fechas
    //Bandera 
    $fechaFlag = isset($_GET['fechaFlag']) ? $_GET['fechaFlag'] : '0';
    //Fecha1
    $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
    //Fecha2
    $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;

    if($fechaInicio == 'null' || $fechaFin == 'null'){
        $fechaInicio = null;
        $fechaFin = null;
    }


    //Categorias
    //Bandera
    $categoriaFlag = isset($_GET['categoriaFlag']) ? $_GET['categoriaFlag'] : '0';
    //id
    $idCategoria = isset($_GET['idCategoria']) ? $_GET['idCategoria'] : '0';


    // Se aplicaron filtros o no se pasan todos los parametros las flags diran que hacer
    if($idUsuarioVendedor != ''){

        //HACER LA PRIMERA LLAMADA PARA LA PRIMER TABLA
        $pdo = $db->connect(); // Obtiene la conexión a la base de datos
        
        // Llamar al modelo y pasar la conexión como parámetro
        $reporteDetallado = new Reporte();

        $resultDetallado = $reporteDetallado->reporteVentasDetallada($pdo, $idUsuarioVendedor, $fechaFlag, $fechaInicio, $fechaFin, $categoriaFlag, $idCategoria);
    
        
        if ($resultDetallado) {
            $FindProducts = 1;
        }

        //echo'RESULTADO :  ';
        //var_dump($resultDetallado);

        //HACER LA SEGUNDA LLAMADA PAR LA ULTIMA TABLA

        // Llamar al modelo y pasar la conexión como parámetro
        $reporteAgrupado = new Reporte();
        
        $resultAgrupado = $reporteAgrupado->reporteVentasAgrupada($pdo, $idUsuarioVendedor, $fechaFlag, $fechaInicio, $fechaFin, $categoriaFlag, $idCategoria);
       
        
        if ($resultAgrupado) {
            $FindProducts2 = 1;
        }

        //echo'RESULTADO 2:  ';
        //var_dump($resultAgrupado);


        $stmt->closeCursor();
        $categoriasClass = new Categories();
        $categorias = $categoriasClass->VerCategorias($conn);

        //echo'RESULTADO CAT:  ';
        //var_dump($categorias);

        //CERRAR LA CONEXION
        $pdo = null;
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
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsSeller/profile_SellerReports.css">
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
                <small> Hola <b> <?php echo $username; ?> </b> </small>
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

    <!-- sidebar y content -->
     <section class="pt-4"> <!-- Este margin es para separar el navbar del contenido-->
        <div class="container">
            
            <div class="row">

                <!-- Begin sidebar -->
                <div class="col-lg-3 col-sm-4 ">  <!-- Agrege el col sm 4 para que al hacer pequeña la pantalla tenga 4 espacios de col y el content tenga 8-->
                
                    <!-- Collapsible wrapper -->
                    <div class="card d-block mb-5" id="navbarSupportedContent"> <!-- Si quiero que el boton de arriba funcione a posterior necesito que esta barra se colapse con collapse y borre el d-lg-block que fuerza el bloque en pantallas lg para que el collpase entre en accion en sm y md-->
                        
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
                                            <input class="form-check-input" type="checkbox" value="" id="Activar_Rango" />
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
                                        <input class="form-check-input" type="radio" name="filtroCategoria" id="ningunaCategoria" value="ninguna" checked>
                                        <label class="form-check-label" for="ningunaCategoria">
                                            Ninguna categoría
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="filtroCategoria" id="unaCategoria" value="una">
                                        <label class="form-check-label" for="unaCategoria">
                                            1 sola categoría
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="filtroCategoria" id="todasCategorias" value="todas">
                                        <label class="form-check-label" for="todasCategorias">
                                            Todas las categorías
                                        </label>
                                    </div>

                                    
                                    <!-- Combobox de categorías -->
                                    <div class="form-group mt-3">
                                        <label for="seleccionarCategoria">Seleccionar categoría:</label>
                                        <select class="form-select" id="seleccionarCategoria" name="seleccionarCategoria">


                                            <!-- Aquí debes incluir las opciones de categorías -->
                                            <?php
                                                if($categorias){
                                                    foreach ($categorias as $categoria) {
                                                        echo '<option value="' . $categoria['ID_Categoria'] . '"
                                                            data-idCategoria="' . $categoria['ID_Categoria'] . '" title="' . $categoria['Descripcion_Categoria'] . '">' .
                                                            $categoria['Nombre_Categoria'] . '</option>';
                                                    }
                                                }else{
                                                    echo "No se encontraron categorías.";
                                                }
                                            ?>
                                        </select>
                              

                                    </div>

                                    
                                </div>

                            </div>

                            <button class="btn btn-primary shadow-0 btn-detalles mb-3 d-flex align-item-center justify-content-center mx-auto" type="button" id="searchInput">Filtrar</button>

                        </div>

                    </div>
                </div>
                <!-- End sidebar -->




                <!-- Content -->
                <div class="col-lg-9 col-sm-8"> <!-- Aqui agrege el sm-8 para que funcione con la columna de filtros-->
                    
                    <!-- Contenedor para Consultas -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="card col-md-12 p-0">

                                <div class="card-header text-center resultados-header">
                                    <h2>Resultados de la consulta de ventas</h2>
                                </div>

                                <!-- Consulta Detallada -->
                                <div class="card m-4">
                                    <div class="card-header">
                                        <h4>Consulta Detallada</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered columna-header">
                                                <thead>
                                                    <tr>
                                                        <th>Fecha y Hora de la Venta</th>
                                                        <th>Categoría</th>
                                                        <th>Producto</th>
                                                        <th>Calificación</th>
                                                        <th>Precio</th>
                                                        <th>Existencia Actual</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if ($FindProducts) {

                                                            foreach ($resultDetallado as $row) {
                                                                echo '<tr>';
                                                                echo '<td>' . $row['FechaHora'] . '</td>';
                                                                echo '<td>' . $row['nombreCategorias'] . '</td>';
                                                                echo '<td>' . $row['nombreProducto'] . '</td>';
                                                                echo '<td>' . $row['PromedioCalificacion'] . '</td>';
                                                                echo '<td>' . $row['Precio'] . '</td>';
                                                                echo '<td>' . $row['Existencia_Actual'] . '</td>';
                                                                echo '</tr>';
                                                            }

                                                        } else {
                                                            echo '<tr><td colspan="6">No se encontraron resultados detallados.</td></tr>';
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Consulta Agrupada -->
                                <div class="card m-4 mt-0">
                                    <div class="card-header">
                                        <h4>Consulta Agrupada</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered columna-header">
                                                <thead>
                                                    <tr>
                                                        <th>Mes/Año de la Venta</th>
                                                        <th>Categoría</th>
                                                        <th>Ventas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if ($FindProducts) {
                                                            foreach ($resultAgrupado as $row) {
                                                                echo '<tr>';
                                                                echo '<td>' . $row['MesAnoVenta'] . '</td>';
                                                                echo '<td>' . $row['Categoria'] . '</td>';
                                                                echo '<td>' . $row['VentasUnidades'] . '</td>';
                                                                echo '</tr>';
                                                            }
                                                        } else {
                                                            echo '<tr><td colspan="6">No se encontraron resultados detallados.</td></tr>';
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <!--<script src="../../../JS/Seller/Creacion_Nuevo_Producto.js"></script>-->
    <script src="../../../JS/Seller/Reportes_Ventas_Filtros.js"></script>
    <script src="../../../JS/General/logout.js"></script>
    
</body>
</html>