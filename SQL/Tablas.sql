DROP DATABASE IF EXISTS dbTiltedShop;

CREATE DATABASE IF NOT EXISTS dbTiltedShop;

USE dbTiltedShop;

-- TABLA USUARIO
CREATE TABLE IF NOT EXISTS tb_Usuario (
    ID_Usuario INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único del usuario',
    ID_Sexo INT NOT NULL COMMENT 'ID que hace referencia al sexo del usuario',
    ID_Roles INT NOT NULL COMMENT 'ID que especifica el rol o tipo de usuario',
    Nombre VARCHAR(25) NOT NULL COMMENT 'Nombre del usuario',
    Apellido_Paterno VARCHAR(25) NOT NULL COMMENT 'Apellido paterno del usuario',
    Apellido_Materno VARCHAR(25) NOT NULL COMMENT 'Apellido materno del usuario',
    Username VARCHAR(15) NOT NULL COMMENT 'Nombre de usuario para iniciar sesión',
    PassW VARCHAR(15) NOT NULL COMMENT 'Contraseña del usuario',
    Correo VARCHAR(25) NOT NULL COMMENT 'Dirección de correo electrónico del usuario',
    Imagen BLOB NOT NULL COMMENT 'Imagen de perfil del usuario almacenada en formato BLOB',
    Fecha_Nacimiento DATE NOT NULL COMMENT 'Fecha de nacimiento del usuario',
    Fecha_Registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha y hora de registro del usuario',
    Privacidad BIT NOT NULL COMMENT 'Indicador de configuración de privacidad',
    Estado VARCHAR(30) NOT NULL COMMENT 'Estado de residencia del usuario',
    Colonia VARCHAR(50) NOT NULL COMMENT 'Colonia de residencia del usuario',
    Calle VARCHAR(30) NOT NULL COMMENT 'Calle de residencia del usuario',
    Numero_Casa VARCHAR(30) NOT NULL COMMENT 'Número de casa de residencia del usuario'
);


-- TABLA ROLES
CREATE TABLE IF NOT EXISTS tb_Roles (
    ID_Roles INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único para el ID de Roles',
    Nombre VARCHAR (20) NOT NULL COMMENT 'Nombre del rol'
);


CREATE TABLE IF NOT EXISTS tb_Sexo (
    ID_Sexo INT AUTO_INCREMENT PRIMARY KEY,
    Género VARCHAR (20) NOT NULL COMMENT 'Género'
);


-- TABLA LISTAS
CREATE TABLE IF NOT EXISTS tb_Listas (
    ID_Lista INT AUTO_INCREMENT PRIMARY KEY,
    ID_Usuario INT NOT NULL COMMENT 'ID del usuario propietario de la lista',
    Nombre_Lista VARCHAR (25) NOT NULL COMMENT 'Nombre de la lista',
    Descripcion TEXT NOT NULL COMMENT 'Descripción de la lista',
    Privacidad BIT NOT NULL COMMENT 'Indicador de configuración de privacidad',
    Estatus BIT DEFAULT 1 NOT NULL COMMENT 'Estatus de la lista'
);

-- TABLA IMAGENES DE LISTAS
CREATE TABLE IF NOT EXISTS tb_Listas_Imagenes (
    ID_Imagen_Lista INT AUTO_INCREMENT PRIMARY KEY,
    ID_Lista INT NOT NULL COMMENT 'ID de la lista a la que pertenece la imagen',
    Imagen BLOB NOT NULL COMMENT 'Imagen de la lista almacenada en formato BLOB'
);

-- TABLA PRODUCTOS

