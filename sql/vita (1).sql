-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-08-2023 a las 03:36:19
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `vita`
--
CREATE DATABASE IF NOT EXISTS `vita` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `vita`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `validar_actualizacion_producto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `validar_actualizacion_producto` (IN `p_nombre_actual` VARCHAR(250), IN `p_nombre` VARCHAR(250), IN `p_descripcion` VARCHAR(250), IN `p_existencias` INT, IN `p_precio` DOUBLE)   BEGIN
    DECLARE v_producto INT;
    DECLARE producto_id INT;
    SET v_producto = 0;

    SELECT COUNT(*) INTO v_producto FROM producto WHERE nombre = p_nombre_actual;

    IF v_producto = 0 THEN
        SELECT 'El producto no existe.';
    ELSE
        -- Verificar si el nuevo nombre está siendo utilizado por otro producto
        SELECT COUNT(*) INTO v_producto FROM producto WHERE nombre = p_nombre AND nombre != p_nombre_actual;

        IF v_producto > 0 THEN
            SELECT 'El nuevo nombre ya está en uso por otro producto.';
        ELSE
            SELECT codigo INTO producto_id FROM producto WHERE nombre = p_nombre_actual LIMIT 1;

            -- Verificar si las existencias a actualizar son mayores a 0
            IF p_existencias <= 0 THEN
                SELECT 'Las existencias a actualizar deben ser mayores a 0.';
            ELSE
               
                -- Actualizar los datos del producto sin modificar el código
                UPDATE producto SET
                    nombre = p_nombre,
                    descripccion = p_descripcion,
                    existencias = p_existencias,
                    precio = p_precio
                WHERE codigo = producto_id;

                SELECT 'Actualización de producto exitosa.';
                -- Mostrar los registros actualizados
                SELECT * FROM producto;
                SELECT * FROM vista_ProductoDetalle;
                SELECT * FROM aviso_producto;

                
            END IF;
        END IF;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `validar_ActualizarUsuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `validar_ActualizarUsuario` (IN `p_nombre_actual` VARCHAR(250), IN `p_nombre_nuevo` VARCHAR(250), IN `p_correo` VARCHAR(250), IN `p_password` VARCHAR(8), IN `p_telefono` VARCHAR(10))   BEGIN
    DECLARE usuario_id INT;
  
    SELECT id_usuario INTO usuario_id FROM usuario WHERE correo = p_nombre_actual LIMIT 1;
    
    IF usuario_id IS NOT NULL THEN
        IF LENGTH(p_password) <= 8 THEN
            UPDATE usuario SET nombre = p_nombre_nuevo, correo = p_correo,
                password = p_password, telefono = p_telefono WHERE id_usuario = usuario_id;
            
            SELECT 'Datos actualizados correctamente.' AS mensaje;
            SELECT * FROM usuario;
            SELECT * FROM aviso_usuario;
            SELECT * FROM vista_UsuarioDetalle;
        ELSE
            SELECT 'Los datos no cumplen con las condiciones especificadas.' AS mensaje;
        END IF;
    ELSE
        SELECT 'El usuario no existe.' AS mensaje;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `validar_compra`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `validar_compra` (IN `p_id_usuario` INT, IN `p_codigo_producto` INT, IN `p_cantidad_comprada` INT)   BEGIN
    DECLARE contador_existencias INT;
    DECLARE contador_usuarios INT;
    DECLARE usuario_rol INT;
   
    SET contador_existencias = 0;
    
    -- Validar existencias del producto
    SELECT existencias INTO contador_existencias FROM producto WHERE codigo = p_codigo_producto;
    
    IF contador_existencias < p_cantidad_comprada THEN
        SELECT 'No hay suficientes existencias del producto solicitado.' AS mensaje;
    ELSE
        SET contador_usuarios = 0;
        
        -- Validar existencia del usuario y obtener su rol
        SELECT COUNT(*), id_rol INTO contador_usuarios, usuario_rol FROM usuario
        WHERE id_usuario = p_id_usuario;
        
        IF contador_usuarios = 0 THEN
            SELECT 'El usuario no existe.' AS mensaje;
        ELSE
            -- Verificar el rol del usuario
            IF usuario_rol = 3 THEN
                -- Actualizar existencias del producto
                START TRANSACTION;
                UPDATE producto SET existencias = existencias - p_cantidad_comprada WHERE codigo = p_codigo_producto;
                
                INSERT INTO compra (id_usuario, codigo, cantidad)
                VALUES (p_id_usuario, p_codigo_producto, p_cantidad_comprada);
                
                SELECT * FROM producto;
                SELECT * FROM aviso_compra;
                SELECT * FROM vista_compra_detalle;
                
                SELECT 'Compra realizada exitosamente.' AS mensaje; 
                
                -- Confirmar la transacción
                COMMIT;
            ELSE
                SELECT 'El usuario no tiene permisos para realizar compras.' AS mensaje;
            END IF;
        END IF;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `validar_EliminarProducto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `validar_EliminarProducto` (IN `p_codigo` INT, IN `p_nombre` VARCHAR(250))   BEGIN
   declare v_count int;
   set v_count = 0;
   
   select count(*)INTO v_count  FROM producto WHERE codigo = p_codigo and nombre=p_nombre;
   
   if v_count =0 then 
      select 'el producto no existe ' AS mensaje;
          
          else 
			  
			  delete from producto where codigo = p_codigo and nombre = p_nombre;
              select 'producto eliminado' AS mensaje;
              
			  select* from aviso_producto;
			  select * from  vista_ProductoDetalle;
			  SELECT * FROM aviso_producto;
      
   end if;
    
END$$

DROP PROCEDURE IF EXISTS `validar_EliminarUsuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `validar_EliminarUsuario` (IN `p_id_usuario` INT(11), IN `p_correo` VARCHAR(250))   BEGIN
    DECLARE contador_usuario INT;

    SET contador_usuario = 0;
    
    SELECT COUNT(*) INTO contador_usuario FROM usuario WHERE id_usuario = p_id_usuario AND correo = p_correo;
    
    IF contador_usuario > 0 THEN
        
        DELETE FROM usuario WHERE id_usuario = p_id_usuario AND correo = p_correo;
        
        START TRANSACTION;
        SELECT 'Usuario eliminado correctamente.' AS mensaje;
        SELECT * FROM usuario;
        select * from aviso_usuario;
    ELSE
        SELECT 'El usuario no existe.' AS mensaje;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `validar_RegistroProdcuto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `validar_RegistroProdcuto` (IN `p_tipo_producto` INT(11), IN `p_nombre` VARCHAR(250), IN `p_descripccion` VARCHAR(250), IN `p_existencias` INT(11), IN `p_precio` DOUBLE, IN `p_imagen` VARCHAR(250))   BEGIN
    DECLARE contador_producto INT;
    
    SET contador_producto = 0;
    
    SELECT COUNT(*) INTO contador_producto FROM producto WHERE nombre = p_nombre;
    
    IF contador_producto > 0 THEN
        SELECT 'El producto ya existe.' AS mensaje;
		ELSEIF p_existencias <= 0 THEN
				SELECT 'Las existencias deben ser mayores a 0.' AS mensaje;
			ELSE
        INSERT INTO producto (tipo_producto, nombre, descripccion, existencias, precio, imagen)
        VALUES (p_tipo_producto, p_nombre, p_descripccion, p_existencias, p_precio, p_imagen);
        
        SELECT 'Producto agregado correctamente.' AS mensaje;
        SELECT * FROM producto;
        select* from aviso_producto;
        SELECT * FROM vista_ProductoDetalle;
        
    END IF;
END$$

DROP PROCEDURE IF EXISTS `validar_registro_usuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `validar_registro_usuario` (IN `p_nombre` VARCHAR(250), IN `p_correo` VARCHAR(100), IN `p_password` VARCHAR(8), IN `p_telefono` VARCHAR(10), IN `p_id_rol` INT)   BEGIN
    DECLARE contador_correo INT;
    DECLARE contador_telefono INT;
	
     
    SELECT COUNT(*) INTO contador_correo FROM usuario WHERE correo = p_correo;
    SELECT COUNT(*) INTO contador_telefono FROM usuario WHERE telefono = p_telefono;
    
    IF contador_correo > 0 THEN
        SELECT 'Ya existe el usuario con ese correo.' AS mensaje;
        
    ELSEIF contador_telefono > 0 THEN
        SELECT 'Ya existe un usuario con ese teléfono.' AS mensaje;
        
    ELSE
       
        INSERT INTO usuario (nombre, correo, password, telefono, id_rol)
        VALUES (p_nombre, p_correo, p_password, p_telefono, p_id_rol);
		SELECT 'Registro exitoso.' AS mensaje;
        SELECT * FROM usuario;
        SELECT * FROM aviso_usuario;
        SELECT * FROM vista_UsuarioDetalle;
      END IF;
        
