<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Campana_aviso extends CI_Controller
{
    function index() 
    {
        $this->load->model('tpov1/campanas_v1/Graficas_model');
        $this->load->model('tpov1/Generales_vp_model');

        $data['link_descarga'] = base_url() . 'index.php/tpov1/exportar/campanas';
        $data['texto_ayuda'] = $this->Generales_vp_model->get_texto_ayuda();
        $data['indicadores_ayuda'] = $this->Generales_vp_model->get_indicadores_ayuda();

        $data['title'] = "Campañas y avisos institucionales";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] = $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Campañas';
        $data['breadcrum_l'] = 'campana_aviso';
        $data['active'] = 'campana_aviso';
        $data['main_content'] = 'tpov1/inicio/campana_aviso';

        $data['ejercicios'] = $this->Graficas_model->dame_todos_ejercicios(true);
        $data['id_ejercicio_ultimo'] = $this->Graficas_model->get_ultimo_id_ejercicio();

        $data['print_url_campanas'] = base_url() . "index.php/tpov1/print_ci/print_campanas/";
        $data['link_descarga_table_campanas'] = base_url() . "index.php/tpov1/exportar/campanas_table";

        $data['print_url_avisos'] = base_url() . "index.php/tpov1/print_ci/print_avisos/";
        $data['link_descarga_table_avisos'] = base_url() . "index.php/tpov1/exportar/avisos_table";
        
        $serviceSide = base_url() . "index.php/tpov1/campanas/getValoresTablaCampanas";

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

    function preparar_exportacion_campanas()
    {
        //Validamos que el usuario tenga acceso
        $this->permiso_capturista();
        
        $this->load->model('tpoadminv1/capturista/Facturas_model');
        $path = $this->Facturas_model->descarga_facturas();

        header('Content-type: application/json');
        
        echo json_encode( base_url() . $path );
    }

    function getCampanas()
    {
        $this->load->model('tpov1/campanas_v1/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 
        $registro = $this->Graficas_model->getCampanasDetalles($this->input->post('id_ejercicio'));
        
        header('Content-type: application/json');
        echo json_encode( $registro );
    }

    function det_campana_avisos()
    {
        $this->load->model('tpov1/campanas_v1/Graficas_model');
        $detalle = $this->Graficas_model->jsonCA_SO($this->input->post('id_ejercicio'));

        header('Content-type: application/json');
        echo $detalle;
        //print_r($detalle);
    }

    function getValoresTablaCampanas()
    {
        $this->load->model('tpov1/campanas_v1/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 
        $registros = $this->Graficas_model->get_desglose_campanas_avisos($ejercicio);

        header('Content-type: application/json');
        echo json_encode( $registros );
    }

    function getValoresTablaAvisos()
    {
        $this->load->model('tpov1/campanas_v1/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->input->post('id_ejercicio'));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 
        $registros = $this->Graficas_model->get_desglose_avisos($ejercicio);

        if($registros != '')
        {
            header('Content-type: application/json');
            echo json_encode( $registros );
        }
        /*
        else{
            header('Content-type: application/json');
            var json6 = '{"myArray": []}';
        }
        */

        
    }

    function campana_detalle()
    {
        $this->load->model('tpov1/campanas_v1/Graficas_model');
        $this->load->model('tpoadminv1/campanas/Campana_model');
        $this->load->model('tpov1/Generales_vp_model');

        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));

        $campana = $this->Campana_model->dame_campana_id($str);

        if($campana != '')
        {
            $disponible = true;
        }
        else
        {
            $disponible = false;
        }

        $nombre = empty($campana) ? '' : $campana['nombre_campana_aviso'];

        $data['title'] = "Detalle Campana";
        $data['heading'] = "Fecha de actualizaci&oacute;n: ";
        $data['heading_s'] = $this->Generales_vp_model->get_fecha_actualizacion();
        $data['breadcrum'] = 'Campaña o aviso Institucional|' . $nombre;
        $data['breadcrum_l'] = 'campana_aviso|campana_aviso/campana_detalle/'.$this->uri->segment(4);
        $data['active'] = 'campana_aviso';
        $data['main_content'] = 'tpov1/inicio/detalle_campana';
        //$data['disponible'] = empty($campana) ? false : true;
        $data['disponible'] = $disponible;

        $data['print_url_servicio'] = base_url() . "index.php/tpov1/print_ci/print_campanas_servicios/";
        $data['link_descarga_table_servicio'] = base_url() . "index.php/tpov1/exportar/campanas_servicios_table/" . $str;

        $data['print_url_otros'] = base_url() . "index.php/tpov1/print_ci/print_campanas_otros/";
        $data['link_descarga_table_otros'] = base_url() . "index.php/tpov1/exportar/campanas_otros_table/" . $str;

        if(isset($campana) && !empty($campana)){
            $linkso_c =  base_url() . "index.php/tpov1/sujetos_obligados/detalle/" . $campana['id_so_contratante'];
            $linkso_s =  base_url() . "index.php/tpov1/sujetos_obligados/detalle/" . $campana['id_so_solicitante'];
            $data['link_so_contratante'] = " <a href='" . $linkso_c . "' target='_blank'>(ver detalle)</a>";
            $data['link_so_solicitante'] = " <a href='" . $linkso_s . "' target='_blank'>(ver detalle)</a>";
        }
        
        $data['campana'] = $campana;
        $data['monto_total'] = $this->Graficas_model->get_desglose_detalle_campanas_avisos($this->uri->segment(4));
        $data['niveles'] = $this->Campana_model->dame_niveles_campana_id($this->uri->segment(4));
        $data['edades'] = $this->Campana_model->dame_edades_campana_id($this->uri->segment(4));
        $data['educacion'] = $this->Campana_model->dame_niveles_educativos_campana_id($this->uri->segment(4));
        $data['lugares'] = $this->Campana_model->dame_lugares_campana_id($this->uri->segment(4));
        $data['sexos'] = $this->Campana_model->dame_sexos_campana_id($this->uri->segment(4));
        $data['audios'] = $this->Campana_model->dame_audios_campana_id($this->uri->segment(4));
        $data['videos'] = $this->Campana_model->dame_videos_campana_id($this->uri->segment(4));
        $data['imagenes'] = $this->Campana_model->dame_imagenes_campana_id($this->uri->segment(4));
        $data['servicios_difusion'] = $this->Graficas_model->dame_serv_dif_campana_id($this->uri->segment(4));
        $data['otros_servicios_difusion'] = $this->Graficas_model->dame_otros_serv_dif_campana_id($this->uri->segment(4));
        $data['texto_ayuda'] = array(
                                'tipo' => 'Indica si se trata de una Campaña o de un Aviso Institucional.',
                                'subtipo' => 'Indicar el subtipo de campaña o aviso institucional, según sea el caso.',
                                'nombre_campana_aviso' => 'T&iacute;tulo de la campa&ntilde;a o aviso institucional.',
                                'ejercicio' => 'A&ntilde;o en que se lleva a cabo la difusi&oacute;n de la campa&ntilde;a.',
                                'trimestre' => 'Trimeste en que se lleva a cabo la difusión de la campaña.',
                                'fecha_inicio_periodo' => 'Fecha de inicio del periodo que se informa',
            					'fecha_termino_periodo' => 'Fecha de termino del periodo que se informa',
                                'autoridad' => 'Autoridad que proporcionó la clave',
                                'soc' => 'Indica el nombre del sujeto obligado que celebra el contrato u orden de compra con el proveedor',
                                'sos' => 'Indica el nombre del sujeto que solicitó el producto o servicio aunque éste no sea quien celebra el contrato u orden de compra con el proveedor (Ej. Sujeto Obligado solicitante: Secretaría de Cultura; sujeto obligado contratante: Coordinación General de Comunicación Social',
                                'tema' => 'Indica el tema de la campaña o aviso institucional (Ej. Salud, Educacion, etc.)',
                                'objetivo_institucional' => 'Objetivo institucional de la campaña o aviso institucional.',
                                'objetivo_comunicacion' => 'Objetivo de comunicación de la campaña o aviso institucional.',
                                'cobertura' => 'Alcance geográfico de la campaña o aviso institucional.',
                                'ambito_geografico' => 'Descripción detallada de la campaña o aviso institucional.',
                                'fecha_inicio' => 'Fecha de inicio de la transmisión de la campaña o aviso institucional.',
                                'fecha_termino' => 'Fecha de término de la transmisión de la campaña o aviso institucional.',
                                'tiempo_oficial' => 'Indica si se utiliz&oacute; o no, tiempo oficial en la transmisi&oacute;n de esa campa&ntilde;a o aviso institucional.',
                                'monto_tiempo' => 'Monto total del tiempo de estado o tiempo fiscal consumidos',
                                'tipoTO' => '',
            					'mensajeTO' => '',
                                'fecha_inicio_to' => 'Fecha de inicio del uso de tiempo oficial de la campa&ntilde;a o aviso institucional',
                                'fecha_termino_to' => 'Fecha de termino del uso de tiempo oficial de la campa&ntilde;a o aviso institucional',
                                'segob' => 'Hiperv&iacute;nculo a la informaci&oacute;n sobre la utilizaci&oacute;n de Tiempo Oficial, publicada por la Direcci&oacute;n General de Radio, Televisi&oacute;n y Cinematograf&iacute;a, adscrita a la Secretar&iacute;a de Gobernaci&oacute;n',
                                'pacs' => 'Nombre o denominaci&oacute;n del documento del programa anual de comunicaci&oacute;n social.',
                                'fecha_publicacion' => 'Fecha en la que se public&oacute; en el Diario Oficial de la Federaci&oacute;n, peri&oacute;dico o gaceta, o portal de Internet institucional correspondiente ',
                                'evaluacion' => 'Evaluaci&oacute;n de la campa&ntilde;a y/o aviso institucional',
                                'documento' => 'Documento de evaluaci&oacute;n',
                                'active' => 'Indica el estado de la informaci&oacute;n "Activa" o "Inactiva".',
                                'fecha_validacion' => 'Fecha de validaci&oacute;n',
                                'area_responsable' => '&Aacute:rea responsable',
                                'anio' => 'A&ntilde;o',
                                'fecha_actualizacion' => 'Fecha de actualización',
                                'evaluacion' => '',
                                'nota' => 'Nota',
                                'monto_total_ejercido' => 'Monto total ejercido',
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


        $data['scripts'] = "<script type='text/javascript'>
                            $(function(){
                                init_tables();
                            });

                            $(function () {

                                $('#nivel').dataTable({
                                    bPaginate: true,
                                    bLengthChange: true,
                                    bFilter: true,
                                    bSort: true,
                                    bInfo: true,
                                    bAutoWidth: false,
                                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                                    oLanguage: {
                                        sSearch: 'Búsqueda ',
                                        sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
                                        sInfo: 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros',
                                        sZeroRecords: 'No se encontraron resultados',
                                        EmptyTable: 'Ningún dato disponible en esta tabla',
                                        sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                                        oPaginate: {
                                            sFirst: 'Primero',
                                            sLast: 'Último',
                                            sNext: 'Siguiente',
                                            sPrevious: 'Anterior'
                                        },
                                        sLengthMenu: '_MENU_ Registros por p&aacute;gina'
                                    },
                                });
                                
                                $('#edad').dataTable({
                                    bPaginate: true,
                                    bLengthChange: true,
                                    bFilter: true,
                                    bSort: true,
                                    bInfo: true,
                                    bAutoWidth: false,
                                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                                    oLanguage: {
                                        sSearch: 'Búsqueda ',
                                        sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
                                        sInfo: 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros',
                                        sZeroRecords: 'No se encontraron resultados',
                                        EmptyTable: 'Ningún dato disponible en esta tabla',
                                        sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                                        oPaginate: {
                                            sFirst: 'Primero',
                                            sLast: 'Último',
                                            sNext: 'Siguiente',
                                            sPrevious: 'Anterior'
                                        },
                                        sLengthMenu: '_MENU_ Registros por p&aacute;gina'
                                    },
                                });

                                $('#lugares').dataTable({
                                    bPaginate: true,
                                    bLengthChange: true,
                                    bFilter: true,
                                    bSort: true,
                                    bInfo: true,
                                    bAutoWidth: false,
                                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                                    oLanguage: {
                                        sSearch: 'Búsqueda ',
                                        sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
                                        sInfo: 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros',
                                        sZeroRecords: 'No se encontraron resultados',
                                        EmptyTable: 'Ningún dato disponible en esta tabla',
                                        sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                                        oPaginate: {
                                            sFirst: 'Primero',
                                            sLast: 'Último',
                                            sNext: 'Siguiente',
                                            sPrevious: 'Anterior'
                                        },
                                        sLengthMenu: '_MENU_ Registros por p&aacute;gina'
                                    },
                                });


                                $('#educacion').dataTable({
                                    bPaginate: true,
                                    bLengthChange: true,
                                    bFilter: true,
                                    bSort: true,
                                    bInfo: true,
                                    bAutoWidth: false,
                                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                                    oLanguage: {
                                        sSearch: 'Búsqueda ',
                                        sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
                                        sInfo: 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros',
                                        sZeroRecords: 'No se encontraron resultados',
                                        EmptyTable: 'Ningún dato disponible en esta tabla',
                                        sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                                        oPaginate: {
                                            sFirst: 'Primero',
                                            sLast: 'Último',
                                            sNext: 'Siguiente',
                                            sPrevious: 'Anterior'
                                        },
                                        sLengthMenu: '_MENU_ Registros por p&aacute;gina'
                                    },
                                });


                                $('#sexo').dataTable({
                                    bPaginate: true,
                                    bLengthChange: true,
                                    bFilter: true,
                                    bSort: true,
                                    bInfo: true,
                                    bAutoWidth: false,
                                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                                    oLanguage: {
                                        sSearch: 'Búsqueda ',
                                        sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
                                        sInfo: 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros',
                                        sZeroRecords: 'No se encontraron resultados',
                                        EmptyTable: 'Ningún dato disponible en esta tabla',
                                        sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                                        oPaginate: {
                                            sFirst: 'Primero',
                                            sLast: 'Último',
                                            sNext: 'Siguiente',
                                            sPrevious: 'Anterior'
                                        },
                                        sLengthMenu: '_MENU_ Registros por p&aacute;gina'
                                    },
                                });

                                $('#audios').dataTable({
                                    bPaginate: true,
                                    bLengthChange: true,
                                    bFilter: true,
                                    bSort: true,
                                    bInfo: true,
                                    bAutoWidth: false,
                                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                                    oLanguage: {
                                        sSearch: 'Búsqueda ',
                                        sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
                                        sInfo: 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros',
                                        sZeroRecords: 'No se encontraron resultados',
                                        EmptyTable: 'Ningún dato disponible en esta tabla',
                                        sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                                        oPaginate: {
                                            sFirst: 'Primero',
                                            sLast: 'Último',
                                            sNext: 'Siguiente',
                                            sPrevious: 'Anterior'
                                        },
                                        sLengthMenu: '_MENU_ Registros por p&aacute;gina'
                                    },
                                });

                                $('#imagenes').dataTable({
                                    bPaginate: true,
                                    bLengthChange: true,
                                    bFilter: true,
                                    bSort: true,
                                    bInfo: true,
                                    bAutoWidth: false,
                                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                                    oLanguage: {
                                        sSearch: 'Búsqueda ',
                                        sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
                                        sInfo: 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros',
                                        sZeroRecords: 'No se encontraron resultados',
                                        EmptyTable: 'Ningún dato disponible en esta tabla',
                                        sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                                        oPaginate: {
                                            sFirst: 'Primero',
                                            sLast: 'Último',
                                            sNext: 'Siguiente',
                                            sPrevious: 'Anterior'
                                        },
                                        sLengthMenu: '_MENU_ Registros por p&aacute;gina'
                                    },
                                });

                                $('#videos').dataTable({
                                    bPaginate: true,
                                    bLengthChange: true,
                                    bFilter: true,
                                    bSort: true,
                                    bInfo: true,
                                    bAutoWidth: false,
                                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                                    oLanguage: {
                                        sSearch: 'Búsqueda ',
                                        sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
                                        sInfo: 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros',
                                        sZeroRecords: 'No se encontraron resultados',
                                        EmptyTable: 'Ningún dato disponible en esta tabla',
                                        sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                                        oPaginate: {
                                            sFirst: 'Primero',
                                            sLast: 'Último',
                                            sNext: 'Siguiente',
                                            sPrevious: 'Anterior'
                                        },
                                        sLengthMenu: '_MENU_ Registros por p&aacute;gina'
                                    },
                                });


                                $('#servicios_difusion').dataTable({
                                    bPaginate: true,
                                    bLengthChange: true,
                                    bFilter: true,
                                    bSort: true,
                                    bInfo: true,
                                    bAutoWidth: false,
                                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                                    oLanguage: {
                                        sSearch: 'Búsqueda ',
                                        sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
                                        sInfo: 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros',
                                        sZeroRecords: 'No se encontraron resultados',
                                        EmptyTable: 'Ningún dato disponible en esta tabla',
                                        sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                                        oPaginate: {
                                            sFirst: 'Primero',
                                            sLast: 'Último',
                                            sNext: 'Siguiente',
                                            sPrevious: 'Anterior'
                                        },
                                        sLengthMenu: '_MENU_ Registros por p&aacute;gina'
                                    },
                                    'footerCallback': function(row, data, start, end, display){
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[\$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };

                                        // Total over this page
                                        pageTotal8 = api
                                            .column( 8 )
                                            .data()
                                            .reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                            }, 0 );

                                        var colum_8 = '$' + parseFloat(pageTotal8, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString(); 
                                        
                                        // Update footer
                                        $( api.column( 8 ).footer() ).html(
                                            colum_8
                                        );
                                    }
                                });

                                $('#otros_servicios_difusion').dataTable({
                                    bPaginate: true,
                                    bLengthChange: true,
                                    bFilter: true,
                                    bSort: true,
                                    bInfo: true,
                                    bAutoWidth: false,
                                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                                    oLanguage: {
                                        sSearch: 'Búsqueda ',
                                        sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
                                        sInfo: 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros',
                                        sZeroRecords: 'No se encontraron resultados',
                                        EmptyTable: 'Ningún dato disponible en esta tabla',
                                        sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                                        oPaginate: {
                                            sFirst: 'Primero',
                                            sLast: 'Último',
                                            sNext: 'Siguiente',
                                            sPrevious: 'Anterior'
                                        },
                                        sLengthMenu: '_MENU_ Registros por p&aacute;gina'
                                    },
                                    'footerCallback': function(row, data, start, end, display){
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[\$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };

                                        // Total over this page
                                        pageTotal8 = api
                                            .column( 8 )
                                            .data()
                                            .reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                            }, 0 );

                                        var colum_8 = '$' + parseFloat(pageTotal8, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString(); 
                                        
                                        // Update footer
                                        $( api.column( 8 ).footer() ).html(
                                            colum_8
                                        );
                                    }
                                });
                            });

                        </script>";

        $this->load->view('tpov1/includes/template', $data);
    }

}