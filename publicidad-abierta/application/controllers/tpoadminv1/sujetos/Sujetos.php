<?php

/*
    INAI - SUJETOS
 */

if (!defined('BASEPATH')) 
{
    exit('No direct script access allowed');
}

class Sujetos extends CI_Controller
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
    
    
    function validate_orden($str)
    {
        if($str != '0'){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

    function validate_estado($str)
    {
        if($str != '0'){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

    function validate_atribucion($str)
    {
        if($str != '0'){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

    function validate_estatus($str)
    {
        if($str != '0'){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

	function busqueda_sujeto() 
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
    
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
        
        $data['title'] = "Sujetos Obligados";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'sujetos'; // solo active 
        $data['subactive'] = 'busqueda_sujeto'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/sujetos/busqueda_sujetos';
        $data['usuarios'] = $this->Sujeto_model->dame_todos_sujetos();
        $data['estados'] = $this->Sujeto_model->dame_todos_estados();
        $data['ordenes'] = $this->Sujeto_model->dame_todos_ordenes();
        //$data['imp_sujeto'] = $this->Sujeto_model->descarga_sujeto();

        $print_url = base_url() . "index.php/tpoadminv1/usuarios/print_ci/print_sujetos";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        $data['path_file_csv'] = $this->Sujeto_model->descarga_sujetos();
        $data['name_file_csv'] = "sujetos_csv.csv";

        //texto para dialogos de ayuda
        $data['texto_ayuda'] = array(
            'funcion' => 'Indica si el sujeto obligado tiene atribuci&oacute;n de contratar servicios o productos, de solicitarlos o ambos [Contratante, solicitante, ambos].',
            'nombre' => 'Son sujetos obligados a transparentar y permitir el acceso a su informaci&oacute;n y proteger los datos
            datos personales que obren en su poder: cualquier autoridad, entidad, &oacute;rgano y organismo de los poderes
            Ejecutivo, Legislativo y Judicial, &oacute,rganos aut&oacute;nomos, partidos pol&iacute;ticos, fideicomisos y fondoso p&uacute;blicos,
            as&iacute; como cualquier persona f&iacute;sica, moral o con sindicato que reciba o ejerza recursos p&uacute;blicos o realice
            actos de autoridad en los &acute;mbitos federal, de las entidades Federativas y municipal.',
            'activo' => 'Indica el estatus del usuario a=Activo, i=Inactivo .'
        );
        
        $data['scripts'] = "<script type='text/javascript'>
                                $(function () {
                                $('#example2').dataTable({
                                    'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, 'Todo']],    //Paginacion

                                    'bPaginate': true,
                                    'bLengthChange': true,
                                    'bFilter': true,
                                    'bSort': true,
                                    'bInfo': true,
                                    'bAutoWidth': false,
                                    'columnDefs': [
                                        { 'orderable': false, 'targets': [7,8,9] }
                                    ], 
                                    'oLanguage': { 
                                        'sSearch': 'B&uacute;squeda  ',
                                        'sInfoFiltered':   '(filtrado de un total de _MAX_ registros)',
                                        'sInfo':           'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
                                        'sZeroRecords':    'No se encontraron resultados',
                                        'EmptyTable':     'Ningún dato disponible en esta tabla',
                                        'sInfoEmpty':      'Mostrando registros del 0 al 0 de un total de 0 registros',
                                        'oPaginate': {
                                            'sFirst':    'Primero',
                                            'sLast':     'Último',
                                            'sNext':     'Siguiente',
                                            'sPrevious': 'Anterior'
                                        },
                                        'sLengthMenu': '_MENU_ Registros por p&aacute;gina'
                                    }
                                });
                            });
                            </script>";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function eliminar_sujeto()
    {
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');

        $eliminar = $this->Sujeto_model->elimina_sujeto($this->uri->segment(5));

        //print_r($eliminar);

        if($eliminar == 1){
            $this->session->set_flashdata('exito', "Registro eliminado correctamente");
        }else {
            $this->session->set_flashdata('error', "Registro no se pudo eliminar");
        }

        redirect('/tpoadminv1/sujetos/sujetos/busqueda_sujeto');
    }


    function get_sujeto()
    {
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
        $usuario_info = $this->Sujeto_model->get_sujeto_id($this->uri->segment(5));

        header('Content-type: application/json');
        
        echo json_encode( $usuario_info );
    }


    function alta_sujeto() 
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
    
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
                
        $data['title'] = "Sujetos Obligados";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'sujetos'; // solo active 
        $data['subactive'] = 'alta_sujeto'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/sujetos/alta_sujeto';
        $data['funciones'] = $this->Sujeto_model->dame_todos_funciones();
        $data['estados'] = $this->Sujeto_model->dame_todos_estados();
        $data['ordenes'] = $this->Sujeto_model->dame_todos_ordenes();
        $data['scripts'] = "";
        
        //texto para dialogos de ayuda
        $data['texto_ayuda'] = array(
            'funcion' => 'Indica si el sujeto obligado tiene atribuci&oacute;n de contratar servicios o productos, de solicitarlos o ambos [Contratante, solicitante, ambos].',
            'nombre' => 'Son sujetos obligados a transparentar y permitir el acceso a su informaci&oacute;n y proteger los datos
                datos personales que obren en su poder: cualquier autoridad, entidad, &oacute;rgano y organismo de los poderes
                Ejecutivo, Legislativo y Judicial, &oacute,rganos aut&oacute;nomos, partidos pol&iacute;ticos, fideicomisos y fondoso p&uacute;blicos,
                as&iacute; como cualquier persona f&iacute;sica, moral o con sindicato que reciba o ejerza recursos p&uacute;blicos o realice
                actos de autoridad en los &acute;mbitos federal, de las entidades Federativas y municipal.',
            'activo' => 'Indica el estatus del usuario a=Activo, i=Inactivo .'
        );

        $this->load->view('tpoadminv1/includes/template', $data);
    }
    
    function validate_alta_sujeto()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
        $this->load->library('form_validation');
        
        //Creamos nuestros propios Form Validation 
        $this->form_validation->set_rules('id_so_atribucion', 'Funcion', 'required|callback_validate_atribucion');
        $this->form_validation->set_message('validate_atribucion','Debes seleccionar una Funci&oacute;n.');
        $this->form_validation->set_rules('id_so_orden_gobierno', 'Orden Gobierno', 'required|callback_validate_orden');
        $this->form_validation->set_message('validate_orden','Debes seleccionar una Orden.');
        $this->form_validation->set_rules('id_so_estado', 'Estado', 'required|callback_validate_estado');
        $this->form_validation->set_message('validate_estado','Debes seleccionar un Estado.');
        $this->form_validation->set_rules('active', 'Estatus', 'required|callback_validate_estatus');
        $this->form_validation->set_message('validate_estatus','Debes seleccionar un Estatus.');

        $this->form_validation->set_rules('nombre_sujeto_obligado', 'Nombre', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('siglas_sujeto_obligado', 'Siglas', 'trim');
        $this->form_validation->set_rules('url_sujeto_obligado', 'URL', 'trim|min_length[10]');
        $this->form_validation->set_error_delimiters('<p>','</p>');
        
        $data['title'] = "Sujetos Obligados";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'sujetos'; // solo active 
        $data['subactive'] = 'alta_sujeto'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/sujetos/alta_sujeto';
        $data['funciones'] = $this->Sujeto_model->dame_todos_funciones();
        $data['estados'] = $this->Sujeto_model->dame_todos_estados();
        $data['ordenes'] = $this->Sujeto_model->dame_todos_ordenes();
        $data['scripts'] = "";
        
        //texto para dialogos de ayuda
        $data['texto_ayuda'] = array(
            'funcion' => 'Indica si el sujeto obligado tiene atribuci&oacute;n de contratar servicios o productos, de solicitarlos o ambos [Contratante, solicitante, ambos].',
            'nombre' => 'Son sujetos obligados a transparentar y permitir el acceso a su informaci&oacute;n y proteger los datos
                datos personales que obren en su poder: cualquier autoridad, entidad, &oacute;rgano y organismo de los poderes
                Ejecutivo, Legislativo y Judicial, &oacute,rganos aut&oacute;nomos, partidos pol&iacute;ticos, fideicomisos y fondoso p&uacute;blicos,
                as&iacute; como cualquier persona f&iacute;sica, moral o con sindicato que reciba o ejerza recursos p&uacute;blicos o realice
                actos de autoridad en los &acute;mbitos federal, de las entidades Federativas y municipal.',
            'activo' => 'Indica el estatus del usuario a=Activo, i=Inactivo .'
        );

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }
        else 
        {
            $alta = $this->Sujeto_model->alta_sujeto();
            
            switch ($alta)
            {
                case 1: $this->session->set_flashdata('exito', "El Sujeto Obligado " . $this->input->post('nombre_sujeto_obligado') . " se ha creado correctamente");
                    redirect('/tpoadminv1/sujetos/sujetos/busqueda_sujeto');
                    break;
                case 2: $this->session->set_flashdata('error', "El Sujeto Obligado " . $this->input->post('nombre_sujeto_obligado') . " ya existe, ingresa otro");
                    $this->load->view('tpoadminv1/includes/template', $data);
                    break;
                default: $this->session->set_flashdata('error', "Hubo un error intente de nuevo");
                    break;
            }
        }
    }
    
    function edita_sujeto()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
	
        $data['title'] = "Edita Sujeto";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "Modifica la información requerida";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'sujetos'; // solo active 
        $data['subactive'] = 'busqueda_sujeto'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/sujetos/edita_sujeto';
        
        $data['sujeto'] = $this->Sujeto_model->dame_sujeto_id($this->uri->segment(5));
        $data['estados'] = $this->Sujeto_model->dame_todos_estados();
        $data['ordenes'] = $this->Sujeto_model->dame_todos_ordenes();
        $data['atribuciones'] = $this->Sujeto_model->dame_todos_funciones();
        $data['scripts'] = "";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_edita_sujeto()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/usuarios/Usuario_model');
        $this->load->library('form_validation');
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');

        //Creamos nuestros propios Form Validation 
        $this->form_validation->set_rules('id_so_atribucion', 'Funcion', 'required|callback_validate_atribucion');
        $this->form_validation->set_message('validate_atribucion','Debes seleccionar una Funci&oacute;n.');
        $this->form_validation->set_rules('id_so_orden_gobierno', 'Orden Gobierno', 'required|callback_validate_orden');
        $this->form_validation->set_message('validate_orden','Debes seleccionar una Orden.');
        $this->form_validation->set_rules('id_so_estado', 'Estado', 'required|callback_validate_estado');
        $this->form_validation->set_message('validate_estado','Debes seleccionar un Estado.');
        $this->form_validation->set_rules('active', 'Estatus', 'required|callback_validate_estatus');
        $this->form_validation->set_message('validate_estatus','Debes seleccionar un Estatus.');
        $this->form_validation->set_rules('nombre_sujeto_obligado', 'Nombre', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('siglas_sujeto_obligado', 'Siglas', 'trim|min_length[2]');
        $this->form_validation->set_rules('url_sujeto_obligado', 'URL', 'trim|min_length[3]');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Edita Sujeto";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "Modifica la información requerida";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'sujetos'; // solo active 
        $data['subactive'] = 'busqueda_sujeto'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/sujetos/edita_sujeto';
        $data['sujeto'] = $this->Sujeto_model->dame_sujeto_id($this->input->post('id_sujeto_obligado'));
        $data['estados'] = $this->Sujeto_model->dame_todos_estados();
        $data['ordenes'] = $this->Sujeto_model->dame_todos_ordenes();
        $data['atribuciones'] = $this->Sujeto_model->dame_todos_funciones();
        $data['scripts'] = "";
        
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }
        else 
        {
            $actualiza = $this->Sujeto_model->edita_sujeto();

            switch ($actualiza) 
            {
                case 1: $this->session->set_flashdata('exito', 'El sujeto obligado fue actualizado correctamente');
                    break;
                case 2: $this->session->set_flashdata('error', "Ya existe el sujeto obligado ".$this->input->post('nombre_sujeto_obligado').", ingresa otro");
                    break;
                default: $this->session->set_flashdata('alerta', "Hubo un error intente de nuevo");
                    break;
            }

            redirect('/tpoadminv1/sujetos/sujetos/edita_sujeto/'.$this->input->post('id_sujeto_obligado'));
        }

    }

}