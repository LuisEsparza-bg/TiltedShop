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

  //Variable para ver saber si encontro mas de 0 productos
  $FindProducts = null;

  // Obtener el texto de búsqueda de la URL
  $searchText = isset($_GET['search']) ? $_GET['search'] : '';
  // echo '<script>var textoBuscar = ' . json_encode($searchText) . ';</script>';


  // Obtener los parámetros de filtro de la URL
  $precioFlag = isset($_GET['precioFlag']) ? $_GET['precioFlag'] : 0;
  $precioFilterValue = isset($_GET['precioFilterValue']) ? $_GET['precioFilterValue'] : '';

  $valoracionFlag = isset($_GET['valoracionFlag']) ? $_GET['valoracionFlag'] : 0;
  $valoracionFilterValue = isset($_GET['valoracionFilterValue']) ? $_GET['valoracionFilterValue'] : '';

  $ventasFlag = isset($_GET['ventasFlag']) ? $_GET['ventasFlag'] : 0;
  $ventasFilterValue = isset($_GET['ventasFilterValue']) ? $_GET['ventasFilterValue'] : '';


  // Verificar si se aplicaron filtros
  $appliedFilters = $precioFlag + $valoracionFlag + $ventasFlag;

  if ($appliedFilters > 0) {
    // Se aplicaron filtros
    //echo '<h1>Filtros aplicados</h1>';
    // Aquí puedes llamar a tu procedimiento almacenado pasando los parámetros de filtro

    //Cargar los productos buscados de forma basica desde el Modelo
    if($searchText != ''){
      $pdo = $db->connect(); // Obtiene la conexión a la base de datos
      
      // Llamar al modelo y pasar la conexión como parámetro
      $productosValClass = new Products();
      
      $productos = $productosValClass->BusquedaAvanzadaProductos($pdo, $searchText, $precioFlag, $precioFilterValue, $valoracionFlag, $valoracionFilterValue, $ventasFlag, $ventasFilterValue);
      $pdo = null;
      
      
      if ($productos) {
        $FindProducts = 1;
      }
    }


  } else {
    // No se aplicaron filtros, es una búsqueda simple
    //echo '<h1>Búsqueda simple</h1>';
    // Aquí puedes realizar la lógica para una búsqueda simple

    //Cargar los productos buscados de forma basica desde el Modelo
    if($searchText != ''){
      $pdo = $db->connect(); // Obtiene la conexión a la base de datos
      $opcion = 1;

      // Llamar al modelo y pasar la conexión como parámetro
      $productosValClass = new Products();
      $productosValClass -> setNombre($searchText);

      $productos = $productosValClass->BusquedaSimpleProductos($pdo, $opcion);
      $pdo = null;
      
      
      if ($productos) {
        $FindProducts = 1;
      }
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
  <link rel="stylesheet" href="../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
  <!-- Google Fonts Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />

  <!-- CSS para la Nav Bar-->
  <link rel="stylesheet" href="../../CSS/Profiles/ActionsClient/Search_Result.css">
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

  <!-- sidebar y content -->
  <section class="pt-4"> <!-- Este margin es para separar el navbar del contenido-->
    <div class="container">
      <div class="row">

        <!-- sidebar -->
        <div class="col-lg-3 col-sm-4 ">
          <!-- Agrege el col sm 4 para que al hacer pequeña la pantalla tenga 4 espacios de col y el content tenga 8-->

          <!-- Toggle button Este bloque de codigo estara bloqueado ya que necesito usar MDB para hacerlo funcionar correctamente asi que lo dejare aqui para mas tarde -->
          <!--
              <button
                      class="btn btn-outline-secondary mb-3 w-100 d-lg-none"
                      type="button"
                      data-mdb-toggle="collapse"
                      data-mdb-target="#navbarSupportedContent"
                      aria-controls="navbarSupportedContent"
                      aria-expanded="false"
                      aria-label="Toggle navigation"
                      >
                <span>Show filter</span>
              </button>
              -->

          <!-- Collapsible wrapper -->
          <div class="card d-block mb-5" id="navbarSupportedContent">
            <!-- Si quiero que el boton de arriba funcione a posterior necesito que esta barra se colapse con collapse y borre el d-lg-block que fuerza el bloque en pantallas lg para que el collpase entre en accion en sm y md-->
            <div class="card-header mb-4 card-Primer-Titulo">
              Filtros
            </div>

            <!-- Esto no es necesario par aque se mueste la card completa en el card padre
                  <div class="row">
                    <div class="container-fluid">
                      -->

            <!-- Filtro de Mayor/Menor -->
            <div class="card mb-3">
              <!-- Encabezado de filtro -->
              <div class="card-header card-titulo">
                Precio
              </div>
              <!-- Cuerpo del filtro -->
              <div class="card-body">
                <!-- Checked checkbox -->
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="filtroPrecio" value="1" id="Filtro_Orden_Mayor" />
                  <label class="form-check-label" for="Filtro_Orden_Mayor">Mayor a Menor</label>

                </div>
                <!-- Checked checkbox -->
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="filtroPrecio" value="0" id="Filtro_Orden_Menor" />
                  <label class="form-check-label" for="Filtro_Orden_Menor">Menor a Mayor</label>

                </div>
              </div>
            </div>
            <!--- 
                    </div>
                  </div>
                -->

            <!-- Filtro de mejor valorado -->
            <div class="card mb-3">
                <!-- Encabezado de filtro -->
                <div class="card-header card-titulo">
                    Valoración
                </div>
                <!-- Cuerpo del filtro -->
                <div class="card-body">
                    <!-- Radio button para Mejor Valorados -->
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="filtroValoracion" value="1" id="Filtro_Mejor_Valorados" />
                        <label class="form-check-label" for="Filtro_Mejor_Valorados">
                            <i class="fas fa-star text-warning"></i> Mejor Valorados
                        </label>
                    </div>
                    <!-- Radio button para Peor Valorados -->
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="filtroValoracion" value="0" id="Filtro_Peor_Valorados" />
                        <label class="form-check-label" for="Filtro_Peor_Valorados">
                            <i class="fas fa-star text-secondary"></i> Peor Valorados
                        </label>
                    </div>
                </div>
            </div>

            <!-- Card que contendra un filtro -->
            <div class="card mb-0">
              <!-- Encabezado de filtro -->
              <div class="card-header card-titulo">
                Ventas
              </div>
              <!-- Cuerpo del filtro -->
              <div class="card-body">
                <!-- Checked checkbox -->
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="filtroVentas" value="1" id="Filtro_Mas_Vendidos" />
                  <label class="form-check-label" for="Filtro_Mas_Vendidos">Más vendidos</label>
                </div>
                <!-- Checked checkbox -->
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="filtroVentas" value="0" id="Filtro_Menos_Vendidos" />
                  <label class="form-check-label" for="Filtro_Menos_Vendidos">Menos Vendidos</label>
                </div>
              </div>
            </div>


          </div>
        </div>
        <!-- End sidebar -->


        <!-- CARGA DE RESULTADOS DE LA BUSQUEDA -->

        <?php 

          if($FindProducts == 1){
          echo'
              <!-- Content -->
              <div class="col-lg-9 col-sm-8"> <!-- Aqui agrege el sm-8 para que funcione con la columna de filtros-->
                <header class="d-sm-flex align-items-center border-bottom mb-4 pb-3">
                  <strong class="d-block py-2" style="color: white;">Resultados encontrados: '.sizeof($productos).'</strong>
                </header>

                <!-- Elemento-->';
          }
          else{
            echo'
              <!-- Content -->
              <div class="col-lg-9 col-sm-8"> <!-- Aqui agrege el sm-8 para que funcione con la columna de filtros-->
                <header class="d-sm-flex align-items-center border-bottom mb-4 pb-3">
                  <strong class="d-block py-2" style="color: white;">No se encontraron productos</strong>
                </header>

                <!-- Elemento-->';
          }
        ?>

        <?php 

          if ($FindProducts) {
            foreach ($productos as $producto) {
              if($producto['tipoProducto'] == 1){
                echo '
                  <div class="row justify-content-center mb-3">
                      <div class="col-md-12">
                          <div class="card shadow-0 border rounded-3">
                              <div class="card-body">
                                  <div class="row g-0">
                                      <!-- Imagen -->
                                      <div class="col-xl-3 col-md-4 d-flex justify-content-center">
                                          <div class="bg-image rounded me-md-3 mb-3 mb-md-0">
                                              <!-- A posterior usar la clase hover-zoom, ripple y ripple-surface-->
                                              <img src="../../Images/Items_Images/audifonos1.png" class="imagenes-busqueda" />
                                              <a href="#!">
                                                  <!-- Debería crear aquí un efecto visual cuando el usuario se coloca sobre la imagen revisar después-->
                                                  <!--
                                                      <div class="hover-overlay">
                                                          <div class="mask" style="background-color: rgba(253, 253, 253, 0.15);"></div>
                                                      </div>
                                                      -->
                                              </a>
                                          </div>
                                      </div>
                                      <!-- Cuerpo del producto-->
                                      <div class="col-xl-6 col-md-5 col-sm-7">
                                          <h5>' . $producto['NombreP'] . '</h5>
                                          <div class="d-flex flex-row">
                                              <div class="text-warning mb-1 me-2">';
                                              // Generar estrellas en función de PromedioCalificacion
                                              $promedioCalificacion = round($producto['PromedioCalificacion']);
                                              for ($i = 1; $i <= 5; $i++) {
                                                  if ($i <= $promedioCalificacion) {
                                                      echo '<i class="fa fa-star"></i>';
                                                  } else {
                                                      echo '<i class="far fa-star"></i>';
                                                  }
                                              }
                                              echo '
                                                  <span class="ms-1">
                                                      ' . $promedioCalificacion . '
                                                  </span>
                                              </div>
                                          </div>

                                          <p class="text mb-4 mb-md-0">
                                              ' . $producto['DescripcionNormalP'] . ' 
                                          </p>
                                      </div>

                                      <!-- Precio y Botones-->
                                      <div class="col-xl-3 col-md-3 col-sm-5 ">
                                          <div class="d-flex align-items-center mb-1">
                                              <h4 class="mb-1 me-1">$' . $producto['PrecioP'] . '</h4>
                                          </div>
                                          <h6 class="text-success">' . $producto['NombreVendedor'] . '</h6>
                                          <div class="mt-2">
                                              <div class="">
                                                  <a class="btn btn-primary shadow-0 btn-detalles"
                                                  href="../../HTML/Profiles/Products_Detail.php?id=' . $producto['IdP'] . '">Detalles</a>
                                              </div>
                                              <div class="d-flex align-items-center mt-2 ">
                                                  
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  ';
              }else{
                echo '
                
                <div class="row justify-content-center mb-3">
                  <div class="col-md-12">
                    <div class="card shadow-0 border rounded-3">
                      <div class="card-body">
                        <div class="row g-0">
                          <!-- Imagen -->
                          <div class="col-xl-3 col-md-4 d-flex justify-content-center">
                            <div class="bg-image rounded me-md-3 mb-3 mb-md-0">
                              <!-- A posterior usar la clase hover-zoom, ripple y ripple-surface-->
                              <img src="../../Images/Items_Images/audifonos1.png" class="imagenes-busqueda" />
                              <a href="#!">
                                <!-- Deberia crear aqui un efecto visual cuando el usuario se coloca sobre la imagen revisar despues-->
                                <!--
                                    <div class="hover-overlay">
                                      <div class="mask" style="background-color: rgba(253, 253, 253, 0.15);"></div>
                                    </div>
                                    -->
                              </a>
                            </div>
                          </div>
                          <!-- Cuerpo del producto-->
                          <div class="col-xl-6 col-md-5 col-sm-7">
                            <h5>' . $producto['NombreP'] . '</h5>
                            <div class="d-flex flex-row">
                                <div class="text-warning mb-1 me-2">';
                                // Generar estrellas en función de PromedioCalificacion
                                $promedioCalificacion = round($producto['PromedioCalificacion']);
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $promedioCalificacion) {
                                        echo '<i class="fa fa-star"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                }
                                echo '
                                    <span class="ms-1">
                                        ' . $promedioCalificacion . '
                                    </span>
                                </div>
                            </div>
      
                            <p class="text mb-4 mb-md-0">
                            ' . $producto['DescripcionNormalP'] . ' 
                            </p>

                          </div>
      
                          <!-- Precio y Botones-->
                          <div class="col-xl-3 col-md-3 col-sm-5 ">
                            <div class="d-flex align-items-center mb-1">
                              <h4 class="mb-1 me-1"> Cotización Requerida </h4> 
                            </div>
                            <h6 class="text-success">' . $producto['NombreVendedor'] . '</h6>
                            <div class="mt-2">
                              <div class="">
                                <a class="btn btn-primary shadow-0 btn-detalles"
                                  href="../../HTML/Profiles/Products_Detail.php?id=' . $producto['IdP'] . '">Detalles</a>
                              </div>
      
                              <div class="d-flex align-items-center mt-2 ">
                               
                              </div>
      
                            </div>
                          </div>
      
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                ';
              }
              
            }
          }
          else{
          echo '<h1><strong class="d-block py-2" style="color: white;">No se encontraron productos o vendedores </strong></h1>';
          }
      
        ?>

        <!-- END CARGA -->

          <hr style="color: white; border-width: 2px;" />

          

        </div>
      </div>
    </div>
  </section>

  <script src="../../Librerias/jquery/jquery-3.7.1.min.js"></script>
  <script src="../../Librerias/popper/popper.min.js"></script>
  <script src="../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
  <script src="../../JS/General/Productos.js"></script>
  <script src="../../JS/General/logout.js"></script>
  <script src="../../JS/Client/Search_Bar_Client_Filters.js"></script>

</body>

</html>