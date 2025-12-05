-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2025 a las 00:26:17
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reservas_hotel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_habitacion`
--

CREATE TABLE `categorias_habitacion` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `capacidad_maxima` int(11) NOT NULL,
  `precio_base` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_habitacion`
--

INSERT INTO `categorias_habitacion` (`id_categoria`, `nombre`, `capacidad_maxima`, `precio_base`) VALUES
(1, 'Estándar', 2, 50.00),
(2, 'Doble', 4, 75.00),
(3, 'Suite', 2, 120.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `documento_identidad` varchar(50) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `ciudad` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `email`, `documento_identidad`, `telefono`, `direccion`, `ciudad`) VALUES
(1, 'Ana Torres', 'ana@mail.com', '123A', '600100100', 'Calle Sol 1', 'Madrid'),
(2, 'Luis Pérez', 'luis@mail.com', '456B', '600200200', 'Calle Luna 3', 'Barcelona'),
(3, 'Marta Gil', 'marta@mail.com', '789C', '600300300', 'Av. Libertad 5', 'Valencia'),
(4, 'Carlos Ruiz', 'carlos@mail.com', '987D', '600400400', 'Calle Mayor 10', 'Madrid'),
(5, 'Laura Díaz', 'laura@mail.com', '654E', '600500500', 'Calle Norte 22', 'Sevilla'),
(6, 'Mario López', 'mario@mail.com', '321F', '600600600', 'C/ Río 12', 'Bilbao'),
(7, 'Sara Molina', 'sara@mail.com', '741G', '600700700', 'Av. Paz 8', 'Zaragoza'),
(8, 'David Vélez', 'david@mail.com', '852H', '600800800', 'C/ Gran Vía 55', 'Madrid'),
(9, 'Elena Costa', 'elena@mail.com', '963I', '600900900', 'C/ Sur 14', 'Alicante'),
(10, 'Pablo Torres', 'pablo@mail.com', '159J', '600111222', 'C/ Pueblo 9', 'Granada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id_factura` int(11) NOT NULL,
  `id_reserva` int(11) NOT NULL,
  `fecha_emision` datetime NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `impuestos` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('Pendiente','Pagada','Cancelada') DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id_factura`, `id_reserva`, `fecha_emision`, `subtotal`, `impuestos`, `total`, `estado`) VALUES
(11, 11, '2025-11-14 00:13:07', 70.00, 7.00, 77.00, 'Pagada'),
(12, 12, '2025-11-14 00:13:07', 50.00, 5.00, 55.00, 'Pendiente'),
(13, 13, '2025-11-14 00:13:07', 140.00, 14.00, 154.00, 'Pagada'),
(14, 14, '2025-11-14 00:13:07', 90.00, 9.00, 99.00, 'Pagada'),
(15, 15, '2025-11-14 00:13:07', 200.00, 20.00, 220.00, 'Pendiente'),
(16, 16, '2025-11-14 00:13:07', 120.00, 12.00, 132.00, 'Cancelada'),
(17, 17, '2025-11-14 00:13:07', 60.00, 6.00, 66.00, 'Pendiente'),
(18, 18, '2025-11-14 00:13:07', 130.00, 13.00, 143.00, 'Pagada'),
(19, 19, '2025-11-14 00:13:07', 110.00, 11.00, 121.00, 'Pagada'),
(20, 20, '2025-11-14 00:13:07', 75.00, 7.50, 82.50, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id_habitacion` int(11) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `estado` enum('Disponible','Ocupada','Mantenimiento','Limpieza') DEFAULT 'Disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id_habitacion`, `numero`, `id_categoria`, `estado`) VALUES
(1, '101', 1, 'Disponible'),
(2, '102', 1, 'Disponible'),
(3, '103', 1, 'Mantenimiento'),
(4, '201', 2, 'Disponible'),
(5, '202', 2, 'Ocupada'),
(6, '203', 2, 'Disponible'),
(7, '301', 3, 'Disponible'),
(8, '302', 3, 'Ocupada'),
(9, '303', 3, 'Limpieza'),
(10, '304', 3, 'Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `tipo_pago` varchar(50) DEFAULT NULL,
  `fecha_pago` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `id_factura`, `monto`, `tipo_pago`, `fecha_pago`) VALUES
(6, 11, 77.00, 'Tarjeta', '2025-11-14 00:19:02'),
(7, 13, 154.00, 'Tarjeta', '2025-11-14 00:19:02'),
(8, 14, 99.00, 'Efectivo', '2025-11-14 00:19:02'),
(9, 18, 143.00, 'Tarjeta', '2025-11-14 00:19:02'),
(10, 19, 121.00, 'Transferencia', '2025-11-14 00:19:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_estadia`
--

CREATE TABLE `registros_estadia` (
  `id_registro` int(11) NOT NULL,
  `id_reserva` int(11) NOT NULL,
  `fecha_checkin` datetime DEFAULT NULL,
  `fecha_checkout` datetime DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registros_estadia`
--

INSERT INTO `registros_estadia` (`id_registro`, `id_reserva`, `fecha_checkin`, `fecha_checkout`, `observaciones`) VALUES
(6, 11, '2025-11-20 14:00:00', '2025-11-22 12:00:00', 'Estancia sin incidencias'),
(7, 13, '2025-11-18 15:00:00', NULL, 'Aún en estancia'),
(8, 14, '2025-11-19 13:00:00', '2025-11-20 12:00:00', 'Cliente solicitó salida tardía'),
(9, 18, '2025-11-30 16:00:00', NULL, 'Check-in realizado sin problemas'),
(10, 19, '2025-11-16 14:30:00', '2025-11-19 11:50:00', 'Incidencia menor: ducha');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_habitacion` int(11) NOT NULL,
  `fecha_reserva` datetime NOT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_salida` date NOT NULL,
  `estado` enum('Pendiente','Confirmada','Check-in','Check-out','Cancelada') DEFAULT 'Pendiente',
  `canal_reserva` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_cliente`, `id_habitacion`, `fecha_reserva`, `fecha_entrada`, `fecha_salida`, `estado`, `canal_reserva`) VALUES
(11, 1, 1, '2025-11-14 00:01:11', '2025-11-20', '2025-11-22', 'Confirmada', 'Web'),
(12, 2, 2, '2025-11-14 00:01:11', '2025-11-21', '2025-11-23', 'Pendiente', 'Teléfono'),
(13, 3, 4, '2025-11-14 00:01:11', '2025-11-18', '2025-11-21', 'Check-in', 'Booking'),
(14, 4, 5, '2025-11-14 00:01:11', '2025-11-19', '2025-11-20', 'Check-out', 'Recepción'),
(15, 5, 6, '2025-11-14 00:01:11', '2025-11-25', '2025-11-28', 'Confirmada', 'Web'),
(16, 6, 7, '2025-11-14 00:01:11', '2025-11-15', '2025-11-17', 'Cancelada', 'Web'),
(17, 7, 8, '2025-11-14 00:01:11', '2025-11-22', '2025-11-24', 'Pendiente', 'Booking'),
(18, 8, 9, '2025-11-14 00:01:11', '2025-11-30', '2025-12-02', 'Confirmada', 'Web'),
(19, 9, 10, '2025-11-14 00:01:11', '2025-11-16', '2025-11-19', 'Check-out', 'Recepción'),
(20, 10, 1, '2025-11-14 00:01:11', '2025-11-22', '2025-11-25', 'Pendiente', 'Teléfono');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'Administrador'),
(4, 'Contabilidad'),
(3, 'Gerencia'),
(5, 'Mantenimiento'),
(2, 'Recepcionista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `nombre`, `precio_unitario`) VALUES
(1, 'Desayuno', 8.00),
(2, 'Spa', 25.00),
(3, 'Lavandería', 12.00),
(4, 'Minibar', 5.00),
(5, 'Transporte aeropuerto', 35.00),
(6, 'Gimnasio', 15.00),
(7, 'Piscina', 10.00),
(8, 'Almohadas', 5.00),
(9, 'Parking', 10.00),
(10, 'Sala de Reuniones', 100.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_reserva`
--

CREATE TABLE `servicios_reserva` (
  `id_servicio_reserva` int(11) NOT NULL,
  `id_reserva` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_rol`, `nombre_usuario`, `email`, `password_hash`, `estado`) VALUES
(1, 1, 'admin', 'admin@hotel.com', 'hash123', 'Activo'),
(2, 2, 'recep1', 'recep1@hotel.com', 'hash123', 'Activo'),
(3, 2, 'recep2', 'recep2@hotel.com', 'hash123', 'Activo'),
(4, 3, 'gerente', 'gerente@hotel.com', 'hash123', 'Activo'),
(5, 4, 'conta', 'conta@hotel.com', 'hash123', 'Activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias_habitacion`
--
ALTER TABLE `categorias_habitacion`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `documento_identidad` (`documento_identidad`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id_factura`),
  ADD UNIQUE KEY `id_reserva` (`id_reserva`);

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id_habitacion`),
  ADD UNIQUE KEY `numero` (`numero`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `registros_estadia`
--
ALTER TABLE `registros_estadia`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_reserva` (`id_reserva`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_habitacion` (`id_habitacion`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `servicios_reserva`
--
ALTER TABLE `servicios_reserva`
  ADD PRIMARY KEY (`id_servicio_reserva`),
  ADD KEY `id_reserva` (`id_reserva`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias_habitacion`
--
ALTER TABLE `categorias_habitacion`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id_habitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `registros_estadia`
--
ALTER TABLE `registros_estadia`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `servicios_reserva`
--
ALTER TABLE `servicios_reserva`
  MODIFY `id_servicio_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`);

--
-- Filtros para la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD CONSTRAINT `habitaciones_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_habitacion` (`id_categoria`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`);

--
-- Filtros para la tabla `registros_estadia`
--
ALTER TABLE `registros_estadia`
  ADD CONSTRAINT `registros_estadia_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_habitacion`) REFERENCES `habitaciones` (`id_habitacion`);

--
-- Filtros para la tabla `servicios_reserva`
--
ALTER TABLE `servicios_reserva`
  ADD CONSTRAINT `servicios_reserva_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`),
  ADD CONSTRAINT `servicios_reserva_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
