<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Proveedores extends CI_Controller
{
    function index() 
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');
        
        $data['title'] = "Gasto por proveedor";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] = $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Gasto por proveedor';
        $data['breadcrum_l'] = 'proveedores';
        $data['active'] = 'proveedores';
        $data['main_content'] = 'tpov1/inicio/gasto_porproveedor';

        $data['print_url'] = base_url() . "index.php/tpov1/print_ci/print_proveedores/";
        $data['link_descarga_table'] = base_url() . "index.php/tpov1/exportar/porproveedores_table";

        $data['link_descarga'] = base_url() . 'index.php/tpov1/exportar/porproveedores';
        $data['indicadores_ayuda'] = $this->Generales_vp_model->get_indicadores_ayuda();

        $data['ejercicios'] = $this->Graficas_model->dame_todos_ejercicios(true);
        $data['id_ejercicio_ultimo'] = $this->Graficas_model->get_ultimo_id_ejercicio();
        $data['valores_slider'] = $this->Graficas_model->get_monto_proveedor_minimo(
            $this->Graficas_model->dame_nombre_ejercicio($data['id_ejercicio_ultimo']));
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                    "inicializar_componentes();" .
                                "});" .
                                "$('select[name=\"id_ejercicio\"]').change(function (){" .
                                    "get_valores_grafica(true);" .
                                    "get_valores_table();" .
                                "});" .
                                "var mnm = $('#minimo').val();" .
                                "var mxn = '$ ' + parseFloat(mnm, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString();" .
                                "$('#tagMinimo').html(mxn);" .
                            "</script>";

        $this->load->view('tpov1/includes/template', $data);
    }

    function proveedor_detalle()
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpoadminv1/capturista/Proveedores_model');
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
        $proveedor = $this->Proveedores_model->dame_proveedor_id($str);
        $nombre = empty($proveedor) ? '' : $proveedor['nombre_razon_social'];
        $data['title'] = "Detalle proveedor";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] =  $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Proveedores|' . $nombre;
        $data['breadcrum_l'] = 'proveedores|proveedores/proveedor_detalle/'.$this->uri->segment(4);
        $data['active'] = 'proveedores';
        $data['main_content'] = 'tpov1/inicio/detalle_proveedor';

        $data['print_url_orden'] = base_url() . "index.php/tpov1/print_ci/print_ordenes_proveedor/";
        $data['link_descarga_table_orden'] = base_url() . "index.php/tpov1/exportar/ordenes_proveedor_table/" . $str;

        $data['print_url_contrato'] = base_url() . "index.php/tpov1/print_ci/print_contratos_proveedor/";
        $data['link_descarga_table_contrato'] = base_url() . "index.php/tpov1/exportar/contratos_proveedor_table/" . $str;

        $data['disponible'] = empty($proveedor) ? false : true;
        $data['proveedor'] = $proveedor;

        $data['texto_ayuda'] = array(
            'ejercicio' => 'Indica el a&ntilde;o del ejercicio presupuestario.',
            'trimestre' => 'Indica el trimestre que se reporta (enero – marzo, abril-junio, julio-septiembre,  octubre-diciembre ).',
            'proveedor' => 'Nombre o raz&oacute;n social del proveedor',
            'orden_compra' => 'Clave o n&uacute;mero de identificaci&oacute;n &uacute;nico de la orden de compra.',
            'campana' => 'Indica el nombre de la campa&ntilde;a o aviso institucional a la que pertenece.',
            'so_contratante' => 'Indica el nombre del sujeto obligado que celebra el contrato u orden de compra con el proveedor.',
            'so_solicitante' => 'Indica el nombre del sujeto que solicit&oacute; el producto o servicio aunque &eacute;ste no sea quien celebra el
            contrato u orden de compra con el proveedor (Ej. Sujeto obligado solicitante: Secretar&iacute;a de Cultura; sujeto obligado contratante: Coordinación General de Comunicaci&oacute;n Social).',
            'numero_contrato' => 'Clave o n&uacute;mero de identificaci&oacute;n &uacute;nico del contrato.',
            'monto_original' => 'Monto total del contrato, con I.V.A. incluido.',
            'monto_modificado' => 'Suma de los  montos de los convenios modificatorios.',
            'monto_total' => 'Suma del monto original del contrato, más el monto modificado.',
            'monto_pagado' => 'Monto pagado a la fecha del periodo publicado.'
        );
        $data['scripts'] = "<script type='text/javascript'>" .
                            "$(function(){". 
                                "init_tables();" .
                            "});" .
                            "</script>";

        $this->load->view('tpov1/includes/template', $data);
    }

    function getDatosProveedorContratos()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_proveedor'));
        
        $registros = $this->Tablas_model->get_contratos_proveedor($str);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function getDatosProveedorOrdenes()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_proveedor'));
        
        $registros = $this->Tablas_model->get_ordenes_proveedor($str);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function getDatosPorproveedores()
    {
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $strminimo = $this->Graficas_model->limpiar_Cadena($this->input->post('minimo'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);

        $minimo = $strminimo;
        if(empty($strminimo))
        {
            $val = $this->Graficas_model->get_monto_proveedor_minimo($ejercicio);
            $minimo = floatval($val['minimo']);
        } 

        $registros = $this->Graficas_model->get_datos_porproveedores($ejercicio, $minimo, $str);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }

    function getDatosPorproveedoresTable()
    {
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Generales_vp_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
        $registros = $this->Tablas_model->get_valores_tabla_proveedores($ejercicio);

        header('Content-type: application/json');

        echo json_encode( $registros );
    }
}

?>