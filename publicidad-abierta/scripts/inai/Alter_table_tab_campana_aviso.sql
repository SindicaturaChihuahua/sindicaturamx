
--
-- Modificaciones en la tabla `tab_campana_aviso`
--

ALTER TABLE tab_campana_aviso ADD COLUMN `fecha_inicio_periodo` date AFTER `objetivo_comunicacion`;
ALTER TABLE tab_campana_aviso ADD COLUMN `fecha_termino_periodo` date AFTER `fecha_inicio_periodo`;
ALTER TABLE tab_campana_aviso ADD COLUMN `id_campana_tipoTO` bigint(20) UNSIGNED NOT NULL AFTER `id_tiempo_oficial`;
ALTER TABLE tab_campana_aviso ADD COLUMN `monto_tiempo` varchar(50) AFTER `fecha_termino_periodo`;
ALTER TABLE tab_campana_aviso ADD COLUMN `hora_to` varchar(50) AFTER `monto_tiempo`;
ALTER TABLE tab_campana_aviso ADD COLUMN `minutos_to` varchar(50) AFTER `hora_to`;
ALTER TABLE tab_campana_aviso ADD COLUMN `segundos_to` varchar(50) AFTER `minutos_to`;
ALTER TABLE tab_campana_aviso ADD COLUMN `mensajeTO` text AFTER `segundos_to`;

