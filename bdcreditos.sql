-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2015 a las 00:48:55
-- Versión del servidor: 5.6.26
-- Versión de PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdcreditos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `IDCLIENTE` int(10) NOT NULL,
  `NOMBRECOMPLETO` varchar(50) NOT NULL,
  `DPI` varchar(13) NOT NULL,
  `TELEFONO` varchar(8) DEFAULT NULL,
  `DIRECCION` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=284 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `creditos`
--

CREATE TABLE IF NOT EXISTS `creditos` (
  `IDCREDITO` int(10) NOT NULL,
  `IDCLIENTE` int(50) DEFAULT NULL,
  `IDUSUARIO` int(50) DEFAULT NULL,
  `COBRADOR` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `FECHAINICIO` date DEFAULT NULL,
  `FECHAFIN` date DEFAULT NULL,
  `IDPLAN` int(10) DEFAULT NULL,
  `IDMONTO` int(10) DEFAULT NULL,
  `montointeres` float NOT NULL,
  `SALDOCAPITAL` float DEFAULT NULL,
  `SALDOINTERES` float DEFAULT NULL,
  `CUOTADIARIA` float DEFAULT NULL,
  `estadoanular` tinyint(4) NOT NULL,
  `ESTADO` tinyint(1) DEFAULT NULL,
  `capitalinvertido` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=374 DEFAULT CHARSET=latin1;


--
-- Estructura de tabla para la tabla `detallecreditos`
--

CREATE TABLE IF NOT EXISTS `detallecreditos` (
  `IDCREDITO` int(10) DEFAULT NULL,
  `FECHA` date DEFAULT NULL,
  `FECHAPAGO` date NOT NULL,
  `ABONOCAPITAL` float DEFAULT NULL,
  `ABONOINTERES` float DEFAULT NULL,
  `SALDOCAPITAL` float DEFAULT NULL,
  `SALDOINTERES` float DEFAULT NULL,
  `estado` tinyint(1) NOT NULL,
  `estadorenovacion` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `montos`
--

CREATE TABLE IF NOT EXISTS `montos` (
  `IDMONTO` int(10) NOT NULL,
  `MONTOCAPITAL` double DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes`
--

CREATE TABLE IF NOT EXISTS `planes` (
  `IDPLAN` int(10) NOT NULL,
  `NOMBREPLAN` varchar(50) DEFAULT NULL,
  `DIAS` int(10) DEFAULT NULL,
  `PORCENTAJEINTERES` int(10) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `planes`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `renovaciones`
--

CREATE TABLE IF NOT EXISTS `renovaciones` (
  `idrenovacion` int(10) NOT NULL,
  `idcredito` int(10) NOT NULL,
  `saldoanterior` float NOT NULL,
  `fecharenovacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipousuarios`
--

CREATE TABLE IF NOT EXISTS `tipousuarios` (
  `IDTIPOUSUARIO` int(10) NOT NULL,
  `NOMBRE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipousuarios`
--

INSERT INTO `tipousuarios` (`IDTIPOUSUARIO`, `NOMBRE`) VALUES
(1, 'Administrador'),
(2, 'Secretaria'),
(3, 'Cobrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `IDUSUARIO` int(10) NOT NULL,
  `NOMBRE` varchar(50) DEFAULT NULL,
  `NOMBREUSUARIO` varchar(50) DEFAULT NULL,
  `PASSWORD` varchar(50) DEFAULT NULL,
  `ESTADO` tinyint(1) DEFAULT NULL,
  `IDTIPOUSUARIO` int(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IDUSUARIO`, `NOMBRE`, `NOMBREUSUARIO`, `PASSWORD`, `ESTADO`, `IDTIPOUSUARIO`) VALUES
(1, 'Administrador', 'admin', 'admin', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`IDCLIENTE`);

--
-- Indices de la tabla `creditos`
--
ALTER TABLE `creditos`
  ADD PRIMARY KEY (`IDCREDITO`),
  ADD KEY `FK_CREDITOS_REFERENCE_CLIENTES` (`IDCLIENTE`),
  ADD KEY `FK_CREDITOS_REFERENCE_USUARIO` (`IDUSUARIO`),
  ADD KEY `FK_CREDITOS_REFERENCE_PLAN` (`IDPLAN`),
  ADD KEY `FK_CREDITOS_REFERENCE_MONTO` (`IDMONTO`);

--
-- Indices de la tabla `detallecreditos`
--
ALTER TABLE `detallecreditos`
  ADD KEY `FK_DETALLECREDITOS_REFERENCE_CREDITOS` (`IDCREDITO`);

--
-- Indices de la tabla `montos`
--
ALTER TABLE `montos`
  ADD PRIMARY KEY (`IDMONTO`);

--
-- Indices de la tabla `planes`
--
ALTER TABLE `planes`
  ADD PRIMARY KEY (`IDPLAN`);

--
-- Indices de la tabla `renovaciones`
--
ALTER TABLE `renovaciones`
  ADD PRIMARY KEY (`idrenovacion`);

--
-- Indices de la tabla `tipousuarios`
--
ALTER TABLE `tipousuarios`
  ADD PRIMARY KEY (`IDTIPOUSUARIO`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IDUSUARIO`),
  ADD KEY `FK_USUARIOS_REFERENCE_TIPOUSUARIOS` (`IDTIPOUSUARIO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `IDCLIENTE` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=284;
--
-- AUTO_INCREMENT de la tabla `creditos`
--
ALTER TABLE `creditos`
  MODIFY `IDCREDITO` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=374;
--
-- AUTO_INCREMENT de la tabla `montos`
--
ALTER TABLE `montos`
  MODIFY `IDMONTO` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `planes`
--
ALTER TABLE `planes`
  MODIFY `IDPLAN` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `renovaciones`
--
ALTER TABLE `renovaciones`
  MODIFY `idrenovacion` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tipousuarios`
--
ALTER TABLE `tipousuarios`
  MODIFY `IDTIPOUSUARIO` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IDUSUARIO` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `creditos`
--
ALTER TABLE `creditos`
  ADD CONSTRAINT `FK_CREDITOS_REFERENCE_CLIENTES` FOREIGN KEY (`IDCLIENTE`) REFERENCES `clientes` (`IDCLIENTE`),
  ADD CONSTRAINT `FK_CREDITOS_REFERENCE_MONTO` FOREIGN KEY (`IDMONTO`) REFERENCES `montos` (`IDMONTO`),
  ADD CONSTRAINT `FK_CREDITOS_REFERENCE_PLAN` FOREIGN KEY (`IDPLAN`) REFERENCES `planes` (`IDPLAN`),
  ADD CONSTRAINT `FK_CREDITOS_REFERENCE_USUARIO` FOREIGN KEY (`IDUSUARIO`) REFERENCES `usuarios` (`IDUSUARIO`);

--
-- Filtros para la tabla `detallecreditos`
--
ALTER TABLE `detallecreditos`
  ADD CONSTRAINT `FK_DETALLECREDITOS_REFERENCE_CREDITOS` FOREIGN KEY (`IDCREDITO`) REFERENCES `creditos` (`IDCREDITO`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `FK_USUARIOS_REFERENCE_TIPOUSUARIOS` FOREIGN KEY (`IDTIPOUSUARIO`) REFERENCES `tipousuarios` (`IDTIPOUSUARIO`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
