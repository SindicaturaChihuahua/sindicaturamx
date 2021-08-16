<?php

/*
INAI / Graficas
 */

class Graficas_model extends CI_Model
{

    /* Sesión de sujetos obligados */

    function get_sujetos_x_atribucion($id_so_atribucion, $id_ejercicio, $ejercicio, $nombre_so_atribucion){
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpoadminv1/Generales_model');
        
        $this->db->where('id_so_atribucion', $id_so_atribucion);
        $query = $this->db->get('vact_sujetos_obligados');

        $tipos_campanas = $this->get_tipos_campanas();
        $array_base = [];
        $facturas_total = 0;
        $contratos_total = 0;
        $ordenes_total = 0;
        $monto_total = 0;
        $array_campanas_totales = [];
        if($query->num_rows() > 0){
            $cont = 0;
            foreach($query->result_array() as $row){
                $monto = $this->Tablas_model->get_so_monto($id_ejercicio, $row['id_so_atribucion'], $row['id_sujeto_obligado']);
                $aux_factura = $this->get_total_facturas_x_so($id_ejercicio, $row['id_sujeto_obligado'], $id_so_atribucion);
                $aux_contratos = sizeof($this->Tablas_model->get_contratos_so($row['nombre_sujeto_obligado'], $ejercicio));
                $aux_ordenes = sizeof($this->Tablas_model->get_ordenes_compra_so($row['nombre_sujeto_obligado'], $ejercicio));
                $totales_campanas = $this->total_so_x_tipo_campana($tipos_campanas, $row['id_so_atribucion'], $row['nombre_sujeto_obligado'], $ejercicio);

                $description = "<b>" . $row['nombre_sujeto_obligado'] . "</b>" .
                    $totales_campanas['texto']  .
                    "<br> Facturas: " . $aux_factura . "<br>" .
                    "<br> Contratos: " . $aux_contratos . " <br>" .
                    "<br> Órdenes de compra: " . $aux_ordenes . " <br>" .
                    "<br> Monto total erogado:" . $this->Generales_model->money_format("%.2n", $monto);

                $array_temp['name'] = $row['siglas_sujeto_obligado'];
                $array_temp['y'] = 5;
                $array_temp['description'] = $description;
                $array_base[$cont] = $array_temp;

                //concateno los totales por tipo de sujeto
                foreach($totales_campanas['array_total'] as $key => $value){
                    if(array_key_exists($key, $array_campanas_totales)){
                        $array_campanas_totales[$key] += $value;
                    }else{
                        $array_campanas_totales[$key] = $value;
                    }
                }

                $facturas_total += $aux_factura;
                $contratos_total += $aux_contratos;
                $ordenes_total += $aux_ordenes;
                $monto_total += $monto;
                $cont++;
            }
        }

        $text_campanas = "";
        foreach($array_campanas_totales as $key => $value){
            $text_campanas .= "<br>" . $key . ": " . $value;
        }

        $text_tipo_sujeto = "<b>" . $nombre_so_atribucion . "</b>" .
            $text_campanas .
            "<br> Facturas: " . $facturas_total . "<br>" .
            "<br> Contratos: " . $contratos_total . " <br>" .
            "<br> Órdenes de compra: " . $ordenes_total . " <br>" .
            "<br> Monto total erogado:" . $this->Generales_model->money_format("%.2n", $monto_total);

        return array(
            'datos' => $array_base,
            'texto' => $text_tipo_sujeto
        );
    }

    function total_so_x_tipo_campana($tipos_campanas, $id_so_atribucion, $nombre_sujeto_obligado, $ejercicio ){
        $text = "";
        $array_total = [];
        foreach($tipos_campanas as $tipo){
            $total = $this->get_total_campanas_avisos_x_so($nombre_sujeto_obligado, $id_so_atribucion, $tipo['id_campana_tipo'], $ejercicio);
            $text .= "<br>" . $tipo['nombre_campana_tipo'] . ":" . $total;
            $array_total[$tipo['nombre_campana_tipo']] = $total;
        }
        return array(
            'texto' => $text,
            'array_total' => $array_total
        );
    }

    function get_grafica_sujetos_obligados($id_ejercicio, $ejercicio){
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
        $sqltext = "
            select 
                a.id_so_atribucion,
                a.nombre_so_atribucion, 
                count(s.id_sujeto_obligado) as total
            from cat_so_atribucion as a
            left join vact_sujetos_obligados as s
            on a.id_so_atribucion = s.id_so_atribucion
            group by a.id_so_atribucion
        ";
        $query = $this->db->query( $sqltext );

        $array_base = [];
        $atribuciones = [];
        $drilldown = [];
        if($query->num_rows() > 0)
        {
            $data = $query->result_array();
            $cont = 0;
            foreach($data as $row){
                $datos = $this->get_sujetos_x_atribucion($row['id_so_atribucion'], $id_ejercicio,  $ejercicio, $row['nombre_so_atribucion']);
                $atribuciones[$cont]['name'] = $row['nombre_so_atribucion'];
                $atribuciones[$cont]['y'] = intval($row['total']);
                $atribuciones[$cont]['description'] =  $datos['texto'];
                $drilldown[$row['nombre_so_atribucion']] = $datos['datos'];
                $cont++;
            }
        }

        return array(
            'data_serie' => $atribuciones, 
            'drilldown' => $drilldown 
        );
    }

