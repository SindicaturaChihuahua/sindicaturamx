<?php


/**
 * Description of Catalogos
 *
 * INAI TPO
 * 
 */

if (!defined('BASEPATH')) 
{
    exit('No direct script access allowed');
}

class Campanas_avisos extends CI_Controller
{
     // Constructor que manda llamar la funcion is_logged_in
    function __construct()
    {
        parent::__construct();
        $this->is_logged_in();
    }

    // Funcion para revisar inicio de session 
    function is_logged_in() 
    {
        $is_logged_in = $this->session->userdata('is_logged_in');
        if (!isset($is_logged_in) || $is_logged_in != true) {
            redirect('tpoadminv1/cms');
        }
    }
    
    // Funcion para cerrar session
    function logout() 
    {
        $this->session->sess_destroy();
        $this->session->sess_create();
        redirect('/');
    }
	
    function permiso_administrador()
    {
        //Revisamos que el usuario sea administrador
        if($this->session->userdata('usuario_rol') != '1')
        {
            redirect('tpoadminv1/securecms/sin_permiso');
        }
    }

    function busqueda_coberturas() 
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
                
        $data['title'] = "Coberturas";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_coberturas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/coberturas';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_coberturas";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['coberturas'] = $this->Coberturas_model->dame_todas_coberturas();
        
        $data['path_file_csv'] = $this->Coberturas_model->descarga_coberturas();
        $data['name_file_csv'] = "coberturas.csv";

        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                        
                                    "$('#coberturas').dataTable({" .
                                        "'bPaginate': true," .
                                        "'bLengthChange': true," .
                                        "'bFilter': true," .
                                        "'bSort': true," .
                                        "'bInfo': true," .
                                        "'bAutoWidth': false," .
                                        "'columnDefs': [ " .
                                            "{ 'orderable': false, 'targets': [3,4,5] } " .
                                        "],". 
                                        "'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, 'Todo']], " .   //Paginacion
                                        "'oLanguage': { " .
                                            "'sSearch': 'B&uacute;squeda '," .
                                            "'sInfoFiltered': '(filtrado de un total de _MAX_ registros)'," .
                                            "'sInfo': 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros'," .
                                            "'sZeroRecords': 'No se encontraron resultados'," .
                                            "'EmptyTable': 'Ning&uacute;n dato disponible en esta tabla'," .
                                            "'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros'," .
                                            "'oPaginate': {" .
                                                "'sFirst': 'Primero'," .
                                                "'sLast': '&Uacute;ltimo'," .
                                                "'sNext': 'Siguiente'," .
                                                "'sPrevious': 'Anterior'" .
                                            "}," .
                                            "'sLengthMenu': '_MENU_ Registros por p&aacute;gina'" .
                                        "}" .
                                    "});" .
                                    "setTimeout(function() { " .
                                        "$('.alert').alert('close');" .
                                    "}, 3000);" .
                                "});" .
                            "</script>";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function get_cobertura()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $cobertura = $this->Coberturas_model->dame_cobertura_id($this->uri->segment(5));

        header('Content-type: application/json');
        
