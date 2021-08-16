<?php

/* 
INAI / CAMPANAS Y AVISOS INSTITUCIONALES
 */

class Logo_Model extends CI_Model
{
    function guardar_bitacora($accion)
    {
        $data = array(
            'id_bitacora' => '0',
            'usuario_bitacora' => $this->session->userdata('usuario_id'),
            'seccion_bitacora' => 'Logo / Fecha de actualización',
            'accion_bitacora' => $accion,
            'fecha_bitacora' => date("Y-m-d H:i:s")
        );

        $this->db->insert('bitacora', $data);
        
        if($this->db->affected_rows() > 0)
        {
            return 1; 
        }
    }


    function dame_fecha_act_manual()
    {
        //$this->db->where('fecha_act', $this->input->post('fecha_act'));
        $this->db->where('active', 'a');
        $query = $this->db->get('tab_fecha_actualizacion_manual');

        if($query->num_rows() > 0)
        {
            $array_fecha_manual = [];
            foreach ($query->result_array() as $row) 
            {
                $array_fecha_manual['id_fecha_act'] = $row['id_fecha_act'];
                $array_fecha_manual['fecha_act'] = $row['fecha_act'];
                $array_fecha_manual['fecha_act'] = $row['fecha_act'];
                $array_fecha_manual['comentario_act'] = $row['comentario_act'];
                //$array_edades['active'] = $this->get_estatus_name($row['active']);
            }
            return $array_fecha_manual;
        }
        else
        {
            return 0;
        }
    }

    //Actualizamos los detalles de las pestanas
    function actualiza_fecha()
    {
        $data_act = array(
            'fecha_act' => $this->input->post('fecha_act'),
            'comentario_act' => $this->input->post('comentario_act'),
            'active' => 'a'
            //'active' => $this->input->post('active')
        );
                        
        $this->db->where('id_fecha_act', $this->input->post('id_fecha_act'));
        $this->db->update('tab_fecha_actualizacion_manual', $data_act);
                
        if($this->db->affected_rows() > 0)
        {
            //Guardamos accion en la bitacora
            $bitacora = $this->guardar_bitacora('Modificación de fecha de actualización');
            if($bitacora == '1')
            {
                return 1;   //Se ha editado correctamente
            }
            /*if($this->input->post('active') == 'i')
            {
                //Guardamos accion en la bitacora
                $bitacora = $this->guardar_bitacora('Eliminación de fecha de actualización');
                if($bitacora == '1')
                {
                    return 2;   //Se ha eliminado la fecha de actualizacion
                }
            }
            else{
                 //Guardamos accion en la bitacora
                 $bitacora = $this->guardar_bitacora('Modificación de fecha de actualización');
                 if($bitacora == '1')
                 {
                     return 1;   //Se ha editado correctamente
                 }
            }*/
        }
        else
        {
            $bitacora = $this->guardar_bitacora('Error al modificar la fecha de actualización');
            if($bitacora == '1')
            {
                return 0;   //Se ha dado de alta correctamente la fecha de actualizacion
            }
        }
    }


    function alta_fecha_manual()
    {
        $this->db->where('id_fecha_act', '1');
        $query = $this->db->get('tab_fecha_actualizacion_manual');

        $data = array(
            'id_fecha_act' => '',
            'fecha_act' => $this->input->post('fecha_act'),
            'comentario_act' => $this->input->post('comentario_act'),
            'active' => 'a',
            'fecha_reg' => date('Y-m-d')
        );

        if($query->num_rows() > 0)
        {
            $this->db->where('id_fecha_act', '1');
            $this->db->update('tab_fecha_actualizacion_manual', $data_new);
        }
        else
        {
            $this->db->insert('tab_fecha_actualizacion_manual', $data_new);   
        }

        if($this->db->affected_rows() > 0)
        {
            //Guardamos accion en la bitacora
            $bitacora = $this->guardar_bitacora('Edición de fecha de actualización');
            return 1;
        }else
        {
            $bitacora = $this->guardar_bitacora('Error al editar la fecha de actualización');        
            return 0;
        }

        /*$this->db->where('fecha_act', $this->input->post('fecha_act'));
        $this->db->where('active', 'a');
        $query = $this->db->get('tab_fecha_actualizacion_manual');

        if($query->num_rows() > 0)
        {
            return 2; // field is duplicated
        }
        else
        {
            $data_new = array(
                'id_fecha_act' => '',
                'fecha_act' => $this->input->post('fecha_act'),
                'comentario_act' => $this->input->post('comentario_act'),
                'active' => 'a',
                'fecha_reg' => date('Y-m-d')
            );
            
            $this->db->insert('tab_fecha_actualizacion_manual', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                //Guardamos accion en la bitacora
                $bitacora = $this->guardar_bitacora('Alta de fecha de actualización');
                if($bitacora == '1')
                {
                    return 1;   //Se ha dado de alta correctamente la fecha de actualizacion
                }
            }else
            {
                $bitacora = $this->guardar_bitacora('Error al dar de alta fecha de actualización');
                if($bitacora == '1')
                {
                    return 0;   //Se ha dado de alta correctamente la fecha de actualizacion
                }
            }
        }*/
    }

