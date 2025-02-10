DELIMITER //
CREATE PROCEDURE SP_GetImagenesProducto(IN p_ID_Producto INT)
BEGIN
    SELECT ID_Imagenes_Producto, ID_Producto, Imagen
    FROM tb_Producto_Imagenes
    WHERE ID_Producto = p_ID_Producto;
END //
DELIMITER ;

DELIMITER $$

CREATE PROCEDURE SP_ObtenerProductosLista(IN in_ID_Lista INT)
BEGIN
    SELECT 
        LP.ID_Lista,
        P.ID_Producto,
        P.Nombre_Producto,
        P.Precio_Unitario,
        P.Tipo_Producto,
        (SELECT I.Imagen 
         FROM tb_Producto_Imagenes I 
         WHERE P.ID_Producto = I.ID_Producto 
         LIMIT 1) AS Imagen
    FROM 
        tb_Lista_Productos LP
    INNER JOIN
        tb_Productos P ON LP.ID_Producto = P.ID_Producto
    WHERE
        LP.ID_Lista = in_ID_Lista AND LP.Estatus = 1 AND P.Estatus = 1;
END $$


DELIMITER //

CREATE PROCEDURE SP_ProductosVendedor(IN user_id VARCHAR(15), IN opcion INT)
BEGIN
	IF opcion = 1 THEN
        SELECT 
            p.ID_Producto,
            p.ID_Usuario_Admin,
            p.ID_Usuario_Vendedor,
            p.Nombre_Producto,
            p.Descripcion_Producto,
            p.Tipo_Producto,
            p.Precio_Unitario,
            p.Cantidad,
            p.Descripcion_Cotizado,
            p.Estatus,
            p.Validacion,
            p.Fecha_Alta,
            (SELECT pi.Imagen FROM tb_Producto_Imagenes pi WHERE pi.ID_Producto = p.ID_Producto LIMIT 1) AS Imagen,
            GROUP_CONCAT(c.Nombre_Categoria) AS Categorias
        FROM tb_Productos p
        LEFT JOIN tb_Categoria_Producto cp ON p.ID_Producto = cp.ID_Producto AND cp.Estatus = 1
        LEFT JOIN tb_Categorias c ON cp.ID_Categoria = c.ID_Categoria
        WHERE p.ID_Usuario_Vendedor = user_id AND p.Estatus = 1 AND p.Validacion = 1 AND p.Tipo_Producto = 1
        GROUP BY p.ID_Producto;
    ELSEIF opcion = 2 THEN
     SELECT 
            p.ID_Producto,
            p.ID_Usuario_Admin,
            p.ID_Usuario_Vendedor,
            p.Nombre_Producto,
            p.Descripcion_Producto,
            p.Tipo_Producto,
            p.Precio_Unitario,
            p.Cantidad,
            p.Descripcion_Cotizado,
            p.Estatus,
            p.Validacion,
            p.Fecha_Alta,
            (SELECT pi.Imagen FROM tb_Producto_Imagenes pi WHERE pi.ID_Producto = p.ID_Producto LIMIT 1) AS Imagen,
            GROUP_CONCAT(c.Nombre_Categoria) AS Categorias
        FROM tb_Productos p
        LEFT JOIN tb_Categoria_Producto cp ON p.ID_Producto = cp.ID_Producto AND cp.Estatus = 1
        LEFT JOIN tb_Categorias c ON cp.ID_Categoria = c.ID_Categoria
        WHERE p.ID_Usuario_Vendedor = user_id AND p.Estatus = 1 AND p.Validacion = 1 AND p.Tipo_Producto = 0
        GROUP BY p.ID_Producto;
    ELSE
    SELECT 'Opción no válida' AS Resultado;
    END IF;
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE SP_MisProductos(IN p_username VARCHAR(15), IN p_option INT, IN p_product_id INT)
BEGIN
    DECLARE user_id INT;

    -- Obtener el ID del usuario a partir del nombre de usuario
    SELECT ID_Usuario INTO user_id
    FROM tb_Usuario
    WHERE Username = p_username;

    -- Verificar la opción y ejecutar la consulta correspondiente
    IF p_option = 1 THEN
        -- Opción 1: Obtener todos los productos del usuario con Estatus = 1
        SELECT 
            p.ID_Producto,
            p.ID_Usuario_Admin,
            p.ID_Usuario_Vendedor,
            p.Nombre_Producto,
            p.Descripcion_Producto,
            p.Tipo_Producto,
            p.Precio_Unitario,
            p.Cantidad,
            p.Descripcion_Cotizado,
            p.Estatus,
            p.Validacion,
            p.Fecha_Alta,
            (SELECT pi.Imagen FROM tb_Producto_Imagenes pi WHERE pi.ID_Producto = p.ID_Producto LIMIT 1) AS Imagen,
            GROUP_CONCAT(c.Nombre_Categoria) AS Categorias
        FROM tb_Productos p
        LEFT JOIN tb_Categoria_Producto cp ON p.ID_Producto = cp.ID_Producto AND cp.Estatus = 1
        LEFT JOIN tb_Categorias c ON cp.ID_Categoria = c.ID_Categoria
        WHERE p.ID_Usuario_Vendedor = user_id AND p.Estatus = 1
        GROUP BY p.ID_Producto;
    ELSEIF p_option = 2 THEN
        -- Opción 2: Obtener un producto específico del usuario por su ID con Estatus = 1
        SELECT 
            ID_Producto,
            ID_Usuario_Admin,
            ID_Usuario_Vendedor,
            Nombre_Producto,
            Descripcion_Producto,
            Tipo_Producto,
            Precio_Unitario,
            Cantidad,
            Descripcion_Cotizado,
            Estatus,
            Validacion,
            Fecha_Alta,
            (SELECT pi.Imagen FROM tb_Producto_Imagenes pi WHERE pi.ID_Producto = ID_Producto LIMIT 1) AS Imagen
        FROM Vista_misProductos
        WHERE ID_Usuario_Vendedor = user_id AND ID_Producto = p_product_id AND Estatus = 1;
    ELSE
        -- Opción no válida
        SELECT 'Opción no válida' AS Resultado;
    END IF;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_ObtenerImagenesProducto(
    IN p_IdProducto INT
)
BEGIN
    -- Obtener las primeras 3 imágenes del producto
    SELECT Imagen
    FROM tb_Producto_Imagenes
    WHERE ID_Producto = p_IdProducto
    ORDER BY ID_Imagenes_Producto ASC
    LIMIT 3;

END //

DELIMITER ;

CREATE PROCEDURE SP_ObtenerDetallesCompra(IN ID_Usuario INT)
BEGIN
    SELECT 
        co.ID_Compra,
        p.Nombre_Producto, 
        p.ID_Producto, 
        dc.Precio_Unitario, 
        (SELECT GROUP_CONCAT(c.Nombre_Categoria) FROM tb_Categoria_Producto cp INNER JOIN tb_Categorias c ON cp.ID_Categoria = c.ID_Categoria WHERE cp.ID_Producto = p.ID_Producto) AS Categorias,
        co.Total, 
        co.Fecha_Hora, 
        (SELECT pi.Imagen FROM tb_Producto_Imagenes pi WHERE pi.ID_Producto = p.ID_Producto LIMIT 1) AS Imagen,
        AVG(v.Calificacion) AS Calificacion_Promedio
    FROM 
        tb_Compra co 
        INNER JOIN tb_Detalles_Compra dc ON co.ID_Compra = dc.ID_Compra
        INNER JOIN tb_Productos p ON dc.ID_Producto = p.ID_Producto
        LEFT JOIN tb_Valoracion v ON p.ID_Producto = v.ID_Producto
    WHERE 
        co.ID_Usuario_Cliente = ID_Usuario
    GROUP BY
        co.ID_Compra, p.Nombre_Producto, p.ID_Producto, dc.Precio_Unitario,
        co.Total, co.Fecha_Hora, Imagen
    ORDER BY 
        co.Fecha_Hora DESC;
END $$

DELIMITER ;


DELIMITER //

CREATE PROCEDURE SP_ObtenerProductosCarrito(IN p_ID_Carrito INT)
BEGIN
    SELECT 
        pc.ID_Producto, 
        p.Nombre_Producto, 
        pc.Cantidad as Cantidad_Carrito,
        pc.Precio,
        pc.Descripcion_Cot_Venta,
        p.Descripcion_Producto,
        p.Tipo_Producto,
        (SELECT i.Imagen 
         FROM tb_Producto_Imagenes i
         WHERE i.ID_Producto = pc.ID_Producto
         ORDER BY i.ID_Imagenes_Producto DESC
         LIMIT 1) AS Imagen
    FROM tb_Productos_Carrito pc
    JOIN tb_Productos p ON pc.ID_Producto = p.ID_Producto
    WHERE pc.ID_Carrito = p_ID_Carrito AND pc.Estatus = 1;
