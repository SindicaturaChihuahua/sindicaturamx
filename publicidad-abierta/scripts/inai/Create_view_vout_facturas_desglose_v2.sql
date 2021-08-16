CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `tpov1_user_views`@`localhost` 
    SQL SECURITY DEFINER
VIEW `vout_facturas_desglose_v2` AS
    SELECT 
        `a`.`id_factura_desglose` AS `ID Detalle Factura`,
        (SELECT 
                `b`.`numero_factura`
            FROM
                `tab_facturas` `b`
            WHERE
                (`a`.`id_factura` = `b`.`id_factura`)) AS `ID Factura`,
        (SELECT 
                `g`.`nombre_campana_tipo`
            FROM
                (`vact_campana_aviso` `b`
                JOIN `cat_campana_tipos` `g`)
            WHERE
                ((`a`.`id_campana_aviso` = `b`.`id_campana_aviso`)
                    AND (`b`.`id_campana_tipo` = `g`.`id_campana_tipo`))) AS `Campana o aviso institucional`,
        (SELECT 
                `b`.`nombre_campana_aviso`
            FROM
                `vact_campana_aviso` `b`
            WHERE
                (`a`.`id_campana_aviso` = `b`.`id_campana_aviso`)) AS `Nombre campana o aviso institucional`,
        (SELECT 
                `c`.`nombre_servicio_clasificacion`
            FROM
                `cat_servicios_clasificacion` `c`
            WHERE
                (`c`.`id_servicio_clasificacion` = `a`.`id_servicio_clasificacion`)) AS `Clasificación del servicio`,
        (SELECT 
                `d`.`nombre_servicio_categoria`
            FROM
                `cat_servicios_categorias` `d`
            WHERE
                (`d`.`id_servicio_categoria` = `a`.`id_servicio_categoria`)) AS `Categoría del servicio`,
        (SELECT 
                `e`.`nombre_servicio_subcategoria`
            FROM
                `cat_servicios_subcategorias` `e`
            WHERE
                (`e`.`id_servicio_subcategoria` = `a`.`id_servicio_subcategoria`)) AS `Subcategoría del servicio`,
        (SELECT 
                `g`.`nombre_servicio_unidad`
            FROM
                `cat_servicios_unidades` `g`
            WHERE
                (`a`.`id_servicio_unidad` = `g`.`id_servicio_unidad`)) AS `Unidad`,
        (SELECT 
                `j`.`nombre_sujeto_obligado`
            FROM
                `tab_sujetos_obligados` `j`
            WHERE
                (`j`.`id_sujeto_obligado` = `a`.`id_so_contratante`)) AS `Sujeto obligado contratante`,
        (SELECT 
                `k`.`partida`
            FROM
                `cat_presupuesto_conceptos` `k`
            WHERE
                (`k`.`id_presupesto_concepto` = `a`.`id_presupuesto_concepto`)) AS `Partida contratante`,
        (SELECT 
                `f`.`nombre_sujeto_obligado`
            FROM
                `tab_sujetos_obligados` `f`
            WHERE
                (`f`.`id_sujeto_obligado` = `a`.`id_so_solicitante`)) AS `Sujeto obligado solicitante`,
        (SELECT 
                `h`.`partida`
            FROM
                `cat_presupuesto_conceptos` `h`
            WHERE
                (`h`.`id_presupesto_concepto` = `a`.`id_presupuesto_concepto_solicitante`)) AS `Partida solicitante`,
        `a`.`area_administrativa` AS `Área administrativa solicitante`,
        `a`.`descripcion_servicios` AS `Descripción del servicio o producto adquirido`,
        `a`.`cantidad` AS `Cantidad`,
        `a`.`precio_unitarios` AS `Precio unitario con I.V.A incluido`,
        `a`.`monto_desglose` AS `Monto`,
        (SELECT 
                `j`.`name_active`
            FROM
                `sys_active` `j`
            WHERE
                (`a`.`active` = `j`.`id_active`)) AS `Estatus`
    FROM
        `vact_facturas_desglose_v2` `a`