        echo json_encode( $cobertura );
    }

    function agregar_cobertura()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');

        $data['title'] = "Agregar cobertura";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_coberturas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_cobertura';

        $data['cobertura_nombre'] = '';
        $data['cobertura_estatus'] = 'null';

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_cobertura()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_campana_cobertura', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar cobertura";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_coberturas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_cobertura';
        
        $data['cobertura_nombre'] = $this->input->post('nombre_campana_cobertura');
        $data['cobertura_estatus'] = $this->input->post('active');
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Coberturas_model->agregar_cobertura();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "La cobertura " . $this->input->post('nombre_campana_cobertura') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La cobertura " . $this->input->post('nombre_campana_cobertura') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_coberturas');
            } 
        }
    }

    function editar_cobertura()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
                
        $data['title'] = "Editar cobertura";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_coberturas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_cobertura';
        
        $data['cobertura'] = $this->Coberturas_model->dame_cobertura_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_cobertura()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_campana_cobertura', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Coberturas";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_coberturas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_cobertura';
        
        $data['cobertura'] = $this->Coberturas_model->dame_cobertura_id($this->input->post('id_campana_cobertura'));

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Coberturas_model->editar_cobertura();
            if($editar == 1){
                $this->session->set_flashdata('exito', "La cobertura " . $this->input->post('nombre_campana_cobertura') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La cobertura " . $this->input->post('nombre_campana_cobertura') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_coberturas');
            }
        }
    }

    function eliminar_cobertura()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_campana_cobertura', 'tab_campana_aviso');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de campañas/avisos institucionales.");
        }else{
            $eliminar = $this->Coberturas_model->eliminar_cobertura($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }
        redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_coberturas');
    }

	function busqueda_tiposTO() 
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/TiposTO_model');
                
        $data['title'] = "Tipo de Tiempos Oficiales";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_tiposTO"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/tipoTO';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_tiposTO";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['tiposTO'] = $this->TiposTO_model->dame_todos_tiposTO();
        
        $data['path_file_csv'] = $this->TiposTO_model->descarga_tiposTO();
        $data['name_file_csv'] = "tiposTO.csv";

        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                        
                                    "$('#tiposTO').dataTable({" .
                                        "'bPaginate': true," .
                                        "'bLengthChange': true," .
                                        "'bFilter': true," .
                                        "'bSort': true," .
                                        "'bInfo': true," .
                                        "'bAutoWidth': false," .
                                        "'columnDefs': [ " .
                                            "{ 'orderable': false, 'targets': [3,4,5] } " .
                                        "],". 
                                        "'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, 'Todo']], " .   //Paginacion
                                        "'oLanguage': { " .
                                            "'sSearch': 'B&uacute;squeda '," .
                                            "'sInfoFiltered': '(filtrado de un total de _MAX_ registros)'," .
                                            "'sInfo': 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros'," .
                                            "'sZeroRecords': 'No se encontraron resultados'," .
                                            "'EmptyTable': 'Ning&uacute;n dato disponible en esta tabla'," .
                                            "'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros'," .
                                            "'oPaginate': {" .
                                                "'sFirst': 'Primero'," .
                                                "'sLast': '&Uacute;ltimo'," .
                                                "'sNext': 'Siguiente'," .
                                                "'sPrevious': 'Anterior'" .
                                            "}," .
                                            "'sLengthMenu': '_MENU_ Registros por p&aacute;gina'" .
                                        "}" .
                                    "});" .
                                    "setTimeout(function() { " .
                                        "$('.alert').alert('close');" .
                                    "}, 3000);" .
                                "});" .
                            "</script>";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function get_tipoTO()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/TiposTO_model');
        $tipoTO = $this->TiposTO_model->dame_tipoTO_id($this->uri->segment(5));

        header('Content-type: application/json');
        
        echo json_encode( $tipoTO );
    }

    function agregar_tipoTO()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/TiposTO_model');

        $data['title'] = "Agregar Tipo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_tiposTO"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_tipoTO';

        $data['tipoTO_nombre'] = '';
        $data['tipoTO_estatus'] = 'null';

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_tipoTO()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/TiposTO_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_campana_tipoTO', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar tipo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_tiposTO"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_tipoTO';
        
        $data['tipoTO_nombre'] = $this->input->post('nombre_campana_tipoTO');
        $data['tipoTO_estatus'] = $this->input->post('active');
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->TiposTO_model->agregar_tipoTO();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El tipo " . $this->input->post('nombre_campana_tipoTO') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El tipo " . $this->input->post('nombre_campana_tipoTO') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_tiposTO');
            } 
        }
    }

    function editar_tipoTO()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/TiposTO_model');
                
        $data['title'] = "Editar tipo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_tiposTO"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_tipoTO';
        
        $data['tipoTO'] = $this->TiposTO_model->dame_tipoTO_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_tipoTO()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/TiposTO_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_campana_tipoTO', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Tipos";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_tiposTO"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_tipoTO';
        
        $data['tipoTO'] = $this->TiposTO_model->dame_tipoTO_id($this->input->post('id_campana_tipoTO'));

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->TiposTO_model->editar_tipoTO();
            if($editar == 1){
                $this->session->set_flashdata('exito', "EL tipo " . $this->input->post('nombre_campana_tipoTO') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El tipo " . $this->input->post('nombre_campana_tipoTO') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_tiposTO');
            }
        }
    }

    function eliminar_tipoTO()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/TiposTO_model');
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_campana_tipoTO', 'tab_campana_aviso');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de campañas/avisos institucionales.");
        }else{
            $eliminar = $this->TiposTO_model->eliminar_tipoTO($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }
        redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_tiposTO');
    }


    function busqueda_objetivos()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
                
        $data['title'] = "Objetivos institucionales";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_objetivos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/objetivos';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_objetivos";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['objetivos'] = $this->Coberturas_model->dame_todos_objetivos();
        $data['path_file_csv'] = $this->Coberturas_model->descarga_objetivos();
        $data['name_file_csv'] = "objetivos_institucionales.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#objetivos').dataTable({" .
                                    "'bPaginate': true," .
                                    "'bLengthChange': true," .
                                    "'bFilter': true," .
                                    "'bSort': true," .
                                    "'bInfo': true," .
                                    "'bAutoWidth': false," .
                                    "'columnDefs': [ " .
                                            "{ 'orderable': false, 'targets': [3,4,5] } " .
                                    "],".
                                    "'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, 'Todo']], " .   //Paginacion
                                    "'oLanguage': { " .
                                        "'sSearch': 'B&uacute;squeda '," .
                                        "'sInfoFiltered': '(filtrado de un total de _MAX_ registros)'," .
                                        "'sInfo': 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros'," .
                                        "'sZeroRecords': 'No se encontraron resultados'," .
                                        "'EmptyTable': 'Ning&uacute;n dato disponible en esta tabla'," .
                                        "'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros'," .
                                        "'oPaginate': {" .
                                            "'sFirst': 'Primero'," .
                                            "'sLast': '&Uacute;ltimo'," .
                                            "'sNext': 'Siguiente'," .
                                            "'sPrevious': 'Anterior'" .
                                        "}," .
                                        "'sLengthMenu': '_MENU_ Registros por p&aacute;gina'" .
                                    "}" .
                                "});" .
                                "setTimeout(function() { " .
                                    "$('.alert').alert('close');" .
                                "}, 3000);" .
                            "});" .
                            "</script>";
        
        $this->load->view('tpoadminv1/includes/template', $data);
        
    }

    function agregar_objetivo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');

        $data['title'] = "Agregar objetivo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_objetivos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_objetivo';

        $data['objetivo_nombre'] = '';
        $data['objetivo_estatus'] = 'null';

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_objetivo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('campana_objetivo', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar objetivo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_objetivos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_objetivo';
        
        $data['objetivo_nombre'] = $this->input->post('campana_objetivo');
        $data['objetivo_estatus'] = $this->input->post('active');
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Coberturas_model->agregar_objetivo();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El objetivo " . $this->input->post('campana_objetivo') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El objetivo " . $this->input->post('campana_objetivo') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_objetivos');
            } 
        }
    }

    function editar_objetivo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');

        $data['title'] = "Editar objetivo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_objetivos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_objetivo';

        $data['objetivo'] = $this->Coberturas_model->dame_objetivo_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_objetivo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('campana_objetivo', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar objetivo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_objetivos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_objetivo';
        
        
        $data['objetivo'] = $this->Coberturas_model->dame_objetivo_id($this->input->post('id_campana_objetivo'));
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Coberturas_model->editar_objetivo();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El objetivo " . $this->input->post('campana_objetivo') . " se ha editado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El objetivo " . $this->input->post('campana_objetivo') . " no se pudo editar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_objetivos');
            } 
        }
    }

    function eliminar_objetivo()
    {   
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_campana_objetivo', 'tab_campana_aviso');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de campañas/avisos institucionales.");
        }else{
            $eliminar = $this->Coberturas_model->eliminar_objetivo($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }
        redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_objetivos');
    }

    function busqueda_tipos()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        

        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
                    
        $data['title'] = "Tipos";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_tipos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/tipos';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_tipos";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['tipos'] = $this->Coberturas_model->dame_todos_tipos();
        $data['path_file_csv'] = $this->Coberturas_model->descarga_tipos();
        $data['name_file_csv'] = "tipos.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#tipos').dataTable({" .
                                    "'bPaginate': true," .
                                    "'bLengthChange': true," .
                                    "'bFilter': true," .
                                    "'bSort': true," .
                                    "'bInfo': true," .
                                    "'bAutoWidth': false," .
                                    "'columnDefs': [ " .
                                            "{ 'orderable': false, 'targets': [3,4,5] } " .
                                    "],".
                                    "'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, 'Todo']], " .   //Paginacion
                                    "'oLanguage': { " .
                                        "'sSearch': 'B&uacute;squeda '," .
                                        "'sInfoFiltered': '(filtrado de un total de _MAX_ registros)'," .
                                        "'sInfo': 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros'," .
                                        "'sZeroRecords': 'No se encontraron resultados'," .
                                        "'EmptyTable': 'Ning&uacute;n dato disponible en esta tabla'," .
                                        "'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros'," .
                                        "'oPaginate': {" .
                                            "'sFirst': 'Primero'," .
                                            "'sLast': '&Uacute;ltimo'," .
                                            "'sNext': 'Siguiente'," .
                                            "'sPrevious': 'Anterior'" .
                                        "}," .
                                        "'sLengthMenu': '_MENU_ Registros por p&aacute;gina'" .
                                    "}" .
                                "});" .
                                "setTimeout(function() { " .
                                    "$('.alert').alert('close');" .
                                "}, 3000);" .
                            "});" .
                            "</script>";
        
        $this->load->view('tpoadminv1/includes/template', $data);

    }

    function agregar_tipo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');

        $data['title'] = "Agregar tipo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_tipos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_tipo';

        $data['tipo_nombre'] = '';
        $data['tipo_estatus'] = 'null';

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_tipo()
    {   
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_campana_tipo', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar tipo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_tipos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_tipo';
        
        $data['tipo_nombre'] = $this->input->post('nombre_campana_tipo');
        $data['tipo_estatus'] = $this->input->post('active');
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Coberturas_model->agregar_tipo();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El tipo " . $this->input->post('nombre_campana_tipo') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El tipo " . $this->input->post('nombre_campana_tipo') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_tipos');
            } 
        }
    }

    function editar_tipo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');

        $data['title'] = "Editar tipo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_tipos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_tipo';

        $data['tipo'] = $this->Coberturas_model->dame_tipo($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_tipo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_campana_tipo', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar objetivo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_tipos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_tipo';
        
        $data['tipo'] = $this->Coberturas_model->dame_tipo($this->input->post('id_campana_tipo'));
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Coberturas_model->editar_tipo();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El tipo " . $this->input->post('nombre_campana_tipo') . " se ha editado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El tipo " . $this->input->post('nombre_campana_tipo') . " no se pudo editar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_tipos');
            } 
        }
    }

    function eliminar_tipo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_campana_tipo', 'tab_campana_aviso');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de campañas/avisos institucionales.");
        }else{
            $eliminar = $this->Coberturas_model->eliminar_tipo($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else if($eliminar == 2){
                $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que tiene dependencias en Subtipos");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }
        redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_tipos');
    }

    function busqueda_subtipos()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
                    
        $data['title'] = "Subtipos";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_subtipos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/subtipos';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_subtipos";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['subtipos'] = $this->Coberturas_model->dame_todos_subtipos();
        $data['path_file_csv'] = $this->Coberturas_model->descarga_subtipos();
        $data['name_file_csv'] = "subtipos.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#subtipos').dataTable({" .
                                    "'bPaginate': true," .
                                    "'bLengthChange': true," .
                                    "'bFilter': true," .
                                    "'bSort': true," .
                                    "'bInfo': true," .
                                    "'bAutoWidth': false," .
                                    "'columnDefs': [ " .
                                            "{ 'orderable': false, 'targets': [4,5,6] } " .
                                    "],".
                                    "'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, 'Todo']], " .   //Paginacion
                                    "'oLanguage': { " .
                                        "'sSearch': 'B&uacute;squeda '," .
                                        "'sInfoFiltered': '(filtrado de un total de _MAX_ registros)'," .
                                        "'sInfo': 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros'," .
                                        "'sZeroRecords': 'No se encontraron resultados'," .
                                        "'EmptyTable': 'Ning&uacute;n dato disponible en esta tabla'," .
                                        "'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros'," .
                                        "'oPaginate': {" .
                                            "'sFirst': 'Primero'," .
                                            "'sLast': '&Uacute;ltimo'," .
                                            "'sNext': 'Siguiente'," .
                                            "'sPrevious': 'Anterior'" .
                                        "}," .
                                        "'sLengthMenu': '_MENU_ Registros por p&aacute;gina'" .
                                    "}" .
                                "});" .
                                "setTimeout(function() { " .
                                    "$('.alert').alert('close');" .
                                "}, 3000);" .
                            "});" .
                            "</script>";
        
        $this->load->view('tpoadminv1/includes/template', $data);

    }

    function agregar_subtipo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');

        $data['title'] = "Agregar subtipo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_subtipos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_subtipo';

        $data['subtipo_nombre'] = '';
        $data['subtipo_estatus'] = 'null';
        $data['tipos'] = $this->Coberturas_model->dame_todos_tipos();
        $data['id_tipo'] = 'null';

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }
    
    function validate_agregar_subtipo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id_campana_tipo', 'Seleccione un tipo', 'required');
        $this->form_validation->set_rules('nombre_campana_subtipo', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar subtipo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_subtipos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_subtipo';
        
        $data['subtipo_nombre'] = $this->input->post('nombre_campana_subtipo');
        $data['subtipo_estatus'] = $this->input->post('active');
        $data['tipos'] = $this->Coberturas_model->dame_todos_tipos();
        $data['id_tipo'] = $this->input->post('id_campana_tipo');
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Coberturas_model->agregar_subtipo();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El subtipo " . $this->input->post('nombre_campana_subtipo') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El subtipo " . $this->input->post('nombre_campana_subtipo') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_subtipos');
            } 
        }
    }

    function editar_subtipo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');

        $data['title'] = "Editar subtipo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_subtipos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_subtipo';

        $data['subtipo'] = $this->Coberturas_model->dame_subtipo_id($this->uri->segment(5));
        $data['tipos'] = $this->Coberturas_model->dame_todos_tipos();

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_subtipo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id_campana_tipo', 'Seleccione un tipo', 'required');
        $this->form_validation->set_rules('nombre_campana_subtipo', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar subtipo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_subtipos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_subtipo';
        
        $data['tipos'] = $this->Coberturas_model->dame_todos_tipos();
        $data['subtipo'] = $this->Coberturas_model->dame_subtipo_id($this->input->post('id_campana_subtipo'));
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Coberturas_model->editar_subtipo();
            if($editar == 1){
                $this->session->set_flashdata('exito', "El subtipo " . $this->input->post('nombre_campana_subtipo') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El subtipo " . $this->input->post('nombre_campana_subtipo') . " no se pudo editar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_subtipos');
            } 
        }
    }

    function eliminar_subtipo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_campana_subtipo', 'tab_campana_aviso');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de campañas/avisos institucionales.");
        }else{
            $eliminar = $this->Coberturas_model->eliminar_subtipo($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }
        redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_subtipos');
    }

    function busqueda_temas()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
                    
        $data['title'] = "Temas";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_temas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/temas';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_temas";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['temas'] = $this->Coberturas_model->dame_todos_temas();
        $data['path_file_csv'] = $this->Coberturas_model->descarga_temas();
        $data['name_file_csv'] = "temas.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#temas').dataTable({" .
                                    "'bPaginate': true," .
                                    "'bLengthChange': true," .
                                    "'bFilter': true," .
                                    "'bSort': true," .
                                    "'bInfo': true," .
                                    "'bAutoWidth': false," .
                                    "'columnDefs': [ " .
                                        "{ 'orderable': false, 'targets': [3,4,5] } " .
                                    "],".
                                    "'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, 'Todo']], " .   //Paginacion
                                    "'oLanguage': { " .
                                        "'sSearch': 'B&uacute;squeda '," .
                                        "'sInfoFiltered': '(filtrado de un total de _MAX_ registros)'," .
                                        "'sInfo': 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros'," .
                                        "'sZeroRecords': 'No se encontraron resultados'," .
                                        "'EmptyTable': 'Ning&uacute;n dato disponible en esta tabla'," .
                                        "'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros'," .
                                        "'oPaginate': {" .
                                            "'sFirst': 'Primero'," .
                                            "'sLast': '&Uacute;ltimo'," .
                                            "'sNext': 'Siguiente'," .
                                            "'sPrevious': 'Anterior'" .
                                        "}," .
                                        "'sLengthMenu': '_MENU_ Registros por p&aacute;gina'" .
                                    "}" .
                                "});" .
                                "setTimeout(function() { " .
                                    "$('.alert').alert('close');" .
                                "}, 3000);" .
                            "});" .
                            "</script>";
        
        $this->load->view('tpoadminv1/includes/template', $data);

    }

    function agregar_tema()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');

        $data['title'] = "Agregar tema";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_temas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_tema';

        $data['tema_nombre'] = '';
        $data['tema_estatus'] = 'null';

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_tema()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_campana_tema', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar tema";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_temas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_tema';
        
        $data['tema_nombre'] = $this->input->post('nombre_campana_tema');
        $data['tema_estatus'] = $this->input->post('active');
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Coberturas_model->agregar_tema();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El tema " . $this->input->post('nombre_campana_tema') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El tema " . $this->input->post('nombre_campana_tema') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_temas');
            } 
        }
    }

    function editar_tema()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Coberturas_model');

        $data['title'] = "Editar tema";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_temas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_tema';

        $data['tema'] = $this->Coberturas_model->dame_tema_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_tema()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_campana_tema', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar tema";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'campanas_avisos'; // class="active"
        $data['optionactive'] = "busqueda_temas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_tema';
        
        $data['tema'] = $this->Coberturas_model->dame_tema_id($this->input->post('id_campana_tema'));
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Coberturas_model->editar_tema();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El tema " . $this->input->post('nombre_campana_tema') . " se ha editado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El tema " . $this->input->post('nombre_campana_tema') . " no se pudo editar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_temas');
            } 
        }
    }

    function eliminar_tema()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Coberturas_model');
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_campana_tema', 'tab_campana_aviso');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de campañas/avisos institucionales.");
        }else{
            $eliminar = $this->Coberturas_model->eliminar_tema($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }
        redirect('/tpoadminv1/catalogos/campanas_avisos/busqueda_temas');
    }

}
