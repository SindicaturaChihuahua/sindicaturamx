<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Contratos_ordenes extends CI_Controller
{
    function index() 
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');

        $data['title'] = "Contratos y &oacute;rdenes de compra";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] =  $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Contratos y &oacute;rdenes de compra';
        $data['breadcrum_l'] = 'contratos_ordenes';
        $data['active'] = 'contratos_ordenes';
        $data['main_content'] = 'tpov1/inicio/contratos_ordenes';

        $data['ejercicios'] = $this->Graficas_model->dame_todos_ejercicios(true);
        $data['id_ejercicio_ultimo'] = $this->Graficas_model->get_ultimo_id_ejercicio();

        $data['print_url_orden'] = base_url() . "index.php/tpov1/print_ci/print_ordenes/";
        $data['link_descarga_table_orden'] = base_url() . "index.php/tpov1/exportar/ordenes_table/";

        $data['print_url_contrato'] = base_url() . "index.php/tpov1/print_ci/print_contratos/";
        $data['link_descarga_table_contrato'] = base_url() . "index.php/tpov1/exportar/contratos_table/";
        
        $data['link_descarga'] = base_url() . 'index.php/tpov1/exportar/contratosOrdenes';
        $data['texto_ayuda'] = $this->Generales_vp_model->get_texto_ayuda();
        $data['indicadores_ayuda'] = $this->Generales_vp_model->get_indicadores_ayuda();

        $data['scripts'] = "<script type='text/javascript'>" .
                            "$(function(){". 
                                "init();" .
                            "});" .
                            "$('select[name=\"id_ejercicio\"]').change(function (){" .
                                "init();" .
                            "});" .
                            "</script>";

        $this->load->view('tpov1/includes/template', $data);
    }

    function get_contratos(){
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
        $registros = $this->Graficas_model->get_contratos($ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function get_ordenes_compra(){
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');

        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
        $registros = $this->Graficas_model->get_ordenes_compra($ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function get_total_monto_ejercido()
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
        $total = $this->Graficas_model->get_total_ejercido_by_ejercicio($ejercicio);

        header('Content-type: application/json');

        echo json_encode( $total );
    }

    /*  Métodos para la vista de detalle de contrato*/
    function contrato_detalle()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpoadminv1/capturista/Contratos_model');
        $str = $this->Generales_vp_model->limpiar_Cadena($this->uri->segment(4));
        $contrato = $this->Contratos_model->dame_contrato_id($str);
        
        if(!empty($contrato)){
            $linkso_c =  base_url() . "index.php/tpov1/sujetos_obligados/detalle/" . $contrato['id_so_contratante'];
            $linkso_s =  base_url() . "index.php/tpov1/sujetos_obligados/detalle/" . $contrato['id_so_solicitante'];
            $linkproveedor =  base_url() . "index.php/tpov1/proveedores/proveedor_detalle/" . $contrato['id_proveedor'];
            $data['link_so_contratante'] = " <a href='" . $linkso_c . "' target='_blank'>(ver detalle)</a>";
            $data['link_so_solicitante'] = " <a href='" . $linkso_s . "' target='_blank'>(ver detalle)</a>";
            $data['link_proveedor'] = " <a href='" . $linkproveedor . "' target='_blank'>(ver detalle)</a>";
        }

        $nombre = empty($contrato) ? '' : $contrato['numero_contrato'];
        $data['title'] = "Detalle contrato";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] =  $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Contratos y &oacute;rdenes de compra| Contrato n&uacute;mero: ' . $nombre;
        $data['breadcrum_l'] = 'contratos_ordenes|contratos_ordenes/contrato_detalle/'.$this->uri->segment(4);
        $data['active'] = 'contratos_ordenes';
        $data['main_content'] = 'tpov1/inicio/detalle_contrato';
        $data['disponible'] = empty($contrato) ? false : true;
        $data['contrato'] = $contrato;

        $data['print_url_servicio'] = base_url() . "index.php/tpov1/print_ci/print_contratos_servicios/";
        $data['link_descarga_table_servicio'] = base_url() . "index.php/tpov1/exportar/contratos_servicios_table/" . $str;

        $data['print_url_otros'] = base_url() . "index.php/tpov1/print_ci/print_contratos_otros/";
        $data['link_descarga_table_otros'] = base_url() . "index.php/tpov1/exportar/contratos_otros_table/" . $str;

        $data['print_url_ordenes'] = base_url() . "index.php/tpov1/print_ci/print_contratos_ordenes/";
        $data['link_descarga_table_ordenes'] = base_url() . "index.php/tpov1/exportar/contratos_ordenes_table/" . $str;

        $data['print_url_convenios'] = base_url() . "index.php/tpov1/print_ci/print_contratos_convenios/";
        $data['link_descarga_table_convenios'] = base_url() . "index.php/tpov1/exportar/contratos_convenios_table/" . $str;

        $data['texto_ayuda'] = $this->Generales_vp_model->get_texto_ayuda();

        $data['scripts'] = "<script type='text/javascript'>" .
                            "$(function(){". 
                                "init();" .
                            "});" .
                            "</script>";

        $this->load->view('tpov1/includes/template', $data);
    }

    /* Carga la informacion de la tabla de convenios modificatorios */
    function get_convenios_modificatorios()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpoadminv1/capturista/Contratos_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_contrato'));
        $registros = $this->Contratos_model->dame_todos_convenios_modificatorios($str);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }
    /* para la tabla en contraros */
    function get_ordenes_compra_contrato()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpoadminv1/capturista/Ordenes_compra_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_contrato'));
        $registros = $this->Ordenes_compra_model->dame_todos_ordenes_compra_by_contrato($str);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    /* para la tabla en contraros */
    function get_servicio_contrato(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_contrato'));
        $registros = $this->Tablas_model->get_servicios_contratos_gasto($str, 1);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    /* para la tabla en contraros */
    function get_servicio_otros_contrato(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_contrato'));
        $registros = $this->Tablas_model->get_servicios_contratos_gasto($str, 2);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    /* Métodos para la vista de detalle de orden de trabajo */
    function orden_detalle()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpoadminv1/capturista/Ordenes_compra_model');
        $str = $this->Generales_vp_model->limpiar_Cadena($this->uri->segment(4));
        $orden = $this->Ordenes_compra_model->dame_orden_compra_id($str);

        if(!empty($orden)){
            $linkso_c =  base_url() . "index.php/tpov1/sujetos_obligados/detalle/" . $orden['id_so_contratante'];
            $linkso_s =  base_url() . "index.php/tpov1/sujetos_obligados/detalle/" . $orden['id_so_solicitante'];
            $linkproveedor =  base_url() . "index.php/tpov1/proveedores/proveedor_detalle/" . $orden['id_proveedor'];
            $linkso_ac =  base_url() . "index.php/tpov1/campana_aviso/campana_detalle/" . $orden['id_campana_aviso'];
            $data['link_so_contratante'] = " <a href='" . $linkso_c . "' target='_blank'>(ver detalle)</a>";
            $data['link_so_solicitante'] = " <a href='" . $linkso_s . "' target='_blank'>(ver detalle)</a>";
            $data['link_proveedor'] = " <a href='" . $linkproveedor . "' target='_blank'>(ver detalle)</a>";
            $data['link_campana_aviso'] = " <a href='" . $linkso_ac . "' target='_blank'>(ver detalle)</a>";
        }

        $nombre = empty($orden) ? '' : $orden['numero_orden_compra'];
        $data['title'] = "Detalle Orden de compra";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] =  $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Contratos y &oacute;rdenes de compra| Orden de compra n&uacute;mero: ' . $nombre;
        $data['breadcrum_l'] = 'contratos_ordenes|contratos_ordenes/orden_detalle/'.$this->uri->segment(4);
        $data['active'] = 'contratos_ordenes';
        $data['main_content'] = 'tpov1/inicio/detalle_orden';
        $data['disponible'] = empty($orden) ? false : true;
        $data['orden'] = $orden;

        $data['print_url_servicio'] = base_url() . "index.php/tpov1/print_ci/print_ordenes_servicios/";
        $data['link_descarga_table_servicio'] = base_url() . "index.php/tpov1/exportar/ordenes_servicios_table/" . $str;

        $data['print_url_otros'] = base_url() . "index.php/tpov1/print_ci/print_ordenes_otros/";
        $data['link_descarga_table_otros'] = base_url() . "index.php/tpov1/exportar/ordenes_otros_table/" . $str;

        $data['texto_ayuda'] = $this->Generales_vp_model->get_texto_ayuda();

        $data['scripts'] = "<script type='text/javascript'>" .
                            "$(function(){". 
                                "init();" .
                            "});" .
                            "</script>";

        $this->load->view('tpov1/includes/template', $data);
    }

    /* para la tabla en ordenes de compra */
    function get_servicio_ordenes(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_orden_compra'));
        $registros = $this->Tablas_model->get_servicios_ordenes_gasto($str, 1);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    /* para la tabla en ordenes de compra */
    function get_servicio_otros_ordenes(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_orden_compra'));
        $registros = $this->Tablas_model->get_servicios_ordenes_gasto($str, 2);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function get_valores_graficas()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $contratos = $this->Graficas_model->get_total_contratos_por_mes($str);
        $ordenes = $this->Graficas_model->get_total_ordenes_por_mes($str);

        $meses = array('ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC');

        $data_contratos = [];
        $data_ordenes = [];
        $data_pie = [];
        $cont = 0;
        $total_global_contratos = 0;
        $total_global_ordenes = 0;
        foreach($meses as $mes)
        {
            $total_c = 0;
            $monto_c = 0;
            for($z = 0; $z < sizeof($contratos); $z++)
            {
                if($mes == $contratos[$z]['mes']){
                    $total_c = floatval($contratos[$z]['total']);
                    $monto_c = floatval($contratos[$z]['monto']);
                }
            }

            $total_oc = 0;
            $monto_oc = 0;
            for($z = 0; $z < sizeof($ordenes); $z++)
            {
                if($mes == $ordenes[$z]['mes']){
                    $total_oc = floatval($ordenes[$z]['total']);
                    $monto_oc = floatval($ordenes[$z]['monto']);
                }
            }

            $data_contratos[$cont] = $total_c;
            $data_ordenes[$cont] = $total_oc;
            
            $aux_monto[0]['name'] = "Contratos";
            $aux_monto[0]['y'] = $monto_c;
            $aux_monto[1]['name'] = "Órdenes de compra";
            $aux_monto[1]['y'] = $monto_oc;

            $total_global_contratos += $monto_c;
            $total_global_ordenes += $monto_oc;

            $data_pie[$mes] = $aux_monto;

            $cont++;
        }
        // completo grafico de pie, valores default
        $aux_monto[0]['name'] = "Contratos";
        $aux_monto[0]['y'] = $total_global_contratos;
        $aux_monto[1]['name'] = "Órdenes de compra";
        $aux_monto[1]['y'] = $total_global_ordenes;
        $data_pie['TOTAL'] = $aux_monto; // este valor para iniciar la grafica
        $data_pie['DEFAULT'] = $aux_monto; //este valor se utilizará para el evento mouseOut

        $data[0]['name'] = "Contratos";
        $data[0]['data'] = $data_contratos;
        $data[1]['name'] = "Órdenes de compra";
        $data[1]['data'] = $data_ordenes;

        $registros = array(
            'categorias' => $meses,
            'datos_grafica' => $data,
            'datos_pie' => $data_pie,
            'total_monto' => $total_global_contratos + $total_global_ordenes
        );

        header('Content-type: application/json');

        echo json_encode( $registros );
    }
}
?>