    function get_total_sujetos(){

        $query = $this->db->get('vact_sujetos_obligados');

        return $query->num_rows();
    }

    function get_so_grafica_sankey($id_ejercicio, $ejercicio, $actual){

        $this->load->model('tpoadminv1/Generales_model');
        $this->load->model('tpov1/graficas/Tablas_model');

        $limite = 20;
        if(!empty($actual) && intval($actual) > 0){
            $limite = intval($actual);
        }

        $this->db->limit($limite);
        $query = $this->db->get('vact_sujetos_obligados');

        $array_items = [];
        $data = $query->result_array();
        $campanas = $this->get_tipos_campanas();
        $weight = 0;
        if($query->num_rows() > 0)
        {
            $cont = 0;
            foreach($data as $row){
                
                $weight_so = $this->get_total_facturas_x_so($id_ejercicio, $row['id_sujeto_obligado'], $row['id_so_atribucion']);
                $weight += $weight_so;

                $array_items[$cont]['from'] = $row['nombre_sujeto_obligado'];
                $array_items[$cont]['to'] = "Facturas";
                $array_items[$cont]['weight'] = $weight_so;
                $cont++;
            }

            foreach($data as $row){
                $weight_so = sizeof($this->Tablas_model->get_contratos_so($row['nombre_sujeto_obligado'], $ejercicio));
                $weight += $weight_so;

                $array_items[$cont]['from'] = $row['nombre_sujeto_obligado'];
                $array_items[$cont]['to'] = "Contratos";
                $array_items[$cont]['weight'] = $weight_so;
                $cont++;
            }

            foreach($data as $row){
                $weight_so = sizeof($this->Tablas_model->get_ordenes_compra_so($row['nombre_sujeto_obligado'], $ejercicio));
                $weight += $weight_so;

                $array_items[$cont]['from'] = $row['nombre_sujeto_obligado'];
                $array_items[$cont]['to'] = "Órdenes de compra";
                $array_items[$cont]['weight'] = $weight_so;
                $cont++;
            }

            if(isset($campanas) && sizeof($campanas) > 0){
                foreach($campanas as $row_tipo){
                    foreach($data as $row){
                        $weight_so = $this->get_total_campanas_avisos_x_so($row['nombre_sujeto_obligado'], $row['id_so_atribucion'], $row_tipo['id_campana_tipo'], $ejercicio);
                        $weight += $weight_so;

                        $array_items[$cont]['from'] = $row['nombre_sujeto_obligado'];
                        $array_items[$cont]['to'] = $row_tipo['nombre_campana_tipo'];
                        $array_items[$cont]['weight'] = $weight_so;
                        $cont++;
                    }
                }
            }
        }

        return array(
            'datos_disponibles' => $weight > 0 ? true : false,
            'datos' => $array_items
        ); 

    }

    function get_total_campanas_avisos_x_so($nombre_sujeto_obligado, $id_so_atribucion, $id_campana_tipo, $ejercicio){
        $this->load->model('tpoadminv1/Generales_model');

        if(!empty($ejercicio)){
            $this->db->where('ejercicio', $ejercicio);
        }
        if($id_so_atribucion == 1 || $id_so_atribucion == 3){
            $this->db->where('contratante', $nombre_sujeto_obligado);
        }else{
            $this->db->where('solicitante', $nombre_sujeto_obligado);
        }

        $this->db->where('id_campana_tipo', $id_campana_tipo);
        $query = $this->db->get('vcampanasyavisos');
        
        return $query->num_rows();
    }

    function get_tipos_campanas(){

        $this->db->where('active', 1);

        $query = $this->db->get('cat_campana_tipos');
        
        return $query->result_array();
    }

    function get_total_facturas_x_so($id_ejercicio, $id_so, $id_atribucion){
        $aux_ejercicio = '';

        if(!empty($id_ejercicio)){
            $aux_ejercicio = ' and id_ejercicio = ' . $id_ejercicio;
        }

        $aux_atribucion = '';
        if($id_atribucion == 1 || $id_atribucion == 3){
            $aux_atribucion = ' on a.id_so_contratante = b.id_sujeto_obligado ';
        }else if($id_atribucion == 2){
            $aux_atribucion = ' on a.id_so_solicitante = b.id_sujeto_obligado ';
        }

        $sqltext = "
            select count(id_factura) as total
            from vact_facturas 
            where id_factura in (
                select a.id_factura
                from vact_facturas_desglose_v2 as a
                join vact_sujetos_obligados b
                " . $aux_atribucion . "
                where b.id_sujeto_obligado = " . $id_so . "
            ) " . $aux_ejercicio . "
        "; 
        $query = $this->db->query( $sqltext );
        $total = 0;

        $row = $query->row();
        if(isset($row)){
            $total = intval($row ->total);
        }

        return $total;
    }


