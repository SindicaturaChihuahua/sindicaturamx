--
-- Base de datos: `inai`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_fecha_actualizacion_manual`
--


CREATE TABLE `tab_fecha_actualizacion_manual` (
  `id_fecha_act` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_act` date NOT NULL,
  `comentario_act` text NOT NULL,
  `active` char(1) NOT NULL,
  `fecha_reg` date NOT NULL,
  PRIMARY KEY (`id_fecha_act`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `tab_fecha_actualizacion_manual` (`id_fecha_act`, `fecha_act`, `comentario_act`, `active`, `fecha_reg`) VALUES
(1, '2018-01-05', 'La fecha de actualización más actual es el 5 de Enero.', 'a', '2018-12-05');

-- --------------------------------------------------------