CREATE DATABASE IF NOT EXISTS `telecom` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `telecom`;

CREATE TABLE `cliente` (
  `codigo` INT(11) NOT NULL AUTO_INCREMENT,
  `telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nif` VARCHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` VARCHAR(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `poblacion` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` VARCHAR(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metodo_pago` VARCHAR(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `proveedor` (
  `codigo` INT(11) NOT NULL AUTO_INCREMENT,
  `telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nif` VARCHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` VARCHAR(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `poblacion` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` VARCHAR(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deuda_existente` DECIMAL(10, 2) DEFAULT 0.00,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `productos` (
  `codigo` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_compra` DECIMAL(10, 2) NOT NULL,
  `precio_venta` DECIMAL(10, 2) NOT NULL,
  `IVA` DECIMAL(5, 2) NOT NULL,
  `codigo_proveedor` INT(11) NOT NULL,
  `codigo_almacen` INT(11) NOT NULL,
  PRIMARY KEY (`codigo`),
  FOREIGN KEY (`codigo_proveedor`) REFERENCES `proveedor`(`codigo`) ON DELETE CASCADE,
  FOREIGN KEY (`codigo_almacen`) REFERENCES `almacen`(`codigo`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `empleados` (
  `codigo` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` VARCHAR(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `facturas_compra` (
  `codigo` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `direccion` VARCHAR(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_proveedor` INT(11) NOT NULL,
  `codigo_empleado` INT(11) NOT NULL,
  PRIMARY KEY (`codigo`),
    FOREIGN KEY (`codigo_proveedor`) REFERENCES `proveedor`(`codigo`),
    FOREIGN KEY (`codigo_empleado`) REFERENCES `empleados`(`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `facturas_venta` (
  `codigo` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `direccion` VARCHAR(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_cliente` INT(11) NOT NULL,
  `codigo_empleado` INT(11) NOT NULL,
  PRIMARY KEY (`codigo`),
  FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente`(`codigo`),
  FOREIGN KEY (`codigo_empleado`) REFERENCES `empleados`(`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `almacen`(
  `codigo` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre_almacen` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubicacion` VARCHAR(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`codigo`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `usuarios` (
  `usuario` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contrasena` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_completo` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`usuario`),
  UNIQUE (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE detalles_factura_compra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_factura INT NOT NULL,
    codigo_producto INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (codigo_factura) REFERENCES facturas_compra(codigo) ON DELETE CASCADE,
    FOREIGN KEY (codigo_producto) REFERENCES productos(codigo) ON DELETE CASCADE
);

CREATE TABLE detalles_factura_venta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_factura INT NOT NULL,
    codigo_producto INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (codigo_factura) REFERENCES facturas_venta(codigo) ON DELETE CASCADE,
    FOREIGN KEY (codigo_producto) REFERENCES productos(codigo) ON DELETE CASCADE
);