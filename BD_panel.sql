-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `panel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `Id` int(11) NOT NULL,
  `Denominacion` varchar(100) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='categorias de las incidencias';

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`Id`, `Denominacion`) VALUES
(1, 'Impositivas'),
(2, 'RRHH'),
(3, 'Consulta Gral.'),
(4, 'Laborales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultas`
--

CREATE TABLE `consultas` (
  `Id` int(11) NOT NULL,
  `Titulo` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `TextoConsulta` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `IdUsuarioCarga` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `FechaCarga` datetime NOT NULL,
  `IdCategoria` int(11) NOT NULL,
  `IdPrioridad` int(11) NOT NULL,
  `Respondida` tinyint(1) NOT NULL DEFAULT 0,
  `Resolucion` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `FechaResolucion` datetime DEFAULT NULL,
  `IdUsuarioResolucion` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='incidencias que se cargan en el sistema';

--
-- Volcado de datos para la tabla `consultas`
--

INSERT INTO `consultas` (`Id`, `Titulo`, `TextoConsulta`, `IdUsuarioCarga`, `FechaCarga`, `IdCategoria`, `IdPrioridad`, `Respondida`, `Resolucion`, `FechaResolucion`, `IdUsuarioResolucion`) VALUES
(1, 'Pago de sueldos', 'Necesito me informen cómo asignar los porcentuales a los pagos de sueldos. Gracias!', '7', '2021-08-01 13:44:43', 4, 3, 1, 'resolucion 1', '2021-07-11 14:21:32', '2'),
(2, 'Pago de vacaciones (urgente)', 'Consulto acerca de los cálculos y retenciones respecto al pago de vacaciones. Muchas gracias.', '7', '2021-09-03 13:48:16', 3, 1, 1, 'Por favor dirigirse a la oficina para que podamos asesorarlo de mejor manera. ', '2021-11-15 22:14:16', '2'),
(3, 'Facturacion monotributo (urgente)', 'Consulta acerca de cómo se gestiona la facturación a monotributistas tras la nueva resolución de AFIP.  Muchas Gracias!', '7', '2021-10-05 16:49:25', 3, 1, 1, 'por favor dirigirse a la oficina para una mejor atencion.', '2021-11-15 22:37:01', '2'),
(5, 'Licencia por paternidad', 'cuantos dias corresponden? Gracias.', '7', '2021-11-15 22:50:29', 4, 2, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `Id` int(11) NOT NULL,
  `Denominacion` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`Id`, `Denominacion`) VALUES
(1, 'Admin'),
(2, 'Suscriptor Basico'),
(3, 'Abogado asesor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paises`
--

CREATE TABLE `paises` (
  `Id` int(11) NOT NULL,
  `Denominacion` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `paises`
--

INSERT INTO `paises` (`Id`, `Denominacion`) VALUES
(1, 'Argentina'),
(2, 'Chile'),
(3, 'Brasil'),
(4, 'Uruguay'),
(5, 'EEUU'),
(6, 'Colombia'),
(7, 'Venezuela'),
(8, 'Nicaragua'),
(9, 'Honduras'),
(10, 'Bolivia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prioridades`
--

CREATE TABLE `prioridades` (
  `Id` int(11) NOT NULL,
  `Denominacion` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='prioridad de la incidencia';

--
-- Volcado de datos para la tabla `prioridades`
--

INSERT INTO `prioridades` (`Id`, `Denominacion`) VALUES
(1, 'Alta'),
(2, 'Media'),
(3, 'Baja');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Apellido` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Clave` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `IdNivel` int(11) NOT NULL,
  `IdPais` int(11) NOT NULL,
  `FechaCreacion` date NOT NULL,
  `Sexo` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `Imagen` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Activo` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Id`, `Nombre`, `Apellido`, `Email`, `Clave`, `IdNivel`, `IdPais`, `FechaCreacion`, `Sexo`, `Imagen`, `Activo`) VALUES
(1, 'sue', 'palacios', 'sue@gmail.com', '202cb962ac59075b964b07152d234b70', 1, 1, '2021-05-30', 'F', 'sue.png', 1),
(2, 'mario', 'gomez', 'mario@gmail.com', '202cb962ac59075b964b07152d234b70', 3, 9, '2021-05-30', 'M', NULL, 1),
(3, 'marta', 'ramirez', 'marta@gmail.com', '202cb962ac59075b964b07152d234b70', 3, 8, '2021-05-30', 'F', 'marta.png', 1),
(4, 'ariel', 'lopez', 'ariel@gmail.com', '202cb962ac59075b964b07152d234b70', 2, 5, '2021-05-30', 'M', NULL, 1),
(5, 'carlos', 'Pellegrini', 'carlos@gmail.com', '202cb962ac59075b964b07152d234b70', 2, 3, '2021-05-31', 'O', NULL, 1),
(6, 'german', 'lopez', 'german@gmail.com', '202cb962ac59075b964b07152d234b70', 2, 1, '2021-06-03', 'M', NULL, 1),
(7, 'Maximiliano', 'Gutierrez', 'maxi@gmail.com', '202cb962ac59075b964b07152d234b70', 2, 1, '2021-06-13', 'M', NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `prioridades`
--
ALTER TABLE `prioridades`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `consultas`
--
ALTER TABLE `consultas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `paises`
--
ALTER TABLE `paises`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `prioridades`
--
ALTER TABLE `prioridades`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
