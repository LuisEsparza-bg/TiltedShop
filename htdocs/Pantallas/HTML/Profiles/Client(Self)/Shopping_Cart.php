<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
  header('Location: /Pantallas/HTML/General/index.php');
  exit;
}

require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\Users.php');
require_once('C:\xampp\htdocs\Pantallas\HTML\Models\CarProd.php');

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
  }
  $IDCarrito = new Users(
    username: $username
  );

  $ProductosActivos = new CarProd(

  );

  $stmt->closeCursor();
  $IDCarrito = $IDCarrito->getCart($conn, $IDCarrito->getUsername());
  // SE MANDA DIRECTAMENTE SIN CONTROLADOR PORQUE AL FINAL SE UTILIZA UN FETCHALL. 
  $stmt = $conn->prepare("CALL SP_ProductosAgotados(:id_carrito)");
  $stmt->bindParam(':id_carrito', $IDCarrito, PDO::PARAM_INT);
  $stmt->execute();
  $productosAgotados = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  $productosEnCarrito = $ProductosActivos->ObtenerProductosActivos($conn, $IDCarrito);
  if ($productosEnCarrito) {
    $totalAPagar = 0;
    foreach ($productosEnCarrito as $producto) {
      $totalAPagar += $producto['Precio'] * $producto['Cantidad_Carrito'];
      $totalAPagarFormatted = number_format($totalAPagar, 2);
    }
  }
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
  <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">
  <link rel="stylesheet" href="../../../CSS/Profiles/ActionsClient/Shopping_Cart.css">

</head>

