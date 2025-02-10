<!DOCTYPE html>
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

    if($rol == 1){
    header('Location: /Pantallas/HTML/Profiles/Admin/Products_TBD.php'); 
    }
    else if($rol == 2){
        header('Location: /Pantallas/HTML/Profiles/Seller/My_Products.php');
    }
    else{

    }

    $conn = null; 
}
else{
    $FindOne = null;
}

?>


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
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsClient/Products_pay.css">

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
                <small> Hola <b> <?php echo $username; ?> </b> </small>
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

    <!-- Ventana de pago  -->
    <section class="py-5 fondo-color"> <!-- Cambie margin por padding para poder pintar el fondo-->
      <div class="container d-flex align-items-center justify-content-center">
        <div class="row ">

            <!-- Card -->
            <div class="col-xl-6 col-sm-12 mx-auto my-auto">
                <div class="card border shadow-0">
                
                    <div class="m-4">
                        <!-- Header de la card-->
                        <h2 class="card-title mb-4">Pago</h2>
                        
                        <!-- Linea de separacion del header y el body-->
                        <hr class="mb-4 linea-separacion">
                        
                        <!-- Body de la card-->
                        <!-- Datos del producto -->
                        <div class="card-body p-0">                        

                            <form id="form1">
                                <!-- Eleccion de metodo de pago-->
                                <div class="row mb-4 "> 
                                    <h3>Seleccione su metodo de pago:</h3>
                                    
                                    <div class="col-12"> <!-- Estos cols son para mantener en fila los raio buttons-->
                                        <label class="form-check-label form-check">
                                            <input type="radio" class="form-check-input" name="metodoPago" value="1" checked> Tarjeta de Crédito
                                        </label>
                                    
                                        <label class="form-check form-check-label">
                                            <input type="radio" class="form-check-input" name="metodoPago" value="2"> Tarjeta de Débito
                                        </label>
                                    
                                        <label class="form-check form-check-label">
                                            <input type="radio" class="form-check-input" name="metodoPago" value="3"> PayPal
                                        </label>
                                    </div>

                                </div>

                                <hr class="mb-4 linea-separacion">

                                <!-- Datos iniciales tarjetas-->
                                <div class="row mb-4">
                                    <h3 class="mb-4">Ingrese los datos de su tarjeta:</h3>
                                    <div class="col-sm-6">
                                        <label for="nombreTarjeta">Nombre del Propietario:</label>
                                        <input type="text" class="form-control" id="nombreTarjeta" name="nombreTarjeta" placeholder="Nombre del Propietario" required>
                                        <small class="text-muted"> *Nombre completo como se muestra en la tarjeta </small>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="numeroTarjeta">Número de la Tarjeta:</label>
                                        <input type="text" class="form-control" id="numeroTarjeta" name="numeroTarjeta" maxlength="16" placeholder="Número de la Tarjeta" required>
                                    </div>
                                </div>

                                

                                <!-- Datos finales tarjetas y aviso de direccion-->
                                <div class="row mb-4"> 
                                    <div class="col-sm-6">
                                        <label for="fechaVencimiento">Fecha de Vencimiento:</label>
                                        <input type="month" class="form-control" id="fechaVencimiento" name="fechaVencimiento" placeholder="MM/AA" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="cvv">CVV:</label>
                                        <input type="number" class="form-control" id="cvv" name="cvv" placeholder="CVV" required>
                                    </div>

                                    <small class="text-muted mt-2"> *Recuerde que la direccion de envio sera la misma que este actualmente en su cuenta </small>
                                </div>

                                <hr class="mb-4 linea-separacion">

                                <!-- Botón para enviar la reseña -->
                                <div class="d-flex align-item-center justify-content-center"> 
                                    <button type="button" id="PayButton" class="btn btn-primary btn-comprar">Confirmar Pago</button>
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
    <script src="../../../JS/Client/Payment2.js"></script>
    <script src="../../../JS/General/logout.js"></script>
    
</body>
</html>