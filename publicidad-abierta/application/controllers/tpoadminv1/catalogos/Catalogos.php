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

class Catalogos extends CI_Controller
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

    function busqueda_atribuciones()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Funciones";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_atribuciones"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/atribuciones';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_atribuciones";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['atribuciones'] = $this->Catalogos_model->dame_todas_atribuciones();
        $data['path_file_csv'] = $this->Catalogos_model->descarga_atribuciones();
        $data['name_file_csv'] = "funciones.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#atribuciones').dataTable({" .
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

    function agregar_atribucion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar funci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_atribuciones"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_atribucion';

    
        $data['atribucion_nombre'] = '';
        $data['atribucion_estatus'] = 'null';
        

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_atribucion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_so_atribucion', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar funci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_atribuciones"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_atribucion';
        
        $data['atribucion_nombre'] = $this->input->post('nombre_so_atribucion');
        $data['atribucion_estatus'] = $this->input->post('active');
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_atribucion();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "La funci&oacute;n " . $this->input->post('nombre_so_atribucion') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La funci&oacute;n " . $this->input->post('nombre_so_atribucion') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_atribuciones');
            } 
        }
    }

    function editar_atribucion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar funci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_atribuciones"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_atribucion';
        
        $data['atribucion'] = $this->Catalogos_model->dame_atribucion_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_atribucion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_so_atribucion', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar funci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_atribuciones"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_atribucion';
        
        $data['atribucion'] = $this->Catalogos_model->dame_atribucion_id($this->input->post('id_so_atribucion'));
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_atribucion();
            if($editar == 1){
                $this->session->set_flashdata('exito', "La funci&oacute;n " . $this->input->post('nombre_so_atribucion') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La funci&oacute;n " . $this->input->post('nombre_so_atribucion') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_atribuciones');
            }
        }
    }

    function eliminar_atribucion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_so_atribucion', 'tab_sujetos_obligados');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de sujetos obligados.");
        }else{
            $eliminar = $this->Catalogos_model->eliminar_atribucion($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }

        redirect('/tpoadminv1/catalogos/catalogos/busqueda_atribuciones');
    }

    function busqueda_estados()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Estados";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_estados"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/estados';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_estados";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['estados'] = $this->Catalogos_model->dame_todos_estados();
        $data['path_file_csv'] = $this->Catalogos_model->descarga_estados();
        $data['name_file_csv'] = "estados.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#estados').dataTable({" .
                                    "'bPaginate': true," .
                                    "'bLengthChange': true," .
                                    "'bFilter': true," .
                                    "'bSort': true," .
                                    "'bInfo': true," .
                                    "'bAutoWidth': false," .
                                    "'columnDefs': [ " .
                                        "{ 'orderable': false, 'targets': [4,5] } " .
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

    function agregar_estado()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar estado";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_estados"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_estado';

    
        $data['estado_nombre'] = '';
        $data['estado_codigo'] = '';
        $data['estado_estatus'] = 'null';
        

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_estado()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_so_estado', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('codigo_so_estado', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar estado";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_estados"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_estado';
        
        $data['estado_nombre'] = $this->input->post('nombre_so_estado');
        $data['estado_nombre'] = $this->input->post('codigo_so_estado');
        $data['estado_estatus'] = $this->input->post('active');
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_estado();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El estado " . $this->input->post('nombre_so_estado') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El estado " . $this->input->post('nombre_so_estado') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_estados');
            } 
        }
    }

    function editar_estado()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar estado";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_estados"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_estado';
        
        $data['estado'] = $this->Catalogos_model->dame_estado_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_estado()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_so_estado', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('codigo_so_estado', 'Ingresa un c&oacute;digo', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar estado";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_estados"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_estado';
        
        $data['estado'] = $this->Catalogos_model->dame_estado_id($this->input->post('id_so_estado'));
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_estado();
            if($editar == 1){
                $this->session->set_flashdata('exito', "El estado " . $this->input->post('nombre_so_estado') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El estado " . $this->input->post('nombre_so_estado') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_estados');
            }
        }
    }

    function eliminar_estado()
    {
        /* se quita por pedido del cliente */
        //Validamos que el usuario tenga acceso
        /*$permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $eliminar = $this->Catalogos_model->eliminar_estado($this->uri->segment(5));

        if($eliminar == 1){
            $this->session->set_flashdata('exito', "Registro eliminado correctamente");
        }else {
            $this->session->set_flashdata('error', "Registro no se pudo eliminar");
        }

        redirect('/tpoadminv1/catalogos/catalogos/busqueda_estados');*/
    }
    
    function busqueda_ordenes()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "&Oacute;rdenes de gobierno";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_ordenes"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/ordenes';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_ordenes";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";

        $data['ordenes'] = $this->Catalogos_model->dame_todos_ordenes();
        $data['path_file_csv'] = $this->Catalogos_model->descarga_ordenes();
        $data['name_file_csv'] = "ordenes_de_gobierno.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#ordenes').dataTable({" .
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

    function agregar_orden()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar &oacute;rden de gobierno";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_ordenes"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_orden';

    
        $data['orden_nombre'] = '';
        $data['orden_estatus'] = 'null';
        

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_orden()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_so_orden_gobierno', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar &oacute;rden de gobierno";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_ordenes"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_orden';
        
        $data['orden_nombre'] = $this->input->post('nombre_so_estado');
        $data['orden_estatus'] = $this->input->post('active');
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_orden();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "La &oacute;rden " . $this->input->post('nombre_so_orden_gobierno') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La &oacute;rden " . $this->input->post('nombre_so_orden_gobierno') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_ordenes');
            } 
        }
    }

    function editar_orden()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar &oacute;rden de gobierno";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_ordenes"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_orden';
        
        $data['orden'] = $this->Catalogos_model->dame_orden_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_orden()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_so_orden_gobierno', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar &oacute;rden de gobierno";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'sujetos_so'; // class="active"
        $data['optionactive'] = "busqueda_ordenes"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_orden';
        
        $data['orden'] = $this->Catalogos_model->dame_orden_id($this->input->post('id_so_orden_gobierno'));
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_orden();
            if($editar == 1){
                $this->session->set_flashdata('exito', "La &oacute;rden " . $this->input->post('nombre_so_orden_gobierno') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La &oacute;rden " . $this->input->post('nombre_so_orden_gobierno') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_ordenes');
            }
        }
    }

    function eliminar_orden()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_so_orden_gobierno', 'tab_sujetos_obligados');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de sujetos obligados.");
        }else{
            $eliminar = $this->Catalogos_model->eliminar_orden($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }

        redirect('/tpoadminv1/catalogos/catalogos/busqueda_ordenes');
    }

    function busqueda_presupuestos()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Partidas presupuestar&iacute;as";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_presupuestos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/presupuestos';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_presupuestos";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['presupuestos'] = $this->Catalogos_model->dame_todos_presupuestos();
        $data['path_file_csv'] = $this->Catalogos_model->descarga_presupuestos();
        $data['name_file_csv'] = "partidas_presupuestarias.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#presupuestos').dataTable({" .
                                    "'bPaginate': true," .
                                    "'bLengthChange': true," .
                                    "'bFilter': true," .
                                    "'bSort': true," .
                                    "'bInfo': true," .
                                    "'bAutoWidth': false," .
                                    "'columnDefs': [ " .
                                        "{ 'orderable': false, 'targets': [8,9,10] } " .
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

    function get_presupuesto()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $presupuesto = $this->Catalogos_model->get_presupuesto_id($this->uri->segment(5));

        header('Content-type: application/json');
        
        echo json_encode( $presupuesto );
    }

    function agregar_presupuesto()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar partida presupuestar&iacute;a";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_presupuestos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_presupuesto';

    
        $data['presupuesto'] = array(
            'capitulo' => '',
            'concepto' => '',
            'partida' => '',
            'denominacion' =>'',
            'descripcion' => '',
            'id_captura' => 'null',
            'active' => 'null'
        );
        

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_presupuesto()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('capitulo', 'Ingresa el cap&iacute;tulo', 'required');
        $this->form_validation->set_rules('concepto', 'Ingresa un concepto', 'required');
        $this->form_validation->set_rules('partida', 'Ingrese una partida', 'required');
        $this->form_validation->set_rules('denominacion', 'Ingrese una denominaci&iacute;n', 'required');
        $this->form_validation->set_rules('descripcion', 'Ingresa una descipci&iacute;n', 'required');
        $this->form_validation->set_rules('id_captura', 'Seleccione un calor de captura valido', 'required');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar partida presupuestar&iacute;a";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_presupuestos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_presupuesto';
        
        $data['presupuesto'] = array(
            'capitulo' => $this->input->post('capitulo'),
            'concepto' => $this->input->post('concepto'),
            'partida' => $this->input->post('partida'),
            'denominacion' => $this->input->post('denominacion'),
            'descripcion' => $this->input->post('descripcion'),
            'id_captura' => $this->input->post('id_captura'),
            'active' => $this->input->post('active')
        );
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_presupuesto();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "La partida " . $this->input->post('partida') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La partida " . $this->input->post('partida') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_presupuestos');
            } 
        }
    }

    function editar_presupuesto()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar partida presupuestar&iacute;a";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_presupuestos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_presupuesto';
        
        $data['presupuesto'] = $this->Catalogos_model->dame_presupuesto_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_presupuesto()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('capitulo', 'Ingresa el cap&iacute;tulo', 'required');
        $this->form_validation->set_rules('concepto', 'Ingresa un concepto', 'required');
        $this->form_validation->set_rules('partida', 'Ingrese una partida', 'required');
        $this->form_validation->set_rules('denominacion', 'Ingrese una denominaci&iacute;n', 'required');
        $this->form_validation->set_rules('descripcion', 'Ingresa una descipci&iacute;n', 'required');
        $this->form_validation->set_rules('id_captura', 'Seleccione un calor de captura valido', 'required');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar partida presupuestar&iacute;a";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_presupuestos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_presupuesto';
        
        $data['presupuesto'] = $this->Catalogos_model->dame_presupuesto_id($this->input->post('id_presupesto_concepto'));
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_presupuesto();
            if($editar == 1){
                $this->session->set_flashdata('exito', "La partida " . $this->input->post('partida') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La partida " . $this->input->post('partida') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_presupuestos');
            }
        }
    }

    function eliminar_presupuesto()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $eliminar = $this->Catalogos_model->eliminar_presupuesto($this->uri->segment(5));

        if($eliminar == 1){
            $this->session->set_flashdata('exito', "Registro eliminado correctamente");
        }else {
            $this->session->set_flashdata('error', "Registro no se pudo eliminar");
        }

        redirect('/tpoadminv1/catalogos/catalogos/busqueda_presupuestos');
    }

    function busqueda_trimestres()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Trimestres";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_trimestres"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/trimestres';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_trimestres";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['trimestres'] = $this->Catalogos_model->dame_todos_trimestres(false);
        $data['path_file_csv'] = $this->Catalogos_model->descarga_trimestres();
        $data['name_file_csv'] = "trimestres.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#trimestres').dataTable({" .
                                    "'bPaginate': true," .
                                    "'bLengthChange': false," .
                                    "'bFilter': true," .
                                    "'bSort': true," .
                                    "'bInfo': true," .
                                    "'bAutoWidth': false," .
                                    "'columnDefs': [ " .
                                        "{ 'orderable': false, 'targets': [3,4] } " .
                                    "],".
                                    "'oLanguage': { " .
                                        "'sSearch': 'Busqueda '," .
                                        "'sInfoFiltered': '(filtrado de un total de _MAX_ registros)'," .
                                        "'sInfo': 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros'," .
                                        "'sZeroRecords': 'No se encontraron resultados'," .
                                        "'EmptyTable': 'Ningún dato disponible en esta tabla'," .
                                        "'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros'," .
                                        "'oPaginate': {" .
                                            "'sFirst': 'Primero'," .
                                            "'sLast': 'Último'," .
                                            "'sNext': 'Siguiente'," .
                                            "'sPrevious': 'Anterior'" .
                                        "}" .
                                    "}" .
                                "});" .
                                "setTimeout(function() { " .
                                    "$('.alert').alert('close');" .
                                "}, 3000);" .
                            "});" .
                            "</script>";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function agregar_trimestre()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar trimestre";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_trimestres"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_trimestre';

    
        $data['trimestre'] = array(
            'trimestre' => '',
            'active' => 'null'
        );
        

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_trimestre()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('trimestre', 'Ingresa un trimestre', 'required|min_length[2]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar trimestre";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_trimestres"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_trimestre';
        
        $data['trimestre'] = array(
            'trimestre' => $this->input->post('trimestre'),
            'active' => $this->input->post('active')
        );

        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = 0; //$this->Catalogos_model->agregar_trimestre();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El trimestre " . $this->input->post('trimestre') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El trimestre " . $this->input->post('trimestre') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_trimestres');
            } 
        }
    }

    function editar_trimestre()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar trimestre";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_trimestres"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_trimestre';
        
        $data['trimestre'] = $this->Catalogos_model->dame_trimestre_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_trimestre()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('trimestre', 'Ingresa un trimestre', 'required|min_length[2]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar trimestre";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_trimestres"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_trimestre';
        
        $data['trimestre'] = $this->Catalogos_model->dame_trimestre_id($this->input->post('id_trimestre'));
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_trimestre();
            if($editar == 1){
                $this->session->set_flashdata('exito', "el trimestre " . $this->input->post('trimestre') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El trimestre " . $this->input->post('trimestre') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_trimestres');
            }
        }
    }

    function eliminar_trimestre()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $eliminar = 0; //$this->Catalogos_model->eliminar_trimestre($this->uri->segment(5));

        if($eliminar == 1){
            $this->session->set_flashdata('exito', "Registro eliminado correctamente");
        }else {
            $this->session->set_flashdata('error', "Registro no se pudo eliminar");
        }

        redirect('/tpoadminv1/catalogos/catalogos/busqueda_trimestres');
    }

    function busqueda_ejercicios()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Ejercicios";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_ejercicios"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/ejercicios';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_ejercicios";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['ejercicios'] = $this->Catalogos_model->dame_todos_ejercicios(false);
        $data['path_file_csv'] = $this->Catalogos_model->descarga_ejercicios();
        $data['name_file_csv'] = "ejercicios.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#ejercicios').dataTable({" .
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

    function agregar_ejercicio()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar ejercicio";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_ejercicios"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_ejercicio';

    
        $data['ejercicio'] = array(
            'ejercicio' => '',
            'active' => 'null'
        );
        

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_ejercicio()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('ejercicio', 'Ingresa un ejercicio', 'required|min_length[2]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar ejercicio";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_ejercicios"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_ejercicio';
        
        $data['ejercicio'] = array(
            'ejercicio' => $this->input->post('ejercicio'),
            'active' => $this->input->post('active')
        );

        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_ejercicio();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El ejercicio " . $this->input->post('ejercicio') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El ejercicio " . $this->input->post('ejercicio') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_ejercicios');
            } 
        }
    }

    function editar_ejercicio()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar ejercicio";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_ejercicios"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_ejercicio';
        
        $data['ejercicio'] = $this->Catalogos_model->dame_ejercicio_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_ejercicio()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('ejercicio', 'Ingresa un ejercicio', 'required|min_length[2]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar ejercicio";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_ejercicios"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_ejercicio';
        
        $data['ejercicio'] = $this->Catalogos_model->dame_ejercicio_id($this->input->post('id_ejercicio'));
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_ejercicio();
            if($editar == 1){
                $this->session->set_flashdata('exito', "El ejercicio " . $this->input->post('ejercicio') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El ejercicio " . $this->input->post('ejercicio') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_ejercicios');
            }
        }
    }

    function eliminar_ejercicio()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $eliminar = $this->Catalogos_model->eliminar_ejercicio($this->uri->segment(5));

        if($eliminar == 1){
            $this->session->set_flashdata('exito', "Registro eliminado correctamente");
        }else {
            $this->session->set_flashdata('error', "Registro no se pudo eliminar");
        }

        redirect('/tpoadminv1/catalogos/catalogos/busqueda_ejercicios');
    }

    function busqueda_personalidades()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Personalidad jur&iacute;dica del proveedor";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_personalidades"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/personalidades';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_personalidades";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['personalidades'] = $this->Catalogos_model->dame_todos_personalidades(false);
        $data['path_file_csv'] = $this->Catalogos_model->descarga_personalidades();
        $data['name_file_csv'] = "personalidades_juridicas.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#personalidades').dataTable({" .
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

    function agregar_personalidad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar personalidad jur&iacute;dica del proveedor";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_personalidades"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_personalidad';

    
        $data['personalidad'] = array(
            'nombre_personalidad_juridica' => '',
            'active' => 'null'
        );
        

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_personalidad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_personalidad_juridica', 'Ingresa un personalidad', 'required|min_length[2]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar personalidad jur&iacute;dica del proveedor";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_personalidades"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_personalidad';
        
        $data['personalidad'] = array(
            'nombre_personalidad_juridica' => $this->input->post('nombre_personalidad_juridica'),
            'active' => $this->input->post('active')
        );

        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_personalidad();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "La personalidad " . $this->input->post('nombre_personalidad_juridica') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La personalidad " . $this->input->post('nombre_personalidad_juridica') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_personalidades');
            } 
        }
    }

    function editar_personalidad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar personalidad jur&iacute;dica del proveedor";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_personalidades"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_personalidad';
        
        $data['personalidad'] = $this->Catalogos_model->dame_personalidad_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_personalidad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_personalidad_juridica', 'Ingresa una personalidad', 'required|min_length[2]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar personalidad jur&iacute;dica del proveedor";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_personalidades"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_personalidad';
        
        $data['personalidad'] = $this->Catalogos_model->dame_personalidad_id($this->input->post('id_personalidad_juridica'));
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_personalidad();
            if($editar == 1){
                $this->session->set_flashdata('exito', "La personalidad " . $this->input->post('nombre_personalidad_juridica') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La personalidad " . $this->input->post('nombre_personalidad_juridica') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_personalidades');
            }
        }
    }

    function eliminar_personalidad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $eliminar = $this->Catalogos_model->eliminar_personalidad($this->uri->segment(5));

        if($eliminar == 1){
            $this->session->set_flashdata('exito', "Registro eliminado correctamente");
        }else {
            $this->session->set_flashdata('error', "Registro no se pudo eliminar");
        }

        redirect('/tpoadminv1/catalogos/catalogos/busqueda_personalidades');
    }

    function busqueda_procedimientos()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Procedimientos de contrataci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_procedimientos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/procedimientos';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_procedimientos";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['procedimientos'] = $this->Catalogos_model->dame_todos_procedimientos(false);
        $data['path_file_csv'] = $this->Catalogos_model->descarga_procedimientos();
        $data['name_file_csv'] = "procedimientos_de_contratacion.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#procedimientos').dataTable({" .
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

    function agregar_procedimiento()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar procedimientos de contrataci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_procedimientos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_procedimiento';

    
        $data['procedimiento'] = array(
            'nombre_procedimiento' => '',
            'active' => 'null'
        );
        

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_procedimiento()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_procedimiento', 'Ingrese un procedimiento', 'required|min_length[2]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar procedimientos de contrataci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_procedimientos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_procedimiento';
        
        $data['procedimiento'] = array(
            'nombre_procedimiento' => $this->input->post('nombre_procedimiento'),
            'active' => $this->input->post('active')
        );

        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_procedimiento();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El procedimiento " . $this->input->post('nombre_procedimiento') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El procedimiento " . $this->input->post('nombre_procedimiento') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_procedimientos');
            } 
        }
    }

    function editar_procedimiento()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar procedimientos de contrataci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_procedimientos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_procedimiento';
        
        $data['procedimiento'] = $this->Catalogos_model->dame_procedimiento_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_procedimiento()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_procedimiento', 'Ingresa un procedimiento', 'required|min_length[2]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar procedimientos de contrataci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_procedimientos"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_procedimiento';
        
        $data['procedimiento'] = $this->Catalogos_model->dame_procedimiento_id($this->input->post('id_procedimiento'));
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_procedimiento();
            if($editar == 1){
                $this->session->set_flashdata('exito', "El procedimiento " . $this->input->post('nombre_procedimiento') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El procedimiento " . $this->input->post('nombre_procedimiento') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_procedimientos');
            }
        }
    }

    function eliminar_procedimiento()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $eliminar = $this->Catalogos_model->eliminar_procedimiento($this->uri->segment(5));

        if($eliminar == 1){
            $this->session->set_flashdata('exito', "Registro eliminado correctamente");
        }else {
            $this->session->set_flashdata('error', "Registro no se pudo eliminar");
        }

        redirect('/tpoadminv1/catalogos/catalogos/busqueda_procedimientos');
    }

    function busqueda_ligas()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Tipo de ligas";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_ligas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/ligas';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_ligas";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['ligas'] = $this->Catalogos_model->dame_todos_ligas(false);
        $data['path_file_csv'] = $this->Catalogos_model->descarga_ligas();
        $data['name_file_csv'] = "tipo_de_ligas.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#ligas').dataTable({" .
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

    function agregar_liga()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar tipo de liga";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_ligas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_liga';

    
        $data['liga'] = array(
            'tipo_liga' => '',
            'active' => 'null'
        );
        

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_liga()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('tipo_liga', 'Ingrese tipo de liga', 'required|min_length[2]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar tipo de liga";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_ligas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_liga';
        
        $data['liga'] = array(
            'tipo_liga' => $this->input->post('tipo_liga'),
            'active' => $this->input->post('active')
        );

        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_liga();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El tipo de liga " . $this->input->post('tipo de liga') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El tipo de liga " . $this->input->post('tipo de liga') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_ligas');
            } 
        }
    }

    function editar_liga()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar tipo de liga";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_ligas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_liga';
        
        $data['liga'] = $this->Catalogos_model->dame_liga_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_liga()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('tipo_liga', 'Ingresa un tipo de liga', 'required|min_length[2]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar tipo de liga";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'otros_co'; // class="active"
        $data['optionactive'] = "busqueda_ligas"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_liga';
        
        $data['liga'] = $this->Catalogos_model->dame_liga_id($this->input->post('id_tipo_liga'));
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_liga();
            if($editar == 1){
                $this->session->set_flashdata('exito', "El tipo de liga " . $this->input->post('tipo de liga') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El tipo de liga " . $this->input->post('tipo de liga') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/catalogos/busqueda_ligas');
            }
        }
    }

    function eliminar_liga()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $eliminar = $this->Catalogos_model->eliminar_liga($this->uri->segment(5));

        if($eliminar == 1){
            $this->session->set_flashdata('exito', "Registro eliminado correctamente");
        }else {
            $this->session->set_flashdata('error', "Registro no se pudo eliminar");
        }

        redirect('/tpoadminv1/catalogos/catalogos/busqueda_ligas');
    }
}