    /* Este método obtiene los distintos so participantes 
        en las diferentes desglose de factura de una campaña
    */
    function get_distint_so_campanas($id_ejercicio, $id_so_atribucion ){

        $aux_ejercicio = "";
        if(!empty($id_ejercicio)){
            $aux_ejercicio = " a.id_ejercicio = " . $id_ejercicio . " and ";
        }
        $aux_so = '';
        switch($id_so_atribucion){
            case 1: // so_contratante
            case 3: // so_contratante_solicitante
                $aux_so = "
                    b.id_so_contratante = d.id_sujeto_obligado and
                    b.id_presupuesto_concepto is not null and
                ";
                break;
            case 2: // so_solicitante
                $aux_so = "
                    b.id_so_solicitante = d.id_sujeto_obligado and
                    b.id_presupuesto_concepto_solicitante is not null and
                ";
                break;
        }

        $sqltext = " 
            select distinct 
                d.id_sujeto_obligado as id_sujeto_obligado,
                d.nombre_sujeto_obligado as nombre_sujeto_obligado,
                d.siglas_sujeto_obligado as sigla 
            from 
                tab_facturas as a,
                tab_facturas_desglose as b,
                vact_campana_aviso as c,
                vact_sujetos_obligados as d,
                cat_so_atribucion as e
            where
                a.id_factura = b.id_factura and
                b.id_campana_aviso = c.id_campana_aviso and
                " . $aux_so . "
                d.id_so_atribucion = e.id_so_atribucion and
                e.id_so_atribucion = " . $id_so_atribucion . " and
                " . $aux_ejercicio . "
                a.active = 1 and
                b.active = 1 
            group by c.id_campana_aviso, d.id_sujeto_obligado
        ";

        $query = $this->db->query( $sqltext );
        $array_items = [];
        if($query->num_rows() > 0)
        {
            $cont = 0;
            foreach($query->result_array() as $row){
                $array_items[$cont]['id_sujeto_obligado'] = $row['id_sujeto_obligado'];
                $array_items[$cont]['nombre_sujeto_obligado'] = $row['nombre_sujeto_obligado'];
                $array_items[$cont]['sigla'] = $row['sigla'];
                $cont += 1;
            }
        }

        return $array_items;
    }

    /* El metodo obtiene la lista de campañas y el monto de acuerdo a un 
       sujeto obligado y su participacion en las facturas
    */
    function get_campanas_so_facturas($id_ejercicio, $id_so_atribucion, $id_sujeto_obligado ){

        $aux_ejercicio = "";
        if(!empty($id_ejercicio)){
            $aux_ejercicio = " a.id_ejercicio = " . $id_ejercicio . " and ";
        }
        $aux_so = '';
        switch($id_so_atribucion){
            case 1: // so_contratante
            case 3: // so_contratante_solicitante
                $aux_so = "
                    b.id_so_contratante = d.id_sujeto_obligado and
                    b.id_presupuesto_concepto is not null and
                ";
                break;
            case 2: // so_solicitante
                $aux_so = "
                    b.id_so_solicitante = d.id_sujeto_obligado and
                    b.id_presupuesto_concepto_solicitante is not null and
                ";
                break;
        }

        $sqltext = " 
            select 
                a.id_factura,
                a.id_ejercicio, 
	            a.numero_factura,
	            d.nombre_sujeto_obligado,
                c.nombre_campana_aviso,
                c.id_campana_aviso,
                sum(b.monto_desglose) as monto
            from 
                tab_facturas as a,
                tab_facturas_desglose as b,
                vact_campana_aviso as c,
                vact_sujetos_obligados as d,
                cat_so_atribucion as e
            where
                a.id_factura = b.id_factura and
                b.id_campana_aviso = c.id_campana_aviso and
                " . $aux_so . "
                d.id_so_atribucion = e.id_so_atribucion and
                d.id_sujeto_obligado =  " . $id_sujeto_obligado . " and
                e.id_so_atribucion = " . $id_so_atribucion . " and
                " . $aux_ejercicio . "
                a.active = 1 and
                b.active = 1
            group by c.id_campana_aviso, d.id_sujeto_obligado
        ";

        $query = $this->db->query( $sqltext );
        $array_items = [];
        if($query->num_rows() > 0)
        {
            $cont = 0;
            foreach($query->result_array() as $row){
                $array_items[$cont]['nombre_campana_aviso'] = $row['nombre_campana_aviso'];
                $array_items[$cont]['id_campana_aviso'] = $row['id_campana_aviso'];
                $array_items[$cont]['monto'] = $row['monto'];
                $cont += 1;
            }
        }

        return $array_items;
    }