END$$

DROP PROCEDURE IF EXISTS `validar_venta`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `validar_venta` (IN `p_id_usuario` INT, IN `p_codigo_producto` INT, IN `p_cantidad_comprada` INT)   BEGIN
    DECLARE contador_existencias INT;
    DECLARE contador_usuarios INT;
    DECLARE usuario_rol INT;
   
    SET contador_existencias = 0;
    
    -- Validar existencias del producto
    SELECT existencias INTO contador_existencias FROM producto WHERE codigo = p_codigo_producto;
    
    IF contador_existencias < p_cantidad_comprada THEN
        SELECT 'No hay suficientes existencias del producto solicitado.' AS mensaje;
    ELSE
        SET contador_usuarios = 0;
        
        -- Validar existencia del usuario y obtener su rol
        SELECT COUNT(*), id_rol INTO contador_usuarios, usuario_rol FROM usuario
        WHERE id_usuario = p_id_usuario;
        
        IF contador_usuarios = 0 THEN
            SELECT 'El usuario no existe.' AS mensaje;
        ELSE
            -- Verificar el rol del usuario
            IF usuario_rol = 2 THEN
                -- Actualizar existencias del producto
                START TRANSACTION;
                UPDATE producto SET existencias = existencias - p_cantidad_comprada 
                WHERE codigo = p_codigo_producto;
                
                INSERT INTO venta (id_usuario, codigo, cantidad)
                VALUES (p_id_usuario, p_codigo_producto, p_cantidad_comprada);
                
                SELECT * FROM producto;
                SELECT * FROM aviso_venta;
                SELECT * FROM vista_venta_detalle;
                
                SELECT 'Compra realizada exitosamente.' AS mensaje; 
                
                -- Confirmar la transacción
                COMMIT;
            ELSE
                SELECT 'El usuario no tiene permisos para realizar compras.' AS mensaje;
            END IF;
        END IF;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_compra`
--

DROP TABLE IF EXISTS `aviso_compra`;
CREATE TABLE `aviso_compra` (
  `mensaje` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aviso_compra`
