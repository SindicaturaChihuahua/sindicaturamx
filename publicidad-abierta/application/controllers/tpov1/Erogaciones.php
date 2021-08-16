<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Erogaciones extends CI_Controller
{
    function index() 
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');

        $data['title'] = "Erogaciones";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] =  $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Erogaciones';
        $data['breadcrum_l'] = 'erogaciones';
        $data['active'] = 'erogaciones';
        $data['main_content'] = 'tpov1/inicio/erogaciones';

        $data['ejercicios'] = $this->Graficas_model->dame_todos_ejercicios(true);
        $data['id_ejercicio_ultimo'] = $this->Graficas_model->get_ultimo_id_ejercicio();

        $data['print_url'] = base_url() . "index.php/tpov1/print_ci/print_erogaciones/";
        $data['link_descarga_table'] = base_url() . "index.php/tpov1/exportar/erogaciones_table";

        $data['link_descarga'] = base_url() . 'index.php/tpov1/exportar/erogaciones';
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

    function get_erogaciones_meses()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/graficas/Indicadores_model');
        $this->load->model('tpoadminv1/Generales_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
        
        $total = $this->Graficas_model->get_total_monto_erogaciones($ejercicio);
        if($total > 0){
            $registro = $this->Graficas_model->get_total_monto_erogaciones_meses($ejercicio, $total);
        }

        $registros = array(
            'total_gasto' => $total,
            'total' => $this->Indicadores_model->get_total_facturas($ejercicio), 
            'meses' => isset($registro) ? $registro['meses'] : [],
            'valores' => isset($registro) ? $registro['valores'] : [],
            'drilldown' => isset($registro) ? $registro['drilldown'] : []  
        );

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function get_erogaciones_tabla()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
        
        $registros = $this->Tablas_model->get_erogaciones($ejercicio);
        

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function detalle()
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpoadminv1/capturista/Facturas_model');

        $str = $this->Generales_vp_model->limpiar_Cadena($this->uri->segment(4));
        $factura = $this->Facturas_model->dame_factura_id($str);

        if(!empty($factura)){
        
            if($factura['id_contrato'] > 1)
            {
                $linkcontrato =  base_url() . "index.php/tpov1/proveedores/proveedor_detalle/" . $factura['id_contrato'];
                $data['linkcontrato'] = " <a href='" . $linkcontrato . "' target='_blank'>(ver detalle)</a>";
            }

            if($factura['id_orden_compra'] > 1)
            {
                $linkorden =  base_url() . "index.php/tpov1/proveedores/proveedor_detalle/" . $factura['id_orden_compra'];
                $data['linkorden'] = " <a href='" . $linkorden . "' target='_blank'>(ver detalle)</a>";
            }
            
            $linkproveedor =  base_url() . "index.php/tpov1/proveedores/proveedor_detalle/" . $factura['id_proveedor'];
            $data['linkproveedor'] = " <a href='" . $linkproveedor . "' target='_blank'>(ver detalle)</a>";
        }

        $nombre = empty($factura) ? '' : $factura['numero_factura'];
        $id = empty($factura) ? '' : $factura['id_factura'];
        $data['title'] = "Detalle erogaci&oacute;n";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] =  $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Erogaciones|N&uacute;mero de factura: ' . $nombre;
        $data['breadcrum_l'] = 'erogaciones|erogaciones/detalle/'. $id;
        $data['active'] = 'erogaciones';
        $data['main_content'] = 'tpov1/inicio/detalle_erogacion';
        $data['disponible'] = empty($factura) ? false : true;
        $data['factura'] = $factura;

        $data['print_url'] = base_url() . "index.php/tpov1/print_ci/print_subconceptos_erogacion/";
        $data['link_descarga_table'] = base_url() . "index.php/tpov1/exportar/subconceptos_erogacion_table/" . $str;

        $data['ejercicios'] = $this->Graficas_model->dame_todos_ejercicios(true);
        $data['id_ejercicio_ultimo'] = $this->Graficas_model->get_ultimo_id_ejercicio();

        $data['texto_ayuda'] = $this->Generales_vp_model->get_texto_ayuda();
       
        $data['scripts'] = "<script type='text/javascript'>" .
                            "$(function(){". 
                                "init();" .
                            "});" .
                            "</script>";

        $this->load->view('tpov1/includes/template', $data);
    }

    function get_factura_desglose()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpoadminv1/capturista/Facturas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_factura'));
        
        $registros = $this->Facturas_model->dame_todas_facturas_desglose($str, true);
        
        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function get_factura_desglose_detalle()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpoadminv1/capturista/Facturas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->uri->segment(4));
        
        $registro = $this->Facturas_model->dame_factura_desglose_id($str);
        
        header('Content-type: application/json');

        echo json_encode( $registro );
    }
}

?>