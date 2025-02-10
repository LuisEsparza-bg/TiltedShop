CREATE VIEW Vista_Categorias AS
SELECT ID_Categoria, Nombre_Categoria, Descripcion_Categoria
FROM tb_Categorias;

-- VER MIS PRODUCTOS

CREATE VIEW Vista_misProductos AS
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
    Fecha_Alta
FROM tb_Productos
WHERE Estatus = 1;


CREATE VIEW Vista_Categorias_Producto AS
SELECT
    CP.ID_Producto,
    CP.ID_Categoria,
    C.Descripcion_Categoria,
    C.Nombre_Categoria
FROM
    tb_Categoria_Producto CP
JOIN
    tb_Categorias C ON CP.ID_Categoria = C.ID_Categoria
WHERE
    CP.Estatus = 1;
    
CREATE VIEW Vista_Productos_Recientes AS
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
WHERE P.Estatus = 1 AND P.Cantidad > 0
ORDER BY P.ID_Producto DESC
LIMIT 3;


USE dbTiltedShop; 

-- VIEW para Obtener los Productos que necesitan ser validados
-- DROP VIEW VistaProductosValidacion;
CREATE OR REPLACE VIEW VistaProductosValidacion AS
SELECT PV.ID_Producto AS IdP, PV.Nombre_Producto AS NombreP, PV.Descripcion_Producto AS DescripcionP
FROM tb_Productos AS PV
WHERE Validacion IS NULL;
-- END VIEW

-- VIEW para Obtener los Productos que fueron validados
-- DROP VIEW IF EXISTS VistaProductosValidados;
CREATE OR REPLACE VIEW VistaProductosValidados AS
SELECT 
	PV.ID_Producto AS IdP, 
	PV.Nombre_Producto AS NombreP, 
    PV.Descripcion_Producto AS DescripcionP,
    U.Username AS NombreAdmin,
    U.ID_Usuario AS IdAdmin
FROM 
	tb_Productos AS PV
JOIN
    tb_Usuario AS U 
ON 
	PV.ID_Usuario_Admin = U.ID_Usuario
WHERE 
	Validacion = 1;
-- END VIEW



-- VIEW para Obtener los Productos que fueron validados
-- DROP VIEW IF EXISTS VistaProductosBusqueda;
CREATE OR REPLACE VIEW VistaProductosBusqueda AS
SELECT 
	PS.ID_Producto AS IdP, 
	PS.Nombre_Producto AS NombreP, 
    PS.Descripcion_Producto AS DescripcionNormalP,
    PS.Descripcion_Cotizado AS DescripcionCotizadoP,
    PS.Precio_Unitario As PrecioP,
    PS.Tipo_Producto AS tipoProducto,
    U.Username AS NombreVendedor,
    PS.ID_Usuario_Vendedor AS IdVendedor,
    COALESCE(AVG(Val.Calificacion), 0) AS PromedioCalificacion
FROM 
	tb_Productos AS PS
JOIN
    tb_Usuario AS U 
ON 
	PS.ID_Usuario_Vendedor = U.ID_Usuario
LEFT JOIN
    tb_Valoracion AS Val
ON 
    PS.ID_Producto = Val.ID_Producto
WHERE 
	PS.Estatus = 1
GROUP BY
	PS.ID_Producto, PS.Nombre_Producto, PS.Descripcion_Producto, PS.Descripcion_Cotizado, PS.Precio_Unitario, U.ID_Usuario, tipoProducto;
-- END VIEW

-- SELECT * FROM VistaProductosBusqueda WHERE NombreP = 'MesaFono';



