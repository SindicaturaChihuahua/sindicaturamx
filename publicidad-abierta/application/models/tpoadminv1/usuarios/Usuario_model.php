<?php

/* 
INAI / USUARIO MODEL
*/

class Usuario_Model extends CI_Model
{

    function guardar_bitacora($accion)
    {
        $data = array(
            'id_bitacora' => '0',
            'usuario_bitacora' => $this->session->userdata('usuario_id'),
            'seccion_bitacora' => 'Usuarios',
            'accion_bitacora' => $accion,
            'fecha_bitacora' => date("Y-m-d H:i:s")
        );

        $this->db->insert('bitacora', $data);
        
        if($this->db->affected_rows() > 0)
        {
            return 1; 
        }
    }


	function dame_todos_sujetos_obligados()
    {
        //$this->db->where('usuario_estatus', 'A');
        //$this->db->or_where('usuario_estatus', 'I');
        
        //$estatus = array('A', 'I');
        //$this->db->where_in('usuario_estatus', $estatus);
        
        $this->db->where('active', '1');
        $query = $this->db->get('tab_sujetos_obligados');
        
        if($query->num_rows() > 0)
        {
            $array_usuarios = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
                {
                    $array_usuarios[$cont]['funcion'] = $row['funcion'];
                    $array_usuarios[$cont]['orden'] = $row['orden'];
                    $array_usuarios[$cont]['estado'] = $row['estado'];
                    $array_usuarios[$cont]['nombre'] = $row['nombre_sujeto_obligado'];
                    $array_usuarios[$cont]['siglas'] = $row['siglas_sujeto_obligado'];
					$array_usuarios[$cont]['url_pagina'] = $row['url__sujeto_obligado'];
					$array_usuarios[$cont]['estatus'] = $row['estatus'];
                    $cont++;
            }
            return $array_usuarios;
        }
    }