CREATE TABLE IF NOT EXISTS tb_Productos (
    ID_Producto INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Usuario_Admin INT NULL COMMENT 'ID del usuario administrador del producto',
    ID_Usuario_Vendedor INT NOT NULL COMMENT 'ID del usuario vendedor del producto',
    Nombre_Producto VARCHAR(35) NOT NULL COMMENT 'Nombre del producto',
    Descripcion_Producto TEXT NOT NULL COMMENT 'Descripción del producto',
    Tipo_Producto BIT NOT NULL COMMENT 'Tipo de producto',
    Precio_Unitario DECIMAL(10, 2) COMMENT 'Precio unitario del producto',
    Cantidad INT COMMENT 'Cantidad de producto disponible',
    Descripcion_Cotizado VARCHAR(300) COMMENT 'Descripción del cotizado',
    Estatus BIT DEFAULT 0 NOT NULL COMMENT 'Estatus del producto',
    Validacion BIT DEFAULT NULL COMMENT 'Validación del producto', 
    Fecha_Alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha y hora de rSP_MisProductosegistro del producto'
);






-- TABLA PRODUCTO IMAGENES

CREATE TABLE IF NOT EXISTS tb_Producto_Imagenes (
    ID_Imagenes_Producto INT AUTO_INCREMENT PRIMARY KEY,
    ID_Producto INT NOT NULL COMMENT 'ID del producto al que pertenece la imagen',
    Imagen LONGBLOB NOT NULL COMMENT 'Imagen del producto almacenada en formato BLOB'
);



-- TABLA PRODUCTO VIDEOS

CREATE TABLE IF NOT EXISTS tb_Producto_Video (
    ID_Video_Producto INT AUTO_INCREMENT PRIMARY KEY,
    ID_Producto INT NOT NULL COMMENT 'ID del producto al que pertenece el video',
    Ruta_Video VARCHAR(255) NOT NULL COMMENT 'Ruta del video del producto'
);


-- TABLA CATEGORIAS

CREATE TABLE IF NOT EXISTS tb_Categorias (
    ID_Categoria INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Usuario_Vendedor INT NOT NULL COMMENT 'ID del usuario vendedor creador de la categoría',
    Nombre_Categoria VARCHAR(35) NOT NULL COMMENT 'Nombre de la categoría',
    Descripcion_Categoria VARCHAR (300) NOT NULL COMMENT 'Descripción de la categoría'
);


-- TABLA CARRITO

CREATE TABLE IF NOT EXISTS tb_Carrito (
    ID_Carrito INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Usuario_Cliente INT NOT NULL COMMENT 'ID del usuario cliente propietario del carrito'
);

-- TABLA CATEGORIA PRODUCTO

CREATE TABLE IF NOT EXISTS tb_Categoria_Producto (
    ID_CatProd INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Producto INT NOT NULL COMMENT 'ID del producto al que se asigna la categoría',
    ID_Categoria INT NOT NULL COMMENT 'ID de la categoría asignada al producto',
    Estatus INT NOT NULL COMMENT 'Estatus del producto'
);



-- TABLA COMPRA

CREATE TABLE IF NOT EXISTS tb_Compra (
    ID_Compra INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Usuario_Cliente INT NOT NULL COMMENT 'ID del usuario cliente que realizó la compra',
    Fecha_Hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha y hora de la compra',
    Total DECIMAL (10,2) NOT NULL COMMENT 'Total de la compra',
    ID_Metodo_Pago INT NOT NULL COMMENT 'Referencia al ID del metodo de pago'
);





-- TABLA DETALLES COMPRA

CREATE TABLE IF NOT EXISTS tb_Detalles_Compra (
    ID_Detalles_Compra INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Compra INT NOT NULL COMMENT 'ID de la compra a la que pertenecen los detalles',
    ID_Producto INT NOT NULL COMMENT 'ID del producto comprado',
    Cantidad INT NOT NULL COMMENT 'Cantidad del producto comprado',
    Precio_Unitario DECIMAL (10,2) NOT NULL COMMENT 'Precio unitario del producto',
    Descripcion_Cot_Venta VARCHAR (300) COMMENT 'Descripción de la cotización de venta'
);


-- Tabla REGALOS

CREATE TABLE IF NOT EXISTS tb_Regalos (
    ID_Regalo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Usuario_Receptor INT NOT NULL COMMENT 'ID del usuario receptor del regalo',
    ID_Compra INT NOT NULL COMMENT 'ID de la compra asociada al regalo'
);

