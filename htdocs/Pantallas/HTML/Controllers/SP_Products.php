<?php

session_start();

include_once '../Conexion/DB_Conection.php';
include '../Models/Categories.php';
include '../Models/Products.php';
include '../Models/CatProd.php';
include '../Models/Imagenes_Productos.php';
include '../Models/Videos.php';

$GlobalUserName;

class ProductosG extends DB
{
    public function GestionProductos()
    {

        $opcion = $_POST["opcion"];

        // SP PARA PRODUCTOS NORMALES, ALTA

        if ($opcion == 1) {

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $nombreProducto = $_POST["nombreProducto"];
                $descripcionProducto = $_POST["descripcionProducto"];
                $inventarioProducto = $_POST["inventarioProducto"];
                $precioProducto = $_POST["precioProducto"];
                $linkVideo = $_POST["linkvideo"];

                $tipo = $_POST["tipo"];

                if(isset($_FILES['imagen1']) && $_FILES['imagen1']['error'] === UPLOAD_ERR_OK) {
                    $blob1 = file_get_contents($_FILES['imagen1']['tmp_name']);
                }

                if(isset($_FILES['imagen2']) && $_FILES['imagen2']['error'] === UPLOAD_ERR_OK) {
                    $blob2 = file_get_contents($_FILES['imagen2']['tmp_name']);
                }

                if(isset($_FILES['imagen3']) && $_FILES['imagen3']['error'] === UPLOAD_ERR_OK) {
                    $blob3 = file_get_contents($_FILES['imagen3']['tmp_name']);
                }


                $producto = new Products(
                    id: 0,
                    idAdmin: 0,
                    idVendedor: 0,
                    nombre: $nombreProducto,
                    descripcion: $descripcionProducto,
                    tipo: $tipo,
                    precio: $precioProducto,
                    cantidad: $inventarioProducto,
                    descripcionC: "",
                    estatus: 1,
                    validacion: 1,
                    fechaAlta: ""
                );

                $categorias = json_decode($_POST['Categorias'], true);
                $categorias = new CatProd(
                    id: 0,
                    producto: 0,
                    idCategoria: $categorias,
                    estatus: 1
                );

                $imagenes1 = new ImagenProducto(
                    imagen: $blob1
                );

                $imagenes2 = new ImagenProducto(
                    imagen: $blob2
                );

                $imagenes3 = new ImagenProducto(
                    imagen: $blob3
                );


                $linkVideos= new VideosProducto(
                    video: $linkVideo
                );


                $db = new DB();
                $pdo = $db->connect();

                

                if ($pdo) {
                    try {
                        $result = $producto->CrearProducto($pdo, $opcion, $username);

                        $IDproducto = new Products(
                        );
                        $IDproducto = $IDproducto->GetLastProductID($pdo);
                        $result = $categorias->CategoriasProductos($pdo, 1);
                        if($blob1)
                        $result = $imagenes1->CrearProducto($pdo, $IDproducto);
                        if($blob2)
                        $result = $imagenes2->CrearProducto($pdo, $IDproducto);
                        if($blob3)
                        $result = $imagenes3->CrearProducto($pdo, $IDproducto);
                        $result = $linkVideos->CrearProducto($pdo, $IDproducto);
                        return $result;
                    } catch (PDOException $e) {
                        return "Error: " . $e->getMessage();
                    }
                }

            }

        }


        // SP PARA PRODUCTOS NORMALES, MODIFICACION


