--
-- Base de datos: `inai`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--


CREATE TABLE `bitacora` (
  `id_bitacora` int(11) NOT NULL,
  `usuario_bitacora` text NOT NULL,
  `seccion_bitacora` text NOT NULL,
  `accion_bitacora` text NOT NULL,
  `fecha_bitacora` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

