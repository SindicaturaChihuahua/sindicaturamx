<?php

/*
INAI / Graficas
 */

class Graficas_model extends CI_Model
{
    /*  funciones para campanas avisos  */

    function getCampanasDetalles($ejercicio)
    {

        $row_list = [];

        /*
        SELECT count( * ) as valor1 
            FROM vact_campana_aviso as a 
            WHERE a.id_campana_tipo = 1
        */


        //Avisos
        /*$sqltext = "
            SELECT count(a.nombre_campana_aviso) as valor1
            from vact_campana_aviso as a, vact_facturas_desglose as b, vact_facturas as c
            where b.id_factura = c.id_factura 
            and b.id_campana_aviso = a.id_campana_aviso  
            and a.id_campana_tipo in ('1')
        ";*/

        $sqltext = "
            SELECT count( * ) as valor1 
            FROM vact_campana_aviso as a
            WHERE a.id_campana_tipo = 1
        ";

        if(!empty($ejercicio)){
            $sqltext .= " and a.id_ejercicio = '" . $ejercicio . "'";
        }

        //$sqltext .= " group by a.nombre_campana_aviso, a.id_campana_aviso";

        $query = $this->db->query( $sqltext );

        if($query->num_rows() > 0)
        {
            //$avisos_contador = $query->num_rows;
            
            foreach ($query->result_array() as $row) 
            {
                $avisos_contador = $row['valor1'];
            }            
        }
        else
        {
            $avisos_contador = 0;
        }


        /*
        SELECT count( * ) as valor1 
            FROM vact_campana_aviso as a 
            WHERE a.id_campana_tipo = 2
        */


        //Campanas
        /*$sqltext2 = "
            SELECT count(a.nombre_campana_aviso) as valor1
            from vact_campana_aviso as a, vact_facturas_desglose as b, vact_facturas as c
            where b.id_factura = c.id_factura 
            and b.id_campana_aviso = a.id_campana_aviso  
            and a.id_campana_tipo in ('2') 
        ";*/

        $sqltext2 = "
            SELECT count( * ) as valor1 
            FROM vact_campana_aviso as a 
            WHERE a.id_campana_tipo = 2
        ";

        if(!empty($ejercicio)){
            $sqltext2 .= " and a.id_ejercicio = '" . $ejercicio . "'";
        }

        //$sqltext2 .= " group by a.nombre_campana_aviso, a.id_campana_aviso";

        $query = $this->db->query( $sqltext2 );
        if($query->num_rows() > 0)
        {
            //$campanas_contador = $query->num_rows;
            foreach ($query->result_array() as $row) 
            {
                $campanas_contador = $row['valor1'];
            }
        }
        else
        {
            $campanas_contador = 0;
        }


        // indx3 Monto Gastado ($) en miles  
        if(!empty($ejercicio)){
            $sqltext3 = "select sum(`a`.`monto_desglose`) as valor3
                        from vact_facturas_desglose_v2 a, 
                        vact_facturas b
                        where a.id_factura = b.id_factura and
                        a.active = 1 and
                        b.id_ejercicio = '$ejercicio'"; 
         } else {
            $sqltext3 = "select sum(`a`.`monto_desglose`) as valor3
                         from vact_facturas_desglose_v2 a, 
                              vact_facturas b
                        where a.id_factura = b.id_factura and
                              a.active = 1 and
                              b.active = 1";
        }

        $query = $this->db->query( $sqltext3 );

        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                if($row['valor3'] != '')
                {
                    $monto_total_ejercido = $row['valor3'];
                }
                else
                {
                    $monto_total_ejercido = '0';
                }
            }
        }

        $row_list = array(
            'avisos' => $avisos_contador,
            'campanas' => $campanas_contador,
            'monto_total_ejercido' => $monto_total_ejercido
        );

        return $row_list;
    }


    //Obtenemos la informacion de las campanas y avisos

    function jsonCA_SO( $ejercicio )
    {
        $ambas   = $this->leeTotalPesosCampanas( "'1','2'", $ejercicio );
        $campana = $this->leeTotalPesosCampanas( "2", $ejercicio );
        $avisos  = $this->leeTotalPesosCampanas( "1", $ejercicio );
        
        if($ambas[2] != '0')
        {
            $Json = '{"name": "Campañas y avisos institucionales ' .  $ambas[0] . '",
                "size": ' . $ambas[1] . ',
                "children": [';
                    if ($campana[1]>0)
                    {
                        $Json = $Json . '
                            {"name": "Campañas ' .  $campana[0] . '", "size": ' . $campana[1]. ', 
                                "children": [ ' . $this->leeChildrenPesosCampanas( "2", $ejercicio ) . '
                                            ]
                            }';     
                    }
                    else{
                        $Json = $Json . '
                            {
                                "name": "Campañas $ 0 ( 0 )", "size": 0,
                                "children": [0]
                            }';
                    }
                        
                    if ($avisos[1]>0)
                    {
                        $Json = $Json . ',
                            {
                                "name": "Avisos institucionales ' . $avisos[0] . '", "size": ' . $avisos[1] . ', 
                                "children": [ ' . $this->leeChildrenPesosCampanas( "1", $ejercicio ) . '
                                            ]
                            }';
                    }
                    else
                    {
                        $Json = $Json . ',
                            {
                                "name": "Avisos institucionales $ 0 ( 0 )", "size": 0, 
                                "children": [0]
                            }';
                    }

            $Json = $Json . ']}';
            return $Json;
        }
    }

    
    function leeTotalPesosCampanas( $filtro, $ejercicio ) {
        $sqltext = "
                    select sum(b.monto_desglose) as total from 
                    vact_campana_aviso as a,
                    vact_facturas_desglose_v2 as b,
                    vact_facturas as c
                    where
                    b.id_factura = c.id_factura and
                    b.id_campana_aviso = a.id_campana_aviso and
                    a.id_campana_tipo in (" . $filtro . ")
                ";
        
        if ($ejercicio != '') {
            $sqltext = $sqltext . " and c.id_ejercicio = '$ejercicio'";
        }
  
        $rtotales = $this->db->query( $sqltext )->result();
        foreach ($rtotales as $totales) {
           $total = $totales->total;
        }
        
        $sqltext1 = "
                SELECT count(*) as cuantos
                from vact_campana_aviso as a, vact_facturas_desglose_v2 as b, vact_facturas as c 
                where b.id_factura = c.id_factura 
                and b.id_campana_aviso = a.id_campana_aviso 
                and a.id_campana_tipo in ($filtro)
                ";

        if ($ejercicio != '') {
           $sqltext1 .= " and c.id_ejercicio = '$ejercicio'";
        }

        $sqltext1 .= " group by a.nombre_campana_aviso, a.id_campana_aviso";
        
        $query = $this->db->query( $sqltext1 );

        if($query->num_rows() > 0)
        {
            $cuantos = $query->num_rows;
        }
        else
        {
            $cuantos = 0;
        }

        $regresa = array("$ " . number_format($total, 0, ".", "," ) . ' ( ' . $cuantos . ' )', $total, $cuantos );
        return $regresa;
    }


    function leeChildrenPesosCampanas( $filtro, $ejercicio )
    {
        if ($ejercicio == '')
        {
           $sqltext = "
                        select a.nombre_campana_aviso as nombre, sum(b.monto_desglose) as total, a.id_campana_aviso as id
                        from vact_campana_aviso as a, vact_facturas_desglose_v2 as b, vact_facturas as c
                        where b.id_factura = c.id_factura
                        and b.id_campana_aviso = a.id_campana_aviso 
                        and a.id_campana_tipo in (" . $filtro . ") group by a.nombre_campana_aviso, a.id_campana_aviso
                    ";
        }
        else
        {
            $sqltext = "
                        select a.nombre_campana_aviso as nombre, sum(b.monto_desglose) as total, a.id_campana_aviso as id
                        from vact_campana_aviso as a, vact_facturas_desglose_v2 as b, vact_facturas as c
                        where b.id_factura = c.id_factura and b.id_campana_aviso = a.id_campana_aviso
                        and c.id_ejercicio = '$ejercicio'
                        and a.id_campana_tipo in (" . $filtro . ") group by a.nombre_campana_aviso, a.id_campana_aviso
                    ";
        }

        
        $query = $this->db->query( $sqltext );
        
        $rtotales = $this->db->query( $sqltext )->result();

        if($rtotales != '')
        {
            $i = 0;
            $childrens = '';
            foreach ($rtotales as $totales) {
                $i = $i + 1;
            }
            $j = 0;
            foreach ($rtotales as $totales) {
                $childrens = $childrens . '{"name": "' . $totales->nombre . ' - $ '. number_format($totales->total, 0, ".", "," ).
                                '", "size": ' .  $totales->total .  ', "id": ' . $totales->id . ', "url": "' . base_url() . 'index.php/tpov1/campana_aviso/campana_detalle/' . $totales->id . '" ' . 
                            '}';
                $j = $j + 1;
                if ($j < $i) {
                    $childrens = $childrens . ',';
                }
            }
        }
        else
        {
            $childrens = '';
        }

        return $childrens;
    }


    
    /* Tabla desglose campanas */
    function get_desglose_campanas_avisos($ejercicio)
    {
        //where ejercicio = '2015' and id_campana_tipo = '1'

        $sqltext = "
        SELECT *
        FROM vcampanasyavisos
        WHERE id_campana_tipo = '2'
        ";

        if(!empty($ejercicio))
        {
            $sqltext .= " and ejercicio = '" . $ejercicio . "'"; 
        }

        $query = $this->db->query( $sqltext );
        
        $array_items = [];
        
        if($query->num_rows() > 0)
        {
            $this->load->model('tpoadminv1/Generales_model');

            $cont = 0;

            foreach ($query->result_array() as $row) 
            {
                $array_items[$cont]['id'] = $cont + 1;
                $array_items[$cont]['id_campana_aviso'] = $row['id_campana_aviso'];
                $array_items[$cont]['ejercicio'] = $row['ejercicio'];
                $array_items[$cont]['trimestre'] = $row['trimestre'];
                $array_items[$cont]['nombre_campana_tipo'] = $row['nombre_campana_tipo'];
                $array_items[$cont]['nombre_campana_aviso'] = $row['nombre_campana_aviso'];
                $array_items[$cont]['contratante'] = $row['contratante'];
                $array_items[$cont]['solicitante'] = $row['solicitante'];
                $array_items[$cont]['nombre_tipo_tiempo'] = $row['nombre_tipo_tiempo'];
                $array_items[$cont]['monto_total'] = $this->Generales_model->money_format("%.2n",$row['monto_total']);
                $cont++;
            }
        }
        return $array_items;
    }


    /* Tabla Avisos institucionales */
    function get_desglose_avisos($ejercicio)
    {
        $sqltext = "
                    SELECT *
                    FROM vcampanasyavisos
                    WHERE id_campana_tipo = '1'
                    ";

        if(!empty($ejercicio))
        {
            $sqltext .= " and ejercicio = '" . $ejercicio . "'"; 
        }

        $query = $this->db->query( $sqltext );

        $array_items = [];
        $sumatoria_monto = 0; 
        if($query->num_rows() > 0)
        {
            $this->load->model('tpoadminv1/Generales_model');

            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                $array_items[$cont]['id'] = $cont + 1;
                $array_items[$cont]['id_campana_aviso'] = $row['id_campana_aviso'];
                $array_items[$cont]['ejercicio'] = $row['ejercicio'];
                $array_items[$cont]['trimestre'] = $row['trimestre'];
                $array_items[$cont]['nombre_campana_tipo'] = $row['nombre_campana_tipo'];
                $array_items[$cont]['nombre_campana_aviso'] = $row['nombre_campana_aviso'];
                $array_items[$cont]['contratante'] = $row['contratante'];
                $array_items[$cont]['solicitante'] = $row['solicitante'];
                $array_items[$cont]['nombre_tipo_tiempo'] = $row['nombre_tipo_tiempo'];
                $array_items[$cont]['monto_total'] = $this->Generales_model->money_format("%.2n",$row['monto_total']);
                $array_items[$cont]['sumatoria_monto'] = $sumatoria_monto + $row['monto_total'];

                $cont++;
            }

            //$array_items['sumatoria_monto'][] = $this->Generales_model->money_format("%.2n",$row['monto_total']);
        }
        
        return $array_items;
    }


    /* Desglose detalle campana */
    function get_desglose_detalle_campanas_avisos($id_campana_aviso)
    {
        $sqltext = "
        SELECT *
        FROM vcampanasyavisos
        WHERE id_campana_aviso = '$id_campana_aviso'
        ";

        $array_items = [];

        $query = $this->db->query( $sqltext );
        if($query->num_rows() > 0)
        {
            $this->load->model('tpoadminv1/Generales_model');

            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                $monto_total = $this->Generales_model->money_format("%.2n",$row['monto_total']);
            }
        }
        else
        {
            $monto_total = '';
        }
        return $monto_total;
    }


    function get_total_campanas_avisos($ejercicio)
    {
        $sqltext = "
            SELECT SUM(monto_total) as total
            FROM vcampanasyavisos
            WHERE id_campana_tipo = '1'
            ";

        if(!empty($ejercicio))
        {
            $sqltext .= " and ejercicio = '" . $ejercicio . "'"; 
        }

        $query = $this->db->query( $sqltext );

        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $total = $this->Generales_model->money_format("%.2n",$row['total']);
            }

            return $total;
        }
    }



    function dame_serv_dif_campana_id($id_campana_aviso)
    {
        /*
        select * 
        from vgasto_clasf_servicio
        where ejercicio = '2015'
        and id_servicio_clasificacion = '2'
        and id_campana_aviso = '2'
        */


        $sqltext = "
                    SELECT *
                    FROM vgasto_clasf_servicio
                    where id_servicio_clasificacion = '1'
                    ";
        
        /*
        if(!empty($ejercicio))
        {
            $sqltext .= " and ejercicio = '" . $ejercicio . "'"; 
        }
        */

        $sqltext .= " and id_campana_aviso = '" . $id_campana_aviso . "'"; 

        $query = $this->db->query( $sqltext );

        if($query->num_rows() > 0)
        {
            $this->load->model('tpoadminv1/Generales_model');

            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                $array_items[$cont]['id_servicio_clasificacion'] = $row['id_servicio_clasificacion'];
                $array_items[$cont]['id_contrato'] = $row['id_contrato'];
                $array_items[$cont]['id_orden_compra'] = $row['id_contrato'];
                $array_items[$cont]['id_proveedor'] = $row['id_proveedor'];
                $array_items[$cont]['id_campana_aviso'] = $row['id_campana_aviso'];
                $array_items[$cont]['id_factura'] = $row['id_factura'];
                $array_items[$cont]['ejercicio'] = $row['ejercicio'];
                $array_items[$cont]['factura'] = $row['factura'];
                $array_items[$cont]['fecha_erogacion'] = $row['fecha_erogacion'];
                $array_items[$cont]['proveedor'] = $row['proveedor'];
                $array_items[$cont]['nombre_servicio_clasificacion'] = $row['nombre_servicio_clasificacion'];
                $array_items[$cont]['nombre_servicio_categoria'] = $row['nombre_servicio_categoria'];
                $array_items[$cont]['nombre_servicio_subcategoria'] = $row['nombre_servicio_subcategoria'];
                $array_items[$cont]['tipo'] = $row['tipo'];
                $array_items[$cont]['nombre_campana_aviso'] = $row['nombre_campana_aviso'];
                $array_items[$cont]['monto_servicio'] = $this->Generales_model->money_format("%.2n",$row['monto_servicio']);
                $cont++;
            }

            return $array_items;
        }
    }


    function dame_otros_serv_dif_campana_id($id_campana_aviso)
    {
        /*
        select * 
        from vgasto_clasf_servicio
        where ejercicio = '2015'
        and id_servicio_clasificacion = '2'
        and id_campana_aviso = '2'
        */


        $sqltext = "
                    SELECT *
                    FROM vgasto_clasf_servicio
                    where id_servicio_clasificacion = '2'
                    ";
        
        /*
        if(!empty($ejercicio))
        {
            $sqltext .= " and ejercicio = '" . $ejercicio . "'"; 
        }
        */

        $sqltext .= " and id_campana_aviso = '" . $id_campana_aviso . "'"; 

        $query = $this->db->query( $sqltext );

        if($query->num_rows() > 0)
        {
            $this->load->model('tpoadminv1/Generales_model');

            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                $array_items[$cont]['id_servicio_clasificacion'] = $row['id_servicio_clasificacion'];
                $array_items[$cont]['id_contrato'] = $row['id_contrato'];
                $array_items[$cont]['id_orden_compra'] = $row['id_contrato'];
                $array_items[$cont]['id_proveedor'] = $row['id_proveedor'];
                $array_items[$cont]['id_campana_aviso'] = $row['id_campana_aviso'];
                $array_items[$cont]['id_factura'] = $row['id_factura'];
                $array_items[$cont]['ejercicio'] = $row['ejercicio'];
                $array_items[$cont]['factura'] = $row['factura'];
                $array_items[$cont]['fecha_erogacion'] = $row['fecha_erogacion'];
                $array_items[$cont]['proveedor'] = $row['proveedor'];
                $array_items[$cont]['nombre_servicio_clasificacion'] = $row['nombre_servicio_clasificacion'];
                $array_items[$cont]['nombre_servicio_categoria'] = $row['nombre_servicio_categoria'];
                $array_items[$cont]['nombre_servicio_subcategoria'] = $row['nombre_servicio_subcategoria'];
                $array_items[$cont]['tipo'] = $row['tipo'];
                $array_items[$cont]['nombre_campana_aviso'] = $row['nombre_campana_aviso'];
                $array_items[$cont]['monto_servicio'] = $this->Generales_model->money_format("%.2n",$row['monto_servicio']);
                $cont++;
            }

            return $array_items;
        }
    }

    /* fin funciones campanas avisos */











    /* Sesión de Gasto por servicios */
    function get_gasto_por_servicio_conjunto($ejercicio)
    {
        if(empty($ejercicio)){
            $sqltext = "
                select 
                    id_mes, 
                    mes_corto, 
                    nombre_servicio_categoria, 
                    TRUNCATE(sum(monto),0) as monto
                from vmeses_porservicio 
                group by id_mes, mes_corto, nombre_servicio_categoria 
                order by id_mes, nombre_servicio_categoria
            ";
        }else{
            $sqltext = "
                select 
                    id_mes, 
                    mes_corto, 
                    nombre_servicio_categoria, 
                    TRUNCATE(sum(monto),0) as monto
                from vmeses_porservicio 
                where ejercicio = '" . $ejercicio . "'
                group by id_mes, mes_corto, nombre_servicio_categoria 
                order by id_mes, nombre_servicio_categoria
            ";
        }

        $query = $this->db->query( $sqltext );
        $meses = array('ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC');
        $gasto = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $categorias = [];
        /*$meses = array('ENE' => 0, 'FEB' => 0, 
            'MAR' => 0, 'ABR' => 0,
            'MAY' => 0, 'JUN' => 0, 
            'JUL' => 0, 'AGO' => 0, 
            'SEP' => 0, 'OCT' => 0, 
            'NOV' => 0, 'DIC' => 0);*/

        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row){
                $offset = array_search($row['mes_corto'], $meses);
                $gasto[$offset] += floatval($row['monto']); 

                $key = $row['nombre_servicio_categoria'];
                if(array_key_exists($key, $categorias)){
                    //array_push($categorias, $row['nombre_servicio_categoria']);
                    $categorias[$key] = floatval($categorias[$key]) + floatval($row['monto']);
                }else{
                    $categorias[$key] = floatval($row['monto']);
                }
            }
        } 

        return array(
           'meses' => $meses,
           'monto' => $gasto,
           'categorias' => $categorias
        );
    }

    /* Monto total ejercido, para los indicadores de Contratos y ordenes en millones de pesos */
    function get_total_ejercido_by_ejercicio($ejercicio)
    {
        $this->load->model('tpoadminv1/Generales_model');

        $sqltext = "
            SELECT TRUNCATE(sum( b.monto_desglose )/1000000,0) as monto
            FROM vact_facturas as a, vact_facturas_desglose_v2 as b, vact_ejercicios as c
            WHERE a.id_factura = b.id_factura and a.id_ejercicio = c.id_ejercicio ";
        
        if(!empty($ejercicio))
        {
            $sqltext .= " and c.ejercicio = '" . $ejercicio . "' "; 
        }

        $query = $this->db->query( $sqltext );
        
        $monto = 0;
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row){}
                
            $monto = $row['monto'];
        } 

        return $this->Generales_model->money_format("%.2n", $monto) . " mdp";
    }

    /* Obtiene las ordenes de compra en base un ejercicio */
    function get_ordenes_compra($ejercicio)
    {
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->model('tpoadminv1/capturista/Ordenes_compra_model');
        $this->load->model('tpoadminv1/Generales_model');

        if(!empty($ejercicio)){
            $this->db->where('ejercicio', $ejercicio);
        }
        $query = $this->db->get('vlista_oc');

        $array_items = [];
        $total = $query->num_rows(); 
        if($query->num_rows() > 0){
            $cont = 0;
            foreach($query->result_array() as $row){
                $array_items[$cont]['id'] = $cont + 1;
                $array_items[$cont]['ejercicio'] = $row['ejercicio'];
                $array_items[$cont]['trimestre'] = $row['trimestre'];
                $array_items[$cont]['numero_orden_compra'] = $row['numero_orden_compra'];
                $array_items[$cont]['so_contratante'] = $row['contratante'];
                $array_items[$cont]['so_solicitante'] = $row['solicitante'];
                $array_items[$cont]['proveedor'] = $row['proveedor'];
                $array_items[$cont]['monto_ejercido'] = $this->Generales_model->money_format("%.2n", $row['monto_ejercido']);
                $array_items[$cont]['link'] = base_url() . "index.php/tpov1/contratos_ordenes/orden_detalle/" . $row['id_orden_compra']; 
                $cont++;
            }
        }

        return array( 
            'data' => $array_items,
            'total' => $total 
        );
    }

    /* Obtiene contratos en base al ejercicio */
    function get_contratos($ejercicio){
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->model('tpoadminv1/capturista/Contratos_model');
        $this->load->model('tpoadminv1/capturista/Ordenes_compra_model');
        $this->load->model('tpoadminv1/Generales_model');

        if(!empty($ejercicio)){
            $this->db->where('ejercicio', $ejercicio);
        }

        $query = $this->db->get('vlista_contratos');

        $array_items = [];
        $total = $query->num_rows(); 
        if($query->num_rows() > 0){
            $cont = 0;
            foreach($query->result_array() as $row){
                $array_items[$cont]['id'] = $cont + 1;
                $array_items[$cont]['ejercicio'] = $row['ejercicio'];
                $array_items[$cont]['trimestre'] = $row['trimestre'];
                $array_items[$cont]['so_contratante'] = $row['contratante'];
                $array_items[$cont]['so_solicitante'] = $row['solicitante'];
                $array_items[$cont]['numero_contrato'] = $row['numero_contrato'];
                $array_items[$cont]['proveedor'] = $row['proveedor'];
                $array_items[$cont]['monto_contrato'] = $this->Generales_model->money_format("%.2n", $row['monto_contrato']);
                $array_items[$cont]['monto_ejercido'] = $this->Generales_model->money_format("%.2n", $row['monto_ejercido']);
                $array_items[$cont]['link'] = base_url() . "index.php/tpov1/contratos_ordenes/contrato_detalle/" . $row['id_contrato'];
                $cont++;
            }
        }

        return array( 
            'data' => $array_items,
            'total' => $total 
        );
    }

    /* Obtiene los contratos en base un proveedor */
    function get_contratos_proveedor($id_proveedor)
    {
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->model('tpoadminv1/capturista/Contratos_model');
        $this->load->model('tpoadminv1/capturista/Ordenes_compra_model');
        $this->load->model('tpoadminv1/Generales_model');

        $this->db->where('id_proveedor', $id_proveedor);
        $this->db->where('id_contrato >', 1 );
        $this->db->where('active', 1 );
        $query = $this->db->get('tab_contratos');

        $array_items = [];
        if($query->num_rows() > 0){
            $cont = 0;
            foreach($query->result_array() as $row){
                $montos = $this->Contratos_model->getMontos($row['id_contrato'], $row['monto_contrato']);
                $array_items[$cont]['id'] = $cont + 1;
                $array_items[$cont]['ejercicio'] = $this->Catalogos_model->dame_nombre_ejercicio($row['id_ejercicio']);
                $array_items[$cont]['trimestre'] = $this->Catalogos_model->dame_nombre_trimestre($row['id_trimestre']);
                $array_items[$cont]['so_contratante'] = $this->Contratos_model->dame_nombre_contratante($row['id_so_contratante']);
                $array_items[$cont]['so_solicitante'] = $this->Contratos_model->dame_nombre_solicitante($row['id_so_solicitante']);
                $array_items[$cont]['numero_contrato'] = $row['numero_contrato'];
                $array_items[$cont]['monto_contrato'] = $this->Generales_model->money_format("%.2n", $row['monto_contrato']);
                $array_items[$cont]['monto_modificado'] = $montos['monto_modificado'];
                $array_items[$cont]['monto_total'] = $montos['monto_total'];
                $array_items[$cont]['monto_pagado'] = $montos['monto_pagado'];
                $array_items[$cont]['link'] = base_url(); /*completar */
                $cont++;
            }
        }

        return $array_items;
    }

    /* Obtiene las ordenes de compra en base un proveedor */
    function get_ordenes_proveedor($id_proveedor)
    {
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->model('tpoadminv1/capturista/Ordenes_compra_model');
        $this->load->model('tpoadminv1/Generales_model');

        $this->db->where('id_proveedor', $id_proveedor);
        $this->db->where('id_orden_compra >', 1);
        $this->db->where('id_contrato', 1 );
        $query = $this->db->get('vact_ordenes_compra_montos');

        $array_items = [];
        if($query->num_rows() > 0){
            $cont = 0;
            foreach($query->result_array() as $row){
                $array_items[$cont]['id'] = $cont + 1;
                $array_items[$cont]['ejercicio'] = $this->Catalogos_model->dame_nombre_ejercicio($row['id_ejercicio']);
                $array_items[$cont]['trimestre'] = $this->Catalogos_model->dame_nombre_trimestre($row['id_trimestre']);
                $array_items[$cont]['numero_orden_compra'] = $row['numero_orden_compra'];
                $array_items[$cont]['nombre_campana_aviso'] = $this->Ordenes_compra_model->dame_nombre_campana_aviso($row['id_campana_aviso']);
                $array_items[$cont]['monto'] = $this->Generales_model->money_format("%.2n", $row['monto']);
                $array_items[$cont]['link'] = base_url(); /*completar */
                $cont++;
            }
        }

        return $array_items;
    }

    /* Obtener los valores de la tabla */
    function get_valores_tabla_proveedores($ejercicio)
    {
        $this->load->model('tpoadminv1/Generales_model');

        if(!empty($ejercicio)){
            $this->db->where('ejercicio', $ejercicio);
        }
        $query = $this->db->get('vtab_proveedores');

        $array_items = [];
        if($query->num_rows() > 0){
            $cont = 0;
            foreach($query->result_array() as $row){
                $array_items[$cont]['id'] = $cont;
                $array_items[$cont]['link'] = base_url() . "index.php/tpov1/proveedores/proveedor_detalle/" . $row['id_proveedor'];
                $array_items[$cont]['ejercicio'] = $row['ejercicio'];
                $array_items[$cont]['nombre'] = $row['nombre'];
                $array_items[$cont]['contratos'] = $row['contratos'];
                $array_items[$cont]['ordenes'] = $row['ordenes'];
                $array_items[$cont]['monto'] = $this->Generales_model->money_format("%.2n", $row['monto']);
                $cont++;
            }
        }
        
        return $array_items;
    }

    /*
    * obtiene valores maximo y minimo en dinero gastado
    * lo dudoso de esta consulta es el limite
    */
    function get_monto_proveedor_minimo($ejercicio)
    {
        if(empty($ejercicio))
        {
            $sqltext = "
                select 
                    b.id_proveedor, 
                    sum(c.monto_desglose) as monto
                from vact_facturas as b, vact_facturas_desglose as c, cat_ejercicios as d
                where b.id_factura = c.id_factura and 
                    b.id_ejercicio = d.id_ejercicio 
                group by b.id_proveedor 
                order by sum(c.monto_desglose) desc 
                limit 29
            ";
        }else
        {
            $sqltext = "
                select 
                    b.id_proveedor, 
                    sum(c.monto_desglose) as monto
                from vact_facturas as b, vact_facturas_desglose as c, cat_ejercicios as d
                where b.id_factura = c.id_factura and 
                    b.id_ejercicio = d.id_ejercicio and
                    d.ejercicio = '" . $ejercicio . "'
                group by b.id_proveedor 
                order by sum(c.monto_desglose) desc 
                limit 29
            ";
        }

        $query = $this->db->query( $sqltext );

        $minimo = 0;
        $maximo = 0;

        $max_rows = $query->num_rows(); 
        if($max_rows > 0)
        {
            $this->load->model('tpoadminv1/Generales_model');

            
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                if($cont == 0){
                    $maximo = floatval($row['monto']);
                }

                if($max_rows == ($cont + 1)){
                    $minimo = floatval($row['monto']);
                }
                $cont++;
            }
        }

        return array(
            'minimo' => $minimo,
            'maximo' => $maximo
        );

    }

    function get_datos_porproveedores($ejercicio, $minimo, $id)
    {
        $categorias = $this->get_lista_categorias_contratos($ejercicio, $minimo);
        $proveedores = $this->get_lista_contratos_proveedor($ejercicio, $minimo);

        $valores = $this->get_monto_proveedor_minimo($ejercicio);

        if(sizeof($categorias) > 0)
        {
            $cont = sizeof($categorias);

            for($z = 0; $z < sizeof($proveedores); $z++ )
            {
                /*$row_list[0] = $proveedores[$z][0];
                $row_list[1] = $proveedores[$z][1];
                $row_list[2] = $proveedores[$z][2];*/
                $categorias[$cont] = $proveedores[$z];
                $cont++;
            }
        }

        $total_provedores = $this->get_total_proveedores($id, true);
        return array(
            'data' => $categorias,
            'total_proveedores' => $total_provedores['total'],
            'total_gasto' => '0',
            'minimo' => $valores['minimo'],
            'maximo' => $valores['maximo'],
        );
    }

    /* Obtener lista de categoria a tipo de contrato */
    function get_lista_categorias_contratos($ejercicio, $minimo)
    {
        $sqltext = "
            select 
                categoria, 
                tipo, 
                sum(total) as total,
                count(*) as numero 
            from vpor_proveedor 
            where total >= " . $minimo . "
        ";

        if(!empty($ejercicio)){
            $sqltext .= " and ejercicio = '" . $ejercicio . "'";
        }

        $sqltext .= " group by categoria, tipo";
        
        $query = $this->db->query( $sqltext );

        $array_items = [];

        if($query->num_rows() > 0)
        {
            $this->load->model('tpoadminv1/Generales_model');

            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                /*$row_list[0] = $row['categoria'];
                $row_list[1] = $row['tipo'];
                $row_list[2] = floatval($row['numero']);
                $row_list[3] = "monto: " . $row['total'];*/
                $row_list = array(
                    'from' => $row['categoria'],
                    'to' => $row['tipo'],
                    'weight' => floatval($row['numero']),
                    'description' => $this->Generales_model->money_format("%.2n", $row['total']) 
                );
                $array_items[$cont] = $row_list;
                $cont++;
            }
        }

        return $array_items;
    }

    function get_lista_contratos_proveedor($ejercicio, $minimo)
    {
        $sqltext = "
            select 
                tipo, 
                proveedor as proveedor1, 
                sum(total) as total, 
                sum(numero) as numero 
            from vpor_proveedor 
            where total >= " . $minimo . "
        ";

        if(!empty($ejercicio)){
            $sqltext .= " and ejercicio = '" . $ejercicio . "'";
        }

        $sqltext .= " group by tipo, proveedor";
        
        $query = $this->db->query( $sqltext );

        $array_items = [];

        if($query->num_rows() > 0)
        {
            $this->load->model('tpoadminv1/Generales_model');
            
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                /*$row_list[0] = $row['tipo'];
                $row_list[1] = $row['proveedor1'];
                $row_list[2] = floatval($row['numero']);
                $row_list[3] = "monto: " . $row['total'];*/

                $row_list = array(
                    'from' => $row['tipo'],
                    'to' => $row['proveedor1'],
                    'weight' => floatval($row['numero']),
                    'description' => $this->Generales_model->money_format("%.2n", $row['total']), 
                );
                $array_items[$cont] = $row_list;
                $cont++;
            }
        }

        return $array_items;
    }

    /* Tabla desglose de partidas en presupuesto */
    function get_desglose_partidas_presupuesto($ejercicio)
    {
        $sqltext = "
        SELECT *
        FROM `vtab_presupuesto_desglose`
        ";

        if(!empty($ejercicio))
        {
            $sqltext .= " where ejercicio = '" . $ejercicio . "'"; 
        }

        $query = $this->db->query( $sqltext );

        if($query->num_rows() > 0)
        {
            $this->load->model('tpoadminv1/Generales_model');

            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                $array_items[$cont]['id'] = $cont + 1;
                $array_items[$cont]['ejercicio'] = $row['ejercicio'];
                $array_items[$cont]['partida'] = $row['partida'];
                $array_items[$cont]['descripcion'] = $row['descripcion'];
                $array_items[$cont]['original'] = $this->Generales_model->money_format("%.2n",$row['original']);
                $array_items[$cont]['modificaciones'] = $this->Generales_model->money_format("%.2n",$row['modificaciones']);
                $array_items[$cont]['presupuesto'] = $this->Generales_model->money_format("%.2n",$row['presupuesto']);
                $array_items[$cont]['ejercido'] = $this->Generales_model->money_format("%.2n",$row['ejercido']);
                $cont++;
            }
            return $array_items;
        }
    }

    /* Gasto por servicio */
    function get_gasto_por_servicio($ejercicio)
    {
        if(empty($ejercicio))
        {
            $sqltext = "
            SELECT 
                `c`.`color_grafica`,
                `c`.`titulo_grafica` AS `nombre_servicio_categoria`,
                IFNULL((SELECT 
                            SUM(`d`.`monto_desglose`)
                        FROM
                            (`inai`.`vact_facturas_desglose` `d`
                            JOIN `inai`.`vact_facturas` `e`)
                        WHERE
                            ((`d`.`id_factura` = `e`.`id_factura`)
                            AND (`d`.`id_servicio_categoria` = `c`.`id_servicio_categoria`))),
                0) AS `monto`
            FROM `inai`.`cat_servicios_categorias` `c`
            ORDER BY `c`.`id_servicio_categoria`
            ";
        }else
        {
            $sqltext = "
            SELECT 
                `c`.`color_grafica`,
                `c`.`titulo_grafica` AS `nombre_servicio_categoria`,
                IFNULL((SELECT 
                            SUM(`d`.`monto_desglose`)
                        FROM
                            (`inai`.`vact_facturas_desglose` `d`
                            JOIN `inai`.`vact_facturas` `e`)
                        WHERE
                            ((`d`.`id_factura` = `e`.`id_factura`)
                            AND (`d`.`id_servicio_categoria` = `c`.`id_servicio_categoria`)
                            AND (`e`.`id_ejercicio` = " . $ejercicio . "))),
                0) AS `monto`
            FROM `inai`.`cat_servicios_categorias` `c`
            ORDER BY `c`.`id_servicio_categoria`
            ";
        }

        $query = $this->db->query( $sqltext );
        $colores = [];
        $categorias = [];
        $montos = [];

        if($query->num_rows() > 0)
        {
            $cont = 0;
            foreach ($query->result_array() as $row) {
                $categorias[$cont] = $row['nombre_servicio_categoria'];
                $montos[$cont] =  floatval($row['monto']);
                $colores[$cont] =  $row['color_grafica'];
                $cont++;
            }
        }   

        return array(
            'categorias' => $categorias,
            'montos' => $montos,
            'colores' => $colores,
            'total' => 0
        );

    }

    /* Top 10 de campañas y avisos*/
    function get_top10_campanas($ejercicio){
        if(empty($ejercicio))
        {
            $sqltext = "
            SELECT 
                `a`.`id_campana_aviso` AS `id_campana_aviso`,
            SUM(`a`.`monto_desglose`) AS `monto`
            FROM
                `inai`.`vact_facturas_desglose` `a`
            GROUP BY `a`.`id_campana_aviso`
            ORDER BY SUM(`a`.`monto_desglose`) DESC
            LIMIT 10
            ";
        }else
        {
            $sqltext = "
            SELECT 
                `a`.`id_campana_aviso` AS `id_campana_aviso`,
            SUM(`a`.`monto_desglose`) AS `monto`
            FROM
                (`inai`.`vact_facturas_desglose` `a`
                JOIN `inai`.`vact_facturas` `b`)
                WHERE
                (`a`.`id_factura` = `b`.`id_factura` AND
                `b`.`id_ejercicio` =  " . $ejercicio. ")
            GROUP BY `a`.`id_campana_aviso`
            ORDER BY SUM(`a`.`monto_desglose`) DESC
            LIMIT 10
            ";
        }

        $query = $this->db->query( $sqltext );
        $campanas = [];
        $montos = [];

        if($query->num_rows() > 0)
        {
            $this->load->model('tpoadminv1/capturista/Ordenes_compra_model');
            $cont = 0;
            foreach ($query->result_array() as $row) {
                $campanas[$cont] = $this->Ordenes_compra_model->dame_nombre_campana_aviso($row['id_campana_aviso']);
                $montos[$cont] =  floatval($row['monto']);
                $cont++;
            }
        }   

        return array(
            'categorias' => $campanas,
            'montos' => $montos,
            'total' => 0
        );

    }


    /* total de proveedores para el tablero de inicio, se puede limitar a top 10*/
    function get_total_proveedores($ejercicio, $esTop)
    {
        if(empty($ejercicio))
        {
            $sqltext = "
            SELECT 
                `b`.`id_proveedor` AS `id_proveedor`,
                SUM(`a`.`monto_desglose`) AS `monto`
            FROM
                (`inai`.`vact_facturas_desglose` `a`
                JOIN `inai`.`vact_facturas` `b`)
                WHERE
                (`a`.`id_factura` = `b`.`id_factura`)
            GROUP BY `b`.`id_proveedor`
            ORDER BY SUM(`a`.`monto_desglose`) DESC ";
        }else{
            $sqltext = "
            SELECT 
                `b`.`id_proveedor` AS `id_proveedor`,
                SUM(`a`.`monto_desglose`) AS `monto`
            FROM
                (`inai`.`vact_facturas_desglose` `a`
                JOIN `inai`.`vact_facturas` `b`)
                WHERE
                (`a`.`id_factura` = `b`.`id_factura`  AND
                `b`.`id_ejercicio` =  " . $ejercicio. " )
            GROUP BY `b`.`id_proveedor`
            ORDER BY SUM(`a`.`monto_desglose`) DESC ";
        }

    
        $query = $this->db->query( $sqltext );
        $proveedores = [];
        $montos = [];
        $total = $query->num_rows(); 
        $limit = -1;

        if($esTop == true )
        {
            //$sqltext .= " LIMIT 10";
            $limit = 10; 
        } 
        
        if($query->num_rows() > 0)
        {
            $this->load->model('tpoadminv1/capturista/Proveedores_model');
            $cont = 0;
            foreach ($query->result_array() as $row) {
                $proveedores[$cont] = $this->Proveedores_model->dame_nombre_proveedor($row['id_proveedor']);
                $montos[$cont] =  floatval($row['monto']);
                $cont++;
                if($cont >= $limit && $limit != -1){
                    break; //solo se envian 10 resultados
                }
            }
        }   

        return array(
            'categorias' => $proveedores,
            'montos' => $montos,
            'total' => $total
        );
    }

    function get_campanas_avisos($ejercicio)
    {
        $tipos_campanas = $this->tipos_campanas();

        $data = [];
        for($z = 0; $z < sizeof($tipos_campanas); $z++){
            $total = $this->get_total_gasto_campana($tipos_campanas[$z]['id_campana_tipo'], $ejercicio);
            $data[$z]['name'] = $tipos_campanas[$z]['nombre_campana_tipo'];
            $data[$z]['y'] = floatval($total);
        }

        return array(
            'data' => $data,
            'total' => $this->get_total_campanas($ejercicio)
        );

    }

    function get_total_campanas($ejercicio)
    {
        $sqltext = "select count(*) as total from vact_campana_aviso as a ";
        
        if(!empty($ejercicio))
        {
            $sqltext .= "
                where a.id_ejercicio in (
                select b.id_ejercicio 
                from 
                cat_ejercicios as b
                where a.id_ejercicio = b.id_ejercicio and b.ejercicio = '". $ejercicio . "' )";
        }

        $query = $this->db->query( $sqltext );
        $total = 0;
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) {
            }
            $total = $row['total'];
        }   

        return $total;
    }

    function get_total_gasto_campana($tipos, $ejercicio)
    {
        $sqltext = 
            "select 
                sum(b.monto_desglose) as total 
            from 
                vact_campana_aviso as a,
                vact_facturas_desglose as b,
                vact_facturas as c
            where
                b.id_factura = c.id_factura and
                b.id_campana_aviso = a.id_campana_aviso and
                a.id_campana_tipo in (" . $tipos .")";

        if(!empty($ejercicio)){
            $sqltext .=
             " and a.id_ejercicio = (select id_ejercicio from cat_ejercicios as z where z.ejercicio = " . $ejercicio. ")";
        }

        $query = $this->db->query( $sqltext );
        $total = 0;
        if($query->num_rows() > 0)
        {
           foreach ($query->result_array() as $row) {
           }
            $total = $row['total'];
        }   
        return $total;
    }

    function tipos_campanas()
    {
        $this->db->where('active', '1');
        $query = $this->db->get('cat_campana_tipos');
        
        if($query->num_rows() > 0)
        {
            $array_tipos = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
                {
                    $array_tipos[$cont]['id_campana_tipo'] = $row['id_campana_tipo'];
                    $array_tipos[$cont]['nombre_campana_tipo'] = $row['nombre_campana_tipo'];
                    $cont++;
            }
            return $array_tipos;
        }
    }

    function get_erogaciones_total($ejercicio)
    {
        if ( $ejercicio == "" ) {
            $sqltext = "SELECT IF(sum(c.monto_desglose) IS NULL,0,sum(c.monto_desglose)) as total
                          FROM vact_facturas as b,
                               vact_facturas_desglose as c
                         WHERE b.id_factura = c.id_factura";
        } else {
            $sqltext = "SELECT IF(sum(c.monto_desglose) IS NULL,0,sum(c.monto_desglose)) as total
                          FROM vact_facturas as b,
                               vact_facturas_desglose as c
                         WHERE b.id_factura = c.id_factura and
                               year(b.fecha_erogacion) = '" . $ejercicio . "'";
         }   

         $query = $this->db->query( $sqltext );
         $total = 1;
         if($query->num_rows() > 0)
         {
            foreach ($query->result_array() as $row) {
            }
            $total = $row['total'];
         }   

         return $this->get_erogaciones_total_mes($ejercicio, $total);
    
    }

    function get_erogaciones_total_mes($ejercicio, $total)
    {
        if ( $ejercicio == "" ) {
            $sqltext = "SELECT a.mes as mes, sum(c.monto_desglose) as total
                          FROM cat_meses as a,
                               vact_facturas as b,
                               vact_facturas_desglose as c
                         WHERE b.id_factura = c.id_factura and
                               month(fecha_erogacion) = mes_orden
                      GROUP BY mes
                      ORDER BY mes_orden";
        } else {
            $sqltext = "SELECT a.mes as mes, sum(c.monto_desglose) as total
                          FROM cat_meses as a,
                               vact_facturas as b,
                               vact_facturas_desglose as c
                         WHERE b.id_factura = c.id_factura and
                               month(fecha_erogacion) = mes_orden and
                               year(b.fecha_erogacion) = '" . $ejercicio . "'
                      GROUP BY mes
                      ORDER BY mes_orden";
         }

        $query = $this->db->query( $sqltext );
        $meses = [];
        $monto = [];
        if($query->num_rows() > 0)
        {
            $count = 0;
            foreach ($query->result_array() as $row) {
                $meses[$count] = $row['mes'];
                $monto[$count] = floatval($row['total']);
                $count++;
            }
        }

        return array(
            'total' => $total,
            'meses' => $meses,
            'monto' => $monto
        );

    }

    function get_query_presupuesto($ejercicio)
    {
        if(empty($ejercicio)){
            $query = "
                select 
                'Total' as partida, 
                'Total' as denominacion, 
                sum(a.monto_presupuesto) as monto_contrato, 
                (select sum(z.monto_desglose) from tab_facturas as y, tab_facturas_desglose as z where y.id_factura = z.id_factura ) as monto_ejercido,
                sum(a.monto_modificacion) as monto_modificado,
                (sum(a.monto_presupuesto) + sum(a.monto_modificacion)) as monto_total
                from 
                vact_presupuestos_desglose as a,
                vact_presupuestos as c
                where
                a.active = 1 and 
                c.active = 1 and 
                a.id_presupuesto = c.id_presupuesto

                union

                select 
                b.partida as partida, 
                b.denominacion as denominacion, 
                sum(a.monto_presupuesto) as monto_contrato, 
                (select sum(z.monto_desglose)
                from
                tab_facturas as y,
                tab_facturas_desglose as z
                where
                y.id_factura = z.id_factura and
                (z.id_presupuesto_concepto = a.id_presupuesto_concepto 
                    ||  z.id_presupuesto_concepto_solicitante = a.id_presupuesto_concepto )) as monto_ejercido,
                sum(a.monto_modificacion) as monto_modificado,
                (sum(a.monto_presupuesto) + sum(a.monto_modificacion)) as monto_total
                from 
                vact_presupuestos_desglose as a,
                cat_presupuesto_conceptos as b,
                vact_presupuestos as c
                where
                a.active = 1 and 
                b.active = 1 and 
                c.active = 1 and 
                a.id_presupuesto = c.id_presupuesto and
                a.id_presupuesto_concepto = b.id_presupesto_concepto
                group by b.partida, b.denominacion";
            } else {
                $query = " 
                select 
                'Total' as partida, 
                'Total' as denominacion, 
                sum(a.monto_presupuesto) as monto_contrato, 
                (select sum(z.monto_desglose) from vact_facturas as y, vact_facturas_desglose as z, cat_ejercicios as x 
                where y.id_factura = z.id_factura and x.id_ejercicio = y.id_ejercicio and x.ejercicio = '" . $ejercicio .  "') as monto_ejercido,
                sum(a.monto_modificacion) as monto_modificado,
                (sum(a.monto_presupuesto) + sum(a.monto_modificacion)) as monto_total
                from 
                vact_presupuestos_desglose as a,
                vact_presupuestos as c,
                vact_ejercicios as d 
                where
                a.active = 1 and 
                c.active = 1 and 
                d.active = 1 and 
                a.id_presupuesto = c.id_presupuesto and
                d.id_ejercicio = c.id_ejercicio and
                d.ejercicio = '". $ejercicio . "'
                union
                select
                b.partida as partida, 
                b.denominacion as denominacion, 
                sum(a.monto_presupuesto) as monto_contrato, 
                (select sum(z.monto_desglose) from vact_facturas as y, vact_facturas_desglose as z, cat_ejercicios as x where y.id_factura = z.id_factura and
                y.id_presupuesto_concepto =a.id_presupuesto_concepto and x.id_ejercicio = y.id_ejercicio and x.ejercicio = '" . $ejercicio .  "') as monto_ejercido,
                sum(a.monto_modificacion) as monto_modificado,
                (sum(a.monto_presupuesto) + sum(a.monto_modificacion)) as monto_total
                from 
                vact_presupuestos_desglose as a,
                cat_presupuesto_conceptos as b,
                vact_presupuestos as c,
                vact_ejercicios as d 
                where
                a.active = 1 and 
                b.active = 1 and 
                c.active = 1 and 
                d.active = 1 and 
                a.id_presupuesto = c.id_presupuesto and
                a.id_presupuesto_concepto = b.id_presupesto_concepto and
                d.id_ejercicio = c.id_ejercicio and
                d.ejercicio = '". $ejercicio . "' 
                group by b.partida, b.denominacion";
        }

        return $query; 
    }

    /* Se manda a llamar en el controlador inicioinicio */
    function getPresupuesto($ejercicio)
    {
        $sqltext = $this->get_query_presupuesto($ejercicio);
        $query = $this->db->query( $sqltext );
        if ($query->num_rows() > 0) {
            $categorias = [];
            $ejercido = [];
            $count = 0;
            $monto_contrato = 0;
            $monto_total = 0;
            $monto_modificado = 0;
            foreach ($query->result_array() as $row) {
                if($row['partida'] != 'Total')
                {
                    $categorias[$count] = $row['partida'] . " " . $row['denominacion'];
                    $ejercido[$count] =  floatval($row['monto_ejercido']);
                    $count++;
                }else{
                    $monto_modificado = floatval($row['monto_modificado']);
                    $monto_contrato = floatval($row['monto_contrato']);
                    $monto_total = floatval($row['monto_total']);
                } 
            }
            return array(
                'categorias' => $categorias,
                'montos' => $ejercido,
                'modificado' => $monto_modificado,
                'presupuesto' => $monto_contrato,
                'total' => $monto_total
            );
        }
        return 0;
    }

    /* Querys para el modulo de presupuestos */
    function getPresupuestoTotales($ejercicio)
    {
        $sqltext = $this->get_query_presupuesto($ejercicio);
        $query = $this->db->query( $sqltext );
        if ($query->num_rows() > 0) {
            $categorias = [];
            $p_ejercido = [];
            $p_modificado = [];
            $p_contrato = [];
            $count = 0;
            $monto_original = 0;
            $monto_ejercido = 0;
            $monto_modificado = 0;
            foreach ($query->result_array() as $row) {
                if($row['partida'] == 'Total')
                {
                    $monto_modificado = floatval($row['monto_total']);
                    $monto_original = floatval($row['monto_contrato']);
                    $monto_ejercido = floatval($row['monto_ejercido']);   
                }else{
                    $categorias[$count] = $row['partida'] . " " . $row['denominacion'];
                    $p_ejercido[$count] = floatval($row['monto_ejercido']);
                    $p_modificado[$count] = floatval($row['monto_total']); // se toma el de monto_total, ya que es la suma del presupuestado y el original
                    $p_contrato[$count] = floatval($row['monto_contrato']);
                    $count++;
                }
            }

            $serie_ejercido['name'] = 'Presupuesto ejercido';
            $serie_ejercido['data'] = $p_ejercido;

            $serie_modificado['name'] = 'Presupuesto modificado';
            $serie_modificado['data'] = $p_modificado;

            $serie_original['name'] = 'Presupuesto original';
            $serie_original['data'] = $p_contrato;

            $series[0] = $serie_original;
            $series[1] = $serie_modificado;
            $series[2] = $serie_ejercido;

            return array(
                'categorias' => $categorias,
                'modificado' => $monto_modificado,
                'original' => $monto_original,
                'ejercido' => $monto_ejercido,
                'series' => $series
            );
        }
        return 0;
    }

    /* cambiar a modelo adecuado en tpov1 */
    function dame_todos_ejercicios($activos)
    {
        $this->load->model('tpoadminv1/Generales_model');

        if($activos == true){
            $this->db->where('active', '1');
        }
        $query = $this->db->get('cat_ejercicios');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
    
                    $array_items[$cont]['id_ejercicio'] = $row['id_ejercicio'];
                    $array_items[$cont]['ejercicio'] = $row['ejercicio'];
                    $cont++;
            }
            return $array_items;
        }
    }

    /* Obtiene el último ejercicio */
    function get_ultimo_id_ejercicio()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $this->db->where('active', '1');
        $query = $this->db->get('cat_ejercicios');
        
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) { }
            return $row['id_ejercicio'];;
        }
    }

    function dame_nombre_ejercicio($id)
    {
        $this->db->select('ejercicio');
        $this->db->where('id_ejercicio', $id);
        $query = $this->db->get('cat_ejercicios');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row){
            }
            return $row['ejercicio'];
        }else
        {
            return '';
        }
    }

    function limpiar_Cadena($str)
    {
        return htmlspecialchars(addslashes($this->limpiarCadena($this->mysql_escape_mimic(strip_tags($str)))));
    }

    private function limpiarCadena($valor){
        $valor = str_ireplace("javascript:alert","",$valor);
        $valor = str_ireplace("alert","",$valor);
        $valor = str_ireplace("SELECT","",$valor);
            $valor = str_ireplace("INSERT","",$valor); 
        $valor = str_ireplace("COPY","",$valor);
        $valor = str_ireplace("DELETE","",$valor);
        $valor = str_ireplace("DROP","",$valor);
        $valor = str_ireplace("DUMP","",$valor);
        $valor = str_ireplace(" OR ","",$valor);
        $valor = str_ireplace(" AND ","",$valor);
        $valor = str_ireplace("%","",$valor);
        $valor = str_ireplace("LIKE","",$valor);
        $valor = str_ireplace("--","",$valor);
        $valor = str_ireplace("^","",$valor);
        $valor = str_ireplace("[","",$valor);
        $valor = str_ireplace("]","",$valor);
        $valor = str_ireplace("\\","",$valor);
        $valor = str_ireplace("!","",$valor);
        $valor = str_ireplace("¡","",$valor);
        $valor = str_ireplace("?","",$valor);
        $valor = str_ireplace("=","",$valor);
        $valor = str_ireplace("&","",$valor);
        $valor = str_ireplace("<?php","",$valor);
        $valor = str_ireplace("?>","",$valor);
        
        return $valor;
    }
    
    private function mysql_escape_mimic($inp) { 
        if(is_array($inp)) 
            return array_map(__METHOD__, $inp); 
     
        if(!empty($inp) && is_string($inp)) { 
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
        } 
        return $inp; 
    } 
}