    /* Sesión de erogaciones */
    /* Calcular total gastado por ejercicio */

    function get_total_monto_erogaciones($ejercicio)
    {
        $sqltext = "
            SELECT IF(sum(c.monto_desglose) IS NULL,0,sum(c.monto_desglose)) as total
            FROM vact_facturas as b,
             vact_facturas_desglose_v2 as c
            WHERE b.id_factura = c.id_factura
        ";

        if(!empty($ejercicio)){
            $sqltext .= " and year(b.fecha_erogacion) = '" . $ejercicio . "'";
        }

        $query = $this->db->query( $sqltext );
        $total = 0;
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row){
                $total = $row['total'];
            }
        }

        return $total;
    }

    /* Calcular erogaciones por mes de acuerdo a un ejercicio ejercicio */
    function get_total_monto_erogaciones_meses($ejercicio, $total)
    {
        $aux = "";
        if(!empty($ejercicio))
        {
            $aux = " and year(b.fecha_erogacion) = '" . $ejercicio . "'";
        }
        $sqltext = "
            SELECT
                a.id_mes, 
                a.mes as mes,
                (sum(c.monto_desglose)/" . $total . ")*100 as total,
                sum(c.monto_desglose) as monto
            FROM cat_meses as a,
                vact_facturas as b,
                vact_facturas_desglose_v2 as c
            WHERE b.id_factura = c.id_factura and
                month(b.fecha_erogacion) = mes_orden " . $aux . "
            GROUP BY mes
            ORDER BY mes_orden
        ";

        $meses = [];
        $meses_formato = [];
        $drilldown = [];
        $query = $this->db->query( $sqltext );
        if($query->num_rows() > 0)
        {
            $cont = 0;
            foreach($query->result_array() as $row){
                $meses[$cont][$row['mes']] = floatval($row['total']);
                $meses_formato[$cont]['name'] = $row['mes'];
                $meses_formato[$cont]['y'] = floatval($row['monto']);
                $meses_formato[$cont]['drilldown'] = $row['mes'];
                $drilldown[$cont]['name'] = $row['mes'];
                $drilldown[$cont]['id'] = $row['mes'];
                $drilldown[$cont]['data'] = $this->get_erogaciones_x_mes($ejercicio, floatval($row['monto']), $row['id_mes']);
                $cont += 1;
            }
        }

        return array(
            'meses' => $meses,
            'valores' => $meses_formato,
            'drilldown' => $drilldown
        );
    }

    function get_erogaciones_x_mes($ejercicio, $total, $mes){

        $aux_ejercicio = "";
        if(!empty($ejercicio)){
            $aux_ejercicio = " year(b.fecha_erogacion) = " . $ejercicio . " and ";
        }

        $sqltext = "
            SELECT 
                a.mes, 
                d.nombre_servicio_subcategoria  as tipo, 
                ifnull(sum(c.monto_desglose), 0) as total
            FROM cat_meses as a,
                vact_facturas as b,
                vact_facturas_desglose_v2 as c,
                cat_servicios_subcategorias as d
            WHERE b.id_factura = c.id_factura and
                c.id_servicio_subcategoria = d.id_servicio_subcategoria and
                month(b.fecha_erogacion) = mes_orden and
                " . $aux_ejercicio . "
                a.id_mes = " . $mes . "
            group by a.mes, d.nombre_servicio_subcategoria
            order by a.mes_orden, c.id_servicio_subcategoria;
        ";

        $query = $this->db->query( $sqltext );
        $mes = [];
        if($query->num_rows() > 0){
            $cont = 0;
            foreach($query->result_array() as $row){
                $mes_subcategoria[0] = $row['tipo'];
                $mes_subcategoria[1] = floatval($row['total']);
                $mes[$cont] = $mes_subcategoria;
                $cont += 1;
            }
        }

        return $mes;
    }

    /* Sesión de Gasto por servicios */
    function get_gasto_por_servicio_conjunto($ejercicio)
    {
        $this->load->model('tpov1/graficas/Indicadores_model');
        if(empty($ejercicio)){
            $sqltext = "
                select 
                    id_mes, 
                    mes_corto, 
                    nombre_servicio_categoria, 
                    sum(monto) as monto
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
                    sum(monto) as monto
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
        $colores_categorias = [];
        $data_pie = []; 
        $categoria_x_mes = [];

        if($query->num_rows() > 0)
        {
            $datos = $query->result_array();
            foreach($datos as $row){
                $offset = array_search($row['mes_corto'], $meses);
                $gasto[$offset] += floatval($row['monto']); 

                $key = $row['nombre_servicio_categoria'];
                if(array_key_exists($key, $categorias)){
                    $categorias[$key] = floatval($categorias[$key]) + floatval($row['monto']);
                }else{
                    $categorias[$key] = floatval($row['monto']);
                }
            }

            $cont = 0;
            foreach($categorias as $key => $value)
            {
                /* Almacena la inicializacion */
                $data_pie[$cont]['name'] =  $key;
                $data_pie[$cont]['y'] = $value;

                $categoria_x_mes[$key] = $this->get_gasto_x_mes_de_categoria($meses, $datos, $key);
                $cont += 1;
            }
        } 
        $categoria_x_mes['GENERALES'] = $gasto;

        return array(
           'meses' => $meses,
           'monto' => $gasto,
           'categorias' => $data_pie,
           'colores' => $this->get_colores_categoria($categorias),
           'categorias_x_mes' => $categoria_x_mes,
           'mes_x_categorias' => $this->get_gasto_categoria_x_mes($meses, $datos, $data_pie),
           'servicios' => $this->get_servicios_categorias(),
           'indicador1' =>  $this->Indicadores_model->get_total_gasto_servicio($ejercicio, 1),
           'indicador2' =>  $this->Indicadores_model->get_total_gasto_servicio($ejercicio, 2)
        );
    }

    function get_colores_categoria($categorias)
    {
        $query = $this->db->get('cat_servicios_categorias');
        $colores = [];
        if($query->num_rows() > 0)
        {
            $cont = 0;
            $datos = $query->result_array();
            foreach($categorias as $key => $value)
            {
                foreach($datos as $row)
                {
                    if($key == $row['titulo_grafica']){
                        $colores[$cont] = $row['color_grafica'];
                        $cont += 1;
                    }
                }
            }
        } 

        return $colores;
    }

    function get_gasto_x_mes_de_categoria($meses, $datos,$categoria){
        $array_items = [];
        $cont = 0;
        foreach($meses as $mes){
            $array_items[$cont] = 0;
            foreach($datos as $row){
                if($row['nombre_servicio_categoria'] == $categoria && $row['mes_corto'] == $mes){
                    $array_items[$cont] += floatval($row['monto']);
                }
            }
            $cont += 1;
        }
        return $array_items;
    }

    function get_gasto_categoria_x_mes($meses, $datos, $generales){
        $array_items['GENERALES'] = $generales;
        foreach($meses as $mes){
            $data_pie = [];
            $categorias = [];
            foreach($datos as $row){
                
                if($row['mes_corto'] == $mes){
                    $key = $row['nombre_servicio_categoria'];
                    if(array_key_exists($key, $categorias)){
                        $categorias[$key] = floatval($categorias[$key]) + floatval($row['monto']);
                    }else{
                        $categorias[$key] = floatval($row['monto']);
                    }
                }
            }

            $cont = 0;
                
            foreach($categorias as $key => $value)
            {
                /* Almacena la inicializacion */
                $data_pie[$cont]['name'] =  $key;
                $data_pie[$cont]['y'] = $value;
                $cont += 1;
            }
            $array_items[$mes] = $data_pie;
        }
        return $array_items;
    }

    function get_servicios_categorias()
    {
        $result = [];
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $servicios = $this->Catalogos_model->dame_clasificaciones_activas();
        $cont = 0;
        for($z = 0; $z < sizeof($servicios); $z++){
            $categorias = $this->Catalogos_model->get_categorias_filtro($servicios[$z]['id_servicio_clasificacion']);
            $array_items = [];
            for($y = 0; $y < sizeof($categorias); $y++){
                $array_items[] = $categorias[$y]['nombre_servicio_categoria'];
            }
            if(sizeof($array_items) > 0){
                $result[$cont][$servicios[$z]['nombre_servicio_clasificacion']] = $array_items;
                $cont += 1;
            }
        }

        return $result;
    }

    /* Sesión de contratos y ordenes de compra */


    /* Este metodo obtiene el total de contratos por mes */
    function get_total_contratos_por_mes($id_ejercicio){
        $aux_ejercicio = "";
        if(!empty($id_ejercicio)){
            $aux_ejercicio = " and b.id_ejercicio = " . $id_ejercicio . " ";
        } 
        $sqltext = "
            SELECT
                mes_corto as mes,
                COUNT(b.id_contrato) as total,
                IFNULL(SUM(d.monto_desglose), 0) as monto
            FROM cat_meses as a
            JOIN vact_contratos as b
            ON month(b.fecha_celebracion) = a.mes_orden 
            LEFT JOIN vact_facturas as c 
            ON c.id_contrato = b.id_contrato
            LEFT JOIN vact_facturas_desglose_v2 as d
            ON c.id_factura = d.id_factura 
            WHERE b.id_contrato > 1
            " . $aux_ejercicio . "
            GROUP BY a.mes_corto 
            ORDER BY a.mes_orden
        ";

        $query = $this->db->query( $sqltext );

        return $query->result_array();
    }

    /* Este metodo obtiene el total de ordenes de compra por mes*/
    function get_total_ordenes_por_mes($id_ejercicio){
        $aux_ejercicio = "";
        if(!empty($id_ejercicio)){
            $aux_ejercicio = " and b.id_ejercicio = " . $id_ejercicio . " ";
        } 
        $sqltext = "
            SELECT
                mes_corto as mes,
                COUNT(b.id_orden_compra) as total,
                IFNULL(SUM(d.monto_desglose), 0) as monto
            FROM cat_meses as a
            JOIN vact_ordenes_compra as b
            ON month(b.fecha_orden) = a.mes_orden 
            LEFT JOIN vact_facturas as c 
            ON c.id_orden_compra = b.id_orden_compra
            LEFT JOIN vact_facturas_desglose_v2 as d
            ON c.id_factura = d.id_factura 
            WHERE b.id_orden_compra > 1 and b.id_contrato = 1
            " . $aux_ejercicio . "
            GROUP BY a.mes_corto 
            ORDER BY a.mes_orden
        ";

        $query = $this->db->query( $sqltext );

        return $query->result_array();
    }

    /* Monto total ejercido, para los indicadores de Contratos y ordenes en millones de pesos */
    function get_total_ejercido_by_ejercicio($ejercicio)
    {
        $this->load->model('tpoadminv1/Generales_model');

        $sqltext = "
            SELECT IFNULL(sum( b.monto_desglose ),0) as monto
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
                from vact_facturas as b, vact_facturas_desglose_v2 as c, cat_ejercicios as d
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
                from vact_facturas as b, vact_facturas_desglose_v2 as c, cat_ejercicios as d
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
                sum(numero) as numero 
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
                $row_list = array(
                    'from' => $row['categoria'],
                    'to' => $row['tipo'],
                    'weight' => floatval($row['numero']),//floatval($row['total']),
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
                $row_list = array(
                    'from' => $row['tipo'],
                    'to' => $row['proveedor1'],
                    'weight' => floatval($row['numero']),//floatval($row['total']),
                    'description' => $this->Generales_model->money_format("%.2n", $row['total']), 
                );
                $array_items[$cont] = $row_list;
                $cont++;
            }
        }

        return $array_items;
    }

    /* Consulta para obtener los valores de la segunda grafica en presupuestos */

    function get_presupuestos_partidas_so($id_ejercicio, $pageSize, $page){
        $this->load->model('tpoadminv1/capturista/Presupuestos_model');
        $this->load->model('tpoadminv1/Generales_model');
        $aux_ejercicio = '';

        if(!empty($id_ejercicio)){
            $aux_ejercicio = ' where b.id_ejercicio = ' . $id_ejercicio . ' ';
        }

        $sqltext = '
            select 
                b.id_sujeto_obligado,
                a.id_so_orden_gobierno,
                b.id_presupuesto,
                d.id_presupuesto_concepto,
                (select nombre_so_orden_gobierno 
                    from cat_so_ordenes_gobierno as c where c.id_so_orden_gobierno = a.id_so_orden_gobierno) as orden_gobierno,
                a.nombre_sujeto_obligado,
                (select partida 
                    from cat_presupuesto_conceptos as e where e.id_presupesto_concepto = d.id_presupuesto_concepto) as partida,
                (select concat(capitulo, "-", concepto, "-", partida, "-", denominacion) 
                    from cat_presupuesto_conceptos as e where e.id_presupesto_concepto = d.id_presupuesto_concepto) as nombre_partida,
                ifnull(d.monto_presupuesto, 0) as monto_presupuesto,
                ifnull(d.monto_modificacion, 0) as monto_modificacion
            from vact_sujetos_obligados as a
            join vact_presupuestos as b
            on a.id_sujeto_obligado = b.id_sujeto_obligado
            left join tab_presupuestos_desglose as d
            on d.id_presupuesto = b.id_presupuesto
            ' . $aux_ejercicio . ' 
            group by b.id_presupuesto, d.id_presupuesto_desglose';
            

        $query = $this->db->query( $sqltext );

        $paginas = $this->get_valores_paginado_presupuestos_so($query->result_array(), $page, $pageSize);
        $data = $this->so_presupuesto_paginacion($query->result_array(), $page, $pageSize);
        //$data = $query->result_array();

        $array_items = [];
        $array_tem_partidas = [];
        $array_tem_ordenes = [];
        $orden_gobierno_weight = [];
        $so_weight = [];
        $legends_p = [];
        $legends_og = [];
        $montos_so = [];
        $montos_p = [];
        if(sizeof($data) > 0)
        {
            foreach ($data as $row) 
            {
                $key = $row['orden_gobierno'];
                $key_so = $row['nombre_sujeto_obligado'];
                if(array_key_exists($key_so, $so_weight)){
                    $so_weight[$key_so] = $so_weight[$key_so] + 1;
                }else{
                    $so_weight[$key_so] = 1;
                }

                if(array_key_exists($key, $orden_gobierno_weight)){
                    $orden_gobierno_weight[$key] = $orden_gobierno_weight[$key] + 1;
                }else{
                    $orden_gobierno_weight[$key] = 1;
                }
            }

            //se agregan las diferentes partidas relacionadas a las diferentes SO 
            foreach($so_weight as $key => $value){
                $so_aux = [];
                $legends_p = [];
                foreach($data as $row){
                    $key_so_p = $row['nombre_sujeto_obligado'];
                    if($row['nombre_sujeto_obligado'] == $key){
                        $key_so = $row['partida'];
                        if(!empty($key_so)){
                            if(array_key_exists($key_so, $so_aux)){
                                $so_aux[$key_so] = $so_aux[$key_so] + 1;
                            }else{
                                $total = $this->get_presupuesto_total_partida($data, $key,  $row['id_presupuesto_concepto']);
                                $total_format = $this->Generales_model->money_format("%.2n",$total);
                                array_push($legends_p, "<br/>". $this->get_numero_nombre_partida($row['nombre_partida']) ." <br/>Con presupuesto total de <b>" . $total_format . "</b>");
                                $so_aux[$key_so] = 1;

                                if(array_key_exists($key_so_p, $montos_so)){
                                    $montos_so[$key_so_p] += $total;
                                }else{
                                    $montos_so[$key_so_p] = $total;
                                }
                            }
                        }
                    }
                }
                $array_tem_partidas = $this->concat_arrays($array_tem_partidas, $so_aux, $key, $legends_p);
            }

            //se buscan las relaciones de las disitintas ordenes de gobierno con los sujetos obligados
            foreach($orden_gobierno_weight as $key => $value){
                $so_aux = [];
                $legends_og = [];
                foreach($data as $row){
                    if($row['orden_gobierno'] == $key){
                        $key_so = $row['nombre_sujeto_obligado'];

                        if(array_key_exists($key_so, $so_aux)){
                            $so_aux[$key_so] = $so_aux[$key_so] + 1;
                        }else{
                            $total = 0;
                            if(array_key_exists($key_so, $montos_so)){
                                $total = $montos_so[$key_so];
                            }

                            $total_format = $this->Generales_model->money_format("%.2n",$total);
                            array_push($legends_og, "Monto original asignado: " . $total_format);
                            $so_aux[$key_so] = 1;

                            if(array_key_exists($key, $montos_p)){
                                $montos_p[$key] += $total;
                            }else{
                                $montos_p[$key] = $total;
                            }
                        }
                    }
                }

                $array_tem_ordenes = $this->concat_arrays($array_tem_ordenes, $so_aux, $key, $legends_og);
            }

            // se estraen los diferentes ordenes de gobierno, asi como el total de la presencia, los mismo con
            // los diferentes sujetos obligados 
            $legends_p = [];
            $orden_gobierno_weight = [];
            foreach ($data as $row) 
            {
                $key = $row['orden_gobierno'];
                if(array_key_exists($key, $orden_gobierno_weight)){
                    $orden_gobierno_weight[$key] = $orden_gobierno_weight[$key] + 1;
                }else{
                    $monto = 0;
                    if(array_key_exists($key, $montos_p)){
                        $monto = $montos_p[$key];
                    }
                    array_push($legends_p, "Monto original asignado " . $this->Generales_model->money_format("%.2n",$monto) . " al Orden de Gobierno <b>" . $row['orden_gobierno']. "</b>");
                    $orden_gobierno_weight[$key] = 1;
                }
            }
            //se agregan a una lista el presupuesto y los difrente slinks a los distintos ordenes de gobierno
            $array_items = $this->concat_arrays($array_items, $orden_gobierno_weight, 'Presupuesto', $legends_p);
            $array_items = $this->concat_arrays_2($array_items, $array_tem_ordenes);
            $array_items = $this->concat_arrays_2($array_items, $array_tem_partidas);

        }

        return array(
            'datos' => $array_items,
            'paginas' => $paginas
        );

    }

    function so_presupuesto_paginacion($data, $page, $pageSize){
        $position = $page * $pageSize;
        $maximo = $position + $pageSize;
        $paginado =  [];
        $so_unicos = [];
        $so_paginados = [];

        /* Obtener los diferentes SO */
        $cont = 0;
        foreach($data as $row){
            if(!in_array($row['id_sujeto_obligado'], $so_unicos)){
                $so_unicos[$cont] = $row['id_sujeto_obligado'];
                $cont++;
            }
        }

        /* Paginar los SO obligados */
        $cont = 0;
        for($i = $position; $i < $maximo; $i++){
            if(isset($so_unicos[$i])){
                $so_paginados[$cont] = $so_unicos[$i];
                $cont++;
            }
        }
        /* Se filtran los registros en base a los SO paginados */
        $cont = 0;
        foreach($data as $row){

            if(in_array($row['id_sujeto_obligado'], $so_paginados)){
            
                $paginado[$cont] = $row;
                $cont++;
            }
        }

        return $paginado;
    }

    function get_valores_paginado_presupuestos_so($data, $page, $pageSize){
        $so_unicos = [];
        $paginas = [];
        if($page == "" ){
            /* Obtener los diferentes SO */
            $cont = 0;
            foreach($data as $row){
                if(!in_array($row['id_sujeto_obligado'], $so_unicos)){
                    $so_unicos[$cont] = $row['id_sujeto_obligado'];
                    $cont++;
                }
            }

            $page_size = 1;
            if(is_numeric($pageSize) && intval($pageSize) > 1){
                $page_size = intval($pageSize);
            }
            
            $num_float = sizeof($so_unicos) / $page_size;
            $total = intval($num_float);
            if((sizeof($so_unicos) % $page_size) == 1 ){
                $total += 1;
            }
            
            for ($i = 0; $i < $total; $i++) { 
                $paginas[$i] = "Página " . ($i+1);
            }

            if($total == 0){
                $paginas[0] = "Página 1";
            }
        }

        
        return $paginas;
    }

    function get_presupuesto_total_partida($objetos, $so, $partida_id){
        $total = 0;
        foreach($objetos as $row){
            if($row['nombre_sujeto_obligado'] == $so && 
                $row['id_presupuesto_concepto'] == $partida_id ){
                    $total += (floatval($row['monto_presupuesto']) + floatval($row['monto_modificacion']));
            }
        }

        return $total;
    }

    function get_numero_nombre_partida($partida){
        $cadenas = explode( '-', $partida );
        if(sizeof($cadenas) >= 4){
            return $cadenas[2]."-".$cadenas[3];
        }else{
            return $partida;
        }
    }

    function concat_arrays($array_base, $objetos, $from_key, $legends){
        
        $cont = sizeof($array_base);
        $z = 0;
        foreach($objetos as $key => $value)
        {
            $row_list = array(
                'from' => $from_key,
                'to' => $key,
                'weight' => intval($value),
                'description' => $legends[$z] 
            );
            $array_base[$cont] = $row_list;
            $cont += 1;
            $z++;
        }

        return $array_base;
    }

    function concat_arrays_2($array_base, $array_other){
        
        $cont = sizeof($array_base);
        $z = 0;
        foreach($array_other as $row)
        {
            $array_base[$cont] = $row;
            $cont += 1;
        }

        return $array_base;
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
                            (`vact_facturas_desglose_v2` `d`
                            JOIN `vact_facturas` `e`)
                        WHERE
                            ((`d`.`id_factura` = `e`.`id_factura`)
                            AND (`d`.`id_servicio_categoria` = `c`.`id_servicio_categoria`))),
                0) AS `monto`
            FROM `cat_servicios_categorias` `c`
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
                            (`vact_facturas_desglose_v2` `d`
                            JOIN `vact_facturas` `e`)
                        WHERE
                            ((`d`.`id_factura` = `e`.`id_factura`)
                            AND (`d`.`id_servicio_categoria` = `c`.`id_servicio_categoria`)
                            AND (`e`.`id_ejercicio` = " . $ejercicio . "))),
                0) AS `monto`
            FROM `cat_servicios_categorias` `c`
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
                `vact_facturas_desglose_v2` `a`
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
                (`vact_facturas_desglose_v2` `a`
                JOIN `vact_facturas` `b`)
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
                (`vact_facturas_desglose_v2` `a`
                JOIN `vact_facturas` `b`)
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
                (`vact_facturas_desglose_v2` `a`
                JOIN `vact_facturas` `b`)
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
                vact_facturas_desglose_v2 as b,
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
                               vact_facturas_desglose_v2 as c
                         WHERE b.id_factura = c.id_factura";
        } else {
            $sqltext = "SELECT IF(sum(c.monto_desglose) IS NULL,0,sum(c.monto_desglose)) as total
                          FROM vact_facturas as b,
                               vact_facturas_desglose_v2 as c
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
                               vact_facturas_desglose_v2 as c
                         WHERE b.id_factura = c.id_factura and
                               month(fecha_erogacion) = mes_orden
                      GROUP BY mes
                      ORDER BY mes_orden";
        } else {
            $sqltext = "SELECT a.mes as mes, sum(c.monto_desglose) as total
                          FROM cat_meses as a,
                               vact_facturas as b,
                               vact_facturas_desglose_v2 as c
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
                    or  z.id_presupuesto_concepto_solicitante = a.id_presupuesto_concepto )) as monto_ejercido,
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
                (select sum(z.monto_desglose) from vact_facturas as y, vact_facturas_desglose_v2 as z, cat_ejercicios as x 
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
                (select sum(z.monto_desglose) 
                    from vact_facturas as y, vact_facturas_desglose_v2 as z, cat_ejercicios as x 
                    where y.id_factura = z.id_factura and
                    (z.id_presupuesto_concepto = a.id_presupuesto_concepto 
                    or  z.id_presupuesto_concepto_solicitante = a.id_presupuesto_concepto ) and x.id_ejercicio = y.id_ejercicio and x.ejercicio = '" . $ejercicio .  "') as monto_ejercido,
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