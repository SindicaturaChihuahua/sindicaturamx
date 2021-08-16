
--
-- Modificaciones en la tabla `tab_presupuestos`
--

ALTER TABLE tab_presupuestos ADD COLUMN `fecha_inicio_periodo` date AFTER `fecha_validacion`;
ALTER TABLE tab_presupuestos ADD COLUMN `fecha_termino_periodo` date AFTER `fecha_inicio_periodo`;
ALTER TABLE tab_presupuestos ADD COLUMN `mision` text  AFTER `nota`;
ALTER TABLE tab_presupuestos ADD COLUMN `objetivo` text  AFTER `mision`;
ALTER TABLE tab_presupuestos ADD COLUMN `metas` text  AFTER `objetivo`;
ALTER TABLE tab_presupuestos ADD COLUMN `programas` text  AFTER `metas`;
ALTER TABLE tab_presupuestos ADD COLUMN `objetivo_transversal` text  AFTER `programas`;
ALTER TABLE tab_presupuestos ADD COLUMN `conjunto_campanas` text  AFTER `objetivo_transversal`;
ALTER TABLE tab_presupuestos ADD COLUMN `temas` text  AFTER `conjunto_campanas`;
ALTER TABLE tab_presupuestos ADD COLUMN `nota_planeacion` text  AFTER `temas`;

