/*
*Se crea la columan id_presupuesto_concepto en la tabla tab_presupuesto_desglose 
* para ahi guardar la relación de las partidas que se hacen uso en las campañas de la 
*factura asociada. 
*/

ALTER TABLE `tab_facturas_desglose` ADD COLUMN `id_so_contratante` bigint(20) unsigned AFTER `id_servicio_unidad`;
ALTER TABLE `tab_facturas_desglose` ADD COLUMN `id_presupuesto_concepto` bigint(20) unsigned AFTER `id_so_contratante`;
ALTER TABLE `tab_facturas_desglose` ADD COLUMN `id_presupuesto_concepto_solicitante` bigint(20) unsigned AFTER `id_so_solicitante`;

/*
*
* Ejecutar la siguiente instrucción para permitir que la columna id_presupuesto_concepto permita
* valores nulos para las siguienntes facturas que se den de alta. 
*
*/

ALTER TABLE `tab_facturas` DROP FOREIGN KEY `FK_tab_facturas_tab_presupuestos_desglose`;

ALTER TABLE `tab_facturas` DROP FOREIGN KEY `fk_so_contratante_fac`;

ALTER TABLE `tab_facturas` MODIFY COLUMN `id_presupuesto_concepto` bigint(20) NULL;

ALTER TABLE `tab_facturas` MODIFY COLUMN `id_so_contratante` bigint(20) NULL;

/*
* 
*Se ejecuta un update a la tabla tab_facturas_desglose desde la tabla tab_facturas 
*en la cual se le asigna el id_presupuesto_concepto de factura al campo id_presupuesto_concepto 
*de tab_facturas_desglose. 
*
*/

/*
*Ejecutar la siguiente instruccion con rollback, esto para verificar que el numero de lineas afectadas correspondan al total de 
*registros que hay en tab_facturas_desglose.
* Si esto sale correcto, ejecutar la linea si el transaction y el rollback
*/

/*START TRANSACTION; */

UPDATE 
    `tab_facturas_desglose` AS `fdes`,
    `tab_facturas` AS `fac`
SET 
    `fdes`.`id_presupuesto_concepto` = `fac`.`id_presupuesto_concepto`,
    `fdes`.`id_so_contratante` = `fac`.`id_so_contratante`
WHERE 
    `fdes`.`id_factura` = `fac`.`id_factura`; 

/* ROLLBACK;*/