-- Tabla LISTA DE PRODUCTOS
CREATE TABLE IF NOT EXISTS tb_Lista_Productos (
    ID_ListaProd INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Lista INT NOT NULL COMMENT 'ID de la lista a la que pertenecen los productos',
    ID_Producto INT NOT NULL COMMENT 'ID del producto en la lista',
    Estatus INT NOT NULL COMMENT 'Es el estatus de la eliminacion logica'
);


-- Tabla PRODUCTOS EN CARRITO

CREATE TABLE IF NOT EXISTS tb_Productos_Carrito (
    ID_ProdCarrito INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Carrito INT NOT NULL COMMENT 'ID del carrito al que pertenece el producto',
    ID_Producto INT NOT NULL COMMENT 'ID del producto en el carrito',
    Cantidad INT NOT NULL COMMENT 'Cantidad del producto en el carrito',
    Precio DECIMAL(10, 2) NOT NULL COMMENT 'Precio del producto en el carrito',
    Descripcion_Cot_Venta VARCHAR(300) COMMENT 'Descripción de la cotización de venta',
    Estatus BIT NOT NULL COMMENT 'Estatus del producto en el carrito'
);


-- Tabla Chats

CREATE TABLE IF NOT EXISTS tb_Chats (
    ID_Chats INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Usuario_Cliente INT NOT NULL COMMENT 'ID del usuario cliente en el chat',
    ID_Usuario_Vendedor INT NOT NULL COMMENT 'ID del usuario vendedor en el chat',
    ID_Producto INT NOT NULL COMMENT 'ID del producto asociado al chat'
);


-- Tabla Historial de Mensajes

CREATE TABLE IF NOT EXISTS tb_Mensajes (
    ID_Historial_Mensajes INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Chats INT NOT NULL COMMENT 'ID del chat al que pertenecen los mensajes',
    ID_Usuario_Emisor INT NOT NULL COMMENT 'ID del usuario que envió el mensaje',
    ID_Usuario_Receptor INT NOT NULL COMMENT 'ID del usuario que recibió el mensaje',
    Mensaje VARCHAR(500) NOT NULL COMMENT 'Contenido del mensaje',
    Fecha_Mensaje TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha y hora del mensaje'
);


-- Tabla Valoracion

CREATE TABLE IF NOT EXISTS tb_Valoracion (
    ID_Valoracion INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Usuario_Cliente INT NOT NULL COMMENT 'ID del usuario cliente que realiza la valoración',
    ID_Producto INT NOT NULL COMMENT 'ID del producto valorado',
    Calificacion INT NOT NULL COMMENT 'Calificación otorgada al producto'
);

-- Tabla Comentarios

CREATE TABLE IF NOT EXISTS tb_Comentarios (
    ID_Comentario INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Usuario_Cliente INT NOT NULL COMMENT 'ID del usuario cliente que realiza el comentario',
    ID_Producto INT NOT NULL COMMENT 'ID del producto comentado',
    Comentario VARCHAR (100) NOT NULL COMMENT 'Contenido del comentario'
);

-- TABLA METODOS_PAGO
CREATE TABLE IF NOT EXISTS tb_Metodos_Pago (
    ID_Metodo_Pago INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Nombre_Metodo_Pago VARCHAR(50) NOT NULL COMMENT 'Nombre del método de pago'
);

-- Detalles de Pago
CREATE TABLE IF NOT EXISTS tb_Detalles_Pago (
    ID_Detalle_Pago INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ID_Compra INT NOT NULL COMMENT 'ID de la compra a la que pertenecen los detalles de pago',
    ID_Metodo_Pago INT NOT NULL COMMENT 'ID del método de pago usado en la compra',
    Transaccion_Paypal VARCHAR(255) COMMENT 'Transacción de Paypal',
    CVV VARCHAR(4) COMMENT 'CVV de la tarjeta',
    Nombre_Tarjeta VARCHAR(100) COMMENT 'Nombre en la tarjeta',
    Numero_Tarjeta VARCHAR(16) COMMENT 'Número de la tarjeta',
    Fecha_Vencimiento VARCHAR(7) COMMENT 'Fecha de vencimiento (MM/YYYY)'
);