END //

DELIMITER ;

DELIMITER //
CREATE PROCEDURE SP_ProductoDescriptivo(
    IN p_IdProducto INT,
    IN p_Opcion INT
)
BEGIN
    DECLARE v_Tipo_Producto BIT;
    DECLARE v_Estatus BIT;
    DECLARE v_Validacion BIT;

    -- Obtener información del producto
    SELECT Tipo_Producto, Estatus, Validacion
    INTO v_Tipo_Producto, v_Estatus, v_Validacion
    FROM tb_Productos
    WHERE ID_Producto = p_IdProducto;

    -- Verificar las condiciones
    IF v_Estatus = 1 AND v_Validacion = 1  THEN
        IF p_Opcion = 1 THEN
            -- Opción 1: Solo información del producto
            SELECT
                p.ID_Producto,
                p.ID_Usuario_Admin,
                p.ID_Usuario_Vendedor,
                p.Nombre_Producto,
                p.Descripcion_Producto,
                p.Tipo_Producto,
                p.Precio_Unitario,
                p.Cantidad,
                p.Descripcion_Cotizado,
                p.Estatus,
                p.Validacion,
                p.Fecha_Alta
            FROM tb_Productos p
            WHERE p.ID_Producto = p_IdProducto;
        ELSEIF p_Opcion = 2 THEN
            -- Opción 2: Nombre del usuario vendedor
            SELECT
                u.Username AS Nombre_Vendedor
            FROM tb_Productos p
            JOIN tb_Usuario u ON p.ID_Usuario_Vendedor = u.ID_Usuario
            WHERE p.ID_Producto = p_IdProducto;
        END IF;
    END IF;

END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_GestionCarrito(
    IN p_opcion INT,
    IN p_ID_Carrito INT,
    IN p_ID_Producto INT,
    IN p_Cantidad INT,
    IN p_Precio DECIMAL(10, 2),
    IN p_Descripcion_Cot_Venta VARCHAR(300),
    IN p_Estatus INT
)
BEGIN
    DECLARE v_Stock INT;
    DECLARE v_ExistingProductCount INT;
    DECLARE v_Tipo INT;
    DECLARE v_Estatus INT;

    -- Validar la opción
    CASE p_opcion
        WHEN 1 THEN
            -- Validar que la cantidad no sea mayor que el stock del producto
            SELECT Cantidad INTO v_Stock FROM tb_Productos WHERE ID_Producto = p_ID_Producto;
            SELECT Tipo_Producto INTO v_Tipo FROM tb_Productos WHERE ID_Producto = p_ID_Producto;
            SELECT Estatus INTO v_Estatus FROM tb_Productos WHERE ID_Producto = p_ID_Producto;
            
             IF v_Estatus = 0 THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'El producto ya no se encuentra disponible';
            END IF;
            
            IF v_Tipo = 1 THEN
            IF p_Cantidad > v_Stock THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'La cantidad solicitada es mayor que el stock disponible.';
            END IF;
            END IF;

            -- Validar si el producto ya está en el carrito
            SELECT COUNT(*) INTO v_ExistingProductCount FROM tb_Productos_Carrito WHERE ID_Carrito = p_ID_Carrito AND ID_Producto = p_ID_Producto AND Estatus = 1;
            IF v_ExistingProductCount > 0 AND v_Tipo = 1 THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'El producto ya está en el carrito. Por favor, modifique la cantidad en la página de carrito.';
            END IF;
            
            IF v_ExistingProductCount > 0 AND v_Tipo = 0 THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'El producto cotizado ya está en el carrito. Por favor, elimina el existente para agregar otro.';
            END IF;

            -- Agregar el producto al carrito
            INSERT INTO tb_Productos_Carrito (ID_Carrito, ID_Producto, Cantidad, Precio, Descripcion_Cot_Venta, Estatus)
            VALUES (p_ID_Carrito, p_ID_Producto, p_Cantidad, p_Precio, p_Descripcion_Cot_Venta, 1);

        WHEN 2 THEN
			SELECT Cantidad INTO v_Stock FROM tb_Productos WHERE ID_Producto = p_ID_Producto;
            IF p_Cantidad > v_Stock THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'La cantidad solicitada es mayor que el stock disponible.';
            END IF;

            -- Modificar la cantidad del producto en el carrito
            UPDATE tb_Productos_Carrito
            SET Cantidad = p_Cantidad
            WHERE ID_Carrito = p_ID_Carrito AND ID_Producto = p_ID_Producto AND Estatus = 1;

        WHEN 3 THEN
            -- Eliminar el producto del carrito (cambiar estatus a 0)
            UPDATE tb_Productos_Carrito
            SET Estatus = 0
            WHERE ID_Carrito = p_ID_Carrito AND ID_Producto = p_ID_Producto;

        WHEN 4 THEN
            -- Eliminar todos los productos del carrito (cambiar estatus a 0)
            UPDATE tb_Productos_Carrito
            SET Estatus = 0
            WHERE ID_Carrito = p_ID_Carrito;

        ELSE
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Opción no válida.';
    END CASE;
    
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_ProductosAgotados(IN p_ID_Carrito INT)
BEGIN
    -- Obtener la lista de productos agotados en el carrito
    SELECT pc.ID_Producto, p.Nombre_Producto
    FROM tb_Productos_Carrito pc
    JOIN tb_Productos p ON pc.ID_Producto = p.ID_Producto
    WHERE pc.ID_Carrito = p_ID_Carrito AND p.Cantidad = 0 AND pc.Estatus = 1 AND p.Tipo_Producto = 1;
    
    -- Actualizar el estatus de los productos agotados en el carrito
    UPDATE tb_Productos_Carrito pc
    JOIN tb_Productos p ON pc.ID_Producto = p.ID_Producto
    SET pc.Estatus = 0
    WHERE pc.ID_Carrito = p_ID_Carrito AND p.Cantidad = 0 AND pc.Estatus = 1 AND p.Tipo_Producto = 1;
    
END //

DELIMITER ;


DELIMITER //




CREATE PROCEDURE SP_CompletarCompra(
    IN p_username VARCHAR(15), 
    IN p_metodoPago INT, 
    IN p_cvv VARCHAR(4), 
    IN p_nombreTarjeta VARCHAR(100), 
    IN p_numeroTarjeta VARCHAR(16), 
    IN p_fechaVencimiento VARCHAR(7)
)
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE v_ID_Usuario INT;
    DECLARE v_ID_Carrito INT;
    DECLARE v_ID_Compra INT;
    DECLARE v_ID_Producto INT;
    DECLARE v_Cantidad INT;
    DECLARE v_Precio DECIMAL(10, 2);
    DECLARE v_CantidadDisponible INT;
    DECLARE v_Descripcion_Cotizado VARCHAR(300);
    DECLARE v_Total DECIMAL(10, 2) DEFAULT 0.00;
    DECLARE v_TipoProducto INT;

    DECLARE cur CURSOR FOR SELECT ID_Producto, Cantidad, Precio, Descripcion_Cot_Venta FROM tb_Productos_Carrito WHERE ID_Carrito = v_ID_Carrito AND Estatus = 1;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- Obtiene el ID del usuario
    SELECT ID_Usuario INTO v_ID_Usuario FROM tb_Usuario WHERE Username = p_username;

    -- Obtiene el ID del carrito
    SELECT ID_Carrito INTO v_ID_Carrito FROM tb_Carrito WHERE ID_Usuario_Cliente = v_ID_Usuario;
    
    -- Verifica si el carrito está vacío
	SELECT COUNT(*) INTO @num_items FROM tb_Productos_Carrito WHERE ID_Carrito = v_ID_Carrito AND Estatus = 1;
	IF @num_items = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El carrito está vacío.';
	END IF;

    START TRANSACTION;

    -- Inserta la compra
    INSERT INTO tb_Compra (ID_Usuario_Cliente, Fecha_Hora, Total, ID_Metodo_Pago) VALUES (v_ID_Usuario, NOW(), 0, p_metodoPago);

    -- Obtiene el ID de la compra
    SET v_ID_Compra = LAST_INSERT_ID();

    -- Inserta los detalles del pago
    INSERT INTO tb_Detalles_Pago (ID_Compra, ID_Metodo_Pago, Transaccion_Paypal, CVV, Nombre_Tarjeta, Numero_Tarjeta, Fecha_Vencimiento) VALUES (v_ID_Compra, p_metodoPago, NULL, p_cvv, p_nombreTarjeta, p_numeroTarjeta, p_fechaVencimiento);

    OPEN cur;

    read_loop: LOOP
        -- Lee los valores del cursor
        FETCH cur INTO v_ID_Producto, v_Cantidad, v_Precio, v_Descripcion_Cotizado;

        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Verifica la cantidad disponible del producto
        SELECT Cantidad INTO v_CantidadDisponible FROM tb_Productos WHERE ID_Producto = v_ID_Producto;
        SELECT Tipo_Producto INTO v_TipoProducto FROM tb_Productos WHERE ID_Producto = v_ID_Producto;
        
        IF v_TipoProducto = 1 THEN
				IF v_Cantidad > v_CantidadDisponible THEN
					ROLLBACK;
					SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El producto ya no existe o su cantidad es menor a la existente.';
        END IF;
        END IF;

        -- Agrega el precio del producto al total
        SET v_Total = v_Total + (v_Cantidad * v_Precio);

        -- Inserta los detalles de la compra
        INSERT INTO tb_Detalles_Compra (ID_Compra, ID_Producto, Cantidad, Precio_Unitario, Descripcion_Cot_Venta) VALUES (v_ID_Compra, v_ID_Producto, v_Cantidad, v_Precio, v_Descripcion_Cotizado );

    END LOOP;

    CLOSE cur;

    -- Actualiza el total de la compra
    UPDATE tb_Compra SET Total = v_Total WHERE ID_Compra = v_ID_Compra;
    
    UPDATE tb_productos_carrito SET Estatus = 0 WHERE ID_Carrito = v_ID_Carrito;

    COMMIT;
