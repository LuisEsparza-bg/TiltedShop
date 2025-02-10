<?php
//Controlador para Visualizar productos en la vista de php
    session_start();

    //Librerias
    include_once '../Conexion/DB_Conection.php';
    include '../Models/Products.php';
    


    class ProductsValidation extends DB
    {

        //CREAR UNA FUNCION QUE CREE LA INSTANCIA DE LA CLASE, LLENE LOS VALORES CON LO BASE A LO RECIBIDO EN LA FUNCION (SI ES APROBADO O NO) Y LUEGO LLAMAR AL SP

        public function Validar($Val_Result, $Val_ID)
        {
            try{

                $db = new DB(); // Crea una instancia de la clase DB para establecer la conexión
                $pdo = $db->connect(); // Obtiene la conexión a la base de datos
                $opcion = 1;

                // Llamar al modelo y pasar la conexión como parámetro
                $productosValClass = new Products();

                // LLenar los datos para la clase del modelo
                $Val_IdAdmin = $_SESSION['idUsuario'];
                
                $productosValClass -> setId($Val_ID);
                $productosValClass -> setValidacion($Val_Result);
                $productosValClass -> setIdAdmin($Val_IdAdmin);
                
                //LLamar al SP
                $productosValClass->ValidacionDeProducto($pdo, $opcion);
                
                $pdo = null;
                // Cambiar el true por el value que venga el result del metodo ValidacionDeProducto
                return ['success' => true]; //Si dejara eso como el retorno de la variable tendria que cambiar le if del ajax ya que espera un success

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
        $action = $_POST['Result'];
        $productId = $_POST['IdProduct'];
        //$adminId = $_POST['IdAdmin'];
     
        $productoValidacion = new ProductsValidation();

        $result = $productoValidacion->Validar($action, $productId);
        
    
        // Devuelve una respuesta JSON al cliente
        header('Content-Type: application/json');
        echo json_encode($result);
    }


    
    
?>