-- ALTER TABLE PARA AGREGAR LAS FOREIGN KEYS
-- FK DETALLES_PAGO - COMPRA
ALTER TABLE tb_Detalles_Pago
ADD CONSTRAINT FK_Detalles_Pago_Compra FOREIGN KEY (ID_Compra) REFERENCES tb_Compra(ID_Compra);

-- FK DETALLES_PAGO - METODO_PAGO
ALTER TABLE tb_Detalles_Pago
ADD CONSTRAINT FK_Detalles_Pago_Metodo_Pago FOREIGN KEY (ID_Metodo_Pago) REFERENCES tb_Metodos_Pago(ID_Metodo_Pago);


-- FK VALORACION

ALTER TABLE tb_Valoracion
ADD CONSTRAINT FK_Cliente_Usuario_Valoracion FOREIGN KEY (ID_Usuario_Cliente) REFERENCES tb_Usuario(ID_Usuario);

ALTER TABLE tb_Valoracion
ADD CONSTRAINT FK_IdProducto_Valoracion FOREIGN KEY (ID_Producto) REFERENCES tb_Productos(ID_Producto);

-- FK COMENTARIO

ALTER TABLE tb_Comentarios
ADD CONSTRAINT FK_Cliente_Usuario_Comentarios FOREIGN KEY (ID_Usuario_Cliente) REFERENCES tb_Usuario(ID_Usuario);

ALTER TABLE tb_Comentarios
ADD CONSTRAINT FK_IdProducto_Comentarios FOREIGN KEY (ID_Producto) REFERENCES tb_Productos(ID_Producto);


-- FK HistorialMensajes

ALTER TABLE tb_Mensajes
ADD CONSTRAINT FK_Usuario_Receptor_Mensajes FOREIGN KEY (ID_Usuario_Receptor) REFERENCES tb_Usuario(ID_Usuario);

ALTER TABLE tb_Mensajes
ADD CONSTRAINT FK_Usuario_Emisor_Mensajes FOREIGN KEY (ID_Usuario_Emisor) REFERENCES tb_Usuario(ID_Usuario);

ALTER TABLE tb_Mensajes
ADD CONSTRAINT FK_IdChats_Mensajes FOREIGN KEY (ID_Chats) REFERENCES tb_Chats(ID_Chats);



-- FK CHATS

ALTER TABLE tb_Chats
ADD CONSTRAINT FK_Cliente_Usuario_Chats FOREIGN KEY (ID_Usuario_Cliente) REFERENCES tb_Usuario(ID_Usuario);

ALTER TABLE tb_Chats
ADD CONSTRAINT FK_Vendedor_Usuario_Chats FOREIGN KEY (ID_Usuario_Vendedor) REFERENCES tb_Usuario(ID_Usuario);

ALTER TABLE tb_Chats
ADD CONSTRAINT FK_IdProducto_Chats FOREIGN KEY (ID_Producto) REFERENCES tb_Productos(ID_Producto);


-- FK Productos en Carrito

ALTER TABLE tb_Productos_Carrito
ADD CONSTRAINT FK_Carrito_ProductosCarrito FOREIGN KEY (ID_Carrito) REFERENCES tb_Carrito(ID_Carrito);

ALTER TABLE tb_Productos_Carrito
ADD CONSTRAINT FK_IdProducto_ProductosCarrito FOREIGN KEY (ID_Producto) REFERENCES tb_Productos(ID_Producto);

-- FK LISTA DE PRODUCTOS

ALTER TABLE tb_Lista_Productos
ADD CONSTRAINT FK_IdProducto_ListaProductos FOREIGN KEY (ID_Producto) REFERENCES tb_Productos(ID_Producto);

ALTER TABLE tb_Lista_Productos
ADD CONSTRAINT FK_IdLista_ListaProductos FOREIGN KEY (ID_Lista) REFERENCES tb_Listas(ID_Lista);


