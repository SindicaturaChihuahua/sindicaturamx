<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Inicio extends CI_Controller
{
    function index() 
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');

        $data['title'] = "Transparencia en publicidad oficial";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] = $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = '';
        $data['breadcrum_l'] = '';
        $data['active'] = 'inicio';
        $data['main_content'] = 'tpov1/inicio/inicio';

        $data['link_descarga'] = base_url() . 'index.php/tpov1/exportar/inicio';
        $data['link_descarga_pnt'] = base_url() . 'index.php/tpov1/exportar/pnt';
        $data['indicadores_ayuda'] = $this->Generales_vp_model->get_indicadores_ayuda();

        $data['ejercicios'] = $this->Graficas_model->dame_todos_ejercicios(true);
        $data['id_ejercicio_ultimo'] = $this->Generales_vp_model->get_ultimo_id_ejercicio_con_erogacion();
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                    "init();" .
                                "});" .
                                "$('select[name=\"id_ejercicio\"]').change(function (){" .
                                    "init();" .
                                 "});" .
                            "</script>";

        $this->load->view('tpov1/includes/template', $data);
    }

    function getGastoPorServicio()
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));

        $registro = $this->Graficas_model->get_gasto_por_servicio($str);

        header('Content-type: application/json');

        echo json_encode( $registro );
    }

    function getTop10Campanas()
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));

        $registro = $this->Graficas_model->get_top10_campanas($str);

        header('Content-type: application/json');

        echo json_encode( $registro );
    }

    function getTop10Proveedores()
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        //$ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 

        $registro = $this->Graficas_model->get_total_proveedores($str, true);

        header('Content-type: application/json');

        echo json_encode( $registro );
    }

    function getPresupuestos()
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 

        $registro = $this->Graficas_model->getPresupuesto($ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registro );
    }

    function getErogacionesMes()
    {
        $this->load->model('tpov1/graficas/Graficas_model');

        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 

        $registro = $this->Graficas_model->get_erogaciones_total($ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registro );
    }

    function getCampanasAvisos()
    {
        $this->load->model('tpov1/graficas/Graficas_model');

        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 

        $registro = $this->Graficas_model->get_campanas_avisos($ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registro );
    }
    
    function getValoresGraficas()
    {
        $this->load->model('tpov1/graficas/Graficas_model');

        $ejercicio = $this->input->post('id_ejercicio'); 

        $presupuesto = $this->Graficas_model->getPresupuesto($ejercicio);
        $erogaciones = $this->Graficas_model->get_erogaciones_total($ejercicio);

        $registro = array(
            'total' => $erogaciones['total'],
            'meses' => $erogaciones['meses'],
            'monto' => $erogaciones['monto'],
            'categorias' => $presupuesto['categorias'],
            'ejercido' => $presupuesto['ejercido']
        );

        header('Content-type: application/json');

        echo json_encode( $registro );
    }

    
}