END //
DELIMITER ;

DELIMITER $$

CREATE PROCEDURE SP_ListaProductos(IN opcion INT, IN in_ID_Lista INT, IN in_ID_Producto INT)
BEGIN
    DECLARE existe INT;
    DECLARE listaActiva INT;
    DECLARE productoActivo INT;

    -- Validar si la lista está activa
    SELECT Estatus INTO listaActiva FROM tb_Listas WHERE ID_Lista = in_ID_Lista;

    IF listaActiva != 1 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La lista que se quiere agregar ya ha sido eliminada';
    END IF;

    -- Validar si el producto está activo
    SELECT Estatus INTO productoActivo FROM tb_Productos WHERE ID_Producto = in_ID_Producto;

    IF productoActivo != 1 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El producto ha sido eliminado o no esta disponible';
    END IF;

    -- Realizar la operación correspondiente
    IF opcion = 1 THEN
        -- Validar si el producto ya existe en la lista y el estatus es = 1
        SELECT COUNT(*) INTO existe FROM tb_Lista_Productos WHERE ID_Lista = in_ID_Lista AND ID_Producto = in_ID_Producto AND Estatus = 1;

        IF existe != 0 AND productoActivo != 0 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El producto ya existe en la lista';
        END IF;

        -- Dar de alta
        INSERT INTO tb_Lista_Productos (ID_Lista, ID_Producto, Estatus) VALUES (in_ID_Lista, in_ID_Producto, 1);
    ELSEIF opcion = 2 THEN
        -- Eliminación lógica
        UPDATE tb_Lista_Productos SET Estatus = 0 WHERE ID_Lista = in_ID_Lista AND ID_Producto = in_ID_Producto;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Opción no válida';
    END IF;
END $$

DELIMITER ;

DELIMITER //
CREATE PROCEDURE SP_GetUltimoProductoID()
BEGIN
    SELECT ID_Producto
    FROM tb_Productos
    ORDER BY ID_Producto DESC
    LIMIT 1;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE SP_Img(IN p_ID_Producto INT, IN p_Imagen LONGBLOB)
BEGIN
    INSERT INTO tb_Producto_Imagenes(ID_Producto, Imagen)
    VALUES (p_ID_Producto, p_Imagen);
END //
DELIMITER ;

DELIMITER $$

CREATE PROCEDURE SP_CambiarImagen(IN p_ID_Imagenes_Producto INT, IN p_Imagen LONGBLOB)
BEGIN
  UPDATE tb_Producto_Imagenes
  SET Imagen = p_Imagen
  WHERE ID_Imagenes_Producto = p_ID_Imagenes_Producto;
END $$

DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_GetUserChats(
    IN p_Username VARCHAR(15)
)
BEGIN
    DECLARE p_ID_Usuario INT;
    
    SELECT ID_Usuario INTO p_ID_Usuario FROM tb_Usuario WHERE Username = p_Username;

   SELECT 
        tb_Chats.ID_Chats,
        tb_Productos.Nombre_Producto,
        tb_Productos.ID_Producto,
        tb_Usuario.Imagen AS 'Imagen',
        tb_Usuario.Username AS 'Username_Vendedor',
        tb_Usuario.ID_Usuario AS 'ID_Vendedor',
        (SELECT MAX(Fecha_Mensaje) FROM tb_Mensajes WHERE ID_Chats = tb_Chats.ID_Chats) AS 'Fecha_Ultimo_Mensaje',
        (SELECT Username FROM tb_Usuario WHERE ID_Usuario = tb_Chats.ID_Usuario_Cliente) AS 'Username_Cliente',
        tb_Chats.ID_Usuario_Cliente AS 'c'
    FROM 
        tb_Chats
    INNER JOIN
        tb_Usuario ON tb_Chats.ID_Usuario_Vendedor = tb_Usuario.ID_Usuario
    INNER JOIN
        tb_Productos ON tb_Chats.ID_Producto = tb_Productos.ID_Producto
    WHERE 
        tb_Chats.ID_Usuario_Vendedor = p_ID_Usuario OR tb_Chats.ID_Usuario_Cliente = p_ID_Usuario;

END//

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE SP_EnviarMensajeS(
    IN p_Username_Emisor VARCHAR(255),
    IN p_Username_Receptor VARCHAR(255),
    IN p_ID_Chats INT,
    IN p_Mensaje VARCHAR(500)
)
BEGIN
    DECLARE p_ID_Usuario_Emisor INT;
    DECLARE p_ID_Usuario_Receptor INT;

    -- Obtener el ID desde el username del emisor
    SELECT ID_Usuario INTO p_ID_Usuario_Emisor
    FROM tb_usuario
    WHERE Username = p_Username_Emisor;

    -- Obtener el ID desde el username del receptor
    SELECT ID_Usuario INTO p_ID_Usuario_Receptor
    FROM tb_usuario
    WHERE Username = p_Username_Receptor;

    INSERT INTO tb_Mensajes (
        ID_Chats, 
        ID_Usuario_Emisor, 
        ID_Usuario_Receptor, 
        Mensaje
    )
    VALUES (
        p_ID_Chats, 
        p_ID_Usuario_Emisor, 
        p_ID_Usuario_Receptor,
        p_Mensaje
    );
END $$

DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_ChatManagement(
    IN p_Opcion INT,
    IN p_Username VARCHAR(15),
    IN p_ID_Usuario_Vendedor INT,
    IN p_ID_Producto INT,
    IN p_ID_Chat INT,
    IN p_ID_Usuario_Emisor INT,
    IN p_ID_Usuario_Receptor INT,
    IN p_Mensaje VARCHAR(500)
)
BEGIN
    DECLARE p_ID_Usuario INT;
    
    SELECT ID_Usuario INTO p_ID_Usuario
    FROM tb_usuario
    WHERE Username = p_Username;
    
    IF p_Opcion = 1 THEN
        -- Insert new chat
        IF EXISTS (
            SELECT 1
            FROM tb_Chats
            WHERE ID_Usuario_Cliente = p_ID_Usuario
                AND ID_Usuario_Vendedor = p_ID_Usuario_Vendedor
                AND ID_Producto = p_ID_Producto
        ) THEN
            SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'El chat ya existe para el usuario cliente, usuario vendedor y producto especificados';
        ELSE
            INSERT INTO tb_Chats (ID_Usuario_Cliente, ID_Usuario_Vendedor, ID_Producto)
            VALUES (p_ID_Usuario, p_ID_Usuario_Vendedor, p_ID_Producto);
        END IF;
    ELSEIF p_Opcion = 2 THEN
        -- Retrieve messages by chat ID
        SELECT *
        FROM tb_Mensajes
        WHERE ID_Chats = p_ID_Chat;
    ELSEIF p_Opcion = 3 THEN
		-- Hace un select de todos los chats del usuario. 
		SELECT c.ID_Usuario_Cliente, c.ID_Usuario_Vendedor, c.ID_Producto, p.Nombre_Producto, MAX(m.Fecha_Mensaje) AS Fecha_Ultimo_Mensaje
        FROM tb_Chats c
        INNER JOIN tb_Mensajes m ON c.ID_Chats = m.ID_Chats
        INNER JOIN tb_Productos p ON c.ID_Producto = p.ID_Producto
        WHERE c.ID_Usuario_Cliente = p_ID_Usuario OR c.ID_Usuario_Vendedor = p_ID_Usuario
        GROUP BY c.ID_Usuario_Cliente, c.ID_Usuario_Vendedor, c.ID_Producto, p.Nombre_Producto;

    END IF;
