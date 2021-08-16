<?php


/**
 * Description of Población Objetivo
 *
 * INAI TPO
 * 
 */

if (!defined('BASEPATH')) 
{
    exit('No direct script access allowed');
}

class Poblacion_objetivo extends CI_Controller
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

    function busqueda_edad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Edad";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_edad"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/edades';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_edades";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['edades'] = $this->Catalogos_model->dame_todas_edades();
        $data['path_file_csv'] = $this->Catalogos_model->descarga_edades();
        $data['name_file_csv'] = "edades.csv";

        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#edades').dataTable({" .
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

    function agregar_edad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar edad";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_edad"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_edad';

        $data['edad_nombre'] = '';
        $data['edad_estatus'] = 'null';

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_edad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_poblacion_grupo_edad', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar edad";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_edad"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_edad';
        
        $data['edad_nombre'] = $this->input->post('nombre_poblacion_grupo_edad');
        $data['edad_estatus'] = $this->input->post('active');
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_edad();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "La edad " . $this->input->post('nombre_poblacion_grupo_edad') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La edad " . $this->input->post('nombre_poblacion_grupo_edad') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/poblacion_objetivo/busqueda_edad');
            } 
        }
    }

    function editar_edad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar edad";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_edad"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_edad';
        
        $data['edad'] = $this->Catalogos_model->dame_edad_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_edad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_poblacion_grupo_edad', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar edad";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_edad"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_edad';
        
        $data['edad'] = $this->Catalogos_model->dame_edad_id($this->input->post('id_poblacion_grupo_edad'));

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_edad();
            if($editar == 1){
                $this->session->set_flashdata('exito', "La edad " . $this->input->post('nombre_poblacion_grupo_edad') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La edad " . $this->input->post('nombre_poblacion_grupo_edad') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/poblacion_objetivo/busqueda_edad');
            }
        }
    }

    function eliminar_edad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_poblacion_grupo_edad', 'rel_campana_grupo_edad');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de campañas/avisos institucionales.");
        }else{
            $eliminar = $this->Catalogos_model->eliminar_edad($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }

        redirect('/tpoadminv1/catalogos/poblacion_objetivo/busqueda_edad');
    }

    function busqueda_nivel_socioeconomico()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Nivel socioecon&oacute;mico";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_nivel_socioeconomico"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/nivel_socioeconomico';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_socioeconomicos";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['socioeconomicos'] = $this->Catalogos_model->dame_todas_socioeconomicos();
        $data['path_file_csv'] = $this->Catalogos_model->descarga_socioeconomicos();
        $data['name_file_csv'] = "niveles_socioeconomicos.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#socioeconomico').dataTable({" .
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

    function agregar_nivel_socioeconomico()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar nivel socioecon&oacute;mico";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_nivel_socioeconomico"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_nivel_socioeconomico';

        $data['socioeconomico_nombre'] = '';
        $data['socioeconomico_estatus'] = 'null';

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_nivel_socioeconomico()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_poblacion_nivel', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar nivel socioecon&oacute;mico";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_nivel_socioeconomico"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_nivel_socioeconomico';
        
        $data['socioeconomico_nombre'] = $this->input->post('nombre_poblacion_nivel');
        $data['socioeconomico_estatus'] = $this->input->post('active');
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_nivel_socioeconomico();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El nivel socioecon&oacute;mico " . $this->input->post('nombre_poblacion_grupo_edad') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El nivel socioecon&oacute;mico " . $this->input->post('nombre_poblacion_grupo_edad') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/poblacion_objetivo/busqueda_nivel_socioeconomico');
            } 
        }
    }

    function editar_nivel_socioeconomico()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar nivel socioecon&oacute;mico";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_nivel_socioeconomico"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_nivel_socioeconomico';
        
        $data['socioeconomico'] = $this->Catalogos_model->dame_socioeconomico_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_nivel_socioeconomico()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_poblacion_nivel', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar nivel socioecon&oacute;mico";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_nivel_socioeconomico"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_nivel_socioeconomico';
        
        $data['socioeconomico'] = $this->Catalogos_model->dame_socioeconomico_id($this->input->post('id_poblacion_nivel'));
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->editar_nivel_socioeconomico();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El nivel socioecon&oacute;mico " . $this->input->post('nombre_poblacion_nivel') . " se ha editado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El nivel socioecon&oacute;mico " . $this->input->post('nombre_poblacion_nivel') . " no se pudo editar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/poblacion_objetivo/busqueda_nivel_socioeconomico');
            } 
        }
    }

    function eliminar_nivel_socioeconomico()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_poblacion_nivel', 'rel_campana_nivel');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de campañas/avisos institucionales.");
        }else{
            $eliminar = $this->Catalogos_model->eliminar_socioeconomico($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }

        redirect('/tpoadminv1/catalogos/poblacion_objetivo/busqueda_nivel_socioeconomico');
    }

    function busqueda_nivel_educacion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Nivel de educaci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_nivel_educacion"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/nivel_educacion';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_educacion";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['educacion'] = $this->Catalogos_model->dame_todas_educacion();
        $data['path_file_csv'] = $this->Catalogos_model->descarga_educacion();
        $data['name_file_csv'] = "niveles_de_educacion.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#educacion').dataTable({" .
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

    function agregar_nivel_educacion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar nivel de educaci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_nivel_educacion"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_nivel_educacion';

        $data['educacion_nombre'] = '';
        $data['educacion_estatus'] = 'null';

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_nivel_educacion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_poblacion_nivel_educativo', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar nivel de educaci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_nivel_educacion"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_nivel_educacion';
        
        $data['educacion_nombre'] = $this->input->post('nombre_poblacion_nivel_educativo');
        $data['educacion_estatus'] = $this->input->post('active');
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_nivel_educacion();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El nivel de educaci&oacute;n " . $this->input->post('nombre_poblacion_nivel_educativo') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El nivel de educaci&oacute;n " . $this->input->post('nombre_poblacion_nivel_educativo') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/poblacion_objetivo/busqueda_nivel_educacion');
            } 
        }
    }

    function editar_nivel_educacion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Editar nivel de educaci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_nivel_educacion"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_nivel_educacion';

        $data['educacion'] = $this->Catalogos_model->dame_educacion_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_nivel_educacion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_poblacion_nivel_educativo', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar nivel de educaci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_nivel_educacion"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_nivel_educacion';
        
        $data['educacion'] = $this->Catalogos_model->dame_educacion_id($this->input->post('id_poblacion_nivel_educativo'));
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->editar_nivel_educacion();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El nivel de educaci&oacute;n " . $this->input->post('nombre_poblacion_nivel_educativo') . " se ha editado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El nivel de educaci&oacute;n " . $this->input->post('nombre_poblacion_nivel_educativo') . " no se pudo editar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/poblacion_objetivo/busqueda_nivel_educacion');
            } 
        }
    }

    function eliminar_nivel_educacion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_poblacion_nivel_educativo', 'rel_campana_nivel_educativo');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de campañas/avisos institucionales.");
        }else{
            $eliminar = $this->Catalogos_model->eliminar_educacion($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }
        redirect('/tpoadminv1/catalogos/poblacion_objetivo/busqueda_nivel_educacion');
    }

    function busqueda_sexo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Sexo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_sexo"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/sexo';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_sexos";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['sexos'] = $this->Catalogos_model->dame_todos_sexo();
        $data['path_file_csv'] = $this->Catalogos_model->descarga_sexo();
        $data['name_file_csv'] = "segmentacion_por_sexos.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#sexo').dataTable({" .
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

    function agregar_sexo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar sexo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_sexo"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_sexo';

        $data['sexo_nombre'] = '';
        $data['sexo_estatus'] = 'null';

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_sexo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_poblacion_sexo', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar sexo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_sexo"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_sexo';
        
        $data['sexo_nombre'] = $this->input->post('nombre_poblacion_sexo');
        $data['sexo_estatus'] = $this->input->post('active');
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_sexo();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El sexo " . $this->input->post('nombre_poblacion_sexo') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El sexo " . $this->input->post('nombre_poblacion_sexo') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/poblacion_objetivo/busqueda_sexo');
            } 
        }
    }

    function editar_sexo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Editar sexo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_sexo"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_sexo';

        $data['sexo'] = $this->Catalogos_model->dame_sexo_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function eliminar_sexo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_poblacion_sexo', 'rel_campana_sexo');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de campañas/avisos institucionales.");
        }else{
            $eliminar = $this->Catalogos_model->eliminar_sexo($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }
        redirect('/tpoadminv1/catalogos/poblacion_objetivo/busqueda_sexo');
    }

    function validate_editar_sexo()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_poblacion_sexo', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar sexo";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'poblacion_objetivo'; // class="active"
        $data['optionactive'] = "busqueda_sexo"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_sexo';
        
        $data['sexo'] = $this->Catalogos_model->dame_sexo_id($this->input->post('id_poblacion_sexo'));
    
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->editar_sexo();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "El sexo " . $this->input->post('nombre_poblacion_sexo') . " se ha editado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "El sexo " . $this->input->post('nombre_poblacion_sexo') . " no se pudo editar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/poblacion_objetivo/busqueda_sexo');
            } 
        }
    }

}

?>