-- VIEW para Obtener los Productos que fueron validados
-- DROP VIEW IF EXISTS DetallesProducto;
CREATE OR REPLACE VIEW DetallesProducto AS
SELECT 
	PS.ID_Producto AS IdP, 
	PS.Nombre_Producto AS NombreP, 
    PS.Descripcion_Producto AS DescripcionNormalP,
    PS.Descripcion_Cotizado AS DescripcionCotizadoP,
    PS.Precio_Unitario As PrecioP,
    PS.Cantidad AS CantidadP,
    U.Username AS NombreVendedor,
    PS.ID_Usuario_Vendedor AS IdVendedor,
    PS.Validacion AS Validacion,
	U_Admin.ID_Usuario AS IdAdmin,  -- Nuevo campo para el ID del admin cotizador
    U_Admin.Username AS NombreAdmin,
    COALESCE(AVG(Val.Calificacion), 0) AS PromedioCalificacion
FROM 
	tb_Productos AS PS
JOIN
    tb_Usuario AS U 
ON 
	PS.ID_Usuario_Vendedor = U.ID_Usuario
LEFT JOIN
    tb_Valoracion AS Val
ON 
    PS.ID_Producto = Val.ID_Producto
LEFT JOIN
    tb_Usuario AS U_Admin  -- Nuevo JOIN para el usuario administrador
ON 
    PS.ID_Usuario_Admin = U_Admin.ID_Usuario  -- Ajusta este campo según tu estructura
WHERE 
	PS.Estatus = 1
GROUP BY
	PS.ID_Producto, PS.Nombre_Producto, PS.Descripcion_Producto, PS.Descripcion_Cotizado, PS.Precio_Unitario,
    PS.Cantidad, U.Username, PS.ID_Usuario_Vendedor, PS.Validacion, U_Admin.ID_Usuario, U_Admin.Username;
-- END VIEW

-- SELECT * FROM DetallesProducto;


-- VIEW PARA OBTENER LOS PRODUCTOS DE UNA COMRPA
CREATE OR REPLACE VIEW VistaProductosComprados AS
SELECT
    DC.ID_Detalles_Compra AS idDetalle,
    DC.ID_Compra AS idCompra,
    DC.ID_Producto AS idProducto,
    P.Nombre_Producto AS nombreP,
    -- P.Tipo_Producto AS tipoProducto,
    U.Username AS vendedor,
    DC.Cantidad AS cantidad
FROM
    tb_Detalles_Compra AS DC
JOIN tb_Productos P 
	ON DC.ID_Producto = P.ID_Producto
JOIN tb_Usuario U 
	ON P.ID_Usuario_Vendedor = U.ID_Usuario;
    
-- SELECT * FROM VistaProductosComprados;



-- VIEW para Obtener los Productos que fueron validados
-- DROP VIEW IF EXISTS VistaProductosBusquedaAvanzada;
CREATE OR REPLACE VIEW VistaProductosBusquedaAvanzada AS
SELECT 
    PS.ID_Producto AS IdP, 
    PS.Nombre_Producto AS NombreP, 
    PS.Descripcion_Producto AS DescripcionNormalP,
    PS.Descripcion_Cotizado AS DescripcionCotizadoP,
    PS.Precio_Unitario As PrecioP,
    PS.Tipo_Producto AS tipoProducto,
    U.Username AS NombreVendedor,
    PS.ID_Usuario_Vendedor AS IdVendedor,
    COALESCE(Val.PromedioCalificacion, 0) AS PromedioCalificacion,
    COALESCE(Ventas.TotalVentas, 0) AS Ventas
FROM 
    tb_Productos AS PS
JOIN
    tb_Usuario AS U 
ON 
    PS.ID_Usuario_Vendedor = U.ID_Usuario
LEFT JOIN (
    SELECT 
        ID_Producto,
        AVG(Calificacion) AS PromedioCalificacion
    FROM 
        tb_Valoracion
    GROUP BY 
        ID_Producto
) AS Val
ON 
    PS.ID_Producto = Val.ID_Producto
LEFT JOIN (
    SELECT 
        ID_Producto,
        SUM(Cantidad) AS TotalVentas
    FROM 
        tb_Detalles_Compra
    GROUP BY 
        ID_Producto
) AS Ventas
ON 
    PS.ID_Producto = Ventas.ID_Producto