    function actualizar_fecha_con_erogacion($fecha, $mensaje)
    {
        
        $this->db->where('id_fecha_act', '1');
        $query = $this->db->get('tab_fecha_actualizacion_manual');

        $data = array(
            'id_fecha_act' => '',
            'fecha_act' => $fecha,
            'comentario_act' => $mensaje,
            'active' => 'a',
            'fecha_reg' => date('Y-m-d')
        );

        if($query->num_rows() > 0)
        {
            $this->db->where('id_fecha_act', '1');
            $this->db->update('tab_fecha_actualizacion_manual', $data);
        }
        else
        {
            $this->db->insert('tab_fecha_actualizacion_manual', $data);   
        }

        if($this->db->affected_rows() > 0)
        {
            //Guardamos accion en la bitacora
            $bitacora = $this->guardar_bitacora('Edición de fecha de actualización: ' . $fecha);
        }else
        {
            $bitacora = $this->guardar_bitacora('Error al editar la fecha de actualización: ' . $fecha );        
        }

        return 1;
    }

    /* obtener el registro de configuracion para el recaptcha */
    function get_registro_recaptcha(){
        $this->db->where('tipo', 'recaptcha');
        $query = $this->db->get('sys_settings');

        return $query->row();
    }

    function agregar_recaptcha(){
        $data = array(
            'id_settings' => '',
            'recaptcha' => $this->input->post('recaptcha'),
            'clave' => $this->input->post('clave'),
            'active' => $this->input->post('active'),
            'tipo' => 'recaptcha'
        );

        $this->db->insert('sys_settings', $data);   

        if($this->db->affected_rows() > 0)
        {
            //Guardamos accion en la bitacora
            $bitacora = $this->guardar_bitacora('Alta de reCAPTCHA: ' . $this->input->post('recaptcha'));
            return 1;
        }else
        {
            $bitacora = $this->guardar_bitacora('Error en el alta de de reCAPTCHA: ' . $this->input->post('recaptcha'));        
            return 0;
        }
    }

    function editar_recaptcha(){
        $data = array(
            'recaptcha' => $this->input->post('recaptcha'),
            'clave' => $this->input->post('clave'),
            'active' => $this->input->post('active'),
            'tipo' => 'recaptcha'
        );

        $this->db->where('id_settings', $this->input->post('id_settings'));
        $this->db->update('sys_settings', $data);

        if($this->db->affected_rows() > 0)
        {
            //Guardamos accion en la bitacora
            $bitacora = $this->guardar_bitacora('Editar la reCAPTCHA: ' . $this->input->post('recaptcha'));
            return 1;
        }else
        {
            $bitacora = $this->guardar_bitacora('Error en editar la reCAPTCHA: ' . $this->input->post('recaptcha'));        
            return 0;
        }
    }

    /* obtener el registro de configuracion para el recaptcha */
    function get_registro_grafica_presupuesto(){
        $this->db->where('tipo', 'grafica');
        $this->db->where('recaptcha', 'presupuestos');
        $query = $this->db->get('sys_settings');

        return $query->row();
    }

    function habilitar_grafica(){
        $registro = $this->get_registro_grafica_presupuesto();

        $action = '';
        $action_error = '';
        if(isset($registro)){
            $data = array(
                'recaptcha' => 'presupuestos',
                'clave' => '',
                'active' => $registro->active == 1 ? 0 : 1,
                'tipo' => 'grafica'
            );

            $action = ($registro->active == 1 ? 'deshabilitó' : 'habilitó');
            $action_error = ($registro->active == 1 ? 'deshabilitar' : 'habilitar');

            $this->db->where('id_settings', $registro->id_settings);
            $this->db->update('sys_settings', $data);
        }else{
            $data = array(
                'recaptcha' => 'presupuestos',
                'clave' => '',
                'active' => 1,
                'tipo' => 'grafica'
            );

            $action = 'habilitó';
            $this->db->insert('sys_settings', $data);   
        }

        if($this->db->affected_rows() > 0)
        {
            //Guardamos accion en la bitacora
            $bitacora = $this->guardar_bitacora('Se ' . $action. ' la gráfica de presupuestos en la vista pública' );
            return 1;
        }else
        {
            $bitacora = $this->guardar_bitacora('No fue posible ' . $action. ' la gráfica de presupuestos en la vista pública.' );        
            return 0;
        }

    }
}

?>