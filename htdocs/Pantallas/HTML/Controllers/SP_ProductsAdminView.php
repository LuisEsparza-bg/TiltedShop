<?php
//Controlador para Visualizar productos en la vista de php

    //Librerias
    include_once '../Conexion/DB_Conection.php';
    include '../Models/Products.php';
    


    class ProdcuctsValidationView extends DB
    {

        public function VisualizarProductosValidacion()
        {
            $db = new DB(); // Crea una instancia de la clase DB para establecer la conexión
            $pdo = $db->connect(); // Obtiene la conexión a la base de datos
            $opcion = 1;

            // Llamar al modelo y pasar la conexión como parámetro
            $productosValClass = new Products();
            $productos = $productosValClass->VerProductosPorValidar($pdo, $opcion);
            $pdo = null;
            return $productos;
        }

    }


    
    
?>