-- FK REGALOS
ALTER TABLE tb_Regalos
ADD CONSTRAINT FK_IdCompra_Regalos FOREIGN KEY (ID_Compra) REFERENCES tb_Compra(ID_Compra);

ALTER TABLE tb_Regalos
ADD CONSTRAINT FK_Usuario_Receptor_Regalos FOREIGN KEY (ID_Usuario_Receptor) REFERENCES tb_Usuario(ID_Usuario);


-- FK DETALLES COMPRA
ALTER TABLE tb_Detalles_Compra
ADD CONSTRAINT FK_IdProducto_DetallesCompra FOREIGN KEY (ID_Producto) REFERENCES tb_Productos(ID_Producto);

ALTER TABLE tb_Detalles_Compra
ADD CONSTRAINT FK_IdCompra_DetallesCompra FOREIGN KEY (ID_Compra) REFERENCES tb_Compra(ID_Compra);

-- FK COMPRA
ALTER TABLE tb_Compra
ADD CONSTRAINT FK_Cliente_Usuario_Compra FOREIGN KEY (ID_Usuario_Cliente) REFERENCES tb_Usuario(ID_Usuario);


-- FK CATEGORIA PRODUCTO

ALTER TABLE tb_Categoria_Producto
ADD CONSTRAINT FK_IdProducto_CategoriaProducto FOREIGN KEY (ID_Producto) REFERENCES tb_Productos(ID_Producto);

ALTER TABLE tb_Categoria_Producto
ADD CONSTRAINT FK_Categoria_CategoriaProducto FOREIGN KEY (ID_Categoria) REFERENCES tb_Categorias(ID_Categoria);


-- FK CARRITO
ALTER TABLE tb_Carrito
ADD CONSTRAINT FK_Cliente_Usuario_Carrito FOREIGN KEY (ID_Usuario_Cliente) REFERENCES tb_Usuario(ID_Usuario);

-- FK CATEGORIAS
ALTER TABLE tb_Categorias
ADD CONSTRAINT FK_Vendedor_Usuario_Categorias FOREIGN KEY (ID_Usuario_Vendedor) REFERENCES tb_Usuario(ID_Usuario);

-- FK Imagenes Productos
ALTER TABLE tb_Producto_Imagenes
ADD CONSTRAINT FK_IdProducto_ImagenesProducto FOREIGN KEY (ID_Producto) REFERENCES tb_Productos(ID_Producto);

ALTER TABLE tb_Producto_Video
ADD CONSTRAINT FK_IdProducto_VideoProducto FOREIGN KEY (ID_Producto) REFERENCES tb_Productos(ID_Producto);


-- FK PRODUCTOS

ALTER TABLE tb_Productos
ADD CONSTRAINT FK_Admin_Usuario_Producto FOREIGN KEY (ID_Usuario_Admin) REFERENCES tb_Usuario(ID_Usuario);

ALTER TABLE tb_Productos
ADD CONSTRAINT FK_Vendedor_Usuario_Producto FOREIGN KEY (ID_Usuario_Vendedor) REFERENCES tb_Usuario(ID_Usuario);


-- FK USUARIO
ALTER TABLE tb_Usuario
ADD CONSTRAINT FK_Sexo_Usuario FOREIGN KEY (ID_Sexo) REFERENCES tb_Sexo(ID_Sexo);

ALTER TABLE tb_Usuario
ADD CONSTRAINT FK_Roles_Usuario FOREIGN KEY (ID_Roles) REFERENCES tb_Roles(ID_Roles);

-- FK LISTA

ALTER TABLE tb_Listas
ADD CONSTRAINT FK_IdUsuario_Lista FOREIGN KEY (ID_Usuario) REFERENCES tb_Usuario(ID_Usuario);

-- FK Lista Imagenes

ALTER TABLE tb_Listas_Imagenes
ADD CONSTRAINT FK_IdLista_ImagenLista FOREIGN KEY (ID_Lista) REFERENCES tb_Listas(ID_Lista);
