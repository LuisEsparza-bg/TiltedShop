<?php
session_start(); 

require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Products.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Categories.php');

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php'); 
    exit; 
}

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
    
    $opcion = 1;
    $stmt->closeCursor();
    $productosClass = new Products();
    $productos = $productosClass->VerMisProductos($conn, $username, $opcion);


    $stmt->closeCursor();
    $categoriasClass = new Categories();
    $categorias = $categoriasClass->VerCategorias($conn);


    $pdo = null;
    $conn = null;

    $FindProducts = null;

    if ($productos) {
      $FindProducts = 1;
      }
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
    
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsSeller/My_Products.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/footer.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/background.css">
</head>
<body class="fondo-color">
    
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

            <!-- sidebar -->
            <div class="col-lg-3 col-sm-4 ">  <!-- Agrege el col sm 4 para que al hacer pequeña la pantalla tenga 4 espacios de col y el content tenga 8-->
              
              <!-- Collapsible wrapper -->
              <div class="card d-block mb-5" id="navbarSupportedContent"> <!-- Si quiero que el boton de arriba funcione a posterior necesito que esta barra se colapse con collapse y borre el d-lg-block que fuerza el bloque en pantallas lg para que el collpase entre en accion en sm y md-->
                <div class="card-header mb-4 card-Primer-Titulo"> 
                  Filtro
                </div>

                <!-- Filtro de Mayor/Menor -->
                <div class="card mb-3">
                    <!-- Encabezado de filtro -->
                    <div class="card-header card-titulo">
                        Categoría
                    </div>
                    <!-- Cuerpo del filtro -->
                    <div class="card-body">
                    

                        <!-- Combobox de categorías -->
                        <div class="form-group mt-3">
                            <label for="seleccionarCategoria">Seleccionar categoría:</label>
                            <select class="form-select" id="seleccionarCategoria" name="seleccionarCategoria">

                            <?php
                                if ($categorias) {
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

                            <button class="container mt-3 text-center btn btn-primary shadow-0 btn-detalles categoria">Buscar por categoría</button>
                            <button class="container mt-3 text-center btn btn-primary shadow-0 btn-detalles categorianull">Limpiar filtro</button>
                        </div>



                    </div>
                </div>

              </div>
            </div>
            <!-- End sidebar -->



            <?php 

if ($FindProducts == 1) {
    echo '
    <!-- Content -->
    <div class="col-lg-9 col-sm-8">
        <header class="d-sm-flex align-items-center border-bottom mb-4 pb-3">
        </header>

        <!-- Elemento-->';
} else {
    echo '
    <!-- Content -->
    <div class="col-lg-9 col-sm-8">
        <header class="d-sm-flex align-items-center border-bottom mb-4 pb-3">
            <strong class="d-block py-2" style="color: white;">No se encontraron productos</strong>
        </header>

        <!-- Elemento-->';
}
?>

<?php
if ($FindProducts) {
    foreach ($productos as $producto) {
        $imagenUsuario = $producto['Imagen'];
        $encodeImagen = base64_encode($imagenUsuario);

        echo '
        <div class="producto row justify-content-center mb-3" data-idcategory='. $producto['Categorias'] .' >
            <div class="col-md-12">
                <div class="card shadow-0 border rounded-3">
                    <div class="card-body">
                        <div class="row g-0">
                            <!-- Imagen -->
                            <div class="col-xl-3 col-md-4 d-flex justify-content-center">
                                <div class="bg-image rounded me-md-3 mb-3 mb-md-0"> <!-- A posterior usar la clase hover-zoom, ripple y ripple-surface-->
                                    <img src="data:image/jpeg;base64,' . $encodeImagen . ' " class="imagenes-busqueda" />
                                    <a href="#!"></a>
                                </div>
                            </div>
                            <!-- Cuerpo del producto-->
                            <div class="col-xl-6 col-md-5 col-sm-7">
                                <h5><a class="ProfileSItems_NameObject" href="../../Profiles/Seller/Products_DetailS.php?id='.$producto['ID_Producto'].'">' . $producto['Nombre_Producto'] . '</a></h5>
                                <div class="d-flex flex-row">
                                    
                                </div>
                                <p class="text mb-4 mb-md-0"> Descripcion: <span class="text-danger"> ' . $producto['Descripcion_Producto'] . '</span> </p>
                                <hr>
                                <p class="text mb-4 mb-md-0"> Categorias: <span class="text-danger"> '. $producto['Categorias'] . ' </span></p>
                            </div>';

        // Check the product type (rol)
        if ($producto['Tipo_Producto'] == 1) {
            echo '
            <!-- Precio y Botones-->
            <div class="col-xl-3 col-md-3 col-sm-5 ">
                <div class="d-flex align-items-center mb-1  justify-content-center">
                    <h4 class="mb-1 me-1">' . $producto['Precio_Unitario'] . '$</h4>
                </div>
                <div class="d-flex justify-content-center">
                    <h6 class="text me-1">Inventario Disponible:</h6>
                    <h6 class="text-success">' . $producto['Cantidad'] . '</h6>
                </div>
                <div class="mt-2">
                    <div class=" d-flex justify-content-center">
                        <a href="../Seller/Products_Modify.php?id=' . $producto['ID_Producto'] . '" class="btn btn-primary shadow-0 btn-detalles">Editar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>';
        } else {
            echo '
            <!-- Botón de editar -->
            <div class="col-xl-3 col-md-3 col-sm-5">
                <div class="mt-2">
                    <div class="d-flex justify-content-center">
                        <a href="../Seller/Products_ModifyC.php?id=' . $producto['ID_Producto'] . '" class="btn btn-primary shadow-0 btn-detalles">Editar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>';
        }
    }
} else {
    echo '<h1><strong class="d-block py-2" style="color: white;">No tienes productos, agrega uno en el apartado de productos </strong></h1>';
}
?>
<!-- End elemento-->

              <hr style="color: white; border-width: 2px;" />

              

            </div>
          </div>
        </div>
      </section>




            
      <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
      <script src="../../../Librerias/popper/popper.min.js"></script>
      <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
      <script src="../../../JS/General/logout.js"></script>
      <script src="../../../JS/Seller/My_Products.js"></script>
    
</body>
</html>