WHERE 
    PS.Estatus = 1;
-- END VIEW

-- SELECT * FROM VistaProductosBusquedaAvanzada;





-- REPORTES

-- VENDEDOR

-- Consulta detallada
CREATE OR REPLACE VIEW VistaVentasDetalladas AS
SELECT
    P.ID_Usuario_Vendedor AS idVendedor,
    C.Fecha_Hora AS FechaHora,
    P.ID_Producto AS idProducto,
    P.Nombre_Producto AS nombreProducto,
    Cat.ID_Categoria AS idCategoria,
    Cat.Nombre_Categoria AS nombreCategorias,
	-- COALESCE(AVG(V.Calificacion), 0) AS PromedioCalificacion,
    COALESCE(V.Calificacion, 0) AS PromedioCalificacion,
    DC.Precio_Unitario AS Precio,
    P.Cantidad AS Existencia_Actual
FROM tb_Compra C
JOIN tb_Detalles_Compra DC ON C.ID_Compra = DC.ID_Compra
JOIN tb_Productos P ON DC.ID_Producto = P.ID_Producto
JOIN tb_Categoria_Producto CP ON P.ID_Producto = CP.ID_Producto
JOIN tb_Categorias Cat ON CP.ID_Categoria = Cat.ID_Categoria
LEFT JOIN tb_Valoracion V ON P.ID_Producto = V.ID_Producto AND C.ID_Usuario_Cliente = V.ID_Usuario_Cliente
GROUP BY idVendedor, FechaHora, idProducto, nombreProducto, idCategoria, nombreCategorias, Precio, Existencia_Actual, PromedioCalificacion;
    
-- SELECT * FROM VistaVentasDetalladas;


-- Consulta agrupada
CREATE OR REPLACE VIEW VistaVentasAgrupadas AS
SELECT
	P.ID_Usuario_Vendedor AS idVendedor,
    DATE_FORMAT(C.Fecha_Hora, '%Y-%m') AS MesAnoVenta,
    Cat.ID_Categoria AS IDCategoria,
    Cat.Nombre_Categoria AS Categoria,
    SUM(DC.Cantidad) AS VentasUnidades,
    SUM(DC.Precio_Unitario * DC.Cantidad) AS VentasDinero
FROM tb_Compra C
JOIN tb_Detalles_Compra DC ON C.ID_Compra = DC.ID_Compra
JOIN tb_Productos P ON DC.ID_Producto = P.ID_Producto
JOIN tb_Categoria_Producto CP ON P.ID_Producto = CP.ID_Producto
JOIN tb_Categorias Cat ON CP.ID_Categoria = Cat.ID_Categoria
GROUP BY idVendedor, MesAnoVenta, IDCategoria, Categoria;

-- SELECT * FROM VistaVentasAgrupadas;
-- COMPRADOR

-- Vista para la consulta de compras por parte de los usuarios compradores
CREATE OR REPLACE VIEW VistaCompras AS
SELECT
	C.ID_Usuario_Cliente AS idComprador,
    C.ID_Compra AS idCompra,
    C.Fecha_Hora AS fechaHoraCompra,
    CP.ID_Categoria AS idCategoria,
    P.ID_Producto AS idProducto,
    P.Nombre_Producto AS nombreProducto,
    V.Calificacion AS calificacion,
    DC.Precio_Unitario AS precio
FROM tb_Compra C
JOIN tb_Detalles_Compra DC ON C.ID_Compra = DC.ID_Compra
JOIN tb_Productos P ON DC.ID_Producto = P.ID_Producto
JOIN tb_Categoria_Producto CP ON P.ID_Producto = CP.ID_Producto
LEFT JOIN tb_Valoracion V ON P.ID_Producto = V.ID_Producto AND C.ID_Usuario_Cliente = V.ID_Usuario_Cliente;


-- SELECT * FROM VistaCompras where idComprador = 5;



    













