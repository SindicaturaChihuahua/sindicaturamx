CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `tpov1_user_views`@`localhost` 
    SQL SECURITY DEFINER
VIEW `vlista_facturas` AS
    SELECT 
        `tab_facturas`.`id_factura` AS `id_factura`,
        (SELECT 
                `c`.`ejercicio`
            FROM
                `cat_ejercicios` `c`
            WHERE
                (`tab_facturas`.`id_ejercicio` = `c`.`id_ejercicio`)) AS `ejercicio`,
        (SELECT 
                `e`.`numero_contrato`
            FROM
                `tab_contratos` `e`
            WHERE
                (`tab_facturas`.`id_contrato` = `e`.`id_contrato`)) AS `contrato`,
        (SELECT 
                `f`.`numero_orden_compra`
            FROM
                `tab_ordenes_compra` `f`
            WHERE
                (`tab_facturas`.`id_orden_compra` = `f`.`id_orden_compra`)) AS `orden`,
        (SELECT 
                `d`.`trimestre`
            FROM
                `cat_trimestres` `d`
            WHERE
                (`tab_facturas`.`id_trimestre` = `d`.`id_trimestre`)) AS `trimestre`,
        (SELECT 
                `d`.`nombre_razon_social`
            FROM
                `tab_proveedores` `d`
            WHERE
                (`tab_facturas`.`id_proveedor` = `d`.`id_proveedor`)) AS `proveedor`,
        `tab_facturas`.`numero_factura` AS `numero_factura`,
        `tab_facturas`.`fecha_erogacion` AS `fecha_erogacion`,
        (SELECT 
                IFNULL(SUM(`i`.`monto_desglose`), 0)
            FROM
                `tab_facturas_desglose` `i`
            WHERE
                ((`tab_facturas`.`id_factura` = `i`.`id_factura`)
                    AND (`i`.`active` = 1))) AS `monto_factura`,
        (SELECT 
                `g`.`name_active`
            FROM
                `sys_active` `g`
            WHERE
                (`tab_facturas`.`active` = `g`.`id_active`)) AS `active`
    FROM
        `tab_facturas`