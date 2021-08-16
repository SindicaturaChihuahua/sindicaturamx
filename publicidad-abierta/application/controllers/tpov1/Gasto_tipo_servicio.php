<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Gasto_tipo_servicio extends CI_Controller
{
    function index() 
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');

        $data['title'] = "Gasto por tipo de servicio";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] =  $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Gasto por tipo de servicio';
        $data['breadcrum_l'] = 'gasto_tipo_servicio';
        $data['active'] = 'gasto_tipo_servicio';
        $data['main_content'] = 'tpov1/inicio/gasto_tipo_servicio';

        $data['print_url'] = base_url() . "index.php/tpov1/print_ci/print_tiposervicios/";
        $data['link_descarga_table'] = base_url() . "index.php/tpov1/exportar/tiposervicios_table";

        $data['ejercicios'] = $this->Graficas_model->dame_todos_ejercicios(true);
        $data['id_ejercicio_ultimo'] = $this->Graficas_model->get_ultimo_id_ejercicio();

        $data['link_descarga'] = base_url() . 'index.php/tpov1/exportar/gastoPorServicio';
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

    function get_gasto_servicio(){

        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
        $registros = $this->Graficas_model->get_gasto_por_servicio_conjunto($ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function get_gasto_table_servicio(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
        $registros = $this->Tablas_model->get_servicios_gasto($ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registros );
        
    }
}

?>