<body>

  <!-- Nav bar -->
  <div class="row Navbar_Container">
    <div class="col-lg-3 col-sm-4">
      <a href="../../../HTML/Profiles/Home.php"><img src="../../../Images/Tilted_Shop_Logo.png" class="Navbar_Logo"
          alt="Tilted Shop Icon"></a>

    </div>

    <div class="col-lg-6 col-sm-4 ">
      <div class="container Navbar_SearchBar">
        <div class="input-group">
          <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
            aria-describedby="search-addon" />
          <a href="../../../HTML/Profiles/Search_Result.php" class="btn btn-primary Navbar_SearchButton">Buscar</a>
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

  <!-- cart + summary -->
  <section class="py-5 fondo-color"> <!-- Cambie margin por padding para poder pintar el fondo-->
    <div class="container">
      <div class="row ">

        <!-- cart -->
        <div class="col-lg-9 col-sm-8">
          <div class="card border shadow-0">

            <div class="m-4">
              <h4 class="card-title mb-4">Mi Carrito</h4>
              <hr class="mb-4 linea-separacion">
              <!-- Aqui comienza los productos -->

              <input type="hidden" id="productos-agotados"
                value="<?php echo htmlspecialchars(json_encode($productosAgotados)); ?>">

              <?php
              if (isset($productosEnCarrito))
                foreach ($productosEnCarrito as $producto): 
                  $imagen =  $producto['Imagen'];
                  $encodeImagen = base64_encode($imagen);
              ?>
                  <div class="row gy-3 mb-4">
                    <div class="col-xl-3 col-md-12 col-lg-4 d-flex justify-content-center border-column">
                      <div class="bg-image rounded me-md-3 mb-3 mb-md-0">
                        <img src="data:image/jpeg;base64,<?php echo $encodeImagen; ?>" class="imagenes-carrito" /> 
                      </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-sm-12 text-center pe-3">
                      <p class="nav-link"> <b>
                          <?php echo strtoupper(htmlspecialchars($producto['Nombre_Producto'])); ?>
                        </b>
                      </a>
                      <?php if ($producto['Tipo_Producto'] == 0): ?>
                        <p class="text-muted">
                          <?php echo htmlspecialchars($producto['Descripcion_Cot_Venta']); ?>
                        </p>
                      <?php else: ?>
                        <p class="text-muted">
                          <?php echo htmlspecialchars($producto['Descripcion_Producto']); ?>
                        </p>
                      <?php endif; ?>
                    </div>

                    <div
                      class="col-xl-3 col-lg-2 col-sm-12 col-md-12 d-flex flex-row flex-lg-column flex-xl-row text-nowrap justify-content-sm-center">
                      <div class="align-item-center justify-content-center">
                        <p class="mb-0"> Cantidad </p>
                        <?php if ($producto['Tipo_Producto'] == 0): ?>
                        <p class="text-muted">
                        <input disabled type="number" style="width: 100px;" class="form-control me-4 quantityInput" min="1"
                          id="QuantityForEach" data-IDProducto="<?php echo htmlspecialchars($producto['ID_Producto']); ?>"
                          value="<?php echo htmlspecialchars($producto['Cantidad_Carrito']); ?>">
                        </p>
                      <?php else: ?>
                        <input type="number" style="width: 100px;" class="form-control me-4 quantityInput" min="1"
                          id="QuantityForEach" data-IDProducto="<?php echo htmlspecialchars($producto['ID_Producto']); ?>"
                          value="<?php echo htmlspecialchars($producto['Cantidad_Carrito']); ?>">
                      <?php endif; ?>

                       
                      </div>
                      <div class="align-item-center mt-xl-2">
                        <text class="h6">$
                          <?php
                          $totalProducto = $producto['Precio'] * $producto['Cantidad_Carrito'];
                          echo htmlspecialchars($totalProducto); ?>
                        </text> <br />
                        <small class="text-muted text-nowrap">$
                          <?php echo htmlspecialchars($producto['Precio']); ?> / cu
                        </small>
                      </div>
                    </div>

                    <div
                      class="col-xl-3 col-lg-3 col-md-12 col-sm-12 d-flex justify-content-sm-center justify-content-lg-center justify-content-xl-center mb-2">
                      <div class="float-md-end justify-content-center align-item-center">
                        <div class="mb-2">
                        <?php if ($producto['Tipo_Producto'] == 0): ?>
                          
                        </p>
                      <?php else: ?>
                        <a class="btn btn-primary shadow-0 btn-comprar" onclick="Buy()" type="button"
                            id="">Comprar</a>
                      <?php endif; ?>
                      
                        </div>
                        <div class="d-flex">
                          <a href="#" class="btn btn-light border text-danger icon-hover DeleteProduct"
                            id="DeleteProductToCart"
                            data-IDProducto="<?php echo htmlspecialchars($producto['ID_Producto']) ?>">
                            <i class="fas fa-trash-alt fa-lg px-5 text-secondary"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr class="mb-4 linea-separacion">
                <?php endforeach; ?>
            </div>
          </div>
        </div>
        <!-- cart -->
        <!-- summary -->
        <div class="col-lg-3 col-sm-4">
          <div class="card mb-3 border shadow-0">

            <div class="card shadow-0 border">
              <div class="card-body">
                <!-- Total a pagar -->
                <div class="d-flex justify-content-between">
                  <p class="mb-2"> <b> Total a pagar:</b></p>
                  <p class="mb-2" id="totalAPagar">
                    <?php if ($productosEnCarrito)
                      echo $totalAPagarFormatted . "$";
                    else {
                      echo "No tienes productos en tu carrito";
                    } ?>
                  </p>
                </div>

                <hr />

                <div class="mt-3">
                  <a href="../../../HTML/Profiles/Client(Self)/Pago_Productos.php?type=all"
                    class="btn btn-success w-100 shadow-0 mb-2 btn-comprar"> <i
                      class="fas fa-shopping-cart fa-lg px-1 text-secondary"></i> Comprar </a>
                  <a href="../../../HTML/Profiles/Home.php" class="btn btn-light w-100 border mt-2"> Seguir comprando
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!-- summary -->
        </div>
      </div>
  </section>





  <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
  <script src="../../../Librerias/popper/popper.min.js"></script>
  <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
  <script src="../../../JS/Client/ShoppingCart.js"></script>
  <script src="../../../JS/General/logout.js"></script>

</body>

</html>