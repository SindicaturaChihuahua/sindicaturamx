
/* Se agregan campos para fuentes presupuestarias */
ALTER TABLE tab_presupuestos_desglose ADD COLUMN `fuente_federal` text AFTER `id_presupuesto_concepto`;
ALTER TABLE tab_presupuestos_desglose ADD COLUMN `monto_fuente_federal` decimal(15,2) DEFAULT 0.00  AFTER `fuente_federal`;
ALTER TABLE tab_presupuestos_desglose ADD COLUMN `fuente_local` text AFTER `monto_fuente_federal`;
ALTER TABLE tab_presupuestos_desglose ADD COLUMN `monto_fuente_local` decimal(15,2) DEFAULT 0.00  AFTER `fuente_local`;