<?php


/**
 * Description of Servicios
 *
 * INAI TPO
 */

if (!defined('BASEPATH')) 
{
    exit('No direct script access allowed');
}

class Servicios extends CI_Controller
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

    function permiso_capturista()
    {
        //Revisamos que el usuario sea administrador
        if($this->session->userdata('usuario_rol') != '2')
        {
            redirect('tpoadminv1/securecms/sin_permiso');
        }
    }
    
    function busqueda_clasificacion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Clasificaci&oacute;n del servicio";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_clasificacion"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/clasificacion';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_clasificaciones";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['clasificaciones'] = $this->Catalogos_model->dame_todas_clasificaciones();
        $data['path_file_csv'] = $this->Catalogos_model->descarga_clasificaciones();
        $data['name_file_csv'] = "clasificacion_servicio.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#clasificaciones').dataTable({" .
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

    function agregar_clasificacion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar clasificaci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_clasificacion"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_clasificacion';

        $data['clasificacion_nombre'] = '';
        $data['clasificacion_estatus'] = 'null';

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_clasificacion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_servicio_clasificacion', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar clasificaci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_clasificacion"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_clasificacion';
        
        $data['clasificacion_nombre'] = $this->input->post('nombre_servicio_clasificacion');
        $data['clasificacion_estatus'] = $this->input->post('active');
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_clasificacion();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "La clasificacion " . $this->input->post('nombre_servicio_clasificacion') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La clasificacion " . $this->input->post('nombre_servicio_clasificacion') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/servicios/busqueda_clasificacion');
            } 
        }
    }

    function editar_clasificacion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar clasificaci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_clasificacion"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_clasificacion';
        
        $data['clasificacion'] = $this->Catalogos_model->dame_clasificacion_id($this->uri->segment(5));

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_clasificacion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_servicio_clasificacion', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar clasificaci&oacute;n";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_clasificacion"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_clasificacion';
        
        $data['clasificacion'] = $this->Catalogos_model->dame_clasificacion_id($this->input->post('id_servicio_clasificacion'));

        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_clasificacion();
            if($editar == 1){
                $this->session->set_flashdata('exito', "La clasificacion " . $this->input->post('nombre_servicio_clasificacion') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La clasificacion " . $this->input->post('nombre_servicio_clasificacion') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/servicios/busqueda_clasificacion');
            }
        }
    }

    function eliminar_clasificacion()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_servicio_clasificacion', 'tab_facturas_desglose');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de facturas.");
        }else{
            $eliminar = $this->Catalogos_model->eliminar_clasificacion($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }
        redirect('/tpoadminv1/catalogos/servicios/busqueda_clasificacion');
    }

    function busqueda_categorias()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Categor&iacute;as";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_categoria"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/categorias';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_categorias";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['categorias'] = $this->Catalogos_model->dame_todas_categorias();
        $data['path_file_csv'] = $this->Catalogos_model->descarga_categorias();
        $data['name_file_csv'] = "categorias.csv";
        
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#categorias').dataTable({" .
                                    "'bPaginate': true," .
                                    "'bLengthChange': true," .
                                    "'bFilter': true," .
                                    "'bSort': true," .
                                    "'bInfo': true," .
                                    "'bAutoWidth': false," .
                                    "'columnDefs': [ " .
                                        "{ 'orderable': false, 'targets': [6,7,8] } " .
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

    function agregar_categoria()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar categor&iacute;a";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_categoria"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_categoria';

    
        $data['categoria_nombre'] = '';
        $data['id_clasificacion'] = '';
        $data['categoria_titulo'] = '';
        $data['categoria_color'] = '#ACACAC';
        $data['categoria_estatus'] = 'null';
        $data['clasificacion'] = $this->Catalogos_model->dame_todas_clasificaciones();
        

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_categoria()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id_servicio_clasificacion', 'Seleccione un valor', 'required');
        $this->form_validation->set_rules('nombre_servicio_categoria', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('titulo_grafica', 'Ingresa un t&iacute;tulo', 'required|min_length[3]');
        $this->form_validation->set_rules('color_grafica', 'Ingresa un color', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar categor&iacute;a";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_categoria"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_categoria';
        
        $data['categoria_nombre'] = $this->input->post('nombre_servicio_categoria');
        $data['id_clasificacion'] = $this->input->post('id_servicio_clasificacion');
        $data['categoria_titulo'] = $this->input->post('titulo_grafica');
        $data['categoria_color'] = $this->input->post('color_grafica');
        $data['categoria_estatus'] = $this->input->post('active');
        $data['clasificacion'] = $this->Catalogos_model->dame_todas_clasificaciones();
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_categoria();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "La categor&iacute;a " . $this->input->post('nombre_servicio_categoria') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La categor&iacute;a " . $this->input->post('nombre_servicio_categoria') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/servicios/busqueda_categorias');
            } 
        }
    }

    function editar_categoria()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar categor&iacute;a";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_categoria"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_categoria';
        
        $data['categoria'] = $this->Catalogos_model->dame_categoria_id($this->uri->segment(5));
        $data['clasificacion'] = $this->Catalogos_model->dame_todas_clasificaciones();

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_categoria()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id_servicio_clasificacion', 'Seleccione un valor', 'required');
        $this->form_validation->set_rules('nombre_servicio_categoria', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('titulo_grafica', 'Ingresa un t&iacute;tulo', 'required|min_length[3]');
        $this->form_validation->set_rules('color_grafica', 'Ingresa un color', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar categor&iacute;a";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_categoria"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_categoria';
        
        $data['categoria'] = $this->Catalogos_model->dame_categoria_id($this->input->post('id_servicio_categoria'));
        $data['clasificacion'] = $this->Catalogos_model->dame_todas_clasificaciones();
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_categoria();
            if($editar == 1){
                $this->session->set_flashdata('exito', "La categor&iacute;a " . $this->input->post('nombre_servicio_categoria') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La categor&iacute;a " . $this->input->post('nombre_servicio_categoria') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/servicios/busqueda_categorias');
            }
        }
    }

    function eliminar_categoria()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_servicio_categoria', 'tab_facturas_desglose');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de facturas.");
        }else{
            $eliminar = $this->Catalogos_model->eliminar_categoria($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }

        redirect('/tpoadminv1/catalogos/servicios/busqueda_categorias');
    }

    function get_categoria_filtro()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_capturista();
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        
        $id_clasificacion = $this->input->post('id_servicio_clasificacion');
        $registros = $this->Catalogos_model->get_categorias_filtro($id_clasificacion);

        header('Content-type: application/json');
        
        echo json_encode( $registros );
    }

    function busqueda_subcategorias()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Subcategor&iacute;as";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_subcategoria"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/subcategorias';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_subcategorias";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['subcategorias'] = $this->Catalogos_model->dame_todas_subcategorias();
        $data['path_file_csv'] = $this->Catalogos_model->descarga_subcategorias();
        $data['name_file_csv'] = "subcategorias.csv";

        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#subcategorias').dataTable({" .
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

    function agregar_subcategoria()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar subcategor&iacute;a";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_subcategoria"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_subcategoria';

    
        $data['subcategoria_nombre'] = '';
        $data['id_categoria'] = '';
        $data['subcategoria_estatus'] = 'null';
        $data['categorias'] = $this->Catalogos_model->dame_todas_categorias();
        

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_subcategoria()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id_servicio_categoria', 'Seleccione un valor', 'required');
        $this->form_validation->set_rules('nombre_servicio_subcategoria', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar subcategor&iacute;a";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_subcategoria"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_subcategoria';
        
        $data['subcategoria_nombre'] = $this->input->post('nombre_servicio_subcategoria');
        $data['id_categoria'] = $this->input->post('id_servicio_categoria');
        $data['subcategoria_estatus'] = $this->input->post('active');
        $data['categorias'] = $this->Catalogos_model->dame_todas_categorias();
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_subcategoria();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "La subcategor&iacute;a " . $this->input->post('nombre_servicio_subcategoria') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La subcategor&iacute;a " . $this->input->post('nombre_servicio_subcategoria') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/servicios/busqueda_subcategorias');
            } 
        }
    }

    function editar_subcategoria()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar subcategor&iacute;a";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_subcategoria"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_subcategoria';
        
        $data['subcategoria'] = $this->Catalogos_model->dame_subcategoria_id($this->uri->segment(5));
        $data['categorias'] = $this->Catalogos_model->dame_todas_categorias();

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_subcategoria()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id_servicio_categoria', 'Seleccione un valor', 'required');
        $this->form_validation->set_rules('nombre_servicio_subcategoria', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar subcategor&iacute;a";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_subcategoria"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_subcategoria';
        
        $data['subcategoria'] = $this->Catalogos_model->dame_subcategoria_id($this->input->post('id_servicio_subcategoria'));
        $data['categorias'] = $this->Catalogos_model->dame_todas_categorias();
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_subcategoria();
            if($editar == 1){
                $this->session->set_flashdata('exito', "La subcategor&iacute;a " . $this->input->post('nombre_servicio_subcategoria') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La subcategor&iacute;a " . $this->input->post('nombre_servicio_subcategoria') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/servicios/busqueda_subcategorias');
            }
        }
    }

    function eliminar_subcategoria()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_servicio_subcategoria', 'tab_facturas_desglose');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de facturas.");
        }else{
            $eliminar = $this->Catalogos_model->eliminar_subcategoria($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }

        redirect('/tpoadminv1/catalogos/servicios/busqueda_subcategorias');
    }

    function get_subcategoria_filtro()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_capturista();
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        
        $id_categoria = $this->input->post('id_servicio_categoria');
        $registros = $this->Catalogos_model->get_subcategorias_filtro($id_categoria);

        header('Content-type: application/json');
        
        echo json_encode( $registros );
    }

    function busqueda_unidades()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                    
        $data['title'] = "Unidades";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_unidades"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/unidades';
        $print_url = base_url() . "index.php/tpoadminv1/print_ci/print_unidades";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        
        $data['unidades'] = $this->Catalogos_model->dame_todas_unidades();
        $data['path_file_csv'] = $this->Catalogos_model->descarga_unidades();
        $data['name_file_csv'] = "unidades.csv";

        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                "$('#unidades').dataTable({" .
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

    function agregar_unidad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');

        $data['title'] = "Agregar unidad";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_unidades"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_unidad';

    
        $data['unidad_nombre'] = '';
        $data['id_subcategoria'] = '';
        $data['unidad_estatus'] = 'null';
        $data['subcategorias'] = $this->Catalogos_model->dame_todas_subcategorias();
        

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_agregar_unidad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id_servicio_subcategoria', 'Seleccione un valor', 'required');
        $this->form_validation->set_rules('nombre_servicio_unidad', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Agregar unidad";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_unidades"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/agregar_unidad';
        
        $data['unidad_nombre'] = $this->input->post('nombre_servicio_unidad');
        $data['id_subcategoria'] = $this->input->post('id_servicio_subcategoria');
        $data['unidad_estatus'] = $this->input->post('active');
        $data['subcategorias'] = $this->Catalogos_model->dame_todas_subcategorias();
        
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $agregar = $this->Catalogos_model->agregar_unidad();
            if($agregar == 1){
                $this->session->set_flashdata('exito', "La unidad " . $this->input->post('nombre_servicio_unidad') . " se ha creado correctamente");
            }else if($agregar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La unidad " . $this->input->post('nombre_servicio_unidad') . " no se pudo agregar");
            }
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/servicios/busqueda_unidades');
            } 
        }
    }

    function editar_unidad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
                
        $data['title'] = "Editar unidad";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_unidades"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_unidad';
        
        $data['unidad'] = $this->Catalogos_model->dame_unidad_id($this->uri->segment(5));
        $data['subcategorias'] = $this->Catalogos_model->dame_todas_subcategorias();

        $data['scripts'] = "";

        $this->load->view('tpoadminv1/includes/template', $data);
    }

    function validate_editar_unidad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id_servicio_subcategoria', 'Seleccione un valor', 'required');
        $this->form_validation->set_rules('nombre_servicio_unidad', 'Ingresa un nombre', 'required|min_length[3]');
        $this->form_validation->set_rules('active', 'Seleccione un estatus valido', 'required');
        $this->form_validation->set_error_delimiters('<p>','</p>');

        $data['title'] = "Editar unidad";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'catalogos'; // solo active 
        $data['subactive'] = 'servicios'; // class="active"
        $data['optionactive'] = "busqueda_unidades"; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/catalogos/editar_unidad';
        
        $data['unidad'] = $this->Catalogos_model->dame_unidad_id($this->input->post('id_servicio_unidad'));
        $data['subcategorias'] = $this->Catalogos_model->dame_todas_subcategorias();
        $data['scripts'] = "";

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('tpoadminv1/includes/template', $data);
        }else
        {
            $redict = true;
            $editar = $this->Catalogos_model->editar_unidad();
            if($editar == 1){
                $this->session->set_flashdata('exito', "La unidad " . $this->input->post('nombre_servicio_unidad') . " se ha editado correctamente");
            }else if($editar == 2){
                $this->session->set_flashdata('alert', "El valor ya se encuentra registrado");
                $this->load->view('tpoadminv1/includes/template', $data);
                $redict = false;
            }else{
                $this->session->set_flashdata('error', "La unidad " . $this->input->post('nombre_servicio_unidad') . " no se pudo editar");
            }
            
            if($redict)
            {
                redirect('/tpoadminv1/catalogos/servicios/busqueda_unidades');
            }
        }
    }

    function eliminar_unidad()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();

        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $existe_foreign = $this->Catalogos_model->exist_register_foreign($this->uri->segment(5), 'id_servicio_unidad', 'tab_facturas_desglose');

        if($existe_foreign){
            $this->session->set_flashdata('alert', "Este registro no puede ser eliminado, ya que se encuentra ligado a registros de facturas.");
        }else{
            $eliminar = $this->Catalogos_model->eliminar_unidad($this->uri->segment(5));

            if($eliminar == 1){
                $this->session->set_flashdata('exito', "Registro eliminado correctamente");
            }else {
                $this->session->set_flashdata('error', "Registro no se pudo eliminar");
            }
        }

        redirect('/tpoadminv1/catalogos/servicios/busqueda_unidades');
    }

    function get_unidad_filtro()
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_capturista();
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        
        $id_subcategoria = $this->input->post('id_servicio_subcategoria');
        $registros = $this->Catalogos_model->get_unidades_filtro($id_subcategoria);

        header('Content-type: application/json');
        
        echo json_encode( $registros );
    }
    
}

?>