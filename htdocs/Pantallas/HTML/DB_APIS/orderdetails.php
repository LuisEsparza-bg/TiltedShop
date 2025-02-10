<?php

    session_start();

    include_once '../Conexion/DB_Conection.php';
    include '../Models/Compra.php';
    include '../Models/Products.php';
    include '../Models/DetallesCompra.php';
    include '../Models/DetallesPago.php';

    echo'entre a order detail';

    if(!empty($_POST['paymentID']) && !empty($_POST['payerID']) &&
    !empty($_POST['token'])  ){
        $paymentID = $_POST['paymentID'];
        $payerID = $_POST['payerID'];
        $token = $_POST['token'];
        
        //Variable de control de numero de items en la compra
        $tipoCompra = $_POST['tipoCompra'];
        
        echo'entre a order detail';

        //INICIA CONEXION
        $db = new DB(); // Crea una instancia de la clase DB para establecer la conexión
        $pdo = $db->connect(); // Obtiene la conexión a la base de datos

        //1 Solo elemento directo sin carrito
        if($tipoCompra == 1){
            //OBTENER EL ID DE PRODUCTO Y LA CANTIDAD
            $productId = $_POST['productId'];
            $cantidadP = $_POST['cantidadP'];
            
            //OBTENER EL TIPO DE PRODUCTO
            $tempCompraClass = new Compra();
            $tipoProducto = $tempCompraClass->obtenerTipoProducto($pdo, $productId);

            if($tipoProducto == NULL){
                return ['success' => false];
            }

            //Revisar si es producto normal si puede proceder la compra
            if($tipoProducto == 1){
                $stockCheck = new Products();               
                //Check de stock
                $stockCheck -> setId($productId);
                $stockCheck -> setCantidad($cantidadP);
                //Llamada para obtener el precio del producto y validar la cantidad una vez mas
                $opcion = 1;
                $resultVerification = $stockCheck->VerificacionStockProducto($pdo, $opcion);

                if ($resultVerification != 1) {
                    
                    $pdo = null;
                    return ['success' => false];
                }
            }
            //si es cotizado no hay problema con la cantidad




            // CREAR LA COMRPA NORMAL
                $productoSaleClass = new Compra();
            // Carga de datos
                //Quien compra comprara
                $IdUsuarioComprador = $_SESSION['idUsuario'];
                $productoSaleClass -> setIdUsuarioCliente($IdUsuarioComprador);
                
                //Cuanto paga
                if($tipoProducto == 1){
                    $precioUnit = $productoSaleClass->obtenerPrecioUnitario($pdo,$productId);
                    $TotalCompra = $precioUnit * $cantidadP;
                    $productoSaleClass -> setTotal($TotalCompra);
                }else{
                    //TODO: AQUI IRIA EL PROCESO PARA UN COTIZADO SOLO RECIBIR PARAMETROS DEL CARRITO?
                }

                //Que metodo de pago uso                    
                $productoSaleClass->setIdMetodoPago(3); //SIEMRPE ES 3 PORQUE ES PAYPAL
                
                //Llamada al SP
                $result = $productoSaleClass->realizarCompra($pdo, $opcion);

                //GUARDAR EL ID DE LA COMPRA
                $idCompra = $result;
    

                echo'PROCESE LA COMRPA';
                var_dump($idCompra);


            //CREAR EL REGISTRO DEL METODO DE PAGO EMPLEADO SEGUN EL ID
                $detallesPagoClass = new DetallesPago();
                
                //VARIABLE PARA QUE EL SP SEPA QUE ES COMPRA PAYPAL
                $tipoPago = 2;
                
            // Metodo de pago NORMAL
                //Cual fue la compra
                $detallesPagoClass->setIdCompra($idCompra);
                //Cual fue el metodo de pago usado
                $detallesPagoClass->setIdMetodoPago(3);
                //Transaccion PayPal
                $detallesPagoClass->setTransaccionPaypal($paymentID);
                //CVV
                $detallesPagoClass->setCvv(NULL);
                //NOMBRE TARJETA
                $detallesPagoClass->setNombreTarjeta(NULL);                    
                //NUMERO TARJETA
                $detallesPagoClass->setNumeroTarjeta(NULL);                    
                //FECHA VENCIMINETO
                $detallesPagoClass->setFechaVencimiento(NULL);
                //Llamada al SP
                $result2 = $detallesPagoClass->cargarDetallePago($pdo, $opcion, $tipoPago);
                

            //ALTA DE UN UNICO PRODUCTO
                //Crear la instancia
                    $altaDetalleProducto = new DetalleCompra();
                //Obtener el tipo de Producto
                
                $eleccionSP = 0;
                if ($tipoProducto == 1){
                    $eleccionSP = 1;
                } else {
                    $eleccionSP = 2;
                }

                //ID de la compra
                $altaDetalleProducto->setIdCompra($idCompra);
                //ID del producto
                $altaDetalleProducto->setIdProducto($productId);
                //Cantidad comprada
                if($tipoProducto == 1){
                    $altaDetalleProducto->setCantidad($cantidadP);
                } else{
                    $altaDetalleProducto->setCantidad(1);
                }
                //Precio al que se compro
                if($tipoProducto == 1){
                    $altaDetalleProducto->setPrecioUnitario($precioUnit);
                }else{
                    //TODO: Setear lo que tenga el cotizado
                }
                

                //Descripcion Cotizado
                if($eleccionSP == 1){
                    //Descripcion Cotizada vacia porque es producto normal segun el tipo de compra en este caso
                    $altaDetalleProducto->setDescripcionCotVenta(NULL);
                }else {
                    //TODO: AQUI DEBE IR UN METODO QUE EXTRAIGA EL TEXTO DEL PRODUCTO QUE SE ESTA COMPRANDO, lo mas seguro sera hacer otro metodo
                    //Descripcion Cotizada vacia porque es producto cotizado
                    $altaDetalleProducto->setDescripcionCotVenta(NULL);
                }
                //Llamar a SP
                $result3 = $altaDetalleProducto->cargarDetalleCompra($pdo, $opcion, $eleccionSP);

            
            //Si todo salio bien retornar true (Cambiar esto)
            echo'Transaccion completada';
            var_dump($result3);
            if($result == true && $result2 == true && $result3 == true){
                
                $pdo = null;
                $response = array('success' => true, 'idCompra' => $idCompra);
                echo json_encode($response);
            }else{
                
                $pdo = null;
                return ['success' => false];
            }


        }else{
            //AQUI IRIA EL PROCESO PARA HACER LA COMPRA DESDE EL CARRITO

            //MAS DE UN PRODUCTO
        }


    
    }

?>

