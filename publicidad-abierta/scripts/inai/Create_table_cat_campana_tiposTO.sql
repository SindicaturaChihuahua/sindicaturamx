--
-- Estructura de tabla para la tabla `cat_campana_tiposTO`
--

CREATE TABLE `cat_campana_tiposTO` (
  `id_campana_tipoTO` bigint(20) UNSIGNED NOT NULL,
  `nombre_campana_tipoTO` varchar(255) NOT NULL,
  `active` bigint(20) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cat_campana_tiposTO`
--

INSERT INTO `cat_campana_tiposTO` (`id_campana_tipoTO`, `nombre_campana_tipoTO`, `active`) VALUES
(1, 'Tiempo de estado', 1),
(2, 'Tiempo fiscal', 1),
(3, 'Tiempo oficial', 1);

--
-- Indices de la tabla `cat_campana_tipoTO`
--
ALTER TABLE `cat_campana_tiposTO`
  ADD PRIMARY KEY (`id_campana_tipoTO`);

ALTER TABLE `cat_campana_tiposTO`
  MODIFY `id_campana_tipoTO` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
