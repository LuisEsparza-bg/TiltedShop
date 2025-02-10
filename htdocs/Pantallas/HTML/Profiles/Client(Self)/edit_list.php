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
        echo "Error al llamar al procedimiento almacenado: ";
    }

    if ($rol == 1) {
        header('Location: /Pantallas/HTML/Profiles/Admin/Products_TBD.php');
    } else if ($rol == 2) {
        header('Location: /Pantallas/HTML/Profiles/Seller/My_Products.php');
    } else {

    }

    $conn = null;
}


$opcion = 3;
$conn = $db->connect();
$FindOne = null;


if(isset($_GET['id'])) {
    $idLista = $_GET['id'];
        
$nombreListas = "";
$descripcionListas = "";
$privacidaListas = "";

$sql = "CALL SP_VerListasDeUsuario(:username, :opcion, :listaid)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':opcion', $opcion, PDO::PARAM_INT);
    $stmt->bindParam(':listaid', $idLista, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $nombreListas = $result['Nombre_Lista'];
        $descripcionListas = $result['Descripcion'];
        $privacidaListas = $result['Privacidad'];
        $FindOne = 1;
    } else {

    }

} 
}



$conn = null;




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsClient/edit_list.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/background.css">
</head>

<body class="">

    <!-- NAVBAR START -->
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
    <!-- NAVBAR END -->

    <div class="List_Container container mt-3 mb-3 p-0">
        <div class="row">
            <div class="col-lg-10 offset-1 mt-2">
                <?php
                if ($FindOne != null) {
                    echo '
                    <h2 class="">Editar ' . $nombreListas . ' </h2>
                    <hr>
                    <form method="post" action="../../Controllers/SP_List.php" id="form_List2">
                        <div class="mb-3">
                            <label class="form-label List_Text">Nombre de la Lista:</label>
                            <input type="text" class="form-control" required name="Lista_Nombre" minlength="1" maxlength="25" value="'.$nombreListas.'">
                        </div>
    
                        <div class="mb-3">
                            <label for="listDescription" class="form-label List_Text">Descripción de la Lista:</label>
                            <textarea class="form-control" rows="4" name="Lista_Descripcion" required >'.$descripcionListas.'</textarea>
                        </div>

                        <div class="mb-3">
                        <input type="text" class="form-control" name="Lista_ID" hidden value="'.$idLista.'">
                    </div>
                      
    
                        <div class="mb-0">
                            <label class="form-label List_Text">Selecciona la privacidad de la Lista:</label>
                            <div class="form-check">';

                    if ($privacidaListas == 1) {
                        echo '
                                <input class="form-check-input" type="radio" name="Lista_Privacidad"
                                checked required value="1">
                            <label class="form-check-label" for="publicList" active>Pública</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Lista_Privacidad"
                                 required value="0">
                            <label class="form-check-label" for="privateList">Privada</label>
                        </div>
                    </div>';
                    } else {
                        echo '
                                <input class="form-check-input" type="radio" name="Lista_Privacidad"
                                 required value="1">
                            <label class="form-check-label" for="publicList" active>Pública</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Lista_Privacidad"
                                 checked required value="0">
                            <label class="form-check-label" for="privateList">Privada</label>
                        </div>
                    </div>';
                    }


                    echo '
                        <input type="hidden" name="Lista_Type" value="2">
    
    
                        <br>
                        <button type="submit" class="btn List_EditButton mb-3"> Modificar Lista</button>
                    </form>';
                } else {
                    echo ' <h2 class="">No puedes acceder a esta lista</h2>';
                }

                ?>


            </div>
        </div>
    </div>


    <script src="../../../JS/login.js"></script>
    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/Client/Edit_List.js"></script>
    <script src="../../../JS/General/logout.js"></script>
</body>


</html>