END//

DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_GetUserMessages(
    IN p_ID_Chat INT
)
BEGIN
    SELECT ID_Historial_Mensajes, ID_Chats, ID_Usuario_Emisor, ID_Usuario_Receptor, Mensaje, Fecha_Mensaje
    FROM tb_Mensajes
    WHERE ID_Chats = p_ID_Chat;
END//

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE SP_GetMyID(IN `input_username` VARCHAR(15))
BEGIN
    SELECT `ID_Usuario` FROM `tb_Usuario` WHERE `Username` = `input_username`;
END $$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE SP_EnviarMensaje(
    IN p_Username VARCHAR(255),
    IN p_ID_Chats INT,
    IN p_Mensaje VARCHAR(500),
    IN p_ID_Usuario_Receptor INT
)
BEGIN
    DECLARE p_ID_Usuario_Emisor INT;

    -- ID desde el username
    SELECT ID_Usuario INTO p_ID_Usuario_Emisor
    FROM tb_usuario
    WHERE Username = p_Username;

    INSERT INTO tb_Mensajes (
        ID_Chats, 
        ID_Usuario_Emisor, 
        ID_Usuario_Receptor, 
        Mensaje
    )
    VALUES (
        p_ID_Chats, 
        p_ID_Usuario_Emisor, 
        p_ID_Usuario_Receptor, 
        p_Mensaje
    );
END $$


DELIMITER ;



DELIMITER //

CREATE PROCEDURE SP_GestionLista(
    IN _opcion INT,
    IN _username VARCHAR(15),
    IN _nombreLista VARCHAR(25),
    IN _descripcionLista TEXT,
    IN _privacidadLista BIT,
    IN _idLista INT
)
BEGIN
    DECLARE _idUsuario INT;
	DECLARE _listDescription TEXT;
	DECLARE _listName VARCHAR(25);
	DECLARE _listPrivacy BIT;

    SELECT ID_Usuario INTO _idUsuario FROM tb_Usuario WHERE Username = _username;

    IF _opcion = 1 THEN
        -- Creación de una nueva lista
        INSERT INTO tb_Listas (ID_Usuario, Nombre_Lista, Descripcion, Privacidad) 
        VALUES (_idUsuario, _nombreLista, _descripcionLista, _privacidadLista);
    ELSEIF _opcion = 2 THEN
        -- Modificación de una lista existente
        UPDATE tb_Listas 
        SET Nombre_Lista = _nombreLista, Descripcion = _descripcionLista, Privacidad = _privacidadLista 
        WHERE ID_Lista = _idLista;
    ELSEIF _opcion = 3 THEN
        -- Eliminación de la lista (Estatus = 0)
        UPDATE tb_Listas 
        SET Estatus = 0
        WHERE ID_Lista = _idLista;
    ELSEIF _opcion = 4 THEN
        -- Validación de propiedad de lista
        SELECT Descripcion, Nombre_Lista, Privacidad
        INTO _listDescription, _listName, _listPrivacy
        FROM tb_Listas
        WHERE ID_Lista = _idLista AND ID_Usuario = _idUsuario AND Estatus = 1;
        END IF;

END;

//
DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_VerListasDeUsuario(
    IN _nombreUsuario VARCHAR(15),
    IN _opcion INT,
    IN _idLista INT
)
BEGIN
    DECLARE _idUsuario INT;
    
    SELECT ID_Usuario INTO _idUsuario FROM tb_Usuario WHERE Username = _nombreUsuario;

    IF _opcion = 1 THEN
        -- OPCION 1: VER LISTAS DEL PROPIO USUARIO
        SELECT ID_Lista, Nombre_Lista, Descripcion, Privacidad
        FROM tb_Listas
        WHERE ID_Usuario = _idUsuario AND Estatus = 1;
    ELSEIF _opcion = 2 THEN
        -- OPCION 2: VER LISTAS DE OTRO USUARIO 
        SELECT ID_Lista, Nombre_Lista, Descripcion, Privacidad
        FROM tb_Listas
        WHERE ID_Usuario = _idUsuario AND Estatus = 1 AND Privacidad = 1;
	ELSEIF _opcion = 3 THEN
        -- OPCION 3: OBTENER LOS DATOS DE LA LISTA PARA EDITAR. 
        SELECT ID_Lista, Nombre_Lista, Descripcion, Privacidad
        FROM tb_Listas
        WHERE ID_Usuario = _idUsuario AND Estatus = 1 AND ID_Lista = _idLista;
    END IF;
END;

//
DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_ObtenerCarrito(IN p_Username VARCHAR(15))
BEGIN
    DECLARE v_UserID INT;

    -- Obtener el ID del usuario basado en el nombre de usuario
    SELECT ID_Usuario INTO v_UserID FROM tb_Usuario WHERE Username = p_Username;

    -- Verificar si el usuario existe
    IF v_UserID IS NOT NULL THEN
        -- Obtener el ID del carrito del usuario
        SELECT ID_Carrito FROM tb_Carrito WHERE ID_Usuario_Cliente = v_UserID;
    ELSE
        -- Usuario no encontrado
        SELECT NULL AS ID_Carrito;
    END IF;
    
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_Productos_Home(IN p_Opcion INT)
BEGIN
    -- Opción 1: Obtener los tres productos más recientes
    IF p_Opcion = 1 THEN
        SELECT * FROM Vista_Productos_Recientes;
    END IF;
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE SP_CategoriasProducto(IN p_IdCategoria INT, IN p_IdProducto INT, IN p_Estatus INT)
BEGIN
    DECLARE last_product_id INT;
    
    -- Obtener el ID del último producto creado
    SELECT MAX(ID_Producto) INTO last_product_id FROM tb_Productos;
    
    -- Insertar en la tabla de relación de categorías y productos
    INSERT INTO tb_Categoria_Producto (ID_Producto, ID_Categoria, Estatus)
    VALUES (last_product_id, p_IdCategoria, p_Estatus);
    
END //

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE SP_CrearCategoria(
    IN p_Nombre_Usuario VARCHAR(15), 
    IN p_Nombre_Categoria VARCHAR(35),
    IN p_Descripcion_Categoria VARCHAR(300)
)
BEGIN
    DECLARE vendedor_id INT;
    DECLARE categoria_count INT;

    -- Obtener el ID del vendedor basado en su nombre
    SELECT ID_Usuario INTO vendedor_id
    FROM tb_Usuario
    WHERE Username = p_Nombre_Usuario;

    -- Verificar si el nombre de la categoría ya existe
    SELECT COUNT(*) INTO categoria_count
    FROM tb_Categorias
    WHERE Nombre_Categoria = p_Nombre_Categoria;

    IF categoria_count > 0 THEN
        -- El nombre de la categoría ya existe, devolver un error SQL
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El nombre de categoría ya existe.';
    ELSE
        -- El nombre de la categoría no existe, proceder a crearla
        INSERT INTO tb_Categorias (ID_Usuario_Vendedor, Nombre_Categoria, Descripcion_Categoria)
        VALUES (vendedor_id, p_Nombre_Categoria, p_Descripcion_Categoria);
    END IF;
END $$

DELIMITER ;

DELIMITER //

