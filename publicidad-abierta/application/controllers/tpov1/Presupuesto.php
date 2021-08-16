<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Presupuesto extends CI_Controller
{
    function index() 
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpoadminv1/logo/Logo_model');

        $data['title'] = "Presupuesto";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] = $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Presupuesto';
        $data['breadcrum_l'] = 'presupuesto';
        $data['active'] = 'presupuesto';
        $data['main_content'] = 'tpov1/inicio/presupuesto';
        $data['print_url'] = base_url() . "index.php/tpov1/print_ci/print_presupuestos/";
        $data['link_descarga_table'] = base_url() . "index.php/tpov1/exportar/presupuesto_table";

        $data['link_descarga'] = base_url() . 'index.php/tpov1/exportar/presupuesto';
        $data['indicadores_ayuda'] = $this->Generales_vp_model->get_indicadores_ayuda();

        $data['ejercicios'] = $this->Graficas_model->dame_todos_ejercicios(true);
        $data['id_ejercicio_ultimo'] = $this->Graficas_model->get_ultimo_id_ejercicio();

        $data['grafica_2'] = $this->Logo_model->get_registro_grafica_presupuesto();

        $data['select_paginado'] = '<select name="pageSize_g2">
                                            <option>5</option>
                                            <option selected>10</option>
                                            <option>15</option>
                                            <option>20</option>
                                        </select> Sujetos obligados por página 
                                        &nbsp &nbsp &nbsp &nbsp
                                        <select name="page_g2"> 
                                            <option>--</option>
                                        </select>';

        $serviceSide = base_url() . "index.php/tpov1/presupuesto/getValoresTablaPresupuestos";

        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                    "inicializar_componentes();" .
                                "});" .
                                "$('select[name=\"id_ejercicio\"]').change(function (){" .
                                    "inicializar_componentes();" .
                                 "});" .
                            "</script>";

        $this->load->view('tpov1/includes/template', $data);
    }

    function getValoresTablaPresupuestos()
    {
    
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 

        $registros = $this->Graficas_model->get_desglose_partidas_presupuesto($ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function getPresupuestos()
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 

        $registro = $this->Graficas_model->getPresupuestoTotales($ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registro );
    }

    function get_presupuestos_grafica(){
        
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $pageSize = $this->Graficas_model->limpiar_Cadena($this->input->post('pageSize'));
        $page = $this->Graficas_model->limpiar_Cadena($this->input->post('page'));

        $registro = $this->Graficas_model->get_presupuestos_partidas_so($str, $pageSize, $page);

        header('Content-type: application/json');

        echo json_encode( $registro );
    }
}