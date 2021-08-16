<?php

/*
 INAI / SECURECMS
 */

/**
 * Description of SecureCms 
 *
 * @author acruz
 */

if (!defined('BASEPATH')) 
{
    exit('No direct script access allowed');
}

class Securecms extends CI_Controller 
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
            //echo 'No tienes acceso a este sitio. <a href="../login">Login</a>';
            //die();
    //        redirect('cms/no_access');
        }
        
    }

    //Guardamos el cierre de sesion en la bitacora
    function guardar_bitacora($accion)
    {
        $data = array(
            'id_bitacora' => '0',
            'usuario_bitacora' => $this->session->userdata('usuario_id'),
            'seccion_bitacora' => 'Login',
            'accion_bitacora' => $accion,
            'fecha_bitacora' => date("Y-m-d H:i:s")
        );

        $this->db->insert('bitacora', $data);
        
        if($this->db->affected_rows() > 0)
        {
            return 1; 
        }
    }




    // Funcion para cerrar session
    function logout() 
    {
        $bitacora = $this->guardar_bitacora('Salida del sistema');
        if($bitacora == '1')
        {
            $this->session->sess_destroy();
            redirect('tpoadminv1/cms/');
        }
    }

    function principal() 
    {
        $data['title'] = "Dashboard";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = ''; // solo active 
        $data['subactive'] = ''; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/admin';
        $data['scripts'] = " ";
        
        $this->load->view('tpoadminv1/usuarios/busqueda_usuario', $data);
    }

    function sin_permiso()
    {
        $data['title'] = "Sin permiso";
        $data['heading'] = $this->session->userdata('usuario_nombre');
        $data['mensaje'] = "";
        $data['job'] = $this->session->userdata('usuario_job');
        $data['active'] = 'usuarios'; // solo active 
        $data['subactive'] = ''; // class="active"
        $data['body_class'] = 'skin-blue';
        $data['main_content'] = 'tpoadminv1/sin_permisos';
        $data['error_number'] = '403';
        $data['error_description'] = 'No cuentas con el permiso sufiente para acceder a esta &aacute;rea ';
        $data['scripts'] = " ";
        $this->load->view('tpoadminv1/includes/template', $data);
    }

}
