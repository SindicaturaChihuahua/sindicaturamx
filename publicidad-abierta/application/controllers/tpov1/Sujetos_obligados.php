<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Sujetos_obligados extends CI_Controller
{
    function index() 
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Indicadores_model');

        $data['title'] = "Sujetos obligados";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] =  $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Sujetos obligados';
        $data['breadcrum_l'] = 'sujetos_obligados';
        $data['active'] = 'sujetos_obligados';
        $data['main_content'] = 'tpov1/inicio/sujetos_obligados';

        $sujetos_total = $this->Graficas_model->get_total_sujetos();
        $data['valores_slider'] = array(
            'minimo' => 1,
            'actual' => $sujetos_total > 20 ? 20 : $sujetos_total,
            'maximo' => $sujetos_total
        );

        $data['ejercicios'] = $this->Graficas_model->dame_todos_ejercicios(true);
        $data['id_ejercicio_ultimo'] = $this->Graficas_model->get_ultimo_id_ejercicio();

        $data['print_url'] = base_url() . "index.php/tpov1/print_ci/print_sujetos_obligados/";
        $data['link_descarga_table'] = base_url() . "index.php/tpov1/exportar/sujetos_obligados_table";

        $data['link_descarga'] = base_url() . 'index.php/tpov1/exportar/sujetosObligados';
        $data['texto_ayuda'] = $this->Generales_vp_model->get_texto_ayuda();
        $data['indicadores_ayuda'] = $this->Generales_vp_model->get_indicadores_ayuda();

        $data['contratantes'] = $this->Indicadores_model->get_total_so_atribucion(1);
        $data['solicitantes'] = $this->Indicadores_model->get_total_so_atribucion(2);
        $data['contratantes_solicitantes'] = $this->Indicadores_model->get_total_so_atribucion(3);

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

    function get_sujetos_obligados(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $actual = $this->Generales_vp_model->limpiar_Cadena($this->input->post('valor_actual'));

        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
        $registros = $this->Graficas_model->get_grafica_sujetos_obligados($str, $ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function get_values_sankey(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $actual = $this->Generales_vp_model->limpiar_Cadena($this->input->post('valor_actual'));

        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
        $registros = $this->Graficas_model->get_so_grafica_sankey($str, $ejercicio, $actual);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function get_so_campanas()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        
        //$so_contratantes = 
        //$so_solicitantes = $this->Graficas_model->get_distint_so_campanas($str, 2);
        //$so_contratantes_solicitantes = $this->Graficas_model->get_distint_so_campanas($str, 3);
        $names_so = array('Contratantes', 'Solicitantes', 'Solicitantes y contratantes');
        $data = [];
        $con_datos = false;
        for($z = 0; $z < 3; $z++)
        {
            $sujetos = $this->Graficas_model->get_distint_so_campanas($str, ($z+1));
            $campanas_montos = [];
            $num = 0;
            $count = 0;
            foreach($sujetos as $so)
            {
                $participacion = $this->Graficas_model->get_campanas_so_facturas($str, ($z+1), $so['id_sujeto_obligado']);
                $total_monto_so = 0;
                foreach ($participacion as $value) {
                    $total_monto_so += floatval($value['monto']);
                    $num += 1;
                }
        
                $campanas_montos[$count]['name'] = sizeof($participacion);
                $campanas_montos[$count]['description'] = $so['nombre_sujeto_obligado'];
                $campanas_montos[$count]['description_2'] = $so['sigla'];
                $campanas_montos[$count]['url'] = base_url() . "index.php/tpov1/sujetos_obligados/detalle/". $so['id_sujeto_obligado'];
                $campanas_montos[$count]['value'] = $total_monto_so; 
                
                if($num > 0){
                    $con_datos = true;
                }

                $count += 1;

                //este mostraba las campañas o avisos en los que estaba relacionado un sujeto
                /*foreach ($participacion as $value) {
                    $campanas_montos[$num]['name'] = $value['nombre_campana_aviso'];
                    $campanas_montos[$num]['description'] = $so['nombre_sujeto_obligado'];
                    $campanas_montos[$num]['description_2'] = $so['sigla'];
                    $campanas_montos[$num]['url'] = base_url() . "index.php/tpov1/campana_aviso/campana_detalle/". $value['id_campana_aviso'];
                    $campanas_montos[$num]['value'] = floatval($value['monto']);
                    $num += 1;
                }

                if($num > 0){
                    $con_datos = true;
                }*/
                
            }
            if(sizeof($campanas_montos) == 1){
                $campanas_montos[1]['name'] = '';
                $campanas_montos[1]['description'] = '';
                $campanas_montos[1]['description_2'] = '';
                $campanas_montos[1]['url'] = '';
                $campanas_montos[1]['value'] = 0; 
            }

            $data[$z]['name'] = $names_so[$z] . " (" . sizeof($sujetos) . ")";
            $data[$z]['data'] = $campanas_montos;
        }

        header('Content-type: application/json');

        $result = array(
            'datos_disponibles' => $con_datos,
            'datos' => $data
        );

        echo json_encode( $result );
    }

    function get_sujetos_montos()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        
        $sujetos = $this->Tablas_model->get_sujetos_montos($str);

        header('Content-type: application/json');

        echo json_encode( $sujetos );
    }

    function detalle()
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');

        $str = $this->Generales_vp_model->limpiar_Cadena($this->uri->segment(4));
        $so = $this->Sujeto_model->get_sujeto_id($str);
        $nombre = empty($so) ? '' : $so['nombre'];
        $id = empty($so) ? '' : $so['id_sujeto_obligado'];
        $data['title'] = "Detalle sujeto obligado";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] =  $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Sujetos obligados|Detalle sujeto obligado: ' . $nombre;
        $data['breadcrum_l'] = 'sujetos_obligados|sujetos_obligados/detalle/'. $id;
        $data['active'] = 'sujetos_obligados';
        $data['main_content'] = 'tpov1/inicio/detalle_sujeto_obligado';
        $data['disponible'] = empty($so) ? false : true;
        $data['so'] = $so;

        $data['print_url_orden'] = base_url() . "index.php/tpov1/print_ci/print_so_ordenes/";
        $data['link_descarga_table_orden'] = base_url() . "index.php/tpov1/exportar/so_ordenes_table/" . $str;

        $data['print_url_contrato'] = base_url() . "index.php/tpov1/print_ci/print_so_contratos/";
        $data['link_descarga_table_contrato'] = base_url() . "index.php/tpov1/exportar/so_contratos_table/" . $str;

        $data['texto_ayuda'] = $this->Generales_vp_model->get_texto_ayuda();
       
        $data['scripts'] = "<script type='text/javascript'>" .
                            "$(function(){". 
                                "init();" .
                            "});" .
                            "</script>";

        $this->load->view('tpov1/includes/template', $data);
    }

    function get_contratos_so(){

        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');

        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_sujeto_obligado'));
        $ejercicio = null; //$this->Graficas_model->dame_nombre_ejercicio($str);
        $so = $this->Sujeto_model->get_sujeto_id($str);
        $nombre = empty($so) ? '' : $so['nombre'];
        $registros = $this->Tablas_model->get_contratos_so($nombre, $ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registros );

    }

    function get_ordenes_compra_so(){

        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');

        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_sujeto_obligado'));
        $ejercicio = null; //$this->Graficas_model->dame_nombre_ejercicio($str);
        $so = $this->Sujeto_model->get_sujeto_id($str);
        $nombre = empty($so) ? '' : $so['nombre'];
        $registros = $this->Tablas_model->get_ordenes_compra_so($nombre, $ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registros );

    }
}

?>