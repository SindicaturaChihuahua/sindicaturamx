<?php

/*
 * INAI TPO
 */

/**
 * Description of Sujetos
 *
 * @author acruz
 */

if (!defined('BASEPATH')) 
{
    exit('No direct script access allowed');
}

class Bitacora extends CI_Controller
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
            //redirect('cms/cms/no_access');
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

    function ejemplo_tabla() 
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/bitacora/Bitacora_model');
        $this->load->model('tpoadminv1/usuarios/Usuario_model');

        $data['title'] = "Bitacora";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "Desglose de actividades";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'bitacora'; // solo active 
        $data['subactive'] = 'busqueda_bitacora'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/bitacora/prueba_tabla';
        $data['bitacora'] = $this->Bitacora_model->dame_todos_bitacora();
        $data['usuarios'] = $this->Usuario_model->dame_todos_usuarios();

        $print_url = base_url() . "index.php/tpoadminv1/usuarios/print_ci/print_bitacora";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        $data['path_file_csv'] = $this->Bitacora_model->descarga_bitacora();
        $data['name_file_csv'] = "bitacora_csv.csv";

        $data['scripts'] = "<script type='text/javascript'>
                            $(function () {
                                $('#example2').dataTable({
                                    'bPaginate': true,
                                    'bLengthChange': false,
                                    'bFilter': true,
                                    'bSort': true,
                                    'bInfo': true,
                                    'bAutoWidth': false,
                                    
                                    'oLanguage': { 
                                        'sSearch': 'Busqueda  ',
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
                            });


                            $(document).ready(function(){
                                $('#cupon_tipo').on('change',function(){
                                    var cat_id = $(this).val();
                                    var datos = 'cat_id=' + cat_id;  //pasamos los valores
                                    
                                    if(cat_id == 'todos')
                                    {
                                        $('#example123').hide(); //oculto mediante id
                                        $('#seleccion').hide(); //oculto mediante id

                                        $('#example').show(); //mostrar el elemento de este id
                                    }

                                    if(cat_id == 'TOTAL')
                                    {
                                        $('#seleccion').hide(); //oculto mediante id
                                        $('#cupon_prod').hide(); //oculto mediante id
                                        
                                        $('#example').show(); //mostrar el elemento de este id
                                        $('#informacion').show(); //mostrar el elemento de este id
                                        $('#informacion').html('<p> Se hara un descuento en el total de la compra </p>');

                                    }
                                    
                                    if(cat_id == 'usuario')
                                    {
                                        $('#example').hide(); //oculto mediante id
                                        $('#cupon_prod').hide(); //oculto mediante id
                                        
                                        $('#seleccion').show(); //mostrar el elemento de este id
                                        
                                        $.ajax({
                                            type:'POST',
                                            url:'".site_url('tpoadminv1/bitacora/bitacora/busqueda_usuarios')." ',
                                            data: datos,
                                            success:function(html){
                                                $('#cupon_aplica').html(html); 
                                            }
                                        }); 
                                    }
                                    
                                    if(cat_id == 'PRODUCTO')
                                    {
                                        $('#seleccion').hide(); //oculto mediante id
                                        $('#informacion').hide(); //oculto mediante id
                                        
                                        $('#cupon_prod').show(); //mostramos este id para que se carge el input
                                        $('#cupon_prod').html('<input type=\"tex\" class=\"form-control\" placeholder=\"Ingrese clave del producto\" name=\"cupon_aplica\" id=\"cupon_aplica\">');
                                    }
                                });
                            });
                            </script>";
        
        $this->load->view('tpoadminv1/includes/template', $data);
    }
    



	function busqueda_bitacora() 
    {
        //Validamos que el usuario tenga acceso
        $permiso = $this->permiso_administrador();
        
        $this->load->model('tpoadminv1/bitacora/Bitacora_model');
        $this->load->model('tpoadminv1/usuarios/Usuario_model');

        $data['title'] = "Bit&aacute;cora";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_rol_nombre');
        $data['active'] = 'bitacora'; // solo active 
        $data['subactive'] = 'busqueda_bitacora'; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/bitacora/busqueda_bitacora';
        $data['bitacora'] = $this->Bitacora_model->dame_todos_bitacora();
        $data['usuarios'] = $this->Usuario_model->dame_todos_usuarios();

        $print_url = base_url() . "index.php/tpoadminv1/usuarios/print_ci/print_bitacora";
        $data['print_onclick'] = "onclick=\"window.open('" . $print_url . "', '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes')\"";
        $data['path_file_csv'] = $this->Bitacora_model->descarga_bitacora();
        $data['name_file_csv'] = "bitacora_csv.csv";

        $data['scripts'] = "<script type='text/javascript'>
                            $(function () {
                                $('#example25').dataTable({
                                    'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, 'Todo']], 


                                    'bPaginate': true,
                                    'bLengthChange': false,
                                    'bFilter': true,
                                    'bSort': true,
                                    'bInfo': true,
                                    'bAutoWidth': false,
                                    
                                    'oLanguage': { 
                                        'sSearch': 'Busqueda  ',
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

    function descarga_bitacora()
    {
        $this->load->model('tpoadminv1/sujetos/Sujetos_model');
    
        $data['sujeto'] = $this->Sujeto_model->descarga_sujeto();
        print_r($data['usuario']);
        die();
    }


    function busqueda_usuarios()
    {
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
        
        $result = $this->Usuario_model->dame_todos_usuarios();
        
        if($result != '0')
        {
            $sel_usuario = '';
            
            echo '<option value="0">Selecciona un usuario...</option>';
            
            for($z = 0; $z < sizeof($result); $z++)
            {
                $sel_usuario .= '<option value="' . $result[$z]['username'] . '">'.$result[$z]['fname']. '</option>';
            }
            
            echo $sel_usuario;
        }
        else
        {
            echo '<option value="0">No hay valores</option>';
        }
    }
}