CREATE PROCEDURE GestionarProductos(
    IN opcion INT,                    -- 1 para alta, 2 para cambio, 3 para baja
    IN producto_id INT,               -- ID del producto (solo para cambio y baja)
    IN nombre_producto VARCHAR(100),  -- Nombre del producto (solo para alta y cambio)
    IN descripcion_producto TEXT,     -- Descripción del producto (solo para alta y cambio)
    IN tipo_producto BIT,             -- Tipo de producto (solo para alta y cambio)
    IN precio DECIMAL(10, 2),         -- Precio del producto (solo para alta y cambio)
    IN cantidad INT,                  -- Cantidad de productos disponibles (solo para alta y cambio)
    IN descripcion_cotizado VARCHAR(300), -- Descripción del cotizado (solo para alta y cambio)
    IN estatus BIT,                   -- Estatus del producto (solo para alta y cambio)
    IN validacion BIT,             -- Validación del producto (solo para alta y cambio)
    IN username_vendedor VARCHAR(100)
)
BEGIN

DECLARE id_vendedor INT;
	
     SELECT ID_Usuario INTO id_vendedor
    FROM tb_usuario
    WHERE Username = username_vendedor;


    -- Opción 1: Alta de producto
    IF opcion = 1 THEN
        INSERT INTO tb_Productos (Nombre_Producto, Descripcion_Producto, Tipo_Producto, Precio_Unitario, Cantidad, Descripcion_Cotizado, Estatus, Validacion, ID_Usuario_Vendedor)
        VALUES (nombre_producto, descripcion_producto, tipo_producto, precio, cantidad, descripcion_cotizado, estatus, validacion, id_vendedor );

    -- Opción 2: Cambio de producto
    ELSEIF opcion = 2 THEN
        UPDATE tb_Productos
        SET Nombre_Producto = nombre_producto, Descripcion_Producto = descripcion_producto,
            Tipo_Producto = tipo_producto, Precio_Unitario = precio, Cantidad = cantidad,
            Descripcion_Cotizado = descripcion_cotizado, Estatus = estatus, Validacion = validacion, Fecha_Alta = fecha_alta
        WHERE ID_Producto = producto_id;

    -- Opción 3: Baja de producto
    ELSEIF opcion = 3 THEN
		UPDATE tb_Productos
        SET Estatus = 0
        WHERE ID_Producto = producto_id;
       

    -- Opción no válida
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Opción no válida';
    END IF;
END //


DELIMITER ;


DELIMITER //
CREATE PROCEDURE SP_ProfileData(IN pUsername VARCHAR(15))
BEGIN
    SELECT u.ID_Roles, u.Nombre, u.Apellido_Paterno, u.Apellido_Materno, u.Username, u.Imagen, u.Fecha_Registro, u.Privacidad
    FROM tb_Usuario AS u
    WHERE u.Username = pUsername;
END //
DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_MyProfileData(IN pUsername VARCHAR(15))
BEGIN
    SELECT ID_Usuario, ID_Sexo, ID_Roles, Nombre, Apellido_Paterno, Apellido_Materno, Correo, Imagen, Fecha_Nacimiento, Fecha_Registro, Privacidad, Estado, Colonia, Calle, Numero_Casa, Username, PassW
    FROM tb_Usuario
    WHERE Username = pUsername;
END //

DELIMITER ;


DELIMITER //
CREATE PROCEDURE SP_ActualizarImagenUsuario(
    IN p_Username VARCHAR(15),
    IN p_Nueva_Imagen LONGBLOB
)
BEGIN
    UPDATE tb_Usuario
    SET Imagen = p_Nueva_Imagen
    WHERE Username = p_Username;
END;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE SP_ActualizarUsuario(
    IN p_ID_Sexo INT,
    IN p_Nombre VARCHAR(25),
    IN p_Apellido_Paterno VARCHAR(25),
    IN p_Apellido_Materno VARCHAR(25),
    IN p_Username VARCHAR(15),
    IN p_PassW VARCHAR(15), 
    IN p_Correo VARCHAR(25),
    IN p_Fecha_Nacimiento DATE,
    IN p_Privacidad BIT,
    IN p_Estado VARCHAR(30),
    IN p_Colonia VARCHAR(50),
    IN p_Calle VARCHAR(30),
    IN p_Numero_Casa VARCHAR(30),
    IN p_ActualUsername VARCHAR(15) -- Agrega el parámetro faltante
)
BEGIN
    DECLARE existing_user INT;
    DECLARE existing_email INT;
    
    SELECT COUNT(*) INTO existing_user FROM tb_Usuario WHERE Username = p_Username AND Username <> p_ActualUsername;
    SELECT COUNT(*) INTO existing_email FROM tb_Usuario WHERE Correo = p_Correo AND Username <> p_ActualUsername;
    
    IF existing_user > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El nombre de usuario ya está en uso por otro usuario';
    END IF;
    
    IF existing_email > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El correo ya está registrado por otro usuario';
    END IF;

    -- Actualiza los detalles del usuario
    UPDATE tb_Usuario
	SET 
    Nombre = p_Nombre,
    Apellido_Paterno = p_Apellido_Paterno,
    Apellido_Materno = p_Apellido_Materno,
    Username = p_Username,
    PassW = p_PassW,
    Correo = p_Correo,
    Fecha_Nacimiento = p_Fecha_Nacimiento,
    Privacidad = p_Privacidad,
    ID_Sexo = p_ID_Sexo,
    Estado = p_Estado,
    Colonia = p_Colonia,
    Calle = p_Calle,
    Numero_Casa = p_Numero_Casa
    WHERE Username = p_ActualUsername; 
END;
//
DELIMITER ;


DELIMITER //

CREATE PROCEDURE SP_VerificarUsuario(
IN p_Username VARCHAR(255), 
IN p_PassW VARCHAR(15) 
)
BEGIN
   DECLARE v_RoleID INT;
    DECLARE v_UserID INT;
    DECLARE v_Username VARCHAR(15);
    
    -- Buscar el ID del usuario y el ID de roles que coincide con el nombre de usuario y contraseña
    SELECT ID_Roles, ID_Usuario , Username INTO v_RoleID, v_UserID, v_Username
    FROM tb_Usuario
    WHERE (Username = p_Username OR Correo = p_Username) AND PassW = p_PassW;
    
    -- Si se encontró un usuario, devuelve los IDs
    IF v_RoleID IS NOT NULL AND v_UserID IS NOT NULL THEN
        SELECT v_RoleID AS ID_Roles, v_UserID AS ID_Usuario, v_Username AS Usuario;
    ELSE
        -- Si no se encontró un usuario, establece el rol en -1 y el ID de usuario en -1
        SELECT -1 AS ID_Roles, -1 AS ID_Usuario;
    END IF;
END 

//

DELIMITER //
CREATE PROCEDURE SP_RegistroUsuario(
    IN p_ID_Sexo INT,
    IN p_ID_Roles INT,
    IN p_Nombre VARCHAR(25),
    IN p_Apellido_Paterno VARCHAR(25),
    IN p_Apellido_Materno VARCHAR(25),
    IN p_Username VARCHAR(15),
    IN p_PassW VARCHAR(15), 
    IN p_Correo VARCHAR(25),
    IN p_Imagen BLOB,
    IN p_Fecha_Nacimiento DATE,
    IN p_Privacidad BIT,
    IN p_Estado VARCHAR(30),
    IN p_Colonia VARCHAR(50),
    IN p_Calle VARCHAR(30),
    IN p_Numero_Casa VARCHAR(30)
)
BEGIN
    DECLARE existing_user INT;
    DECLARE existing_email INT;
    
    SELECT COUNT(*) INTO existing_user FROM tb_Usuario WHERE Username = p_Username;
    SELECT COUNT(*) INTO existing_email FROM tb_Usuario WHERE Correo = p_Correo;
    
    IF existing_user > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El usuario ya existe';
    END IF;
    
    IF existing_email > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El correo ya está registrado';
    END IF;
    
    
   IF p_ID_Roles = 1 OR p_ID_Roles = 2 THEN
    SET p_Privacidad = 1;
END IF;
    
    INSERT INTO tb_Usuario (
        ID_Sexo, ID_Roles, Nombre, Apellido_Paterno, Apellido_Materno, Username,
        PassW, Correo, Imagen, Fecha_Nacimiento, Privacidad, Estado,
        Colonia, Calle, Numero_Casa
    ) VALUES (
        p_ID_Sexo, p_ID_Roles, p_Nombre, p_Apellido_Paterno, p_Apellido_Materno, p_Username,
        p_PassW, p_Correo, p_Imagen, p_Fecha_Nacimiento, p_Privacidad, p_Estado,
        p_Colonia, p_Calle, p_Numero_Casa
    );
END;
//
DELIMITER ;

DELIMITER //

