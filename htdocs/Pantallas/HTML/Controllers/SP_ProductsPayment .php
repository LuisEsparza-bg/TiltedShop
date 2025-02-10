<?php
//Controlador para Visualizar productos en la vista de php
    session_start();

    //Librerias
    include_once '../Conexion/DB_Conection.php';
    include '../Models/Products.php';
    include '../Models/Compra.php';
    include '../Models/DetallesCompra.php';
    include '../Models/DetallesPago.php';
    


    class ProductsPayment extends DB
    {
        private $tipoProducto;
        private $nombreTarjeta;
        private $numeroTarjeta;
        private $fechaVencimiento;
        private $cvv;
        private $metodoPago;

        // ... (otros atributos y métodos de la clase)

        public function getTipoProducto()
        {
            return $this->tipoProducto;
        }

        public function setTipoProducto($tipoProducto)
        {
            $this->tipoProducto = $tipoProducto;
            return $this;
        }

        public function getNombreTarjeta()
        {
            return $this->nombreTarjeta;
        }

        public function setNombreTarjeta($nombreTarjeta)
        {
            $this->nombreTarjeta = $nombreTarjeta;
            return $this;
        }

        public function getNumeroTarjeta()
        {
            return $this->numeroTarjeta;
        }

        public function setNumeroTarjeta($numeroTarjeta)
        {
            $this->numeroTarjeta = $numeroTarjeta;
            return $this;
        }

        public function getFechaVencimiento()
        {
            return $this->fechaVencimiento;
        }

        public function setFechaVencimiento($fechaVencimiento)
        {
            $this->fechaVencimiento = $fechaVencimiento;
            return $this;
        }

        public function getCvv()
        {
            return $this->cvv;
        }

        public function setCvv($cvv)
        {
            $this->cvv = $cvv;
            return $this;
        }

        public function getMetodoPago()
        {
            return $this->metodoPago;
        }

        public function setMetodoPago($metodoPago)
        {
            $this->metodoPago = $metodoPago;
            return $this;
        }


        //CREAR UNA FUNCION QUE CREE LA INSTANCIA DE LA CLASE, LLENE LOS VALORES CON LO BASE A LO RECIBIDO EN LA FUNCION (SI ES APROBADO O NO) Y LUEGO LLAMAR AL SP

        public function saleProcess ($idP, $cantidadP)
        {
            try{

                $db = new DB(); // Crea una instancia de la clase DB para establecer la conexión
                $pdo = $db->connect(); // Obtiene la conexión a la base de datos
                $opcion = 1;



                //VERIFICAR QUE EL PRODUCTO SE PUEDE COMPRAR  
                    $stockCheck = new Products();               
                    //Check de stock
                    $stockCheck -> setId($idP);
                    $stockCheck -> setCantidad($cantidadP);
                    //Llamada para obtener el precio del producto y validar la cantidad una vez mas
                    $resultVerification = $stockCheck->VerificacionStockProducto($pdo, $opcion);

                    if ($resultVerification != 1) {
                        
                        $pdo = null;
                        return ['success' => false];
                    }


                // CREAR LA COMRPA NORMAL
                    $productoSaleClass = new Compra();
                // Carga de datos
                    //Quien compra comprara
                    $IdUsuarioComprador = $_SESSION['idUsuario'];
                    $productoSaleClass -> setIdUsuarioCliente($IdUsuarioComprador);
                    
                    //Cuanto paga
                    $precioUnit = $productoSaleClass->obtenerPrecioUnitario($pdo,$idP);
                    $TotalCompra = $precioUnit * $cantidadP;
                    $productoSaleClass -> setTotal($TotalCompra);


                    //Que metodo de pago uso                    
                    $productoSaleClass->setIdMetodoPago($this->metodoPago);
                    
                    //Llamada al SP
                    $result = $productoSaleClass->realizarCompra($pdo, $opcion);

                    //GUARDAR EL ID DE LA COMPRA
                    $idCompra = $result;
        

                //CREAR EL REGISTRO DEL METODO DE PAGO EMPLEADO SEGUN EL ID
                    $detallesPagoClass = new DetallesPago();
                    
                    // TIPO DE PAGO
                    //Analizar cual es el metodo de pago usado para avisar en el sp con el tipo de pago
                    $tipoPago = 0;
                    if($this->metodoPago == 1 || $this->metodoPago == 2){
                        $tipoPago = 1;
                    }elseif($this->metodoPago == 3){
                        $tipoPago = 2;
                    }
                
                // Metodo de pago NORMAL
                    //Cual fue la compra
                    $detallesPagoClass->setIdCompra($idCompra);
                    //Cual fue el metodo de pago usado
                    $detallesPagoClass->setIdMetodoPago($this->metodoPago);
                    //Transaccion PayPal
                    $detallesPagoClass->setTransaccionPaypal(NULL);
                    //CVV
                    $detallesPagoClass->setCvv($this->cvv);
                    //NOMBRE TARJETA
                    $detallesPagoClass->setNombreTarjeta($this->nombreTarjeta);                    
                    //NUMERO TARJETA
                    $detallesPagoClass->setNumeroTarjeta($this->numeroTarjeta);                    
                    //FECHA VENCIMINETO
                    $detallesPagoClass->setFechaVencimiento($this->fechaVencimiento);
                    //Llamada al SP
                    $result2 = $detallesPagoClass->cargarDetallePago($pdo, $opcion, $tipoPago);
                    echo "Respuesta Detalle pago:";
                    var_dump($result2);

                //AQUI IRIA EL METODO DE PAYPAL


                //Aqui hacer el alta del producto para detalles de la compra contemplar todos los datos igual
                //ALTA DE UN UNICO PRODUCTO
                    //Crear la instancia
                    $altaDetalleProducto = new DetalleCompra();
                    //Obtener el tipo de Producto
                    $productos = $stockCheck->DetallesProducto($pdo, $opcion);
                    $esCotizado = $productos[0]['Validacion'];
                    $tipoProducto = 0;
                    if ($esCotizado == 1){
                        $tipoProducto = 2;
                    } else {
                        $tipoProducto = 1;
                    }
                            //echo "Tipo Producto:";
                            //var_dump($tipoProducto);
                    //ID de la compra
                    $altaDetalleProducto->setIdCompra($idCompra);
                    //ID del producto
                    $altaDetalleProducto->setIdProducto($idP);
                    //Cantidad comprada
                    $altaDetalleProducto->setCantidad($cantidadP);
                    //Precio al que se compro
                    $altaDetalleProducto->setPrecioUnitario($precioUnit);
                    //Descripcion Cotizado
                    if($tipoProducto == 1){
                        //Descripcion Cotizada vacia porque es producto normal segun el tipo de compra en este caso
                        $altaDetalleProducto->setDescripcionCotVenta(NULL);
                    }else {
                        //TODO: AQUI DEBE IR UN METODO QUE EXTRAIGA EL TEXTO DEL PRODUCTO QUE SE ESTA COMPRANDO, lo mas seguro sera hacer otro metodo
                        //Descripcion Cotizada vacia porque es producto cotizado
                        $altaDetalleProducto->setDescripcionCotVenta(NULL);
                    }
                    //Llamar a SP
                    $result3 = $altaDetalleProducto->cargarDetalleCompra($pdo, $opcion, $tipoProducto);

                
                //Si todo salio bien retornar true (Cambiar esto)
                if($result == true && $result2 == true && $result3 == true){
                    echo "Respuesta Detalle 2 pago:";
                    var_dump($result3);
                    $pdo = null;
                    return ['success' => true, 'idCompra' => $result]; 
                }else{
                    echo "Respuesta Detalle 3 pago:";
                    var_dump($result3);
                    $pdo = null;
                    return ['success' => false];
                }

                //Si algo salio mal enviar el dato
                
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
        //Si es compra de producto normal por metodo de pago normal
        $productoIdParam = $_POST['productId'];
        $cantidadParam = $_POST['cantidad'];

        $tipoProductoParam = $_POST['tipoProd']; //(NORMAL O COTIZADO)
        $tipoCompraParam = $_POST['tipoCom']; // 1 PRODUCTO O MULTIPRODUCTO  CREO QUE TAMPOCO IMPORTA
        
        $nombreTarjetaParam = $_POST['nombreTarjeta'];
        $numeroTarjetaParam = $_POST['numeroTarjeta'];
        $fechaVencimientoParam = $_POST['fechaVencimiento'];
        $cvvParam = $_POST['cvv'];
        $metodoParam = $_POST['metodoP'];

        //var_dump($metodoParam);
        
        $efectuarCompra = new ProductsPayment();

        $efectuarCompra->setMetodoPago($metodoParam);
        $efectuarCompra->setCvv($cvvParam);
        $efectuarCompra->setNombreTarjeta($nombreTarjetaParam);
        $efectuarCompra->setNumeroTarjeta($numeroTarjetaParam);
        $efectuarCompra->setFechaVencimiento($fechaVencimientoParam);

        $result = $efectuarCompra->saleProcess($productoIdParam, $cantidadParam);


        //TODO: FALTA CONTEMPLAR EL PROCESO DE PAGO CUANDO ES UN COTIZADO
        //NO HACE FALTA CONTEMPLAR EL PROCESO DE PAGO PAR A MUCHOS PRODUCTOS ESO LO HIZO LUIS
        //NO HACE FALTA CONTEMPLAR EL TIPO DE COMPRA NORMAL O PAYPAL YA QUE ESO OCURRE EN ORDER DETAILS
        
    
        // Devuelve una respuesta JSON al cliente
        header('Content-Type: application/json');
        echo json_encode($result);
    }


    
    
?>