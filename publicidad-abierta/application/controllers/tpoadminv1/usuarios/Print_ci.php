<?php

/**
 * Description of Print_CI
 *
 * @author acruz
 */

if (!defined('BASEPATH')) 
{
    exit('No direct script access allowed');
}

class Print_CI extends CI_Controller
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
         /*
         $is_logged_in = $this->session->userdata('is_logged_in');
         if (!isset($is_logged_in) || $is_logged_in != true) {
             //redirect('cms/cms/no_access');
             redirect('cms/cms');
         }
         */
     }
     
     // Funcion para cerrar session
     function logout() 
     {
         $this->session->sess_destroy();
         $this->session->sess_create();
         redirect('/');
     }


    function print_usuarios()
    {
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
        
        $data['title'] = "Usuarios";
        $data['registros'] = $this->Usuario_model->dame_todos_usuarios();
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'conteo',
            'username',
            'sujeto_nombre',
            'email',
            'fname',
            'lname',
            'rol_user',
            'active'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Usuario',
            'Sujeto Obligado',
            'Email',
            'Nombre',
            'Apellido',
            'Rol',
            'Estatus'
        );

        $this->load->view('tpoadminv1/includes/print_template', $data);
    }


    function print_roles()
    {
        $this->load->model('tpoadminv1/usuarios/Usuario_model');
        
        $data['title'] = "Roles";
        $data['registros'] = $this->Usuario_model->dame_todos_roles();
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id_rol',
            'nombre_rol',
            'descripcion_rol',
            'active'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Nombre',
            'Descripcion',
            'Estatus'
        );

        $this->load->view('tpoadminv1/includes/print_template', $data);
    }


    function print_sujetos()
    {
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
        
        $data['title'] = "Sujetos Obligados";
        $data['registros'] = $this->Sujeto_model->dame_todos_sujetos();

        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'conteo',
            'orden',
            'estado',
            'nombre_sujeto_obligado',
            'siglas_sujeto_obligado',
            'url_sujeto_obligado',
            'estatus'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Orden',
            'Estado',
            'Nombre',
            'Siglas',
            'URL',
            'Estatus'
        );

        $this->load->view('tpoadminv1/includes/print_template', $data);
    }


    function print_bitacora()
    {
        $this->load->model('tpoadminv1/bitacora/Bitacora_model');
        
        $data['title'] = "Bitacora";
        $data['registros'] = $this->Bitacora_model->dame_todos_bitacora();

        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id_bitacora',
            'usuario_nombre',
            'seccion_bitacora',
            'accion_bitacora',
            'fecha_bitacora'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Usuario',
            'Seccion',
            'Accion',
            'Fecha'
        );

        $this->load->view('tpoadminv1/includes/print_template', $data);
    }


    function print_campanas()
    {
        $this->load->model('tpoadminv1/campanas/Campana_model');
        
        $data['title'] = "Campañas y avisos institucionales";
        $data['registros'] = $this->Campana_model->dame_todas_campanas_avisos();

        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'conteo',
            'nombre_campana_tipo',
            'nombre_campana_subtipo',
            'nombre_campana_aviso',
            'nombre_ejercicio',
            'nombre_trimestre',
            'nombre_so_solicitante',
            'nombre_so_contratante',
            'active',
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Tipo',
            'Subtipo',
            'Nombre',
            'Ejercicio',
            'Trimestre',
            'Sujeto Obligado Solicitante',
            'Sujeto Obligado Contratante',
            'Estatus'
        );

        $this->load->view('tpoadminv1/includes/print_template', $data);
    }


    function exportar_campanas(){
        
        //Validamos que el usuario tenga acceso
        $this->permiso_capturista();

        $this->load->model('tpoadminv1/campanas/Campana_model');
        
        $data['title'] = "Creando archivo campanasyavisos.csv";
        $data['scripts'] = "<script type='text/javascript'>" .
                                "$(function () {" .
                                    "preparar_exportacion();" .
                                " })".
                            "</script>";

        $this->load->view('tpoadminv1/includes/export_template', $data);
    }

    function permiso_capturista()
    {
        //Revisamos que el usuario sea administrador
        if($this->session->userdata('usuario_rol') != '2')
        {
            redirect('tpoadminv1/securecms/sin_permiso');
        }
    }

}

?>