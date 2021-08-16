<?php


/**
 * Description of Coberturas_model
 *
 * Modelo de acceso movimientos en el modulo de coberturas
 * 
 * 
 */

class Coberturas_Model extends CI_Model
{

    function registro_bitacora($seccion,$accion){
        $this->load->model('tpoadminv1/bitacora/Bitacora_model');

        $reg_bitacora = array(
            'seccion' => $seccion, 
            'accion' => $accion 
        );

        $this->Bitacora_model->guardar_bitacora_general($reg_bitacora);

    }

	function dame_todas_coberturas()
    {
        $this->load->model('tpoadminv1/Generales_model');
        $query = $this->db->get('cat_campana_coberturas');
        
        if($query->num_rows() > 0)
        {
            $array_coberturas = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $array_coberturas[$cont]['id_campana_cobertura'] = $row['id_campana_cobertura'];
                    $array_coberturas[$cont]['nombre_campana_cobertura'] = $row['nombre_campana_cobertura'];
                    $array_coberturas[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_coberturas;
        }
    }

    /** Funcion para la descarga a .csv de coberturas **/
    function descarga_coberturas()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/coberturas.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_campana_coberturas');
        $csv_header = array('#',utf8_decode('Cobertura de campaña'), 'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_campana_cobertura']),
                    utf8_decode($row['nombre_campana_cobertura']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_cobertura_id($id_cobertura)
    {
        $this->db->where('id_campana_cobertura', $id_cobertura);
        
        $query = $this->db->get('cat_campana_coberturas');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function agregar_cobertura()
    {
        
        $this->db->where('nombre_campana_cobertura', $this->input->post('nombre_campana_cobertura'));
        
        $query = $this->db->get('cat_campana_coberturas');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_campana_cobertura' => '',
                'nombre_campana_cobertura' => $this->input->post('nombre_campana_cobertura'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_campana_coberturas', $data_new);

            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Cobertura', 'Alta cobertura: ' . $data_new['nombre_campana_cobertura']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_cobertura()
    {
        $this->db->where('nombre_campana_cobertura', $this->input->post('nombre_campana_cobertura'));
        $this->db->where_not_in('id_campana_cobertura', $this->input->post('id_campana_cobertura'));
        $query = $this->db->get('cat_campana_coberturas');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_campana_cobertura' => $this->input->post('nombre_campana_cobertura'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_campana_cobertura', $this->input->post('id_campana_cobertura'));
            $this->db->update('cat_campana_coberturas', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Cobertura', 'Editar cobertura: ' . $data_update['nombre_campana_cobertura']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function eliminar_cobertura($id)
    {
        $reg_eliminado = $this->dame_cobertura_id($id);

        $this->db->where('id_campana_cobertura', $id);
        $this->db->delete('cat_campana_coberturas');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Cobertura', 'Eliminación cobertura: ' . $reg_eliminado['nombre_campana_cobertura']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function dame_todos_objetivos()
    {
        $this->load->model('tpoadminv1/Generales_model');
        $query = $this->db->get('cat_campana_objetivos');
        
        if($query->num_rows() > 0)
        {
            $array_objetivos = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
                {
                    $array_objetivos[$cont]['id_campana_objetivo'] = $row['id_campana_objetivo'];
                    $array_objetivos[$cont]['campana_objetivo'] = $row['campana_objetivo'];
                    $array_objetivos[$cont]['estatus'] = $this->Generales_model->get_estatus_name($row['active']);
                    $array_objetivos[$cont]['active'] = $row['active'];
                    $cont++;
            }
            return $array_objetivos;
        }
    }

    /** Funcion para la descarga a .csv de objetivos institucionales **/
    function descarga_objetivos()
    {
        $this->load->model('tpoadminv1/Generales_model');
        $filename = 'dist/csv/objetivos_institucionales.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_campana_objetivos');
        $csv_header = array('#',utf8_decode('Objetivos de campañas o avisos institucionales'), 'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_campana_objetivo']),
                    utf8_decode($row['campana_objetivo']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function agregar_objetivo()
    {
        $this->db->where('campana_objetivo', $this->input->post('campana_objetivo'));
        
        $query = $this->db->get('cat_campana_objetivos');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_campana_objetivo' => '',
                'campana_objetivo' => $this->input->post('campana_objetivo'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_campana_objetivos', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Objetivos institucionales', 'Alta de objetivo: ' . $data_new['campana_objetivo']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_objetivo()
    {
        $this->db->where('campana_objetivo', $this->input->post('campana_objetivo'));
        $this->db->where_not_in('id_campana_objetivo', $this->input->post('id_campana_objetivo'));

        $query = $this->db->get('cat_campana_objetivos');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'campana_objetivo' => $this->input->post('campana_objetivo'),
                'active' => $this->input->post('active'),
            );

            $this->db->where('id_campana_objetivo', $this->input->post('id_campana_objetivo'));
            $this->db->update('cat_campana_objetivos', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Objetivos institucionales', 'Edición de objetivo: ' . $data_update['campana_objetivo']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function dame_objetivo_id($id_objetivo)
    {
        $this->db->where('id_campana_objetivo', $id_objetivo);
        
        $query = $this->db->get('cat_campana_objetivos');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function eliminar_objetivo($id)
    {
        $reg_eliminado = $this->dame_objetivo_id($id);

        $this->db->where('id_campana_objetivo', $id);
        $this->db->delete('cat_campana_objetivos');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Objetivos institucionales', 'Eliminación objetivo institucional: ' . $reg_eliminado['campana_objetivo']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function dame_todos_tipos()
    {
        $this->load->model('tpoadminv1/Generales_model');
        $query = $this->db->get('cat_campana_tipos');
        
        if($query->num_rows() > 0)
        {
            $array_tipos = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
                {
                    $array_tipos[$cont]['id_campana_tipo'] = $row['id_campana_tipo'];
                    $array_tipos[$cont]['nombre_campana_tipo'] = $row['nombre_campana_tipo'];
                    $array_tipos[$cont]['estatus'] = $this->Generales_model->get_estatus_name($row['active']);
                    $array_tipos[$cont]['active'] = $row['active'];
                    $cont++;
            }
            return $array_tipos;
        }
    }

    /** Funcion para la descarga a .csv de objetivos institucionales **/
    function descarga_tipos()
    {
        $this->load->model('tpoadminv1/Generales_model');
        $filename = 'dist/csv/tipos_campanas.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_campana_tipos');
        $csv_header = array('#',utf8_decode('Tipos de campañas o avisos institucionales'), 'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_campana_tipo']),
                    utf8_decode($row['nombre_campana_tipo']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function agregar_tipo()
    {
        $this->db->where('nombre_campana_tipo', $this->input->post('nombre_campana_tipo'));
        
        $query = $this->db->get('cat_campana_tipos');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_campana_tipo' => '',
                'nombre_campana_tipo' => $this->input->post('nombre_campana_tipo'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_campana_tipos', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Tipos', 'Alta tipo: ' . $data_new['nombre_campana_tipo']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_tipo()
    {
        $this->db->where('nombre_campana_tipo', $this->input->post('nombre_campana_tipo'));
        $this->db->where_not_in('id_campana_tipo', $this->input->post('id_campana_tipo'));

        $query = $this->db->get('cat_campana_tipos');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_campana_tipo' => $this->input->post('nombre_campana_tipo'),
                'active' => $this->input->post('active'),
            );

            $this->db->where('id_campana_tipo', $this->input->post('id_campana_tipo'));
            $this->db->update('cat_campana_tipos', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Tipos', 'Edición tipo: ' . $data_update['nombre_campana_tipo']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function dame_tipo($id_tipo)
    {
        $this->db->where('id_campana_tipo', $id_tipo);
        
        $query = $this->db->get('cat_campana_tipos');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function eliminar_tipo($id)
    {
        $this->db->where('id_campana_tipo', $id);
        $query = $this->db->get('cat_campana_subtipos');
        if($query->num_rows() > 0)
        {
            return 2;
        }else{
            $reg_eliminado = $this->dame_tipo($id);

            $this->db->where('id_campana_tipo', $id);
            $this->db->delete('cat_campana_tipos');
            
            if($this->db->affected_rows() > 0)
            {
                if(!empty($reg_eliminado))
                    $this->registro_bitacora('Catálogos/Tipos', 'Eliminación tipo: ' . $reg_eliminado['nombre_campana_tipo']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function dame_todos_subtipos()
    {
        $this->load->model('tpoadminv1/Generales_model');
        $query = $this->db->get('cat_campana_subtipos');
        
        if($query->num_rows() > 0)
        {
            $array_subtipos = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
                {
                    $nombretipo = $this->dame_tipo($row['id_campana_tipo']); 
                    $array_subtipos[$cont]['id_campana_subtipo'] = $row['id_campana_subtipo'];
                    $array_subtipos[$cont]['id_campana_tipo'] = $row['id_campana_tipo'];
                    $array_subtipos[$cont]['nombre_campana_tipo'] = $nombretipo != '' ? $nombretipo['nombre_campana_tipo'] : '';
                    $array_subtipos[$cont]['nombre_campana_subtipo'] = $row['nombre_campana_subtipo'];
                    $array_subtipos[$cont]['estatus'] = $this->Generales_model->get_estatus_name($row['active']);
                    $array_subtipos[$cont]['active'] = $row['active'];
                    $cont++;
            }
            return $array_subtipos;
        }
    }

    /** Funcion para la descarga a .csv de objetivos institucionales **/
    function descarga_subtipos()
    {
        $this->load->model('tpoadminv1/Generales_model');
        $filename = 'dist/csv/subtipos_campanas.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_campana_subtipos');
        $csv_header = array('#', utf8_decode('Tipos de campaña o aviso institucional'), utf8_decode('Subipos de campaña o aviso institucional'), 'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $nombretipo = $this->dame_tipo($row['id_campana_tipo']); 
                $csv = array(
                    utf8_decode($row['id_campana_subtipo']),
                    utf8_decode($nombretipo != '' ? $nombretipo['nombre_campana_tipo'] : ''),
                    utf8_decode($row['nombre_campana_subtipo']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function agregar_subtipo()
    {
        $this->db->where('nombre_campana_subtipo', $this->input->post('nombre_campana_subtipo'));
        $query = $this->db->get('cat_campana_subtipos');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_campana_subtipo' => '',
                'id_campana_tipo' => $this->input->post('id_campana_tipo'),
                'nombre_campana_subtipo' => $this->input->post('nombre_campana_subtipo'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_campana_subtipos', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Subtipos', 'Alta subtipo: ' . $data_new['nombre_campana_subtipo']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_subtipo()
    {
        $this->db->where('nombre_campana_subtipo', $this->input->post('nombre_campana_subtipo'));
        $this->db->where_not_in('id_campana_subtipo', $this->input->post('id_campana_subtipo'));

        $query = $this->db->get('cat_campana_subtipos');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'id_campana_tipo' => $this->input->post('id_campana_tipo'),
                'nombre_campana_subtipo' => $this->input->post('nombre_campana_subtipo'),
                'active' => $this->input->post('active'),
            );

            $this->db->where('id_campana_subtipo', $this->input->post('id_campana_subtipo'));
            $this->db->update('cat_campana_subtipos', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Subtipos', 'Edición subtipo: ' . $data_update['nombre_campana_subtipo']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function dame_subtipo_id($id_subtipo)
    {
        $this->db->where('id_campana_subtipo', $id_subtipo);
        
        $query = $this->db->get('cat_campana_subtipos');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function eliminar_subtipo($id)
    {
        $reg_eliminado = $this->dame_subtipo_id($id);

        $this->db->where('id_campana_subtipo', $id);
        $this->db->delete('cat_campana_subtipos');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Subtipos', 'Eliminación subtipo: ' . $reg_eliminado['nombre_campana_subtipo']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function dame_todos_temas()
    {
        $this->load->model('tpoadminv1/Generales_model');
        $query = $this->db->get('cat_campana_temas');
        
        if($query->num_rows() > 0)
        {
            $array_temas = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
                {
                    $array_temas[$cont]['id_campana_tema'] = $row['id_campana_tema'];
                    $array_temas[$cont]['nombre_campana_tema'] = $row['nombre_campana_tema'];
                    $array_temas[$cont]['estatus'] = $this->Generales_model->get_estatus_name($row['active']);
                    $array_temas[$cont]['active'] = $row['active'];
                    $cont++;
            }
            return $array_temas;
        }
    }

    /** Funcion para la descarga a .csv de objetivos institucionales **/
    function descarga_temas()
    {
        $this->load->model('tpoadminv1/Generales_model');
        $filename = 'dist/csv/temas.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_campana_temas');
        $csv_header = array('#',utf8_decode('Tema'), 'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_campana_tema']),
                    utf8_decode($row['nombre_campana_tema']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function agregar_tema()
    {
        $this->db->where('nombre_campana_tema', $this->input->post('nombre_campana_tema'));
        $query = $this->db->get('cat_campana_temas');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_campana_tema' => '',
                'nombre_campana_tema' => $this->input->post('nombre_campana_tema'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_campana_temas', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Temas', 'Alta tema: ' . $data_new['nombre_campana_tema']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_tema()
    {
        $this->db->where('nombre_campana_tema', $this->input->post('nombre_campana_tema'));
        $this->db->where_not_in('id_campana_tema', $this->input->post('id_campana_tema'));

        $query = $this->db->get('cat_campana_temas');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_campana_tema' => $this->input->post('nombre_campana_tema'),
                'active' => $this->input->post('active'),
            );

            $this->db->where('id_campana_tema', $this->input->post('id_campana_tema'));
            $this->db->update('cat_campana_temas', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Temas', 'Edición tema: ' . $data_update['nombre_campana_tema']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function dame_tema_id($id_tema)
    {
        $this->db->where('id_campana_tema', $id_tema);
        
        $query = $this->db->get('cat_campana_temas');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function eliminar_tema($id)
    {
        $reg_eliminado = $this->dame_tema_id($id);

        $this->db->where('id_campana_tema', $id);
        $this->db->delete('cat_campana_temas');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Temas', 'Eliminación tema: ' . $reg_eliminado['nombre_campana_tema']);            
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }
}

?>