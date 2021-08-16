<?php

/*
INAI / USUARIOS
*/

if (!defined('BASEPATH')) 
{
    exit('No direct script access allowed');
}

class Usuarios extends CI_Controller
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

    function validate_sujeto($str)
    {
        if($str != '0')
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function validate_rol($str)
    {
        if($str != '0')
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }


    function busqueda_usuario() 
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
                
        $data['title'] = "Usuarios";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'usuarios'; // solo active 
        $data['subactive'] = 'busqueda_usuario'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/usuarios/busqueda_usuario';
        $data['usuarios'] = $this->Usuario_model->dame_todos_usuarios();
        
        $print_url = base_url() . "index.php/tpoadminv1/usuarios/print_ci/print_usuarios";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        $data['path_file_csv'] = $this->Usuario_model->descarga_usuarios();
        $data['name_file_csv'] = "usuarios_csv.csv";

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
                                        { 'orderable': false, 'targets': [7,8] }
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
                                });" .
                                "setTimeout(function() { " .
                                    "$('.alert').alert('close');" .
                                "}, 3000);" .
                            " });
                            </script>";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }
    
    function imp_busqueda_usuario() 
    {
        //Revisamos que tenga los permisos para entrar a esta pantalla
        
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
                
        $data['title'] = "";
        $data['heading'] = "";
        $data['mensaje'] = "";
        $data['job'] = "";
        $data['active'] = ''; // solo active 
        $data['subactive'] = ''; // class="active"
        $data['body_class'] = '';
        $data['main_content'] = 'tpoadminv1/usuarios/imp_busqueda_usuario';
        $data['usuarios'] = $this->Usuario_model->dame_todos_usuarios();
        $data['scripts'] = "";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function busqueda_sujetos_obligados() 
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
                
        $data['title'] = "Sujetos Obligados";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "Busca usuario a modificar";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'usuarios'; // solo active 
        $data['subactive'] = 'busqueda_usuario'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/usuarios/busqueda_sujetos_obligados';
        
        $data['usuarios'] = $this->Usuario_model->dame_todos_sujetos_obligados();
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#example2').dataTable({" .
                                    "'bPaginate': true," .
                                    "'bLengthChange': false," .
                                    "'bFilter': true," .
                                    "'bSort': true," .
                                    "'bInfo': true," .
                                    "'bAutoWidth': false" .
                                "});" .
                            "});" .
                            "</script>";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function alta_usuario() 
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/usuarios/Usuario_model');
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
                
        $data['title'] = "Usuarios";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'usuarios'; // solo active 
        $data['subactive'] = 'alta_usuario'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/usuarios/alta_usuario';
        $data['sujetos'] = $this->Sujeto_model->dame_todos_sujetos();
        $data['roles'] = $this->Usuario_model->dame_todos_roles();
        $data['scripts'] = "";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }
    
    function validate_alta_usuario()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
        $this->load->library('form_validation');
		
        $this->form_validation->set_rules('username', 'Usuario', 'required|min_length[3]');
        $this->form_validation->set_rules('fname', 'Nombre(s)', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('lname', 'Apellidos', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('phone', 'Tel&eacute;fono', 'trim|min_length[8]');
        $this->form_validation->set_rules('email', 'Correo electr&oacute;nico', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Contrase&ntilde;a', 'trim|required|min_length[3]');

        //Creamos nuestros propios Form Validation 
        $this->form_validation->set_rules('record_user', '"Sujeto Obligado"', 'required|callback_validate_sujeto');
        $this->form_validation->set_message('validate_sujeto','Debes seleccionar un Sujeto Obligado.');
        $this->form_validation->set_rules('rol_user', '"Rol"', 'required|callback_validate_rol');
        $this->form_validation->set_message('validate_rol','Debes seleccionar un Rol.');

        $this->form_validation->set_error_delimiters('<p>','</p>');
        
        $data['title'] = "Usuarios";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'usuarios'; // solo active 
        $data['subactive'] = 'alta_usuario'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/usuarios/alta_usuario';
        $data['sujetos'] = $this->Sujeto_model->dame_todos_sujetos();
        $data['roles'] = $this->Usuario_model->dame_todos_roles();
        $data['scripts'] = "";
        
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }
        else 
        {
            $alta = $this->Usuario_model->alta_usuario();
            
            switch($alta)
            {
                case 1:
                    $this->session->set_flashdata('exito', "El Usuario ".$this->input->post('username')." ha sido dado de alta exitosamente");
                    redirect('/tpoadminv1/usuarios/usuarios/busqueda_usuario');
                    break;
                case 2: 
                    $this->session->set_flashdata('error', "Ya existe el usuario ".$this->input->post('username'). " ,ingresa otro");
                    $this->load->view('tpoadminv1/includes/template', $data);
                    break;
                case 3:
                    $this->session->set_flashdata('error', "Ya existe un usuario con el correo electr&oacute;nico ".$this->input->post('email'). ", ingresa otro");
                    $this->load->view('tpoadminv1/includes/template', $data);
                    break;
                default:
                    $this->session->set_flashdata('alerta', "Hubo un error intente de nuevo");
                    $this->load->view('tpoadminv1/includes/template', $data);
                    break;
            }
        }
    }
    
    function get_usuario()
    {
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
        $usuario_info = $this->Usuario_model->get_usuario_id($this->uri->segment(5));

        header('Content-type: application/json');
        
        echo json_encode( $usuario_info );
    }


    function edita_usuario()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/usuarios/Usuario_model');
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
        
        $data['title'] = "Usuarios";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'usuarios'; // solo active 
        $data['subactive'] = 'busqueda_usuario'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/usuarios/edita_usuario';
        
        $data['usuario'] = $this->Usuario_model->dame_usuario_id($this->uri->segment(5));
        $data['sujetos'] = $this->Sujeto_model->dame_todos_sujetos();
        $data['roles'] = $this->Usuario_model->dame_todos_roles();
        $data['scripts'] = "";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }
    
    function validate_edita_usuario()
    {   
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
    
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('username', 'Usuario', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('email', 'Correo Electr&oacute;nico', 'trim|required|valid_email');
        $this->form_validation->set_rules('fname', 'Nombre(s)', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('lname', 'Apellidos', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('phone', 'Tel&eacute;fono', 'trim|min_length[8]');
        
        //Creamos nuestros propios Form Validation 
        $this->form_validation->set_rules('record_user', '"Sujeto Obligado"', 'required|callback_validate_sujeto');
        $this->form_validation->set_message('validate_sujeto','Debes seleccionar un Sujeto Obligado.');
        $this->form_validation->set_rules('rol_user', '"Rol"', 'required|callback_validate_rol');
        $this->form_validation->set_message('validate_rol','Debes seleccionar un Rol.');


        $this->form_validation->set_error_delimiters('<p>','</p>');
        
        $data['title'] = "Usuarios";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'usuarios'; // solo active 
        $data['subactive'] = 'busqueda_usuario'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/usuarios/edita_usuario';
        
        $data['usuario'] = $this->Usuario_model->dame_usuario_id($this->input->post('id_user'));
        $data['sujetos'] = $this->Sujeto_model->dame_todos_sujetos();
        $data['roles'] = $this->Usuario_model->dame_todos_roles();
        $data['scripts'] = "";
        
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }
        else 
        {
            $edita = $this->Usuario_model->edita_usuario($this->input->post('id_user'));
            
            switch ($edita) 
            {
                case 1: $this->session->set_flashdata('exito', "El usuario " . $this->input->post('username') . " se ha actualizado correctamente");
                    redirect('/tpoadminv1/usuarios/usuarios/busqueda_usuario');
                    break;
                case 2: $this->session->set_flashdata('error', "El usuario " . $this->input->post('username') . " ya existe, ingresa otro");
                    $this->load->view('tpoadminv1/includes/template', $data);
                    break;
                case 3: $this->session->set_flashdata('error', "Ya existe un usuario con el correo " . $this->input->post('email') . " , ingresa otro");
                    $this->load->view('tpoadminv1/includes/template', $data);
                    break;
                default: $this->session->set_flashdata('alerta', "Hubo un error intente de nuevo");
                    redirect('/tpoadminv1/usuarios/usuarios/busqueda_usuario');
                    break;
            }
            
        }
    }

    function eliminar_usuario($usuario_id)
    {
        //Al dar click en el boton rechazar, pasamos los valores necesarios para ser utilizados en el modelo, con la funcion rechazar_vacaciones
        $this->load->model('tpoadminv1/usuarios/Usuario_model');

        $elimina = $this->Usuario_model->elimina_usuario($usuario_id);
        if($elimina == '1')
        {
            //$username = $this->Usuario_model->dame_username($usuario_id);

            $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            redirect('/tpoadminv1/usuarios/usuarios/busqueda_usuario');
        }
        
    }
    

    function descarga_usuario()
    {
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
    
        $data['usuario'] = $this->Usuario_model->descarga_usuario();
        print_r($data['usuario']);
        die();
    }



    function busqueda_rol() 
    {
        //Revisamos que tenga los permisos para entrar a esta pantalla
        
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
                
        $data['title'] = "Roles";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'usuarios'; // solo active 
        $data['subactive'] = 'busqueda_rol'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/usuarios/busqueda_rol';
        $data['roles'] = $this->Usuario_model->dame_todos_roles();

        $print_url = base_url() . "index.php/tpoadminv1/usuarios/print_ci/print_roles";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        $data['path_file_csv'] = $this->Usuario_model->descarga_roles();
        $data['name_file_csv'] = "roles_csv.csv";

        $data['scripts'] = "<script type='text/javascript'>
                                $(function () {
                                $('#example2').dataTable({
                                    'bPaginate': true,
                                    'bLengthChange': false,
                                    'bFilter': true,
                                    'bSort': false,
                                    'bInfo': true,
                                    'bAutoWidth': false,
                                    
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
                                    }
                                });
                                setTimeout(function() { 
                                    $('.alert').alert('close');
                                }, 3000);
                            });
                            </script>";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function alta_rol() 
    {
        //Revisamos que tenga los permisos para entrar a esta pantalla
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
                
        $data['title'] = "Alta Rol";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "Crea roles para administrar";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'usuarios'; // solo active 
        $data['subactive'] = 'alta_rol'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/usuarios/alta_rol';
        $data['scripts'] = "";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_alta_rol()
    {
        //Revisamos que tenga los permisos para entrar a esta pantalla
        
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
        $this->load->library('form_validation');
		
        $this->form_validation->set_rules('nombre_rol', 'Ingresa nombre', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('descripcion_rol', 'Ingresa una descripcion', 'trim|required|min_length[3]');
        $this->form_validation->set_error_delimiters('<p>','</p>');
        
        $data['title'] = "Alta Rol";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "Crea roles para administrar";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'usuarios'; // solo active 
        $data['subactive'] = 'alta_rol'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/usuarios/alta_rol';
        $data['scripts'] = "";
        
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }
        else 
        {
            $alta = $this->Usuario_model->alta_rol();
            
            switch ($alta) 
            {
                case 1: $this->session->set_flashdata('exito', "El Rol " . $this->input->post('nombre_rol') . " se ha creado correctamente");
                    break;
                case 2: $this->session->set_flashdata('error', "El Rol " . $this->input->post('nombre_rol') . " ya existe, ingresa otro");
                    break;
                default: $this->session->set_flashdata('alerta', "Hubo un error intente de nuevo");
                    break;
            }

            redirect('/tpoadminv1/usuarios/usuarios/busqueda_rol');
        }
    }

    function eliminar_rol()
    {
        $this->load->model('tpoadminv1/usuarios/Usuario_model');

        $eliminar = $this->Usuario_model->elimina_rol($this->uri->segment(5));

        if($eliminar == 1){
            $this->session->set_flashdata('exito', "Registro eliminado correctamente");
        }else {
            $this->session->set_flashdata('error', "Registro no se pudo eliminar");
        }

        redirect('/tpoadminv1/usuarios/usuarios/busqueda_rol');
    }

    function edita_rol() 
    {
        //Revisamos que tenga los permisos para entrar a esta pantalla
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
                
        $data['title'] = "Roles";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'usuarios'; // solo active 
        $data['subactive'] = 'busqueda_rol'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/usuarios/edita_rol';
        $data['scripts'] = "";
        $data['rol'] = $this->Usuario_model->dame_rol_id($this->uri->segment(5));
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_edita_rol()
    {
        //Revisamos que tenga los permisos para entrar a esta pantalla
        
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
        $this->load->library('form_validation');
		
        $this->form_validation->set_rules('nombre_rol', 'Ingresa nombre', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('descripcion_rol', 'Ingresa una descripcion', 'trim|required|min_length[3]');
        $this->form_validation->set_error_delimiters('<p>','</p>');
        
        $data['title'] = "Roles";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'usuarios'; // solo active 
        $data['subactive'] = 'busqueda_rol'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/usuarios/edita_rol';
        $data['rol'] = $this->Usuario_model->dame_rol_id($this->input->post('id_rol'));
        //$data['rol'] = $this->input->post('id_rol');
        $data['scripts'] = "";
        
        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }
        else 
        {
            $edita = $this->Usuario_model->edita_rol();
            
            switch ($edita) 
            {
                case 1: $this->session->set_flashdata('exito', "El Rol " . $this->input->post('nombre_rol') . " se ha actualizado correctamente");
                    break;
                case 2: $this->session->set_flashdata('error', "El Rol " . $this->input->post('nombre_rol') . " ya existe, ingresa otro");
                    break;
                default: $this->session->set_flashdata('alerta', "Hubo un error intente de nuevo");
                    break;
            }

            //$this->load->view('tpoadminv1/includes/template', $data);
            redirect('/tpoadminv1/usuarios/usuarios/busqueda_rol');
        }
    }
}
