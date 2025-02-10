<?php
require_once('C:\xampp\htdocs\Pantallas\HTML\Conexion\DB_Conection.php');
session_start(); 

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /Pantallas/HTML/General/index.php'); 
    exit; 
}

$username = null;

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
}


// Verifica si el usuario ha iniciado sesión y obtiene el nombre de usuario de la sesión
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $db = new DB();
    $pdo = $db->connect();

    if ($pdo) {
        $sql = "CALL SP_MyProfileData(:username)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        $pdo = null;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../CSS/Profiles/ActionsClient/edit_profile.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/navbar.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/footer.css">
    <link rel="stylesheet" href="../../../CSS/General Styles/background.css">
</head>

<body>

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


    <br>
    <br>


    <div class="row">

        <div class="col-lg-1 col-sm-4"> </div>
        <div class="col-lg-10">
            <h1>Editar Perfil</h1>
            <hr>
        </div>
    </div>



    <div class="row">
        <div class="col-lg-1 col-sm-4"> </div>



        <div class="col-lg-5">
            <div class="container EditProfile_EditPhotoDataContainer">

                <div class="container justify-content-center mt-3 text-center">
                    <img class="EditProfile_ProfilePhoto"
                        src="data:image/jpeg;base64,<?php echo base64_encode($userData['Imagen']); ?>"
                        alt="Profile_Image">
                </div>

                <form id="form1" method="post" action="../../DB_APIS/SP_EditProfilePhoto.php" enctype="multipart/form-data">
                    <div class="form-group mt-3">
                        <label for="Edit_Photo">Selecciona una foto nueva</label>
                        <input type="file" class="form-control-file" id="Edit_Photo" name="Edit_Photo" required multiple>

                        <button id="Login_Submit" type="submit" class="btn EditProfile_SubmitButtonEdit">Actualizar
                            Imagen</button>
                    </div>
                </form>

            </div>
        </div>






        <div class="col-lg-5">
            <div class="container EditProfile_EditDataContainer">


                <form id="form2" method="post" action="../../Controllers/SP_EditProfile.php">
                    <div class="form-group">

                        <label class="Login_Labels">Nombre de Usuario:</label>
                        <input type="text" class="form-control Login_Inputs" id="Edit_Username" name="Edit_Username"
                            placeholder="Nombre de Usuario" value="<?php echo $userData['Username']; ?>" required minlength="3" maxlength="15" >

                        <label class="Login_Labels">Contraseña:</label>
                        <input type="password" class="form-control Login_Inputs" id="Edit_Password" name="Edit_Password"
                            placeholder="Contraseña" value="<?php echo $userData['PassW']; ?>" required required minlength="8" maxlength="15">

                        <label class="Login_Labels">Nombre:</label>
                        <input type="text" class="form-control Login_Inputs" id="Edit_Name" name="Edit_Name"
                            placeholder="Nombre" required value="<?php echo $userData['Nombre']; ?>" minlength="2" maxlength="25">

                        <label class="Login_Labels">Apellido Paterno:</label>
                        <input type="text" class="form-control Login_Inputs" id="Edit_LastName1" name="Edit_LastName1"
                            placeholder="Apellido Paterno" required
                            value="<?php echo $userData['Apellido_Paterno']; ?>" minlength="3" maxlength="25">

                        <label class="Login_Labels">Apellido Materno:</label>
                        <input type="text" class="form-control Login_Inputs" id="Edit_LastName2" name="Edit_LastName2"
                            placeholder="Apellido Materno" required
                            value="<?php echo $userData['Apellido_Materno']; ?>" minlength="3" maxlength="25">

                        <label class="Login_Labels">Fecha de Nacimiento:</label>
                        <input type="date" class="form-control Login_Inputs" id="Edit_Birthday" name="Edit_Birthday"
                            required value="<?php echo $userData['Fecha_Nacimiento']; ?>">

                        <label class="Login_Labels">Correo Electrónico:</label>
                        <input type="email" class="form-control Login_Inputs" id="Edit_Email" name="Edit_Email"
                            placeholder="Correo Electrónico" required value="<?php echo $userData['Correo']; ?>" minlength="3" maxlength="25">

                        <label class="Login_Labels">Estado:</label>
                        <input type="text" class="form-control Login_Inputs" id="Edit_Estado" name="Edit_Estado"
                            placeholder="Estado" required value="<?php echo $userData['Estado']; ?>" minlength="3" maxlength="30">

                        <label class="Login_Labels">Colonia:</label>
                        <input type="text" class="form-control Login_Inputs" id="Edit_Colonia" name="Edit_Colonia"
                            placeholder="Colonia" required value="<?php echo $userData['Colonia']; ?>" minlength="3" maxlength="50">

                        <label class="Login_Labels">Calle:</label>
                        <input type="text" class="form-control Login_Inputs" id="Edit_Calle" name="Edit_Calle"
                            placeholder="Calle" required value="<?php echo $userData['Calle']; ?>" minlength="3" maxlength="30">

                        <label class="Login_Labels">Número de Casa:</label>
                        <input type="text" class="form-control Login_Inputs" id="Edit_NumeroCasa" name="Edit_NumeroCasa"
                            placeholder="Número de Casa" required value="<?php echo $userData['Numero_Casa']; ?>" minlength="3" maxlength="30">


                        <label>Género:</label>
                        <select class="form-control" id="Edit_Gender" name="Edit_Gender" required>
                            <option value="Hombre" <?php if ($userData['ID_Sexo'] == 1)
                                echo 'selected'; ?>>Hombre
                            </option>
                            <option value="Mujer" <?php if ($userData['ID_Sexo'] == 2)
                                echo 'selected'; ?>>Mujer</option>
                        </select>

                        <label for="Edit_privacity">Privacidad:</label>
                        <select class="form-control" id="Edit_privacity" name="Edit_privacity" required>
                            <option value="1" <?php if ($userData['Privacidad'] == 1)
                                echo 'selected'; ?>>Perfil Público
                            </option>
                            <option value="0" <?php if ($userData['Privacidad'] == 0)
                                echo 'selected'; ?>>Perfil Privado
                            </option>
                        </select>


                        <button id="Login_Submit" type="submit" class="btn EditProfile_SubmitButtonEdit">Enviar</button>
                    </div>
                </form>

            </div>
        </div>






    </div>


    <script src="../../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../../Librerias/popper/popper.min.js"></script>
    <script src="../../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
    <script src="../../../JS/Client/Profile_Edit.js"></script>
    <script src="../../../JS/General/logout.js"></script>

</body>


</html>