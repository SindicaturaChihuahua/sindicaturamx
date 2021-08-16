<?php

/*
INAI / MEMBERSHIP
 */

class Membership_model extends CI_Model
{

    function validate_cms() 
    {
        $this->db->where('active', 'a');
        $this->db->where('username', $this->input->post('username'));
        //$this->db->where('password', sha1($this->input->post('password')));
        $query = $this->db->get('sec_users');

        if ($query->num_rows() == 1)
        {
            $usuario_data = [];
            
            foreach ($query->result_array() as $row)
            {
                //Si el usuario existe, entonces validamos que la contraseña sea correcta
                if($row['password'] == sha1($this->input->post('password')))
                {
                    $nombre_rol = $this->dame_nombre_rol($row['rol_user']);
                    $id_so_atribucion = $this->dame_sujeto_atribucion($row['record_user']);

                    $usuario_data['usuario_id'] = $row['id_user'];
                    $usuario_data['usuario_alias'] = $row['username'];
                    $usuario_data['usuario_email'] = $row['email'];
                    $usuario_data['usuario_nombre'] = $row['username'];
                    $usuario_data['usuario_rol'] = $row['rol_user'];
                    $usuario_data['usuario_rol_nombre'] = $nombre_rol;
                    $usuario_data['usuario_id_so_atribucion'] = $id_so_atribucion;

                    return $usuario_data;
                }
                else
                {
                    return 2;   //La contraseña es incorrecta    
                }
            }
        }
        else
        {
            return 1; 
        }
    }


    function dame_nombre_rol($id_rol)
    {
        $this->db->select('nombre_rol');
        $this->db->where('id_rol', $id_rol);
        $query = $this->db->get('cat_roles_administracion');

        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) {
                $nombre_rol = $row['nombre_rol'];
            }
            
            return $nombre_rol;
        }
    }

    function dame_sujeto_atribucion($sujeto_id)
    {
        $this->db->select('id_so_atribucion');
        $this->db->where('id_sujeto_obligado', $sujeto_id);
        $query = $this->db->get('tab_sujetos_obligados');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row){
            }
            return $row['id_so_atribucion'];
        }
        else
        {
            return 0;
        }
    }

}