    function descarga_usuarios()
    {
        $filename = 'dist/csv/usuarios.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('sec_users');
        $csv_header = array('#',
                    'Usuario',
                    'Email',
                    'Nombre',
                    'Apellido',
                    'Sujeto Obligado',
                    'Estatus'
                //utf8_decode('Usuario'),
                );
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            $cont = 1;
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($cont),
                    utf8_decode($row['username']),
                    utf8_decode($row['email']),
                    utf8_decode($row['fname']),
                    utf8_decode($row['lname']),
                    utf8_decode($this->dame_sujeto_nombre($row['record_user'])),
                    utf8_decode($this->get_estatus_nombre($row['active']))
                );
                fputcsv($myfile, $csv);
                $cont += 1;
            }
        }

        fclose($myfile);

        return $filename;
    }


    function descarga_roles()
    {
        $filename = 'dist/csv/usuarios.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $this->db->where('active', 'a');
        $query = $this->db->get('cat_roles_administracion');
        $csv_header = array('#',
                    'Nombre',
                    'Descripcion',
                    'Estatus'
                //utf8_decode('Usuario'),
                );
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_rol']),
                    utf8_decode($row['nombre_rol']),
                    utf8_decode($row['descripcion_rol']),
                    utf8_decode($this->get_estatus_nombre($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_todos_usuarios()
    {
        $estatus = array('a', 'i');
        $this->db->where_in('active', $estatus);
        $query = $this->db->get('sec_users');
        
        if($query->num_rows() > 0)
        {
            $array_usuarios = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                $array_usuarios[$cont]['conteo'] = $cont+1;
                $array_usuarios[$cont]['id_user'] = $row['id_user'];
                $array_usuarios[$cont]['username'] = $row['username'];
                $array_usuarios[$cont]['sujeto_nombre'] = $this->dame_sujeto_nombre($row['record_user']);
                $array_usuarios[$cont]['email'] = $row['email'];
                $array_usuarios[$cont]['fname'] = $row['fname'];
                $array_usuarios[$cont]['lname'] = $row['lname'];
                $array_usuarios[$cont]['rol_user'] = $this->dame_rol_nombre($row['rol_user']);
                $array_usuarios[$cont]['active'] = $this->get_estatus_nombre($row['active']);
                $cont++;
            }
            return $array_usuarios;
        }
    }

    function dame_rol_nombre($rol_id)
    {
        $this->db->select('nombre_rol');
        $this->db->where('id_rol', $rol_id);
        $this->db->where('active', 'a');
        $query = $this->db->get('cat_roles_administracion');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row){
            }
            return $row['nombre_rol'];
        }
    }


    function dame_sujeto_nombre($sujeto_id)
    {
        $this->db->select('nombre_sujeto_obligado');
        $this->db->where('id_sujeto_obligado', $sujeto_id);
        $this->db->where('active', '1');
        $query = $this->db->get('tab_sujetos_obligados');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row){
            }
            return $row['nombre_sujeto_obligado'];
        }
    }


    function get_usuario_id($id_user)
    {
        $this->db->where('active', 'a');
        //$this->db->where('usuario_estatus', 'A');
        //$this->db->or_where('usuario_estatus', 'I');
        
        $this->db->where('id_user', $id_user);
        
        $query = $this->db->get('sec_users');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }

    }

    
    function get_estatus_nombre($estatus)
    {
        switch ($estatus) 
        {
            case 'a':  return 'Activo';
                break;
            case 'b':  return 'Inactivo';
                break;
            case 'i':  return 'Inactivo';
                break;
            case '1':  return 'Activo';
                break;
            case '2':  return 'Inactivo';
                break;
            default: return 'Activo';
                break;
        }
    }


    function dame_usuario_id($id_user)
    {
        
        $estatus = array('a', 'i');
        $this->db->where_in('active', $estatus);
        
        $this->db->where('id_user', $id_user);
        
        $query = $this->db->get('sec_users');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }
        
    }
    

    function alta_usuario()
    {
        //Validamos que no exista un usuario con el correo

        $estatus = array('a', 'i');
        $this->db->where_in('active', $estatus);
        $this->db->where('email', $this->input->post('email'));
        $query = $this->db->get('sec_users');
        
        if ($query->num_rows() > 0)
        {
            return 3;
        }

        //Validamos que no exista un usuario con el username 
        $this->db->where('username', $this->input->post('username'));
        $this->db->where('active', 'a');
        $query = $this->db->get('sec_users');
        
        if ($query->num_rows() > 0)
        {
            return 2;
        }

        $data = array(
            'id_user' => '0',
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'phone' => $this->input->post('phone'),
            'password' => sha1($this->input->post('password')),
            'active' => 'a',
            'record_user' => $this->input->post('record_user'),
            'rol_user' => $this->input->post('rol_user'),
            'active' => $this->input->post('active'),
            'created' => date("Y-m-d")
        );

        $this->db->insert('sec_users', $data);
        
        if($this->db->affected_rows() > 0)
        {
            $username = $this->dame_ultimo_usuario(0);
            
            $bitacora = $this->guardar_bitacora('Alta del usuario: '.$username);
            if($bitacora == '1')
            {
                return 1; 
            }
        }
    }


    function edita_usuario($id_user)
    {
        //Validamos que no este dado de alta un usuario con ese correo
        $estatus = array('a', 'i');
        $this->db->where_in('active', $estatus);
        $this->db->where('email', $this->input->post('email'));
        $this->db->where('id_user !=', $this->input->post('id_user'));
        $query = $this->db->get('sec_users');
        
        if ($query->num_rows() > 0)
        {
            return 3;
        }

        //Validamos que no exista un usuario con el username
        $estatus = array('a', 'i');
        $this->db->where_in('active', $estatus);
        $this->db->where('username', $this->input->post('username'));
        $this->db->where('id_user !=', $this->input->post('id_user'));
        $query = $this->db->get('sec_users');
        
        if ($query->num_rows() > 0)
        {
            return 2;
        }

        //Revisamos si el campo contraseña fue modificado
        if($this->input->post('password') != '')
        {
            $data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'fname' => $this->input->post('fname'),
                'lname' => $this->input->post('lname'),
                'phone' => $this->input->post('phone'),
                'password' => sha1($this->input->post('password')),
                'active' => $this->input->post('active'),
                'record_user' => $this->input->post('record_user'),
                'rol_user' => $this->input->post('rol_user')
            );
        }
        else
        {
            $data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'fname' => $this->input->post('fname'),
                'lname' => $this->input->post('lname'),
                'phone' => $this->input->post('phone'),
                'active' => $this->input->post('active'),
                'record_user' => $this->input->post('record_user'),
                'rol_user' => $this->input->post('rol_user')
            );
        }

        $this->db->where('id_user', $id_user);
        $this->db->update('sec_users', $data);
        
        if($this->db->affected_rows() > 0)
        {
            $username = $this->dame_username($id_user);

            $bitacora = $this->guardar_bitacora('Edición del usuario: '.$username);
            if($bitacora == '1')
            {
                return 1; 
            }
        }
    }
    
    function existe_usuario()
    {
        $this->db->where('usuario_alias', $this->input->post('username'));
        $this->db->or_where('usuario_email', $this->input->post('email'));
        $query = $this->db->get('usuarios');

        if ($query->num_rows() == 1)
        {
            return false;
        }
    }
    
    function dame_ultimo_usuario($id_user)
    {
        $this->db->limit(1);
        $this->db->order_by('id_user', 'DESC');
        $query = $this->db->get('sec_users');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $username = $row['username'];
            }
            return $username;
        }
    }

    function elimina_usuario($id)
    {
        $usuario_eliminado = $this->dame_usuario_id($id);
        $this->db->where('id_user', $id);
        $this->db->delete('sec_users');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($usuario_eliminado))
                $this->guardar_bitacora('Eliminación del usuario : ' . $usuario_eliminado['username']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function dame_username($id_user)
    {
        //$this->db->where('active', 'e');
        $this->db->where('id_user', $id_user);
        $query = $this->db->get('sec_users');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $username = $row['username'];
            }
            return $username;
        }
    }



    function descarga_usuario()
    {
        $myfile = fopen(FCPATH . 'dist/csv/usuarios.csv', 'w');
        
        $query = $this->db->get('sec_users');
        
        $csv_header = array('#',utf8_decode('Cobertura de campaña'));
        fputcsv($myfile, $csv_header);
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $csv = array(
                utf8_decode($row['id_user']),
                utf8_decode($row['username']),
                //utf8_decode($this->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }
        fclose($myfile);
    }

    
    function descarga_usuario2()
    {
        $myfile = fopen(FCPATH . 'dist/csv/usuarios.csv', 'w');
        $csv = '';
        $csv .= "Nombre".","."Apellido".","."Email".","."Telefono".","."Usuario".PHP_EOL;
		
        //$this->db->where('active', 'a');
        $query = $this->db->get('sec_users');
            
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $sujeto_id = $row['record_user'];
                $orden_nombre = $this->dame_sujeto_nombre($sujeto_id);

                $csv .= utf8_decode($row['fname']).',';
                $csv .= utf8_decode($row['lname']).',';
                $csv .= utf8_decode($row['email']).',';
                $csv .= utf8_decode($row['phone']).',';
                $csv .= utf8_decode($row['username']).',';
                $csv .= utf8_decode($orden_nombre);
                $csv .= PHP_EOL;
            }
        }

        fwrite($myfile, $csv);
        fclose($myfile);
    }


    function dame_todos_roles()
    {
        //$this->db->where('active', 'a');
        $query = $this->db->get('cat_roles_administracion');
        
        if($query->num_rows() > 0)
        {
            $array_roles = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                $array_roles[$cont]['id_rol'] = $row['id_rol'];
                $array_roles[$cont]['nombre_rol'] = $row['nombre_rol'];
                $array_roles[$cont]['descripcion_rol'] = $row['descripcion_rol'];
                $array_roles[$cont]['active'] = $row['active'] == 'a' ? 'Activo' : 'Inactivo';
                $cont++;
            }
            return $array_roles;
        }
    }


    function alta_rol()
    {
        $this->db->where('nombre_rol', $this->input->post('nombre_rol'));
        $this->db->where('active', 'a');
        $query = $this->db->get('cat_roles_administracion');
        
        if ($query->num_rows() > 0)
        {
            return 2;
        }
        
        $data = array(
            'id_rol' => '0',
            'nombre_rol' => $this->input->post('nombre_rol'),
            'descripcion_rol' => $this->input->post('descripcion_rol'),
            'active' => 'a',
            'fecha_rol' => date("Y-m-d")
        );

        $this->db->insert('cat_roles_administracion', $data);
        
        if($this->db->affected_rows() > 0)
        {
            //Guardamos accion en la bitacora
            $bitacora = $this->guardar_bitacora('Alta Rol');
            if($bitacora == '1')
            {
                return 1; 
            }

        }
    }


    function dame_rol_id($id_rol)
    {
        //$this->db->where('active', 'a');
        $this->db->where('id_rol', $id_rol);
        $query = $this->db->get('cat_roles_administracion');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }
        
    }

    function edita_rol()
    {
        //Validamos que no este dado de alta un rol con el mismo nombre
        $this->db->where('nombre_rol', $this->input->post('nombre_rol'));
        //$this->db->where('active', 'a');
        $this->db->where('id_rol != ', $this->input->post('id_rol'));
        $query = $this->db->get('cat_roles_administracion');
        
        if ($query->num_rows() > 0)
        {
            return 2;
        }
        

        $data = array(
            'nombre_rol' => $this->input->post('nombre_rol'),
            'descripcion_rol' => $this->input->post('descripcion_rol'),
            'active' => $this->input->post('active'),
        );

        $this->db->where('id_rol', $this->input->post('id_rol'));
        $this->db->update('cat_roles_administracion', $data);
        
        if($this->db->affected_rows() > 0)
        {
           //Guardamos accion en la bitacora
           $bitacora = $this->guardar_bitacora('Edición Rol');
           if($bitacora == '1')
           {
               return 1; 
           }
        }
    }

    function elimina_rol($id_rol)
    {
        $data = array(
            'active' => 'i',
        );

        $this->db->where('id_rol', $id_rol);
        $this->db->update('cat_roles_administracion', $data);
        
        if($this->db->affected_rows() > 0)
        {
            //Guardamos accion en la bitacora
            $bitacora = $this->guardar_bitacora('Eliminación Rol');
            if($bitacora == '1')
            {
                return 1; 
            }            
        }
    }

    function Is_Rol_Active($id_rol)
    {
        $this->db->where('id_rol', $id_rol);
        $query = $this->db->get('cat_roles_administracion');
        
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) { }
            return $row['active'] == 'a' ? true : false;       
        }
        return false;
    }

}