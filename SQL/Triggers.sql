DELIMITER //
CREATE TRIGGER tr_NuevoCarrito AFTER INSERT ON tb_Usuario
FOR EACH ROW
BEGIN
    -- Verificar si el nuevo usuario tiene el rol deseado (rol = 3)
    IF NEW.ID_Roles = 3 THEN
        -- Insertar un nuevo registro en la tabla tb_Carrito con la ID del nuevo usuario
        INSERT INTO tb_Carrito (ID_Usuario_Cliente) VALUES (NEW.ID_Usuario);
    END IF;
END;
//
DELIMITER ;

-- DROP TRIGGER tgr_Restar_Inventario;
DELIMITER //
CREATE TRIGGER tgr_Restar_Inventario
AFTER INSERT ON tb_Detalles_Compra
FOR EACH ROW
BEGIN
    -- Verifica si el producto es cotizado
    DECLARE es_cotizado BOOLEAN;
    
    SELECT Tipo_Producto INTO es_cotizado
    FROM tb_Productos
    WHERE ID_Producto = NEW.ID_Producto;

    IF es_cotizado = 1 THEN
        -- Resta la cantidad del inventario
        UPDATE tb_Productos
        SET Cantidad = Cantidad - NEW.Cantidad
        WHERE ID_Producto = NEW.ID_Producto;
    END IF;
END //
DELIMITER ;


-- TRIGGER de poner en las listas estatus 0 a los productos eliminados y en el carrito.