CREATE PROCEDURE Sp_GestionCategoriaProducto(IN p_IdCategoria INT, IN p_IdProducto INT, IN p_Estatus INT, IN p_opcion INT)
BEGIN
    -- Si la opción es 1, insertar una nueva relación entre categoría y producto
    IF p_opcion = 1 THEN
        -- Insertar en la tabla de relación de categorías y productos
        INSERT INTO tb_Categoria_Producto (ID_Producto, ID_Categoria, Estatus)
        VALUES (p_IdProducto, p_IdCategoria, p_Estatus);
    -- Si la opción es 2, actualizar el campo "Estatus" a 0
    ELSEIF p_opcion = 2 THEN
        UPDATE tb_Categoria_Producto
        SET Estatus = 0
        WHERE ID_Producto = p_IdProducto AND ID_Categoria = p_IdCategoria;
    END IF;
    
END //

DELIMITER ;

DELIMITER //


DELIMITER //
CREATE PROCEDURE SP_ObtenerCategoriasDeProducto(IN p_ProductID INT)
BEGIN
    SELECT Nombre_Categoria, ID_Categoria, Descripcion_Categoria
    FROM Vista_Categorias_Producto
    WHERE ID_Producto = p_ProductID;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE SP_VerCategorias()
BEGIN
    SELECT ID_Categoria, Nombre_Categoria, Descripcion_Categoria
    FROM Vista_Categorias;
END //
DELIMITER ;


USE dbTiltedShop; 

-- SP Mostrar Productos a Evaluar
DELIMITER //
-- DROP PROCEDURE IF EXISTS SP_ObtenerProductosAValidar;
CREATE PROCEDURE SP_ObtenerProductosAValidar()
BEGIN
    SELECT IdP, NombreP, DescripcionP FROM VistaProductosValidacion;
END //

DELIMITER ;
-- END SP


-- SP Validar o Invalidar un Producto
DELIMITER //
-- DROP PROCEDURE IF EXISTS  SP_ValidarProducto;
CREATE PROCEDURE SP_ValidarProducto
(
IN idProductoParam INT, 
IN validarParam BIT,
IN idAdminParam INT
)
BEGIN
    IF validarParam = 1 THEN
        -- Realizar acción de validación, 1 es true
        UPDATE tb_Productos SET Validacion = 1, Estatus = 1, ID_Usuario_Admin = idAdminParam WHERE ID_Producto = idProductoParam;
    ELSE
        -- Realizar acción de no validación, 0 es false 
        UPDATE tb_Productos SET Validacion = 0, Estatus = 0,  ID_Usuario_Admin = idAdminParam WHERE ID_Producto = idProductoParam;
    END IF;
END //
DELIMITER ;
-- END SP

-- CALL SP_ValidarProducto(2, 1, 1);


-- SP Mostrar Productos Evaluados
DELIMITER //
-- DROP PROCEDURE IF EXISTS SP_ObtenerProductosValidados;
CREATE PROCEDURE SP_ObtenerProductosValidados
(
IN idAdminParam INT
)
BEGIN
    SELECT IdP, NombreP, DescripcionP, NombreAdmin FROM VistaProductosValidados
    WHERE IdAdmin = idAdminParam;
END //

DELIMITER ;
-- END SP

-- CALL SP_ObtenerProductosValidados(1);


-- SP Mostrar Productos Evaluados
DELIMITER //
-- DROP PROCEDURE IF EXISTS SP_BusquedaSimpleProductos;
CREATE PROCEDURE SP_BusquedaSimpleProductos
(
IN textoParam VARCHAR (25)
)
BEGIN
	SET textoParam = CONCAT('%', textoParam, '%'); -- Agrega '%' al principio y al final del textoParam
    
    SELECT IdP, NombreP, DescripcionNormalP, DescripcionCotizadoP, PrecioP, NombreVendedor, IdVendedor, PromedioCalificacion, tipoProducto 
    FROM VistaProductosBusqueda
    WHERE NombreP LIKE textoParam OR NombreVendedor LIKE textoParam;
    
END //

DELIMITER ;
-- END SP
-- CALL SP_BusquedaSimpleProductos("a");


-- SP Mostrar Detalles de Productos
DELIMITER //
-- DROP PROCEDURE IF EXISTS SP_DetallesProducto;
CREATE PROCEDURE SP_DetallesProducto
(
IN idProductoParam INT
)
BEGIN
	    
    SELECT IdP, NombreP, DescripcionNormalP, DescripcionCotizadoP, PrecioP,
    CantidadP, NombreVendedor, IdVendedor, Validacion, NombreAdmin, IdAdmin, PromedioCalificacion 
    FROM DetallesProducto
    WHERE IdP = idProductoParam;
    
END //

DELIMITER ;
-- END SP

-- CALL SP_DetallesProducto(5);



-- SP Mostrar Revisar si un Producto tiene stock
DELIMITER //
-- DROP PROCEDURE IF EXISTS SP_VerificarStock;
CREATE PROCEDURE SP_VerificarStock(
IN idProductParam INT, 
IN cantidadParam INT 
)
BEGIN
	DECLARE v_StockPass INT;
        
    -- Buscar el ID del usuario y el ID de roles que coincide con el nombre de usuario y contraseña
    SELECT P.Cantidad INTO v_StockPass
    FROM tb_Productos AS P
    WHERE P.ID_Producto = idProductParam;
    -- AND P.Cantidad >= cantidadParam;
    
    -- Si se encontró el producto, verificar si puede o no comprarse segun el stock
    IF v_StockPass >= cantidadParam THEN
		-- Si hay stock suficiente
        SELECT 1 AS stockStatus; 
    ELSE
        -- No hay stock suficiente
        SELECT -1 AS stockStatus; 
    END IF;
END  //

DELIMITER ;
-- END SP

-- CALL SP_VerificarStock(5, 51);



-- SP Crear una compra
DELIMITER //
-- DROP PROCEDURE IF EXISTS SP_GenerarCompra;
CREATE PROCEDURE SP_GenerarCompra(
IN userIdParam INT, 
IN totalParam DECIMAL(10, 2) ,
IN metodoParam INT
)
BEGIN
	
    -- Insertar una nueva compra
    INSERT INTO tb_Compra (ID_Usuario_Cliente, Total, ID_Metodo_Pago)
    VALUES (userIdParam, totalParam, metodoParam);
    
    -- Obtener el ID del último registro insertado
    SELECT LAST_INSERT_ID() as 'LastInsertId';

END  //

DELIMITER ;
-- END SP
-- CALL SP_GenerarCompra(1, 100.50, 2);



-- SP Crear detalles de una compra
DELIMITER //
-- DROP PROCEDURE IF EXISTS SP_GenerarDetalleCompra;
CREATE PROCEDURE SP_GenerarDetalleCompra(
IN tipoProductoParam INT, -- 1 es producto normal y 2 es un producto cotizado
IN compraIdParam INT,
IN productIdParam INT, 
IN cantidadParam INT, 
IN precioUnitParam DECIMAL(10, 2) ,
IN descripcionCotParam VARCHAR (300)
-- TODO: FALTA GENERAR EL ID QUE DEFINE SI ES COMPRA NORMAL O COTIZADO PARA SABER SI AGREGARA EL COT O NO EN EL INSERT
)
BEGIN
	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Error during transaction';
    END;

    START TRANSACTION;
        
		IF tipoProductoParam = 1 THEN
			 -- Insertar un nuevo detalle de compra
			INSERT INTO tb_Detalles_Compra (ID_Compra, ID_Producto, Cantidad, Precio_Unitario)
			VALUES (compraIdParam, productIdParam, cantidadParam, precioUnitParam);
		END IF;
		
		IF tipoProductoParam = 2 THEN
			 -- Insertar un nuevo detalle de compra
			INSERT INTO tb_Detalles_Compra (ID_Compra, ID_Producto, Cantidad, Precio_Unitario, Descripcion_Cot_Venta)
			VALUES (compraIdParam, productIdParam, cantidadParam, precioUnitParam, descripcionCotParam);
		END IF;
    
	COMMIT;
END  //

DELIMITER ;
-- END SP
-- CALL SP_GenerarDetalleCompra(1, 3, 5, 20.50, 'Detalle de la cotización');




