CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `tpov1_user_views`@`localhost` 
    SQL SECURITY DEFINER
VIEW `vact_facturas_desglose_v2` AS
    SELECT 
        `tab_facturas_desglose`.`id_factura_desglose` AS `id_factura_desglose`,
        `tab_facturas_desglose`.`id_factura` AS `id_factura`,
        `tab_facturas_desglose`.`id_campana_aviso` AS `id_campana_aviso`,
        `tab_facturas_desglose`.`id_servicio_clasificacion` AS `id_servicio_clasificacion`,
        `tab_facturas_desglose`.`id_servicio_categoria` AS `id_servicio_categoria`,
        `tab_facturas_desglose`.`id_servicio_subcategoria` AS `id_servicio_subcategoria`,
        `tab_facturas_desglose`.`id_servicio_unidad` AS `id_servicio_unidad`,
        `tab_facturas_desglose`.`id_so_contratante` AS `id_so_contratante`,
        `tab_facturas_desglose`.`id_presupuesto_concepto` AS `id_presupuesto_concepto`,
        `tab_facturas_desglose`.`id_so_solicitante` AS `id_so_solicitante`,
        `tab_facturas_desglose`.`id_presupuesto_concepto_solicitante` AS `id_presupuesto_concepto_solicitante`,
        `tab_facturas_desglose`.`numero_partida` AS `numero_partida`,
        `tab_facturas_desglose`.`descripcion_servicios` AS `descripcion_servicios`,
        `tab_facturas_desglose`.`cantidad` AS `cantidad`,
        `tab_facturas_desglose`.`precio_unitarios` AS `precio_unitarios`,
        `tab_facturas_desglose`.`monto_desglose` AS `monto_desglose`,
        `tab_facturas_desglose`.`area_administrativa` AS `area_administrativa`,
        `tab_facturas_desglose`.`fecha_validacion` AS `fecha_validacion`,
        `tab_facturas_desglose`.`area_responsable` AS `area_responsable`,
        `tab_facturas_desglose`.`periodo` AS `periodo`,
        `tab_facturas_desglose`.`fecha_actualizacion` AS `fecha_actualizacion`,
        `tab_facturas_desglose`.`nota` AS `nota`,
        `tab_facturas_desglose`.`active` AS `active`
    FROM
        `tab_facturas_desglose`
    WHERE
        (`tab_facturas_desglose`.`active` = 1)