<?php
require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');

session_start();

// VALIDAR LA SESSION
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php');
    exit;
}


// VALIDAR LA SESSION Y CARGAR DATOS DEL USUARIO 
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
            $imagenUsuario = $result['Imagen'];
            $encodeImagen = base64_encode($imagenUsuario);
            $nombreUsuario = $result['Nombre'];
            $apellidoPaterno = $result['Apellido_Paterno'];
            $apellidoMaterno = $result['Apellido_Materno'];
            $fechaCreacion = $result['Fecha_Registro'];
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

    $conn = null;
}


// VALIDAR LA SESSION Y CARGAR LISTAS DEL USUARIO 

$opcion = 1;
$listaid = 0;

$conn = $db->connect();

$sql = "CALL SP_VerListasDeUsuario(:username, :opcion, :listaid)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':opcion', $opcion, PDO::PARAM_INT);
    $stmt->bindParam(':listaid', $listaid, PDO::PARAM_INT);
    $stmt->execute();
    
    $listas = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $listas[] = $row; 
    }


} else {
    echo "Error al llamar al procedimiento almacenado: ";
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
    <link rel="stylesheet" href="../../../CSS/Profiles/profile_client.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/footer.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/background.css">

</head>

<body>

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

    <br>
    <br>

    <div class="row">

        <!-- TO DO: Hacer offset en todo lo primero-->

        <?php
        echo '<div class="ProfileC_Container col-lg-10 offset-lg-1">
    <img src="data:image/jpeg;base64,' . $encodeImagen . '" class="ProfileC_ContainerPhoto" alt="Cart icon">
    <div class="d-flex flex-column">
        <span class="ProfileC_Containertext">@' . $username . '</span>
        <span class="ProfileC_ContainertextSmall">Usuario desde: ' . $fechaCreacion . '</span>
        <span class="ProfileC_ContainertextSmall">Nombre completo: ' . $nombreUsuario . " " . $apellidoPaterno . " " . $apellidoMaterno . '</span>
        <a href="../../../HTML/Profiles/Client(Self)/edit_profile.php" class="btn ProfileC_ListButtons">Editar Perfil</a>
        <a href="../../../HTML/Profiles/Profile_Client.php?'. "username=". $username .'" class="btn ProfileC_ListButtons">Ver mi perfil publico</a>
    </div>
</div>';
        ?>

    </div>


    <div class="row mt-3 mb-3">

        <!-- TO DO: Hacer offset en todo lo primero, Alertas de borrar producto y Lista y agregar a Carrito-->
        <div class="ProfileC_ContainerLists col-lg-10 offset-lg-1 p-0">
            <div class=" ProfileC_ListDropdown col-lg-2 d-flex flex-column">
                <span class="ProfileC_ListText"> Mis Listas</span>
                <hr>
                <div class="btn-group-lg d-flex flex-column">
                    <a href="../../../HTML/Profiles/Client(Self)/new_list.php" class="btn ProfileC_AddListButton"> <img
                            src="../../../Images/Icons/plus_icon.png" class="Profile_IconImage mb-1" alt="">Crear Nueva
                        Lista</a>
                        <?php 
                        foreach ($listas as $lista) {
                            $nombreListas = $lista['Nombre_Lista'];
                            $descripcionListas = $lista['Descripcion'];
                            $idListas = $lista['ID_Lista'];
                        
                            // Agrega un botón con atributos data-* que contienen el ID, nombre y descripción de la lista
                            echo '<button type="button" class="btn ProfileC_ListButtons ListActiveButtons" data-id="' . $idListas . '" data-nombre="' . htmlspecialchars($nombreListas) . '" data-descripcion="' . htmlspecialchars($descripcionListas) . '">' . $nombreListas . '</button>';
                        }
                        ?>

                </div>
            </div>

            <div class="col-lg-10 align-items-center text-center ProfileC_ContainerDescriptionLists">
                <p class="h1" id="Lista_Nombre" ></p>
                <p class="ProfileC_ListTextDescription" id="Lista_Descripcion"></p>
                <a href="../../../HTML/Profiles/Client(Self)/edit_list.php" class="btn ProfileC_ListButtons ProfileC_EditListLink" id="ProfileC_EditListLink" hidden> <img
                        src="../../../Images/Icons/edit_icon.png" class="Profile_IconImage mb-1" > Editar
                    Lista</a>
                <button class="btn ProfileC_ListButtons ProfileC_DeleteListButton" data-idLista="" hidden id="ProfileC_DeleteListButton"> <img
                        src="../../../Images/Icons/trash_icon.png"  class="Profile_IconImage mb-1" alt=""> Eliminar Lista
                </button>

                <hr>
                <div class="container" id="ItemContainer">
                    <p class="ProfileC_HeaderObjects">Objetos en la lista:</p>
                    <div class="row" >
                        

                        <!-- Modal de confirmación de eliminación 
                        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de que quieres eliminar este producto de tu lista?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn ProfileC_ListButtons">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->


                    </div>
                </div>
            </div>
        </div>
        <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
        <script src="../../../Librerias/popper/popper.min.js"></script>
        <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
        <script src="../../../JS/General/logout.js"></script>
        <script src="../../../JS/Client/ListProfile.js"></script>


</body>




</html>