-- SP Crear registro de el pago de una compra
DELIMITER //
-- DROP PROCEDURE IF EXISTS SP_GenerarDetallesPago;
CREATE PROCEDURE SP_GenerarDetallesPago(
    IN tipoPagoParam INT, -- 1 es pago normal 2 es pago de paypal
    IN compraIdParam INT,
    IN metodoPagoIdParam INT,
    IN transaccionPaypalParam VARCHAR(255),
    IN cvvParam VARCHAR(4),
    IN nombreTarjetaParam VARCHAR(100),
    IN numeroTarjetaParam VARCHAR(16),
    IN fechaVencimientoParam VARCHAR(7)
)
BEGIN

	IF tipoPagoParam = 1 THEN
		INSERT INTO tb_Detalles_Pago (ID_Compra, ID_Metodo_Pago, CVV, Nombre_Tarjeta, Numero_Tarjeta, Fecha_Vencimiento) 
        VALUES (compraIdParam, metodoPagoIdParam, cvvParam, nombreTarjetaParam, numeroTarjetaParam, fechaVencimientoParam);
	END IF;
    
	IF tipoPagoParam = 2 THEN
		INSERT INTO tb_Detalles_Pago (ID_Compra, ID_Metodo_Pago, Transaccion_Paypal)         
        VALUES (compraIdParam, metodoPagoIdParam, transaccionPaypalParam);
    END IF;
END //

DELIMITER ;



-- Obtener el precio de un producto dado
DELIMITER //
-- DROP PROCEDURE SP_ObtenerPrecioUnitario;
CREATE PROCEDURE SP_ObtenerPrecioUnitario(IN productoIdParam INT)
BEGIN
    -- DECLARE precioUnitario DECIMAL(10, 2);

    -- Obtener el precio unitario del producto
    SELECT Precio_Unitario -- INTO precioUnitario
    FROM tb_Productos
    WHERE ID_Producto = productoIdParam;

    -- Devolver el resultado
    -- SELECT precioUnitario AS 'Precio_Unitario';
END //

DELIMITER ;
CALL SP_ObtenerPrecioUnitario(6);



-- SP para obtener cual producto se vendio dado un id de compra
DELIMITER //
-- DROP PROCEDURE SP_ObtenerProductosPorCompra;
CREATE PROCEDURE SP_ObtenerProductosPorCompra(
IN compraIdParam INT,
IN productIdParam INT
)
BEGIN

    SELECT
    idDetalle, idCompra, idProducto, nombreP, vendedor, cantidad
    FROM 
		VistaProductosComprados
    WHERE
        idCompra = compraIdParam AND idProducto = productIdParam;
END //

DELIMITER ;

CALL SP_ObtenerProductosPorCompra(70,6);


-- SP guardar una valoracion
DELIMITER //
CREATE PROCEDURE SP_InsertarValoracion(
    IN usuarioIdParam INT,
    IN productoIdParam INT,
    IN calificacionParam INT
)
BEGIN
    INSERT INTO tb_Valoracion (ID_Usuario_Cliente, ID_Producto, Calificacion)
    VALUES (usuarioIdParam, productoIdParam, calificacionParam);
END //
DELIMITER ;


-- SP guardar un comentario
DELIMITER //
CREATE PROCEDURE SP_InsertarComentario(
    IN usuarioIdParam INT,
    IN productoIdParam INT,
    IN comentarioParam VARCHAR(100)
)
BEGIN
    INSERT INTO tb_Comentarios (ID_Usuario_Cliente, ID_Producto, Comentario)
    VALUES (usuarioIdParam, productoIdParam, comentarioParam);
END //
DELIMITER ;

-- SP para determinar si un usario ya valoro este producto
DELIMITER //
-- DROP PROCEDURE IF EXISTS SP_VerificarValoracion;
CREATE PROCEDURE SP_VerificarValoracion(
    IN usuarioIdParam INT,
    IN productoIdParam INT
    
)
BEGIN
    DECLARE cantidadValoraciones INT;

    -- Verificar si el usuario ya ha valorado el producto
    SELECT COUNT(*) INTO cantidadValoraciones
    FROM tb_Valoracion
    WHERE ID_Usuario_Cliente = usuarioIdParam AND ID_Producto = productoIdParam;

    -- Asignar el resultado de salida
    IF cantidadValoraciones > 0 THEN
		SELECT 1 AS valoracionStatus; 
    ELSE
		SELECT 0 AS valoracionStatus; 
	END IF;
    
END //

DELIMITER ;






-- SP para determinar el resultado de los filtros
DELIMITER //
-- DROP PROCEDURE SP_BusquedaAvanzadaProductos;
CREATE PROCEDURE SP_BusquedaAvanzadaProductos(
    IN searchQuery VARCHAR(255), -- Texto a buscar
    IN usePriceFilter BOOLEAN,
    IN orderByPrice INT, -- 0 para ascendente, 1 para descendente
    IN useRatingFilter BOOLEAN,
    IN orderByRating INT, -- 0 para ascendente, 1 para descendente
    IN useSalesFilter BOOLEAN,
    IN orderBySales INT -- 0 para ascendente, 1 para descendente
)
BEGIN
    -- Construir la consulta SQL dinámica
    SET @sqlQuery = 'SELECT * FROM VistaProductosBusquedaAvanzada  
						WHERE NombreP LIKE CONCAT("%", ?, "%")
							OR NombreVendedor LIKE CONCAT("%", ?, "%")';

    -- Agregar criterios de ordenamiento según los filtros activados
	SET @orderByClause = '';
    SET @orderByClause = AddOrderByCriterion(@orderByClause, 'PrecioP', orderByPrice, usePriceFilter);
    SET @orderByClause = AddOrderByCriterion(@orderByClause, 'PromedioCalificacion', orderByRating, useRatingFilter);
    SET @orderByClause = AddOrderByCriterion(@orderByClause, 'Ventas', orderBySales, useSalesFilter); 
    
        -- Agregar la cláusula ORDER BY a la consulta SQL dinámica
    IF @orderByClause != '' THEN
        SET @sqlQuery = CONCAT(@sqlQuery, ' ORDER BY ', @orderByClause);
    END IF;

    -- Preparar y ejecutar la consulta SQL dinámica
    PREPARE stmt FROM @sqlQuery;
    SET @searchQuery = searchQuery;
    EXECUTE stmt USING @searchQuery, @searchQuery; 
    DEALLOCATE PREPARE stmt;
END //
DELIMITER ;

-- CALL SP_BusquedaAvanzadaProductos('ven', false, 1, true, 1, true, 0);


-- SP PARA SABER EL TIPO DE PRODUCTO
DELIMITER //
-- DROP PROCEDURE SP_ObtenerTipoProducto;
CREATE PROCEDURE SP_ObtenerTipoProducto(
IN productoIdParam INT
)
BEGIN
    DECLARE tipo BIT;

    SELECT Pro.Tipo_Producto INTO tipo
    FROM tb_Productos AS Pro
    WHERE Pro.ID_Producto = productoIdParam;

    IF tipo IS NOT NULL THEN
        SELECT tipo AS Tipo_Producto;
    ELSE
        SELECT -1 AS Tipo_Producto; -- Valor que indica que el producto no fue encontrado
    END IF;
END //

DELIMITER ;

-- CALL SP_ObtenerTipoProducto(7);











-- CONSULTA DINAMICA PARA LOS REPORTES DE VENDEDOR

DELIMITER //
-- DROP PROCEDURE SP_ConsultarVentasDetalladas;
CREATE PROCEDURE SP_ConsultarVentasDetalladas(
	IN idVendedorParam INT,
    IN usarFiltroFecha BOOL,
    IN fechaInicioParam DATE,
    IN fechaFinParam DATE,
    IN usarFiltroCategoria BOOL,
    IN idCategoriaParam INT    
)
BEGIN
    -- Asignar valores a variables internas
    SET @idVendedorVar = idVendedorParam;
    SET @fechaInicioVar = fechaInicioParam;
    SET @fechaFinVar = fechaFinParam;
    SET @idCategoriaVar = idCategoriaParam;

    SET @sql = 'SELECT * FROM VistaVentasDetalladas WHERE 1=1 AND idVendedor = ? ';

    -- Agregar filtros dinámicamente según los parámetros
    IF usarFiltroFecha THEN
		-- Formatear las fechas antes de concatenarlas a la consulta
        SET @fechaInicioVar = DATE_FORMAT(fechaInicioParam, '%Y-%m-%d');
        SET @fechaFinVar = DATE_FORMAT(fechaFinParam, '%Y-%m-%d');
        SET @sql = CONCAT(@sql, 'AND DATE_FORMAT(FechaHora, "%Y-%m-%d") BETWEEN ? AND ? ');
    END IF;

    IF usarFiltroCategoria THEN
        SET @sql = CONCAT(@sql, 'AND idCategoria = ? ');
    END IF;

    -- Preparar y ejecutar la consulta dinámica
    PREPARE stmt FROM @sql;

    -- Ejecutar la consulta dinámica con variables internas según los filtros
    IF usarFiltroFecha AND usarFiltroCategoria THEN
        EXECUTE stmt USING @idVendedorVar, @fechaInicioVar, @fechaFinVar, @idCategoriaVar;
    ELSEIF usarFiltroFecha THEN
        EXECUTE stmt USING @idVendedorVar, @fechaInicioVar, @fechaFinVar;
    ELSEIF usarFiltroCategoria THEN
        EXECUTE stmt USING @idVendedorVar, @idCategoriaVar;
    ELSE
        EXECUTE stmt USING @idVendedorVar;
    END IF;

    DEALLOCATE PREPARE stmt;
