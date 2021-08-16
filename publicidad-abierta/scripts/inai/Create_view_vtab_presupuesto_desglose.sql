CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `tpov1_user_views`@`localhost` 
    SQL SECURITY DEFINER
VIEW `vtab_presupuesto_desglose` AS
    SELECT 
        `ej`.`ejercicio` AS `ejercicio`,
        `con`.`partida` AS `partida`,
        `con`.`denominacion` AS `descripcion`,
        SUM(`des`.`monto_presupuesto`) AS `original`,
        SUM(`des`.`monto_modificacion`) AS `modificaciones`,
        (SUM(`des`.`monto_presupuesto`) + SUM(`des`.`monto_modificacion`)) AS `presupuesto`,
        (SELECT 
                SUM(`fglo`.`monto_desglose`)
            FROM
                (`tab_facturas` `fac`
                JOIN `tab_facturas_desglose` `fglo` ON ((`fac`.`id_factura` = `fglo`.`id_factura`)))
            WHERE
                ((`fac`.`active` = 1)
                    AND (`fglo`.`active` = 1)
                    AND (`fac`.`id_ejercicio` = `ej`.`id_ejercicio`)
                    AND ((`fglo`.`id_presupuesto_concepto` = `des`.`id_presupuesto_concepto`)
                    OR (`fglo`.`id_presupuesto_concepto_solicitante` = `des`.`id_presupuesto_concepto`)))) AS `ejercido`
    FROM
        (((`tab_presupuestos_desglose` `des`
        JOIN `tab_presupuestos` `pres` ON ((`pres`.`id_presupuesto` = `des`.`id_presupuesto`)))
        JOIN `cat_ejercicios` `ej` ON ((`ej`.`id_ejercicio` = `pres`.`id_ejercicio`)))
        JOIN `cat_presupuesto_conceptos` `con` ON ((`con`.`id_presupesto_concepto` = `des`.`id_presupuesto_concepto`)))
    GROUP BY `con`.`denominacion` , `ej`.`ejercicio`