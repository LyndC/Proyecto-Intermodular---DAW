DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS roles;

CREATE TABLE roles (
    id_rol INT PRIMARY KEY AUTO_INCREMENT,
    nombre_rol VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    id_rol INT NOT NULL,
    nombre_usuario VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
);
DROP TABLE IF EXISTS categorias_habitacion;
DROP TABLE IF EXISTS habitaciones;

CREATE TABLE categorias_habitacion (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    capacidad_maxima INT NOT NULL,
    precio_base DECIMAL(10,2) NOT NULL
);

CREATE TABLE habitaciones (
    id_habitacion INT PRIMARY KEY AUTO_INCREMENT,
    numero VARCHAR(10) NOT NULL UNIQUE,
    id_categoria INT NOT NULL,
    estado ENUM('Disponible', 'Ocupada', 'Mantenimiento', 'Limpieza') DEFAULT 'Disponible',
    FOREIGN KEY (id_categoria) REFERENCES categorias_habitacion(id_categoria)
);

DROP TABLE IF EXISTS clientes;

CREATE TABLE clientes (
    id_cliente INT PRIMARY KEY, 
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE,
    documento_identidad VARCHAR(50) UNIQUE,
    telefono VARCHAR(20),
    direccion varchar(255),
    ciudad varchar (55),
    
    FOREIGN KEY (id_cliente) REFERENCES usuarios(id_usuario)
);

DROP TABLE IF EXISTS reservas;

CREATE TABLE reservas (
    id_reserva INT PRIMARY KEY AUTO_INCREMENT,
    id_cliente INT NOT NULL,
    id_habitacion INT NOT NULL,
    fecha_reserva DATETIME NOT NULL,
    fecha_entrada DATE NOT NULL,
    fecha_salida DATE NOT NULL,
    estado ENUM('Pendiente', 'Confirmada', 'Check-in', 'Check-out', 'Cancelada') DEFAULT 'Pendiente',
    canal_reserva VARCHAR(50),
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    FOREIGN KEY (id_habitacion) REFERENCES habitaciones(id_habitacion)
);

DROP TABLE IF EXISTS servicios;
DROP TABLE IF EXISTS facturas;
DROP TABLE IF EXISTS pagos;

CREATE TABLE servicios (
    id_servicio INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    precio_unitario DECIMAL(10,2) NOT NULL
);

CREATE TABLE facturas (
    id_factura INT PRIMARY KEY AUTO_INCREMENT,
    id_reserva INT UNIQUE NOT NULL,
    fecha_emision DATETIME NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    impuestos DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('Pendiente', 'Pagada', 'Cancelada') DEFAULT 'Pendiente',
    FOREIGN KEY (id_reserva) REFERENCES reservas(id_reserva)
);

CREATE TABLE pagos (
    id_pago INT PRIMARY KEY AUTO_INCREMENT,
    id_factura INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    tipo_pago VARCHAR(50),
    fecha_pago DATETIME NOT NULL,
    FOREIGN KEY (id_factura) REFERENCES facturas(id_factura)
);

DROP TABLE IF EXISTS registros_estadia;

CREATE TABLE registros_estadia (
    id_registro INT PRIMARY KEY AUTO_INCREMENT,
    id_reserva INT NOT NULL,
    fecha_checkin DATETIME,
    fecha_checkout DATETIME,
    observaciones TEXT,
    FOREIGN KEY (id_reserva) REFERENCES reservas(id_reserva)
);

DROP TABLE IF EXISTS servicios_reserva;

CREATE TABLE servicios_reserva (
    id_servicio_reserva INT PRIMARY KEY AUTO_INCREMENT,
    id_reserva INT NOT NULL,
    id_servicio INT NOT NULL,
    cantidad INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_reserva) REFERENCES reservas(id_reserva),
    FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio)
);

