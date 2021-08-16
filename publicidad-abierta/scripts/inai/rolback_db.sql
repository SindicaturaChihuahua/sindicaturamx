
ALTER TABLE sec_users DROP COLUMN `rol_user`;

ALTER TABLE tab_presupuestos DROP COLUMN `mision`;
ALTER TABLE tab_presupuestos DROP COLUMN `objetivo`;
ALTER TABLE tab_presupuestos DROP COLUMN `metas`;
ALTER TABLE tab_presupuestos DROP COLUMN `programas`;
ALTER TABLE tab_presupuestos DROP COLUMN `objetivo_transversal`;
ALTER TABLE tab_presupuestos DROP COLUMN `conjunto_campanas`;
ALTER TABLE tab_presupuestos DROP COLUMN `temas`;

DROP TABLE `bitacora`;
DROP TABLE `cat_roles_administracion`;