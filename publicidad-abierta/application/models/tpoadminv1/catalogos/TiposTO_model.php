<?php


/**
 * Description of TiposTO_model
 *
 * Modelo de acceso movimientos en el modulo de tiposTO
 * 
 * 
 */

class TiposTO_Model extends CI_Model
{

    function registro_bitacora($seccion,$accion){
        $this->load->model('tpoadminv1/bitacora/Bitacora_model');

        $reg_bitacora = array(
            'seccion' => $seccion, 
            'accion' => $accion 
        );

        $this->Bitacora_model->guardar_bitacora_general($reg_bitacora);

    }

	function dame_todos_tiposTO()
    {
        $this->load->model('tpoadminv1/Generales_model');
        $query = $this->db->get('cat_campana_tiposTO');
        
        if($query->num_rows() > 0)
        {
            $array_tiposTO = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $array_tiposTO[$cont]['id_campana_tipoTO'] = $row['id_campana_tipoTO'];
                    $array_tiposTO[$cont]['nombre_campana_tipoTO'] = $row['nombre_campana_tipoTO'];
                    $array_tiposTO[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_tiposTO;
        }
    }

    /** Funcion para la descarga a .csv de tiposTO **/
    function descarga_tiposTO()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/tiposTO.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_campana_tiposTO');
        $csv_header = array('#',utf8_decode('Tipo Tiempo Oficial'), 'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_campana_tipoTO']),
                    utf8_decode($row['nombre_campana_tipoTO']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_tipoTO_id($id_tipoTO)
    {
        $this->db->where('id_campana_tipoTO', $id_tipoTO);
        
        $query = $this->db->get('cat_campana_tiposTO');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function agregar_tipoTO()
    {
        
        $this->db->where('nombre_campana_tipoTO', $this->input->post('nombre_campana_tipoTO'));
        
        $query = $this->db->get('cat_campana_tiposTO');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_campana_tipoTO' => '',
                'nombre_campana_tipoTO' => $this->input->post('nombre_campana_tipoTO'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_campana_tiposTO', $data_new);

            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/TipoTO', 'Alta TipoTO: ' . $data_new['nombre_campana_tipoTO']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_tipoTO()
    {
        $this->db->where('nombre_campana_tipoTO', $this->input->post('nombre_campana_tipoTO'));
        $this->db->where_not_in('id_campana_tipoTO', $this->input->post('id_campana_tipoTO'));
        $query = $this->db->get('cat_campana_tiposTO');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_campana_tipoTO' => $this->input->post('nombre_campana_tipoTO'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_campana_tipoTO', $this->input->post('id_campana_tipoTO'));
            $this->db->update('cat_campana_tiposTO', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/TipoTO', 'Editar tipoTO: ' . $data_update['nombre_campana_tipoTO']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function eliminar_tipoTO($id)
    {
        $reg_eliminado = $this->dame_tipoTO_id($id);

        $this->db->where('id_campana_tipoTO', $id);
        $this->db->delete('cat_campana_tiposTO');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/TipoTO', 'Eliminación tipoTO: ' . $reg_eliminado['nombre_campana_tipoTO']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    
}

?>