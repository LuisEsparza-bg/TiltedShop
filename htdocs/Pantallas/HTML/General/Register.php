<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../CSS/General Pages/Register.css">

    <!-- Libreria para iconos de boostrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <!--Barra superior tipo navbar-->
    <div class="row justify-content-center LoginPage_Bar">
        <img src="../../Images/Tilted_Shop_Logo.png" class="Login_Logo" alt="Tilted Shop Icon">

    </div>


    <div class="row">

        <div class="col-8 container mt-3 mb-3">
            <div class="card">
                <div class="card-header">
                    Registro de Usuario
                </div>
                <div class="card-body">
                    <!-- Formulario aquí -->
                    <form method="post" action="../../HTML/DB_APIS/SP_UserRegister.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nombreUsuario">Nombre de Usuario</label>
                            <input type="text" id="nombreUsuario" name="Register_nombreUsuario" class="form-control" required minlength="3" maxlength="15">
                        </div>

                        <div class="form-group">
                            <label for="correo">Correo Electrónico</label>
                            <input type="email" id="correo" name="Register_correo" class="form-control" required minlength="3" maxlength="25">
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" id="password" name="Register_password" class="form-control" required minlength="8" maxlength="15">
                            <small class="form-text text-muted">La contraseña debe tener al menos 8 caracteres, una
                                mayúscula, un número y un carácter especial.</small>
                        </div>

                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="Register_nombre" class="form-control" required minlength="3" maxlength="25">
                        </div>

                        <div class ="form-group">
                            <label for="apellido1">Apellido Paterno</label>
                            <input type="text" id="apellido1" name="Register_apellido1" class="form-control" required minlength="3" maxlength="25">
                        </div>

                        <div class="form-group">
                            <label for="apellido2">Apellido Materno</label>
                            <input type="text" id="apellido2" name="Register_apellido2" class="form-control" required minlength="3" maxlength="25">
                        </div>

                        <div class="form-group">
                            <label for="sexo">Sexo</label>
                            <select class="form-control" id="sexo" name="Register_sexo">
                                <option value="Male">Hombre</option>
                                <option value="Female">Mujer</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="rol">Rol</label>
                            <select class="form-control" id="rol" name="Register_Rol">
                                <option value="Vendedor">Vendedor</option>
                                <option value="Comprador">Comprador</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fechaNacimiento">Fecha de Nacimiento</label>
                            <input type="date" id="fechaNacimiento" name="Register_fechaNacimiento"
                                class="form-control">
                        </div>

                        <!-- Domicilio -->
                        <div class="d-flex">
                            <div class="col-md-6  form-group me-2">
                                <label for="estado">Estado</label>
                                <input type="text" id="estado" name="Register_estado" class="form-control">
                            </div>

                            <div class="col-md-6  form-group">
                                <label for="colonia">Colonia</label>
                                <input type="text" id="colonia" name="Register_colonia" class="form-control">
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="col-md-6  form-group me-2">
                                <label for="calle">Calle</label>
                                <input type="text" id="calle" name="Register_calle" class="form-control">
                            </div>

                            <div class="col-md-6  form-group">
                                <label for="numeroCasa">Número de casa</label>
                                <input type="text" id="numeroCasa" name="Register_numeroCasa" class="form-control">
                            </div>
                        </div>
                        <!-- END Domicilio -->

                        <div class="form-group">
                            <label for="tipoPrivacidad">Tipo de Privacidad del Perfil</label>
                            <select class="form-control" id="tipoPrivacidad" name="Register_tipoPrivacidad">
                                <option value="public">Público</option>
                                <option value="private">Privado</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="imagen">Imagen del Perfil</label>
                            <br>
                            <input type="file" id="imagen" name="Register_imagen" class="form-control-file" multiple>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-block btn-primary btn-register">Registrarse</button>
                        </div>
                    </form>


                
                    <hr>
                    <div class="form-group text-center">
                        <p class="text-center">O regístrate con:</p>
                        <button type="button" class="btn btn-block btn-facebook">
                            <i class="fa fa-facebook"></i> Iniciar sesión con Facebook
                        </button>
                        <br>
                        <button type="button" class="btn btn-block btn-google mt-2">
                            <i class="fa fa-google"></i> Iniciar sesión con Google
                        </button>
                        <br>
                        <a href="../General/index.php" class="btn btn-block btn-secondary  mt-3">Volver</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="../../JS/General/registro.js"></script> 
    <script src="../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../Librerias/popper/popper.min.js"></script>
    <script src="../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
</body>

</html>