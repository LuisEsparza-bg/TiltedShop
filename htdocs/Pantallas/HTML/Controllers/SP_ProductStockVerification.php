<?php
//Controlador para Visualizar productos en la vista de php
    // session_start();

    //Librerias
    include_once '../Conexion/DB_Conection.php';
    include '../Models/Products.php';
    


    class ProductStockValidation extends DB
    {

        //CREAR UNA FUNCION QUE CREE LA INSTANCIA DE LA CLASE, LLENE LOS VALORES CON LO BASE A LO RECIBIDO EN LA FUNCION (SI ES APROBADO O NO) Y LUEGO LLAMAR AL SP

        public function ValidarStock($idP, $cantidadP)
        {
            try{

                $db = new DB(); // Crea una instancia de la clase DB para establecer la conexión
                $pdo = $db->connect(); // Obtiene la conexión a la base de datos
                $opcion = 1;

                // Llamar al modelo y pasar la conexión como parámetro
                $productoStockClass = new Products();

                // LLenar los datos para la clase del modelo
                $productoStockClass -> setId($idP);
                $productoStockClass -> setCantidad($cantidadP);
                                
                //LLamar al SP
                $result = $productoStockClass->VerificacionStockProducto($pdo, $opcion);

                if($result == 1){
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
        $productoIdParam = $_POST['productId'];
        $cantidadParam = $_POST['cantidad'];
             
        $verificacionStock = new ProductStockValidation();

        $result = $verificacionStock->ValidarStock($productoIdParam, $cantidadParam);
        
    
        // Devuelve una respuesta JSON al cliente
        header('Content-Type: application/json');
        echo json_encode($result);
    }


    
    
?>