END //

DELIMITER ;

-- CALL SP_ConsultarVentasDetalladas(2, 0, '2023-11-18', '2023-11-19', 0, 2);


-- CONSULTA AGRUPADA
DELIMITER //
-- DROP PROCEDURE SP_ConsultarVentasAgrupadas;
CREATE PROCEDURE SP_ConsultarVentasAgrupadas(
	IN idVendedorParam INT,
    IN usarFiltroFecha BOOL,
    IN fechaInicioParam DATE,
    IN fechaFinParam DATE,
    IN usarFiltroCategoria BOOL,
    IN idCategoriaParam INT    
)
BEGIN
    -- Asignar valores a variables internas
    SET @idVendedorVar = idVendedorParam;
    SET @fechaInicioVar = fechaInicioParam;
    SET @fechaFinVar = fechaFinParam;
    SET @idCategoriaVar = idCategoriaParam;

    SET @sql = 'SELECT * FROM VistaVentasAgrupadas WHERE 1=1 AND idVendedor = ? ';

    -- Agregar filtros dinámicamente según los parámetros
    IF usarFiltroFecha THEN
        -- Formatear las fechas antes de concatenarlas a la consulta
        SET @fechaInicioVar = DATE_FORMAT(fechaInicioParam, '%Y-%m');
        SET @fechaFinVar = DATE_FORMAT(fechaFinParam, '%Y-%m');
        SET @sql = CONCAT(@sql, 'AND MesAnoVenta BETWEEN ? AND ? ');
    END IF;

    IF usarFiltroCategoria THEN
        SET @sql = CONCAT(@sql, 'AND IDCategoria = ? ');
    END IF;

    -- Preparar y ejecutar la consulta dinámica
    PREPARE stmt FROM @sql;

    -- Ejecutar la consulta dinámica con variables internas según los filtros
    IF usarFiltroFecha AND usarFiltroCategoria THEN
        EXECUTE stmt USING @idVendedorVar, @fechaInicioVar, @fechaFinVar, @idCategoriaVar;
    ELSEIF usarFiltroFecha THEN
        EXECUTE stmt USING @idVendedorVar, @fechaInicioVar, @fechaFinVar;
    ELSEIF usarFiltroCategoria THEN
        EXECUTE stmt USING @idVendedorVar, @idCategoriaVar;
    ELSE
        EXECUTE stmt USING @idVendedorVar;
    END IF;

    DEALLOCATE PREPARE stmt;
END //

DELIMITER ;

-- CALL SP_ConsultarVentasAgrupadas(2, 0, '2023-11-18', '2023-11-19', 0, 2);



-- Consultar los 3 productos mas vendidos
DELIMITER //
-- DROP PROCEDURE ObtenerTop3ProductosVendidos;
CREATE PROCEDURE ObtenerTop3ProductosVendidos()
BEGIN
    SELECT
        IdP,
        NombreP,
        DescripcionNormalP,
        DescripcionCotizadoP,
        PrecioP,
        tipoProducto,
        NombreVendedor,
        IdVendedor,
        PromedioCalificacion,
        Ventas
    FROM
        VistaProductosBusquedaAvanzada
    ORDER BY Ventas DESC
    LIMIT 3;
END //

DELIMITER ;

-- CALL ObtenerTop3ProductosVendidos();



-- Consultar los 3 productos mejor valorados
DELIMITER //
-- DROP PROCEDURE ObtenerTop3ProductosMejorValorados;
CREATE PROCEDURE ObtenerTop3ProductosMejorValorados()
BEGIN
    SELECT
        IdP,
        NombreP,
        DescripcionNormalP,
        DescripcionCotizadoP,
        PrecioP,
        tipoProducto,
        NombreVendedor,
        IdVendedor,
        PromedioCalificacion,
        Ventas
    FROM
        VistaProductosBusquedaAvanzada
    ORDER BY PromedioCalificacion DESC
    LIMIT 3;
END //

DELIMITER ;

-- CALL ObtenerTop3ProductosMejorValorados();


DELIMITER //

CREATE PROCEDURE ObtenerProductosTop3()
BEGIN
    SELECT 
        P.ID_Producto, 
        P.Nombre_Producto, 
        P.Descripcion_Producto, 
        P.Precio_Unitario,
        (SELECT I.Imagen 
         FROM tb_Producto_Imagenes I 
         WHERE P.ID_Producto = I.ID_Producto 
         ORDER BY I.ID_Imagenes_Producto DESC
         LIMIT 1) AS Imagen,
        COALESCE(AVG(V.Calificacion), 0) AS PromedioCalificacion
    FROM tb_Productos P
    LEFT JOIN tb_Valoracion V ON P.ID_Producto = V.ID_Producto
    WHERE P.Estatus = 1 AND P.Cantidad > 0
    GROUP BY P.ID_Producto, P.Nombre_Producto, P.Descripcion_Producto, P.Precio_Unitario
    ORDER BY PromedioCalificacion DESC
    LIMIT 3;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE ObtenerProductosRecientesC()
BEGIN
    SELECT 
        P.ID_Producto, 
        P.Nombre_Producto, 
        P.Descripcion_Producto, 
        P.Precio_Unitario,
        (SELECT I.Imagen 
         FROM tb_Producto_Imagenes I 
         WHERE P.ID_Producto = I.ID_Producto 
         ORDER BY I.ID_Imagenes_Producto DESC
         LIMIT 1) AS Imagen
    FROM tb_Productos P
    WHERE P.Tipo_Producto = 0 AND P.Estatus = 1 AND P.Validacion = 1
    ORDER BY P.Fecha_Alta DESC
    LIMIT 3;
END //

DELIMITER ;


DELIMITER //
CREATE PROCEDURE Sp_Video(IN p_idProducto INT, IN p_rutaVideo VARCHAR(255))
BEGIN
    INSERT INTO tb_Producto_Video(ID_Producto, Ruta_Video)
    VALUES(p_idProducto, p_rutaVideo);
END //
DELIMITER ;




DELIMITER //
CREATE PROCEDURE Sp_ObtenerVideo(IN p_idProducto INT)
BEGIN
	SELECT Ruta_Video
	FROM tb_Producto_Video
	WHERE ID_Producto = p_idProducto;
END //
DELIMITER ;

INSERT INTO tb_valoracion()
VALUES()


SELECT * FROM tb_Producto_Video;
call Sp_ObtenerVideo(5);

SELECT * FROM tb_usuario;

DELIMITER $$
CREATE PROCEDURE SP_ProductosMasVendidos()
BEGIN
    SELECT
        p.ID_Producto,
        (SELECT Imagen FROM tb_Producto_Imagenes pi WHERE pi.ID_Producto = p.ID_Producto LIMIT 1) AS Imagen,
        p.Nombre_Producto,
        p.Precio_Unitario,
        COUNT(dc.ID_Producto) AS CantidadVendida
    FROM
        tb_Productos p
        INNER JOIN tb_Detalles_Compra dc ON p.ID_Producto = dc.ID_Producto
        WHERE
        p.Estatus != 0 AND p.Cantidad > 0
    GROUP BY
        p.ID_Producto, p.Nombre_Producto, p.Precio_Unitario 
    ORDER BY
        CantidadVendida DESC
    LIMIT 3;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE SP_ActualizarRutaVideo(IN p_ID_Producto INT, IN p_RutaVideo VARCHAR(300))
BEGIN
    UPDATE tb_Producto_Video
    SET Ruta_Video = p_RutaVideo
    WHERE ID_Producto = p_ID_Producto;
END $$
DELIMITER ;




















































