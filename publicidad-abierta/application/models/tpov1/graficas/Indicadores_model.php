<?php

/*
INAI 
** Inidicadores: 
** Consultas para los indicadores globales de cada vista del portal publico 
** 
 */

class Indicadores_model extends CI_Model
{

    /* Indicadores para sujetos obligados
        estos son fijos y se obtien ne base a la tabla tab_sujetos_obligados
    */
    function get_total_so_atribucion ($id_so_atribucion){
        $sqltext = "
            SELECT count(*) as total
            FROM tab_sujetos_obligados 
            WHERE id_so_atribucion = " . $id_so_atribucion . " 
            AND active = 1;
        ";

        $query = $this->db->query( $sqltext );

        $total = 0;
        foreach($query->result_array() as $row){
            $total = $row['total'];
        }

        return $total;

    }

    
    /* Inidicadores en erogaciones */

    function get_total_facturas($ejercicio)
    {
        $aux = "";
        if(!empty($ejercicio)){
            $aux = "
                , vact_ejercicios as b 
                where a.id_ejercicio = b.id_ejercicio
                   and b.ejercicio = '" . $ejercicio . "'";
        }

        $sqltext = "
            select count(*) as total
            from vact_facturas a " . $aux;

        $query = $this->db->query( $sqltext );

        $total = 0;
        foreach($query->result_array() as $row){
            $total = $row['total'];
        }

        return $total;
    }


    /* Vista Gasto por servicio */

    function get_total_gasto_servicio($ejercicio, $id_servicio)
    {
        $this->load->model('tpoadminv1/Generales_model');

        $sqltext = "
            SELECT sum( b.monto_desglose ) as gasto
            FROM vact_facturas as a, vact_facturas_desglose_v2 as b, vact_ejercicios as c
            WHERE a.id_factura = b.id_factura 
                and a.id_ejercicio = c.id_ejercicio
                and b.id_servicio_clasificacion = " . $id_servicio;

        if(!empty($ejercicio))
        {
            $sqltext .= " and c.ejercicio = '" . $ejercicio . "'";
        }

        $query = $this->db->query( $sqltext );

        $monto = 0;
        foreach($query->result_array() as $row){
            $monto = floatval($row['gasto']);
        }

        return $monto;
    }

}

?>