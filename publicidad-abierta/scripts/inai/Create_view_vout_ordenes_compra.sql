-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Servidor: db768805800.hosting-data.io
-- Tiempo de generación: 09-08-2019 a las 03:29:41
-- Versión del servidor: 5.5.60-0+deb7u1-log
-- Versión de PHP: 7.0.33-0+deb9u3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db768805800`
--

-- --------------------------------------------------------

--
-- Estructura para la vista `vout_ordenes_compra`
--
DROP view vout_ordenes_compra;


CREATE ALGORITHM=UNDEFINED DEFINER=`dbo771185753`@`%` SQL SECURITY DEFINER VIEW `vout_ordenes_compra`  AS  select `a`.`numero_orden_compra` AS `ID_Orden_de_compra)`,
(select `b`.`nombre_razon_social` from `tab_proveedores` `b` where (`a`.`id_proveedor` = `b`.`id_proveedor`)) AS `Proveedor`,
(select `c`.`nombre_procedimiento` from `cat_procedimientos` `c` where (`a`.`id_procedimiento` = `c`.`id_procedimiento`)) AS `Procedimiento`,
(select `d`.`numero_contrato` from `tab_contratos` `d` where (`a`.`id_contrato` = `d`.`id_contrato`)) AS `Contrato`,
(select `e`.`ejercicio` from `cat_ejercicios` `e` where (`a`.`id_ejercicio` = `e`.`id_ejercicio`)) AS `Ejercicio`,
(select `f`.`trimestre` from `cat_trimestres` `f` where (`a`.`id_trimestre` = `f`.`id_trimestre`)) AS `Trimestre`,
(select `g`.`nombre_sujeto_obligado` from `tab_sujetos_obligados` `g` where (`g`.`id_sujeto_obligado` = `a`.`id_so_contratante`)) AS `Sujeto_obligado_ordenante`,
(select `l`.`nombre_campana_aviso` from (`vact_campana_aviso` `k` join `tab_campana_aviso` `l`) where ((`a`.`id_campana_aviso` = `k`.`id_campana_aviso`) and 
(`k`.`nombre_campana_aviso` = `l`.`nombre_campana_aviso`))) AS `Campana o aviso institucional`,
(select `i`.`nombre_sujeto_obligado` from `tab_sujetos_obligados` `i` where (`i`.`id_sujeto_obligado` = `a`.`id_so_solicitante`)) AS `Sujeto obligado solicitante`,
`a`.`numero_orden_compra` AS `numero_orden_de_compra`,`a`.`descripcion_justificacion` AS `Justificación`,`a`.`fecha_orden` AS `Fecha_de_orden`,
`a`.`file_orden` AS `Archivo_de_la_orden_de_compra_en_PDF_(Vínculo_al_documento)`,
(select `j`.`name_active` from `sys_active` `j` where (`a`.`active` = `j`.`id_active`)) AS `Estatus` from `vact_ordenes_compra` `a` where (`a`.`id_orden_compra` > 1) ;

--
-- VIEW  `vout_ordenes_compra`
-- Datos: Ninguna
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
