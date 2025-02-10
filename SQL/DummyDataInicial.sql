USE dbTiltedShop; 

-- Insertar el rol de Administrador
INSERT INTO tb_Roles (Nombre) VALUES ('Administrador');

-- Insertar el rol de Vendedor
INSERT INTO tb_Roles (Nombre) VALUES ('Vendedor');

-- Insertar el rol de Comprador
INSERT INTO tb_Roles (Nombre) VALUES ('Comprador');



-- Insertar un registro para sexo masculino
INSERT INTO tb_Sexo (Género) VALUES ('Masculino');

-- Insertar un registro para sexo femenino
INSERT INTO tb_Sexo (Género) VALUES ('Femenino');



-- Insertar un usuario Administrador
INSERT INTO tb_Usuario (ID_Sexo, ID_Roles, Nombre, Apellido_Paterno, Apellido_Materno, Username, PassW, Correo, Imagen, Fecha_Nacimiento, Fecha_Registro, Privacidad, Estado, Colonia, Calle, Numero_Casa)
VALUES (1, 1, 'Max', 'Molina', 'Alvarado', 'admin', 'Hola123!', 'admin@example.com', 'imagen_admin', '2000-01-01', NOW(), 1, 'EstadoAdmin', 'ColoniaAdmin', 'CalleAdmin', '1');

-- Insertar un usuario Vendedor
INSERT INTO tb_Usuario (ID_Sexo, ID_Roles, Nombre, Apellido_Paterno, Apellido_Materno, Username, PassW, Correo, Imagen, Fecha_Nacimiento, Fecha_Registro, Privacidad, Estado, Colonia, Calle, Numero_Casa)
VALUES (1, 2, 'Mauricio', 'Ruiz', 'Castillo', 'vendedor', 'Hola123!', 'vendedor@example.com', 'imagen_vendedor', '2000-01-01', NOW(), 1, 'EstadoVendedor', 'ColoniaVendedor', 'CalleVendedor', '1');

-- Insertar un usuario Comprador
INSERT INTO tb_Usuario (ID_Sexo, ID_Roles, Nombre, Apellido_Paterno, Apellido_Materno, Username, PassW, Correo, Imagen, Fecha_Nacimiento, Fecha_Registro, Privacidad, Estado, Colonia, Calle, Numero_Casa)
VALUES (1, 3, 'Luis', 'Esparza', 'Lopez', 'comprador', 'Hola123!', 'comprador@example.com', 'imagen_comprador', '2000-01-01', NOW(), 1, 'EstadoComprador', 'ColoniaComprador', 'CalleComprador', '1');



-- Insertar metodos de pago
INSERT INTO tb_Metodos_Pago (Nombre_Metodo_Pago) VALUES
('Tarjeta de Crédito'),
('Tarjeta de Débito'),
('PayPal');


