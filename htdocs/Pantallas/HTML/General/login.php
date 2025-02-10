<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../Librerias/bootstrap-5.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../CSS/General Pages/login.css">
    <link rel="stylesheet" href="../../CSS/General Styles/background.css">
    <link rel="stylesheet" href="../../CSS/General Styles/footer.css">
    <!-- Enlace al archivo CSS de Bootstrap -->
</head>

<body class="">

    <div class="row justify-content-center LoginPage_Bar">
        <img src="../../Images/Tilted_Shop_Logo.png" class="Login_Logo" alt="Tilted Shop Icon">

    </div>

    <div class="container justify-content-center">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="container Login_ContainerForms">
                    
                    <form id="Login_Form" method="post" action="#" >

                        <h2 class="Login_FormText"> Iniciar Sesión</h2>
                        <div class="form-group">

                            <label class="Login_Labels">Correo Electronico</label>
                            
                            <input type="text" class="form-control Login_Inputs" name="Login_Email" id="Login_Email"
                                placeholder="Correo electronico" required min="3">
                            
                            <small id="pmessageRTEmail" class="form-text Login_SmallLabel"></small>

                            <!--   PARA MENSAJES DE VALIDACIONES CON JS
                                <small class="form-text Login_SmallLabel">Tilted Shop nunca compartira tu email con terceros.</small>
                                      -->
                        </div>
                        
                        <div class="form-group">
                            <label class="Login_Labels">Contraseña</label>
                            <input type="password" class="form-control Login_Inputs" name="Login_Password" id="Login_Password"
                                placeholder="Contraseña" required required minlength="8" maxlength="15">
                            <small id="pmessageRTPassword" class="form-text Login_SmallLabel"></small>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="Login_RememberMe" class="form-check-input ">
                            <label id="label_RememberMe" class="form-check-label">Recordarme</label>
                        </div>
                        <br>


                        <!-- TO DO: Entender porque si funciona con un div distinto pero no con la clase de MissPasswordText-->
                    

                        <button id="Login_Submit" type="submit" class="btn Login_InputButton">Enviar</button>

                    </form>

                    <div class="text-center">
                        ¿No tienes una cuenta? <a class="Login_MissPasswordText" href="../General/Register.php">
                            Registrarse</a>
                    </div>

                    <!-- TO DO: Entender esta linea-->

                    <div class="CustomLine ">
                        <span class="Letter">O</span>
                    </div>

                    <button type="submit" class="btn Login_ButtonFacebook"> <img class="Login_FBLogo"
                            src="../../Images/Login_Images/FacebookLogo.png" alt="Descripción de la imagen">
                        Iniciar Sesión con Facebook</button>


                </div>
                </form>
            </div>
        </div>
    </div>

        <script src="../../JS/General/login.js"></script>   
    <script src="../../Librerias/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../Librerias/popper/popper.min.js"></script>
    <script src="../../Librerias/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
</body>



<footer>



</footer>

</html>