--

INSERT INTO `aviso_compra` (`mensaje`) VALUES
('Se ha insertado una compra | COIDGO: 3.| CORREO: 3| FECHA  : 2023-07-14 19:13:15'),
('Se ha insertado una compra | COIDGO: 3.| CORREO: 3| FECHA  : 2023-07-14 19:22:20'),
('Se ha insertado una compra | COIDGO: 3.| CORREO: 3| FECHA  : 2023-07-14 19:31:08'),
('Se ha insertado una compra | COIDGO: 3.| CORREO: 3| FECHA  : 2023-07-14 19:32:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_producto`
--

DROP TABLE IF EXISTS `aviso_producto`;
CREATE TABLE `aviso_producto` (
  `mensaje` varchar(250) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aviso_producto`
--

INSERT INTO `aviso_producto` (`mensaje`, `fecha_registro`) VALUES
('Se ha actualizado un producto,| PRODUCTO:   cafe late .| CODIGO:6| TIPO PRODUCTO: 1', '2023-08-05 00:20:02'),
('Se ha actualizado un producto,| PRODUCTO:  Cafe.| CODIGO:3| TIPO PRODUCTO: 1', '2023-08-05 00:18:55'),
('Se ha actualizado un producto,| PRODUCTO:  Chocolate de fresa .| CODIGO:5| TIPO PRODUCTO: 1', '2023-08-05 00:19:43'),
('Se ha actualizado un producto,| PRODUCTO:  Concha.| CODIGO:9| TIPO PRODUCTO: 2', '2023-08-03 00:11:48'),
('Se ha actualizado un producto,| PRODUCTO:  dona.| CODIGO:11| TIPO PRODUCTO: 2', '2023-08-05 01:03:37'),
('Se ha registrado un nuevo producto,| PRODUCTO:   Concha .| CODIGO:10| TIPO PRODUCTO: 2', '2023-08-05 00:35:00'),
('Se ha registrado un nuevo producto,| PRODUCTO:  Bizcocho.| CODIGO:12| TIPO PRODUCTO: 2', '2023-08-06 00:15:25'),
('Se ha registrado un nuevo producto,| PRODUCTO:  pan.| CODIGO:11| TIPO PRODUCTO: 2', '2023-08-05 00:39:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_usuario`
--

DROP TABLE IF EXISTS `aviso_usuario`;
CREATE TABLE `aviso_usuario` (
  `mensaje` varchar(250) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aviso_usuario`
--

INSERT INTO `aviso_usuario` (`mensaje`, `fecha_registro`) VALUES
('Se ha actualizado un usuario: fer. correo:fernando@gmail.com', '2023-08-08 00:30:48'),
('Se ha actualizado un usuario: fernando. correo:fernando@gmail.com', '2023-08-05 22:48:27'),
('Se ha actualizado un usuario: Fernando. correo:juan@gmail.com', '2023-08-08 00:27:48'),
('Se ha insertado un nuevo usuario: brandon. correo:brandon@gmail.com', '2023-08-08 00:48:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_venta`
--

DROP TABLE IF EXISTS `aviso_venta`;
CREATE TABLE `aviso_venta` (
  `mensaje` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aviso_venta`
--

INSERT INTO `aviso_venta` (`mensaje`) VALUES
('Se ha insertado una compra | COIDGO: 3.| CORREO: 2| FECHA  : 2023-07-14 20:00:07'),
('Se ha insertado una compra | COIDGO: 3.| CORREO: 2| FECHA  : 2023-07-14 20:00:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_producto`
--

DROP TABLE IF EXISTS `categoria_producto`;
CREATE TABLE `categoria_producto` (
  `id` int(11) NOT NULL,
  `tipo_producto` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria_producto`
--

INSERT INTO `categoria_producto` (`id`, `tipo_producto`) VALUES
(1, 'BEBIDA'),
(2, 'PAN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

DROP TABLE IF EXISTS `compra`;
CREATE TABLE `compra` (
  `folio` int(11) NOT NULL,
  `establecimiento` varchar(52) NOT NULL DEFAULT 'LA CONCHITA',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`folio`, `establecimiento`, `fecha`, `id_usuario`, `codigo`, `cantidad`) VALUES
(1, 'LA CONCHITA', '2023-07-15 01:12:18', 3, 3, 50),
(2, 'LA CONCHITA', '2023-07-15 01:13:15', 3, 3, 50),
(3, 'LA CONCHITA', '2023-07-15 01:22:20', 3, 3, 50),
(4, 'LA CONCHITA', '2023-07-15 01:31:08', 3, 3, 50),
(5, 'LA CONCHITA', '2023-07-15 01:32:09', 3, 3, 50);

--
-- Disparadores `compra`
--
DROP TRIGGER IF EXISTS `trigger_AvisoNuevaCompra`;
DELIMITER $$
CREATE TRIGGER `trigger_AvisoNuevaCompra` AFTER INSERT ON `compra` FOR EACH ROW BEGIN
   DECLARE mensaje VARCHAR(255);
   SET mensaje = CONCAT('Se ha insertado una compra | COIDGO: ', NEW.codigo,'.| CORREO: ',NEW.id_usuario, '| FECHA  : ', NOW());
   INSERT INTO aviso_compra (mensaje) VALUES (mensaje);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto` (
  `codigo` int(11) NOT NULL,
  `tipo_producto` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `descripccion` varchar(250) NOT NULL,
  `existencias` int(11) NOT NULL,
  `precio` double NOT NULL,
  `imagen` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`codigo`, `tipo_producto`, `nombre`, `descripccion`, `existencias`, `precio`, `imagen`) VALUES
(3, 1, 'Cafe', 'cafe soluble', 548, 120, '../../img/productos/cafe.jpeg'),
(5, 1, 'Chocolate de fresa ', 'Chocolate de fresa caliente', 700, 100, '../../img/productos/capuchino.jpeg'),
(6, 1, ' cafe late ', 'cafe late sin crema', 200, 150, '../../img/productos/chocolate.jpeg'),
(10, 2, ' Concha ', 'concha sin azucar', 200, 40, '../../img/productos/concha.jpeg'),
(11, 2, 'dona', 'dona', 40, 40, '../../img/productos/dona.jpeg'),
(12, 2, 'Bizcocho', 'Bizchoco con mantequilla ', 40, 40, '../../img/productos/bizcocho.jpeg');

--
-- Disparadores `producto`
--
DROP TRIGGER IF EXISTS `trigger_AvisoActualizarProducto`;
DELIMITER $$
CREATE TRIGGER `trigger_AvisoActualizarProducto` AFTER UPDATE ON `producto` FOR EACH ROW BEGIN
    DECLARE mensaje VARCHAR(255);
    SET mensaje = CONCAT('Se ha actualizado un producto,| PRODUCTO:  ', NEW.nombre,'.| CODIGO:',NEW.codigo,
    '| TIPO PRODUCTO: ',NEW.tipo_producto);
    INSERT INTO aviso_producto (mensaje, fecha_registro) VALUES (mensaje, NOW());
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trigger_AvisoEliminarProducto`;
DELIMITER $$
CREATE TRIGGER `trigger_AvisoEliminarProducto` AFTER DELETE ON `producto` FOR EACH ROW BEGIN
    DECLARE mensaje VARCHAR(255);
    SET mensaje = CONCAT('Se ha actualizado un producto,| PRODUCTO:  ', old.nombre,'.| CODIGO:',old.codigo,
    '| TIPO PRODUCTO: ',old.tipo_producto);
    INSERT INTO aviso_producto (mensaje, fecha_registro) VALUES (mensaje, NOW());
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trigger_AvisoNuevoPRODUCTO`;
DELIMITER $$
CREATE TRIGGER `trigger_AvisoNuevoPRODUCTO` AFTER INSERT ON `producto` FOR EACH ROW BEGIN
    DECLARE mensaje VARCHAR(255);
    SET mensaje = CONCAT('Se ha registrado un nuevo producto,| PRODUCTO:  ', NEW.nombre,'.| CODIGO:',NEW.codigo,
    '| TIPO PRODUCTO: ',NEW.tipo_producto);
    INSERT INTO aviso_producto (mensaje, fecha_registro) VALUES (mensaje, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `tipo` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `tipo`) VALUES
(1, 'Administrador'),
(2, 'Empleado'),
(3, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `correo` varchar(250) NOT NULL,
  `password` varchar(8) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `correo`, `password`, `telefono`, `id_rol`) VALUES
(1, 'leo', 'leo@gmail.com', '12345678', '3334567898', 1),
(2, 'Adan', 'adan@gmail.com', '12345678', '3345654589', 2),
(3, 'fer', 'fernando@gmail.com', '12345678', '3344344433', 3),
(4, 'Fernando', 'juan@gmail.com', '12345678', '3333333333', 3),
(5, 'brandon', 'brandon@gmail.com', '12345678', '3344343444', 3);

--
-- Disparadores `usuario`
--
DROP TRIGGER IF EXISTS `trigger_AvisoActualizarUsuario`;
DELIMITER $$
CREATE TRIGGER `trigger_AvisoActualizarUsuario` AFTER UPDATE ON `usuario` FOR EACH ROW BEGIN
    DECLARE mensaje VARCHAR(255);
    SET mensaje = CONCAT('Se ha actualizado un usuario: ', NEW.nombre,'. correo:',NEW.correo);
    INSERT INTO aviso_usuario (mensaje, fecha_registro) VALUES (mensaje, NOW());
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trigger_AvisoEliminarUsuario`;
DELIMITER $$
CREATE TRIGGER `trigger_AvisoEliminarUsuario` AFTER DELETE ON `usuario` FOR EACH ROW BEGIN
    DECLARE mensaje VARCHAR(255);
    SET mensaje = CONCAT('Se ha eliminado un suario: ', OLD.nombre,'. correo:',OLD.correo);
    INSERT INTO aviso_usuario (mensaje, fecha_registro) VALUES (mensaje, NOW());
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trigger_AvisoNuevoUsuario`;
DELIMITER $$
CREATE TRIGGER `trigger_AvisoNuevoUsuario` AFTER INSERT ON `usuario` FOR EACH ROW BEGIN
    DECLARE mensaje VARCHAR(255);
    SET mensaje = CONCAT('Se ha insertado un nuevo usuario: ', NEW.nombre,'. correo:',NEW.correo);
    INSERT INTO aviso_usuario (mensaje, fecha_registro) VALUES (mensaje, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

DROP TABLE IF EXISTS `venta`;
CREATE TABLE `venta` (
  `folio` int(11) NOT NULL,
  `establecimiento` varchar(250) NOT NULL DEFAULT 'LA CONCHITA',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`folio`, `establecimiento`, `fecha`, `id_usuario`, `codigo`, `cantidad`) VALUES
(1, 'LA CONCHITA', '2023-07-15 02:00:07', 2, 3, 1),
(2, 'LA CONCHITA', '2023-07-15 02:00:30', 2, 3, 1);

--
-- Disparadores `venta`
--
DROP TRIGGER IF EXISTS `trigger_AvisoNuevaVenta`;
DELIMITER $$
CREATE TRIGGER `trigger_AvisoNuevaVenta` AFTER INSERT ON `venta` FOR EACH ROW BEGIN
   DECLARE mensaje VARCHAR(255);
   SET mensaje = CONCAT('Se ha insertado una compra | COIDGO: ', NEW.codigo,'.| CORREO: ',NEW.id_usuario, '| FECHA  : ', NOW());
   INSERT INTO aviso_venta (mensaje) VALUES (mensaje);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_compra_detalle`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_compra_detalle`;
CREATE TABLE `vista_compra_detalle` (
`folio` int(11)
,`establecimiento` varchar(52)
,`fecha` timestamp
,`cantidad` int(11)
,`id_usuario` int(11)
,`nombre_usuario` varchar(250)
,`nombre_producto` varchar(250)
,`precio` double
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_productodetalle`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_productodetalle`;
CREATE TABLE `vista_productodetalle` (
`codigo` int(11)
,`tipo_producto` varchar(250)
,`nombre` varchar(250)
,`descripccion` varchar(250)
,`existencias` int(11)
,`precio` varchar(23)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_productodetalleimg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_productodetalleimg`;
CREATE TABLE `vista_productodetalleimg` (
`codigo` int(11)
,`tipo_producto` varchar(250)
,`nombre` varchar(250)
,`descripccion` varchar(250)
,`existencias` int(11)
,`precio` varchar(23)
,`imagen` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_usuariodetalle`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_usuariodetalle`;
CREATE TABLE `vista_usuariodetalle` (
`id_usuario` int(11)
,`nombre` varchar(250)
,`correo` varchar(250)
,`roles` varchar(250)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_venta_detalle`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_venta_detalle`;
CREATE TABLE `vista_venta_detalle` (
`folio` int(11)
,`establecimiento` varchar(250)
,`fecha` timestamp
,`cantidad` int(11)
,`id_usuario` int(11)
,`nombre_empleado` varchar(250)
,`nombre_producto` varchar(250)
,`precio` double
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_compra_detalle`
--
DROP TABLE IF EXISTS `vista_compra_detalle`;

DROP VIEW IF EXISTS `vista_compra_detalle`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_compra_detalle`  AS SELECT `c`.`folio` AS `folio`, `c`.`establecimiento` AS `establecimiento`, `c`.`fecha` AS `fecha`, `c`.`cantidad` AS `cantidad`, `u`.`id_usuario` AS `id_usuario`, `u`.`nombre` AS `nombre_usuario`, `p`.`nombre` AS `nombre_producto`, `p`.`precio` AS `precio` FROM ((`compra` `c` join `usuario` `u` on(`c`.`id_usuario` = `u`.`id_usuario`)) join `producto` `p` on(`c`.`codigo` = `p`.`codigo`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_productodetalle`
--
DROP TABLE IF EXISTS `vista_productodetalle`;

DROP VIEW IF EXISTS `vista_productodetalle`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_productodetalle`  AS SELECT `p`.`codigo` AS `codigo`, `cp`.`tipo_producto` AS `tipo_producto`, `p`.`nombre` AS `nombre`, `p`.`descripccion` AS `descripccion`, `p`.`existencias` AS `existencias`, concat('$',`p`.`precio`) AS `precio` FROM (`producto` `p` join `categoria_producto` `cp` on(`p`.`tipo_producto` = `cp`.`id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_productodetalleimg`
--
DROP TABLE IF EXISTS `vista_productodetalleimg`;

DROP VIEW IF EXISTS `vista_productodetalleimg`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_productodetalleimg`  AS SELECT `p`.`codigo` AS `codigo`, `cp`.`tipo_producto` AS `tipo_producto`, `p`.`nombre` AS `nombre`, `p`.`descripccion` AS `descripccion`, `p`.`existencias` AS `existencias`, concat('$',`p`.`precio`) AS `precio`, `p`.`imagen` AS `imagen` FROM (`producto` `p` join `categoria_producto` `cp` on(`p`.`tipo_producto` = `cp`.`id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_usuariodetalle`
--
DROP TABLE IF EXISTS `vista_usuariodetalle`;

DROP VIEW IF EXISTS `vista_usuariodetalle`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_usuariodetalle`  AS SELECT `u`.`id_usuario` AS `id_usuario`, `u`.`nombre` AS `nombre`, `u`.`correo` AS `correo`, `r`.`tipo` AS `roles` FROM (`usuario` `u` join `roles` `r` on(`u`.`id_rol` = `r`.`id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_venta_detalle`
--
DROP TABLE IF EXISTS `vista_venta_detalle`;

DROP VIEW IF EXISTS `vista_venta_detalle`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_venta_detalle`  AS SELECT `c`.`folio` AS `folio`, `c`.`establecimiento` AS `establecimiento`, `c`.`fecha` AS `fecha`, `c`.`cantidad` AS `cantidad`, `u`.`id_usuario` AS `id_usuario`, `u`.`nombre` AS `nombre_empleado`, `p`.`nombre` AS `nombre_producto`, `p`.`precio` AS `precio` FROM ((`venta` `c` join `usuario` `u` on(`c`.`id_usuario` = `u`.`id_usuario`)) join `producto` `p` on(`c`.`codigo` = `p`.`codigo`)) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aviso_compra`
--
ALTER TABLE `aviso_compra`
  ADD PRIMARY KEY (`mensaje`);

--
-- Indices de la tabla `aviso_producto`
--
ALTER TABLE `aviso_producto`
  ADD PRIMARY KEY (`mensaje`);

--
-- Indices de la tabla `aviso_usuario`
--
ALTER TABLE `aviso_usuario`
  ADD PRIMARY KEY (`mensaje`);

--
-- Indices de la tabla `aviso_venta`
--
ALTER TABLE `aviso_venta`
  ADD PRIMARY KEY (`mensaje`);

--
-- Indices de la tabla `categoria_producto`
--
ALTER TABLE `categoria_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_producto` (`tipo_producto`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`folio`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `codigo` (`codigo`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `tipo_producto` (`tipo_producto`),
  ADD KEY `INDICE_PRODUCTO` (`nombre`,`precio`,`descripccion`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`folio`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `codigo` (`codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria_producto`
--
ALTER TABLE `categoria_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `folio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `folio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `compra_ibfk_2` FOREIGN KEY (`codigo`) REFERENCES `producto` (`codigo`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`tipo_producto`) REFERENCES `categoria_producto` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`codigo`) REFERENCES `producto` (`codigo`);


--
-- Metadatos
--
USE `phpmyadmin`;

--
-- Metadatos para la tabla aviso_compra
--

--
-- Metadatos para la tabla aviso_producto
--

--
-- Metadatos para la tabla aviso_usuario
--

--
-- Metadatos para la tabla aviso_venta
--

--
-- Metadatos para la tabla categoria_producto
--

--
-- Metadatos para la tabla compra
--

--
-- Metadatos para la tabla producto
--

--
-- Metadatos para la tabla roles
--

--
-- Metadatos para la tabla usuario
--

--
-- Metadatos para la tabla venta
--

--
-- Metadatos para la tabla vista_compra_detalle
--

--
-- Metadatos para la tabla vista_productodetalle
--

--
-- Metadatos para la tabla vista_productodetalleimg
--

--
-- Metadatos para la tabla vista_usuariodetalle
--

--
-- Metadatos para la tabla vista_venta_detalle
--

--
-- Metadatos para la base de datos vita
--
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
