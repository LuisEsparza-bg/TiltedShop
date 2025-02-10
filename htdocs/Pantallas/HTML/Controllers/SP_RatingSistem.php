<?php
//Controlador para Visualizar productos en la vista de php
    session_start();

    //Librerias
    include_once '../Conexion/DB_Conection.php';
    include '../Models/Products.php';
    include '../Models/Valoracion.php';
    include '../Models/Comentarios.php';
    


    class ProductValoration extends DB
    {

        //CREAR UNA FUNCION QUE CREE LA INSTANCIA DE LA CLASE, LLENE LOS VALORES CON LO BASE A LO RECIBIDO EN LA FUNCION (SI ES APROBADO O NO) Y LUEGO LLAMAR AL SP

        public function registrarValoracion($idP, $valoracionP, $comentarioP)
        {
            try{

                $db = new DB(); // Crea una instancia de la clase DB para establecer la conexión
                $pdo = $db->connect(); // Obtiene la conexión a la base de datos
                $opcion = 1;

                //Quien valora
                $idUsuarioValorador = $_SESSION['idUsuario'];


                //CARGAR LA VALORACION NUMERICA
                    // Crear una instancia de la clase
                    $valoracionClass = new Valoracion();
                    
                    // LLenar los datos para la clase del modelo
                    //Asignar el valor de el usuario que valora
                    $valoracionClass -> setIdUsuarioCliente($idUsuarioValorador);
                    //Asignar el producto que se valora                    
                    $valoracionClass -> setIdProducto($idP);
                    //Asignar el valor de la valoracion
                    $valoracionClass -> setCalificacion($valoracionP);
                      
                    //LLamar al SP
                    $result = $valoracionClass->valoracionProducto($pdo, $opcion);

                    //echo'Aqui llegue bien';
                    //var_dump($result);
                //END

                //CARGAR LA RESEÑA DE TEXTP
                    // Crear una instancia de la clase
                    $comentarioClass = new Comentario();
                    
                    // LLenar los datos para la clase del modelo
                    //Asignar el valor de el usuario que valora
                    $comentarioClass -> setIdUsuarioCliente($idUsuarioValorador);
                    //Asignar el producto que se valora                    
                    $comentarioClass -> setIdProducto($idP);
                    //Asignar el valor de la valoracion
                    $comentarioClass -> setContenido($comentarioP);

                    //LLamar al SP
                    $result2 = $comentarioClass->comentarProducto($pdo, $opcion);

                    
                    //echo'Que pasa aqui';
                    //var_dump($result2);
                //END
                                
                

                if($result == true && $result2 == true){
                    $pdo = null;
                    return ['success' => true]; 
                }else{
                    $pdo = null;
                    return ['success' => false];
                }
                
            } catch (Exception $e) {
                return ['success' => false, 'error' => $e->getMessage()];
            } finally {
                if (isset($pdo)) {
                   // $pdo = null;
                }
            }

        }


    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $valoracionParam = $_POST['valoracionProducto'];
        $comentarioParam = $_POST['comentarioProducto'];
        $productoIdParam = $_POST['idProducto'];

             
        $altaValoracion = new ProductValoration();

        $result = $altaValoracion->registrarValoracion($productoIdParam, $valoracionParam, $comentarioParam);
        
    
        // Devuelve una respuesta JSON al cliente
        header('Content-Type: application/json');
        echo json_encode($result);
    }


    
    
?>