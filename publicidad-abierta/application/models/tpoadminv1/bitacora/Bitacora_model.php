<?php

/* 
BITACORA MODEL
 */

class Bitacora_Model extends CI_Model
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

    function descarga_bitacora()
    {
        $filename = 'dist/csv/usuarios.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        //$this->db->where('active', '1');
        $query = $this->db->get('bitacora');
        $csv_header = array('#',
                    'Usuario',
                    utf8_decode('Sección'),
                    utf8_decode('Acción'),
                    'Fecha y Hora',
                //utf8_decode('Usuario'),
                );
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_bitacora']),
                    utf8_decode($this->dame_usuario_nombre($row['usuario_bitacora'])),
                    utf8_decode($row['seccion_bitacora']),
                    utf8_decode($row['accion_bitacora']),
                    utf8_decode($row['fecha_bitacora'])
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

	function dame_todos_bitacora()
    {
        $this->db->order_by('fecha_bitacora', 'desc');
        $query = $this->db->get('bitacora');
        
        if($query->num_rows() > 0)
        {
            $array_bitacora = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                $usuario_id = $row['usuario_bitacora'];

                $usuario_nombre = $this->dame_usuario_nombre($usuario_id);

                $array_bitacora[$cont]['id_bitacora'] = $row['id_bitacora'];
                $array_bitacora[$cont]['usuario_nombre'] = $this->dame_usuario_nombre($row['usuario_bitacora']);
                $array_bitacora[$cont]['seccion_bitacora'] = $row['seccion_bitacora'];
                $array_bitacora[$cont]['accion_bitacora'] = $row['accion_bitacora'];
                $array_bitacora[$cont]['fecha_bitacora'] = $row['fecha_bitacora'];
                $cont++;
            }
            return $array_bitacora;
        }
    }


    function dame_usuario_nombre($usuario_id)
    {
        $this->db->select('username');
        $this->db->where('id_user', $usuario_id);
        $estatus = array('a', 'i');
        $this->db->where_in('active', $estatus);
        $query = $this->db->get('sec_users');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row){
            }
            return $row['username'];
        }
        
    }

    function dame_todos_usuarios()
    {
        
        //$this->db->where('usuario_estatus', 'A');
        //$this->db->or_where('usuario_estatus', 'I');
        
        //$estatus = array('A', 'I');
        //$this->db->where_in('usuario_estatus', $estatus);
        
        $this->db->where('active', 'a');
        $query = $this->db->get('sec_users');
        
        if($query->num_rows() > 0)
        {
            $array_usuarios = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                $sujeto_id = $row['record_user'];

                $orden_nombre = $this->dame_sujeto_nombre($sujeto_id);

                $array_usuarios[$cont]['id_user'] = $row['id_user'];
                $array_usuarios[$cont]['username'] = $row['username'];
                $array_usuarios[$cont]['sujeto_nombre'] = $orden_nombre;
                $array_usuarios[$cont]['email'] = $row['email'];
                $array_usuarios[$cont]['fname'] = $row['fname'];
                $array_usuarios[$cont]['lname'] = $row['lname'];
                $array_usuarios[$cont]['active'] = $row['active'];
                $cont++;
            }
            return $array_usuarios;
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

    
    function get_estatus_name($estatus)
    {
        if($estatus == 1){
            return 'Activo';
        }else if($estatus == 2){
            return 'Inactivo';
        }else if($estatus == 3){
            return 'En Proceso';
        }else if($estatus == 4){
            return 'Pago Emitido';
        }else {
            return '-nada-';
        }
    }


    function dame_usuario_id($id_user)
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

    function guardar_bitacora_general($registro)
    {
        /**   
         *  $registro = array(
         *      'seccion' => 'referente al módulo',
         *      'accion' => 'describe la accion que se ejecuto',
         *  );
         * */ 

        $data = array(
            'id_bitacora' => '0',
            'usuario_bitacora' => $this->session->userdata('usuario_id'),
            'seccion_bitacora' => $registro['seccion'],
            'accion_bitacora' => $registro['accion'],
            'fecha_bitacora' => date("Y-m-d H:i:s")
        );

        $this->db->insert('bitacora', $data);
        
        if($this->db->affected_rows() > 0)
        {
            return 1; 
        }
    }
    
}