        if ($opcion == 2) {

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $nombreProducto = $_POST["nombreProducto"];
                $descripcionProducto = $_POST["descripcionProducto"];
                $inventarioProducto = $_POST["inventarioProducto"];
                $precioProducto = $_POST["precioProducto"];
                $tipo = $_POST["tipo"];
                $idProducto = $_POST["IDProducto"];


                $producto = new Products(
                    id: $idProducto,
                    idAdmin: 0,
                    idVendedor: 0,
                    nombre: $nombreProducto,
                    descripcion: $descripcionProducto,
                    tipo: $tipo,
                    precio: $precioProducto,
                    cantidad: $inventarioProducto,
                    descripcionC: "",
                    estatus: 1,
                    validacion: 1,
                    fechaAlta: ""
                );

                $BoolNewCategories = 0;
                $BoolDeletedCategories = 0;

                if (isset($_POST['NewCategorias'])) {
                    $categorias = $_POST["NewCategorias"];
                    $categoriasNew = new CatProd(
                        id: 0,
                        producto: $idProducto,
                        idCategoria: $categorias,
                        estatus: 1
                    );
                    $BoolNewCategories = 1;
                }

                if (isset($_POST['DeleteCategorias'])) {
                    $categorias2 = $_POST["DeleteCategorias"];
                    $categoriasDelete = new CatProd(
                        id: 0,
                        producto: $idProducto,
                        idCategoria: $categorias2,
                        estatus: 1
                    );
                    $BoolDeletedCategories = 1;
                }


                $db = new DB();
                $pdo = $db->connect();

                if ($pdo) {
                    try {
                        $result = $producto->CrearProducto($pdo, $opcion, $username);
                        if ($BoolNewCategories == 1)
                            $result = $categoriasNew->CategoriasProductos($pdo, 2);
                        if ($BoolDeletedCategories == 1)
                            $result = $categoriasDelete->CategoriasProductos($pdo, 3);
                        return $result;
                    } catch (PDOException $e) {
                        return "Error: " . $e->getMessage();
                    }
                }

            }

        }



        // SP PARA PRODUCTOS NORMALES, BAJA


        if ($opcion == 3) {

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $idProducto = $_POST["IDProducto"];


                $producto = new Products(
                    id: $idProducto,
                    idAdmin: 0,
                    idVendedor: 0,
                    nombre: "",
                    descripcion: "",
                    tipo: 1,
                    precio: 1,
                    cantidad: "",
                    descripcionC: "",
                    estatus: 1,
                    validacion: 1,
                    fechaAlta: ""
                );

                $db = new DB();
                $pdo = $db->connect();

                if ($pdo) {
                    try {
                        $result = $producto->CrearProducto($pdo, $opcion, $username);
                        return $result;
                    } catch (PDOException $e) {
                        return "Error: " . $e->getMessage();
                    }
                }

            }

        }


        if ($opcion == 4) {

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $nombreProducto = $_POST["nombreProducto"];
                $descripcionProducto = $_POST["descripcionProducto"];
                $tipo = $_POST["tipo"];
                $linkVideo = $_POST["linkvideo"];
                $opcion = 1;

                if(isset($_FILES['imagen1']) && $_FILES['imagen1']['error'] === UPLOAD_ERR_OK) {
                    $blob1 = file_get_contents($_FILES['imagen1']['tmp_name']);
                }

                if(isset($_FILES['imagen2']) && $_FILES['imagen2']['error'] === UPLOAD_ERR_OK) {
                    $blob2 = file_get_contents($_FILES['imagen2']['tmp_name']);
                }

                if(isset($_FILES['imagen3']) && $_FILES['imagen3']['error'] === UPLOAD_ERR_OK) {
                    $blob3 = file_get_contents($_FILES['imagen3']['tmp_name']);
                }


                $imagenes1 = new ImagenProducto(
                    imagen: $blob1
                );

                $imagenes2 = new ImagenProducto(
                    imagen: $blob2
                );

                $imagenes3 = new ImagenProducto(
                    imagen: $blob3
                );

                $linkVideos= new VideosProducto(
                    video: $linkVideo
                );




                $producto = new Products(
                    id: 0,
                    idAdmin: 0,
                    idVendedor: 0,
                    nombre: $nombreProducto,
                    descripcion: $descripcionProducto,
                    tipo: $tipo,
                    precio: 0,
                    cantidad: "",
                    descripcionC: "",
                    estatus: 1,
                    // LA VALIDACION ES 0 DEBIDO A QUE SE DEBE PRIMERO VALIDAR EL PRODUCTO POR EL ADMIN
                    validacion: null,
                    fechaAlta: ""
                );

                $categorias = json_decode($_POST['Categorias'], true);
                $categorias = new CatProd(
                    id: 0,
                    producto: 0,
                    idCategoria: $categorias,
                    estatus: 1
                );

                $db = new DB();
                $pdo = $db->connect();

                if ($pdo) {
                    try {
                        $result = $producto->CrearProducto($pdo, $opcion, $username);
                        $IDproducto = new Products(
                        );
                        $IDproducto = $IDproducto->GetLastProductID($pdo);
                        $result = $categorias->CategoriasProductos($pdo, 1);
                        if($blob1)
                        $result = $imagenes1->CrearProducto($pdo, $IDproducto);
                        if($blob2)
                        $result = $imagenes2->CrearProducto($pdo, $IDproducto);
                        if($blob3)
                        $result = $imagenes3->CrearProducto($pdo, $IDproducto);
                        $result = $linkVideos->CrearProducto($pdo, $IDproducto);
                        return $result;
                    } catch (PDOException $e) {
                        return "Error: " . $e->getMessage();
                    }
                }

            }

        }

        if ($opcion == 5) {

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $nombreProducto = $_POST["nombreProducto"];
                $descripcionProducto = $_POST["descripcionProducto"];
                $tipo = $_POST["tipo"];
                $idProducto = $_POST["IDProducto"];
                $validacion = $_POST["Validacion"];


                $producto = new Products(
                    id: $idProducto,
                    // TODO: Obtener el Admin ID que lo valido. (IMPORTANTE QUE SINO SE SOBREESCRIBE);
                    idAdmin: 0,
                    idVendedor: 0,
                    nombre: $nombreProducto,
                    descripcion: $descripcionProducto,
                    tipo: $tipo,
                    precio: 0,
                    cantidad: 0,
                    descripcionC: "",
                    estatus: 1,
                    validacion: $validacion,
                    fechaAlta: ""
                );

                $BoolNewCategories = 0;
                $BoolDeletedCategories = 0;

                if (isset($_POST['NewCategorias'])) {
                    $categorias = $_POST["NewCategorias"];
                    $categoriasNew = new CatProd(
                        id: 0,
                        producto: $idProducto,
                        idCategoria: $categorias,
                        estatus: 1
                    );
                    $BoolNewCategories = 1;
                }

                if (isset($_POST['DeleteCategorias'])) {
                    $categorias2 = $_POST["DeleteCategorias"];
                    $categoriasDelete = new CatProd(
                        id: 0,
                        producto: $idProducto,
                        idCategoria: $categorias2,
                        estatus: 1
                    );
                    $BoolDeletedCategories = 1;
                }


                $opcion = 2;

                $db = new DB();
                $pdo = $db->connect();

                if ($pdo) {
                    try {
                        $result = $producto->CrearProducto($pdo, $opcion, $username);
                        if ($BoolNewCategories == 1)
                            $result = $categoriasNew->CategoriasProductos($pdo, 2);
                        if ($BoolDeletedCategories == 1)
                            $result = $categoriasDelete->CategoriasProductos($pdo, 3);
                        return $result;
                    } catch (PDOException $e) {
                        return "Error: " . $e->getMessage();
                    }
                }

            }

        }



    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productoAlta = new ProductosG();
    $result = $productoAlta->GestionProductos();
    if ($result === true) {
    } else {
        echo $result;
    }
}

?>