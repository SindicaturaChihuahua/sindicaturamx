<?php

/**
 * Description of Catalogos_model
 *
 * Modelo de acceso movimientos en el modulo de catálogos
 * 
 * INAI TPO
 */
class Catalogos_Model extends CI_Model
{
    function registro_bitacora($seccion,$accion){
        $this->load->model('tpoadminv1/bitacora/Bitacora_model');

        $reg_bitacora = array(
            'seccion' => $seccion, 
            'accion' => $accion 
        );

        $this->Bitacora_model->guardar_bitacora_general($reg_bitacora);

    }

    function get_estatus_captura($estatus)
    {
        if($estatus == 1){
            return 'Si';
        }else if($estatus == 2){
            return 'No';
        }else {
            return '-nada-';
        }
    }

    function exist_register_foreign($id, $id_name, $data_base)
    {
        $this->db->where($id_name, $id);
        $query = $this->db->get($data_base);
        
        if($query->num_rows() > 0)
        {
            return true;
        }else
        {
            return false; 
        }
    }

    function dame_todas_edades()
    {
        $this->load->model('tpoadminv1/Generales_model');
        
        $query = $this->db->get('cat_poblacion_grupo_edad');
        
        if($query->num_rows() > 0)
        {
            $array_edades = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $array_edades[$cont]['id_poblacion_grupo_edad'] = $row['id_poblacion_grupo_edad'];
                    $array_edades[$cont]['nombre_poblacion_grupo_edad'] = $row['nombre_poblacion_grupo_edad'];
                    $array_edades[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_edades;
        }
    }

    /** Funcion para la descarga a .csv de edades **/
    function descarga_edades()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/edades.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_poblacion_grupo_edad');
        $csv_header = array('#',utf8_decode('Segmentación de edad'), 'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_poblacion_grupo_edad']),
                    utf8_decode($row['nombre_poblacion_grupo_edad']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }
    
    function eliminar_edad($id)
    {
        $reg_eliminado = $this->dame_edad_id($id);

        $this->db->where('id_poblacion_grupo_edad', $id);
        $this->db->delete('cat_poblacion_grupo_edad');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Edades', 'Eliminación edad: ' . $reg_eliminado['nombre_poblacion_grupo_edad']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function dame_edad_id($id)
    {
        $this->db->where('id_poblacion_grupo_edad', $id);
        
        $query = $this->db->get('cat_poblacion_grupo_edad');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function agregar_edad()
    {
        $this->db->where('nombre_poblacion_grupo_edad', $this->input->post('nombre_poblacion_grupo_edad'));
        
        $query = $this->db->get('cat_poblacion_grupo_edad');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_poblacion_grupo_edad' => '',
                'nombre_poblacion_grupo_edad' => $this->input->post('nombre_poblacion_grupo_edad'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_poblacion_grupo_edad', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Edades', 'Alta de edad: ' . $data_new['nombre_poblacion_grupo_edad']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_edad()
    {

        $this->db->where('nombre_poblacion_grupo_edad', $this->input->post('nombre_poblacion_grupo_edad'));
        $this->db->where_not_in('id_poblacion_grupo_edad', $this->input->post('id_poblacion_grupo_edad'));
        $query = $this->db->get('cat_poblacion_grupo_edad');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_poblacion_grupo_edad' => $this->input->post('nombre_poblacion_grupo_edad'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_poblacion_grupo_edad', $this->input->post('id_poblacion_grupo_edad'));
            $this->db->update('cat_poblacion_grupo_edad', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Edades', 'Edición de edad : ' . $data_update['nombre_poblacion_grupo_edad']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_todas_socioeconomicos()
    {
        $this->load->model('tpoadminv1/Generales_model');
        $query = $this->db->get('cat_poblacion_nivel');
        
        if($query->num_rows() > 0)
        {
            $array_edades = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $array_edades[$cont]['id_poblacion_nivel'] = $row['id_poblacion_nivel'];
                    $array_edades[$cont]['nombre_poblacion_nivel'] = $row['nombre_poblacion_nivel'];
                    $array_edades[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_edades;
        }
    }

    /** Funcion para la descarga a .csv de objetivos institucionales **/
    function descarga_socioeconomicos()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/nivel_socioeconomicos.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_poblacion_nivel');
        $csv_header = array('#',utf8_decode('Nivel socioeconómico'), 'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_poblacion_nivel']),
                    utf8_decode($row['nombre_poblacion_nivel']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function eliminar_socioeconomico($id)
    {
        $reg_eliminado = $this->dame_socioeconomico_id($id);

        $this->db->where('id_poblacion_nivel', $id);
        $this->db->delete('cat_poblacion_nivel');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Nivel socioeconómico', 'Eliminación nivel socioeconómico : ' . $reg_eliminado['nombre_poblacion_nivel']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_nivel_socioeconomico()
    {
        $this->db->where('nombre_poblacion_nivel', $this->input->post('nombre_poblacion_nivel'));
        
        $query = $this->db->get('cat_poblacion_nivel');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_poblacion_nivel' => '',
                'nombre_poblacion_nivel' => $this->input->post('nombre_poblacion_nivel'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_poblacion_nivel', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Nivel socioeconómico', 'Alta de nivel socioeconómico: ' . $data_new['nombre_poblacion_nivel']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_nivel_socioeconomico()
    {

        $this->db->where('nombre_poblacion_nivel', $this->input->post('nombre_poblacion_nivel'));
        $this->db->where_not_in('id_poblacion_nivel', $this->input->post('id_poblacion_nivel'));
        $query = $this->db->get('cat_poblacion_nivel');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_poblacion_nivel' => $this->input->post('nombre_poblacion_nivel'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_poblacion_nivel', $this->input->post('id_poblacion_nivel'));
            $this->db->update('cat_poblacion_nivel', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Nivel socioeconómico', 'Edición de nivel socioeconómico: ' . $data_update['nombre_poblacion_nivel']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_socioeconomico_id($id)
    {
        $this->db->where('id_poblacion_nivel', $id);
        
        $query = $this->db->get('cat_poblacion_nivel');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function dame_todas_educacion()
    {
        $this->load->model('tpoadminv1/Generales_model');
        $query = $this->db->get('cat_poblacion_nivel_educativo');
        
        if($query->num_rows() > 0)
        {
            $array_edades = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $array_edades[$cont]['id_poblacion_nivel_educativo'] = $row['id_poblacion_nivel_educativo'];
                    $array_edades[$cont]['nombre_poblacion_nivel_educativo'] = $row['nombre_poblacion_nivel_educativo'];
                    $array_edades[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_edades;
        }
    }

    /** Funcion para la descarga a .csv de nivel de educación**/
    function descarga_educacion()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/nivel_educativo.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_poblacion_nivel_educativo');
        $csv_header = array('#',utf8_decode('Nivel de educación'), 'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_poblacion_nivel_educativo']),
                    utf8_decode($row['nombre_poblacion_nivel_educativo']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function eliminar_educacion($id)
    {
        $reg_eliminado = $this->dame_educacion_id($id);

        $this->db->where('id_poblacion_nivel_educativo', $id);
        $this->db->delete('cat_poblacion_nivel_educativo');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Nivel de educación', 'Eliminación nivel de educación: ' . $reg_eliminado['nombre_poblacion_nivel_educativo']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_nivel_educacion()
    {
        $this->db->where('nombre_poblacion_nivel_educativo', $this->input->post('nombre_poblacion_nivel_educativo'));
        
        $query = $this->db->get('cat_poblacion_nivel_educativo');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_poblacion_nivel_educativo' => '',
                'nombre_poblacion_nivel_educativo' => $this->input->post('nombre_poblacion_nivel_educativo'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_poblacion_nivel_educativo', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Nivel de educación', 'Alta de nivel de educación: ' . $data_new['nombre_poblacion_nivel_educativo']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function dame_educacion_id($id)
    {
        $this->db->where('id_poblacion_nivel_educativo', $id);
        
        $query = $this->db->get('cat_poblacion_nivel_educativo');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function editar_nivel_educacion()
    {

        $this->db->where('nombre_poblacion_nivel_educativo', $this->input->post('nombre_poblacion_nivel_educativo'));
        $this->db->where_not_in('id_poblacion_nivel_educativo', $this->input->post('id_poblacion_nivel_educativo'));
        $query = $this->db->get('cat_poblacion_nivel_educativo');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_poblacion_nivel_educativo' => $this->input->post('nombre_poblacion_nivel_educativo'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_poblacion_nivel_educativo', $this->input->post('id_poblacion_nivel_educativo'));
            $this->db->update('cat_poblacion_nivel_educativo', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Nivel de educación', 'Edición de nivel de educación: ' . $data_update['nombre_poblacion_nivel_educativo']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_todos_sexo()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $query = $this->db->get('cat_poblacion_sexo');
        
        if($query->num_rows() > 0)
        {
            $array_sexos = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $array_sexos[$cont]['id_poblacion_sexo'] = $row['id_poblacion_sexo'];
                    $array_sexos[$cont]['nombre_poblacion_sexo'] = $row['nombre_poblacion_sexo'];
                    $array_sexos[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_sexos;
        }
    }

    /** Funcion para la descarga a .csv de objetivos institucionales **/
    function descarga_sexo()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/segmentacion_sexo.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_poblacion_sexo');
        $csv_header = array('#',utf8_decode('Segmentación por sexo'), 'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_poblacion_sexo']),
                    utf8_decode($row['nombre_poblacion_sexo']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_sexo_id($id)
    {
        $this->db->where('id_poblacion_sexo', $id);
        
        $query = $this->db->get('cat_poblacion_sexo');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function eliminar_sexo($id)
    {
        $reg_eliminado = $this->dame_sexo_id($id);

        $this->db->where('id_poblacion_sexo', $id);
        $this->db->delete('cat_poblacion_sexo');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Sexo', 'Eliminación sexo: ' . $reg_eliminado['nombre_poblacion_sexo']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_sexo()
    {
        $this->db->where('nombre_poblacion_sexo', $this->input->post('nombre_poblacion_sexo'));
        
        $query = $this->db->get('cat_poblacion_sexo');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_poblacion_sexo' => '',
                'nombre_poblacion_sexo' => $this->input->post('nombre_poblacion_sexo'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_poblacion_sexo', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Sexo', 'Alta de sexo: ' . $data_new['nombre_poblacion_sexo']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_sexo()
    {

        $this->db->where('nombre_poblacion_sexo', $this->input->post('nombre_poblacion_sexo'));
        $this->db->where_not_in('id_poblacion_sexo', $this->input->post('id_poblacion_sexo'));
        $query = $this->db->get('cat_poblacion_sexo');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_poblacion_sexo' => $this->input->post('nombre_poblacion_sexo'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_poblacion_sexo', $this->input->post('id_poblacion_sexo'));
            $this->db->update('cat_poblacion_sexo', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Sexo', 'Edición de sexo: ' . $data_update['nombre_poblacion_sexo']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_todas_clasificaciones()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $query = $this->db->get('cat_servicios_clasificacion');
        
        if($query->num_rows() > 0)
        {
            $array_edades = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $array_edades[$cont]['id_servicio_clasificacion'] = $row['id_servicio_clasificacion'];
                    $array_edades[$cont]['nombre_servicio_clasificacion'] = $row['nombre_servicio_clasificacion'];
                    $array_edades[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_edades;
        }
    }

    function dame_clasificaciones_activas()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $this->db->where('active', '1');
        $query = $this->db->get('cat_servicios_clasificacion');
        
        if($query->num_rows() > 0)
        {
            $array_edades = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $array_edades[$cont]['id_servicio_clasificacion'] = $row['id_servicio_clasificacion'];
                    $array_edades[$cont]['nombre_servicio_clasificacion'] = $row['nombre_servicio_clasificacion'];
                    $array_edades[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_edades;
        }
    }

    function descarga_clasificaciones()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/clasificaciones_servicio.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_servicios_clasificacion');
        $csv_header = array('#',utf8_decode('Clasificación del servicio'), 'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_servicio_clasificacion']),
                    utf8_decode($row['nombre_servicio_clasificacion']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function agregar_clasificacion()
    {
        $this->db->where('nombre_servicio_clasificacion', $this->input->post('nombre_servicio_clasificacion'));
        
        $query = $this->db->get('cat_servicios_clasificacion');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_servicio_clasificacion' => '',
                'nombre_servicio_clasificacion' => $this->input->post('nombre_servicio_clasificacion'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_servicios_clasificacion', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Servicios clasificación', 'Alta de clasificación: ' . $data_new['nombre_servicio_clasificacion']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_clasificacion()
    {

        $this->db->where('nombre_servicio_clasificacion', $this->input->post('nombre_servicio_clasificacion'));
        $this->db->where_not_in('id_servicio_clasificacion', $this->input->post('id_servicio_clasificacion'));
        $query = $this->db->get('cat_servicios_clasificacion');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_servicio_clasificacion' => $this->input->post('nombre_servicio_clasificacion'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_servicio_clasificacion', $this->input->post('id_servicio_clasificacion'));
            $this->db->update('cat_servicios_clasificacion', $data_update);
    
            if($this->db->affected_rows() > 0)
            {$this->registro_bitacora('Catálogos/Servicios clasificación', 'Edición de clasificación: ' . $data_update['nombre_servicio_clasificacion']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function eliminar_clasificacion($id)
    {
        $reg_eliminado = $this->dame_clasificacion_id($id);

        $this->db->where('id_servicio_clasificacion', $id);
        $this->db->delete('cat_servicios_clasificacion');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Servicios clasificación', 'Eliminación clasificación: ' . $reg_eliminado['nombre_servicio_clasificacion']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function dame_clasificacion_id($id)
    {
        $this->db->where('id_servicio_clasificacion', $id);
        
        $query = $this->db->get('cat_servicios_clasificacion');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function dame_nombre_servicio_clasificacion($id)
    {
        $this->db->select('nombre_servicio_clasificacion');
        $this->db->where('id_servicio_clasificacion', $id);
        
        $query = $this->db->get('cat_servicios_clasificacion');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row['nombre_servicio_clasificacion'];
        }else{
            return '';
        }
    }

    function get_categorias_filtro($id_servicio_clasificacion)
    {
        $this->load->model('tpoadminv1/Generales_model');

        $this->db->where('id_servicio_clasificacion', $id_servicio_clasificacion);
        $this->db->where('active', '1');
        $query = $this->db->get('cat_servicios_categorias');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $array_items[$cont]['id_servicio_categoria'] = $row['id_servicio_categoria'];
                    $array_items[$cont]['nombre_servicio_categoria'] = $row['nombre_servicio_categoria'];
                    $cont++;
            }
            return $array_items;
        }
    }

    function dame_todas_categorias()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $query = $this->db->get('cat_servicios_categorias');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $clasificacion = $this->dame_clasificacion_id($row['id_servicio_clasificacion']);

                    $array_items[$cont]['id_servicio_categoria'] = $row['id_servicio_categoria'];
                    $array_items[$cont]['id_servicio_clasificacion'] = $row['id_servicio_clasificacion'];
                    $array_items[$cont]['nombre_servicio_categoria'] = $row['nombre_servicio_categoria'];
                    $array_items[$cont]['nombre_servicio_clasificacion'] = $clasificacion != '' ? $clasificacion['nombre_servicio_clasificacion'] : ''; 
                    $array_items[$cont]['titulo_grafica'] = $row['titulo_grafica'];
                    $array_items[$cont]['color_grafica'] = $row['color_grafica'];
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function descarga_categorias()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/categorias.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_servicios_categorias');
        $csv_header = array('#',
                    utf8_decode('Clasificación del servicio'),
                    utf8_decode('Categorías del servicio'),
                    utf8_decode('Título gráfica'),
                    utf8_decode('Color gráfica'),
                    'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $clasificacion = $this->dame_clasificacion_id($row['id_servicio_clasificacion']);
                $csv = array(
                    utf8_decode($row['id_servicio_categoria']),
                    utf8_decode($clasificacion != '' ? $clasificacion['nombre_servicio_clasificacion'] : ''),
                    utf8_decode($row['nombre_servicio_categoria']),
                    utf8_decode($row['titulo_grafica']),
                    utf8_decode($row['color_grafica']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_categoria_id($id)
    {
        $this->db->where('id_servicio_categoria', $id);
        
        $query = $this->db->get('cat_servicios_categorias');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function dame_nombre_servicio_categoria($id)
    {
        $this->db->select('nombre_servicio_categoria');
        $this->db->where('id_servicio_categoria', $id);
        
        $query = $this->db->get('cat_servicios_categorias');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row['nombre_servicio_categoria'];
        }else{
            return '';
        }
    }

    function eliminar_categoria($id)
    {
        $reg_eliminado = $this->dame_categoria_id($id);

        $this->db->where('id_servicio_categoria', $id);
        $this->db->delete('cat_servicios_categorias');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Servicios categorías', 'Eliminación categoría : ' . $reg_eliminado['nombre_servicio_categoria']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_categoria()
    {
        $this->db->where('nombre_servicio_categoria', $this->input->post('nombre_servicio_categoria'));
        $this->db->where('id_servicio_clasificacion', $this->input->post('id_servicio_clasificacion'));
        
        $query = $this->db->get('cat_servicios_categorias');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_servicio_categoria' => '',
                'id_servicio_clasificacion' => $this->input->post('id_servicio_clasificacion'),
                'nombre_servicio_categoria' => $this->input->post('nombre_servicio_categoria'),
                'titulo_grafica' => $this->input->post('titulo_grafica'),
                'color_grafica' => $this->input->post('color_grafica'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_servicios_categorias', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Servicios categorías', 'Alta de categoría: ' . $data_new['nombre_servicio_categoria']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_categoria()
    {

        $this->db->where('nombre_servicio_categoria', $this->input->post('nombre_servicio_categoria'));
        $this->db->where('id_servicio_clasificacion', $this->input->post('id_servicio_clasificacion'));
        $this->db->where_not_in('id_servicio_categoria', $this->input->post('id_servicio_categoria'));
        $query = $this->db->get('cat_servicios_categorias');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'id_servicio_clasificacion' => $this->input->post('id_servicio_clasificacion'),
                'nombre_servicio_categoria' => $this->input->post('nombre_servicio_categoria'),
                'titulo_grafica' => $this->input->post('titulo_grafica'),
                'color_grafica' => $this->input->post('color_grafica'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_servicio_categoria', $this->input->post('id_servicio_categoria'));
            $this->db->update('cat_servicios_categorias', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Servicios categorías', 'Edición de categoría: ' . $data_update['nombre_servicio_categoria']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function get_subcategorias_filtro($id_servicio_categoria)
    {
        $this->load->model('tpoadminv1/Generales_model');

        $this->db->where('id_servicio_categoria', $id_servicio_categoria);
        $this->db->where('active', '1');
        $query = $this->db->get('cat_servicios_subcategorias');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $array_items[$cont]['id_servicio_subcategoria'] = $row['id_servicio_subcategoria'];
                    $array_items[$cont]['nombre_servicio_subcategoria'] = $row['nombre_servicio_subcategoria'];
                    $cont++;
            }
            return $array_items;
        }
    }

    function dame_todas_subcategorias()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $query = $this->db->get('cat_servicios_subcategorias');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $categoria = $this->dame_categoria_id($row['id_servicio_categoria']);

                    $array_items[$cont]['id_servicio_categoria'] = $row['id_servicio_categoria'];
                    $array_items[$cont]['id_servicio_subcategoria'] = $row['id_servicio_subcategoria'];
                    $array_items[$cont]['nombre_servicio_subcategoria'] = $row['nombre_servicio_subcategoria'];
                    $array_items[$cont]['nombre_servicio_categoria'] = $categoria != '' ? $categoria['nombre_servicio_categoria'] : '';
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function descarga_subcategorias()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/subcategorias.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_servicios_subcategorias');
        $csv_header = array('#',
                    utf8_decode('Categorías del servicio'),
                    utf8_decode('Subcategorías del servicio'),
                    'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $categoria = $this->dame_categoria_id($row['id_servicio_categoria']);
                $csv = array(
                    utf8_decode($row['id_servicio_subcategoria']),
                    utf8_decode($categoria != '' ? $categoria['nombre_servicio_categoria'] : ''),
                    utf8_decode($row['nombre_servicio_subcategoria']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_subcategoria_id($id)
    {
        $this->db->where('id_servicio_subcategoria', $id);
        
        $query = $this->db->get('cat_servicios_subcategorias');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function dame_nombre_servicio_subcategoria($id)
    {
        $this->db->select('nombre_servicio_subcategoria');
        $this->db->where('id_servicio_subcategoria', $id);
        
        $query = $this->db->get('cat_servicios_subcategorias');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row['nombre_servicio_subcategoria'];
        }else{
            return '';
        }
    }

    function eliminar_subcategoria($id)
    {
        $reg_eliminado = $this->dame_subcategoria_id($id);

        $this->db->where('id_servicio_subcategoria', $id);
        $this->db->delete('cat_servicios_subcategorias');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Servicios subcategorías', 'Eliminación subcategoría: ' . $reg_eliminado['nombre_servicio_subcategoria']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_subcategoria()
    {
        $this->db->where('nombre_servicio_subcategoria', $this->input->post('nombre_servicio_subcategoria'));
        $this->db->where('id_servicio_categoria', $this->input->post('id_servicio_categoria'));
        
        $query = $this->db->get('cat_servicios_subcategorias');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_servicio_subcategoria' => '',
                'id_servicio_categoria' => $this->input->post('id_servicio_categoria'),
                'nombre_servicio_subcategoria' => $this->input->post('nombre_servicio_subcategoria'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_servicios_subcategorias', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Servicios subcategorías', 'Alta de subcategoría: ' . $data_new['nombre_servicio_subcategoria']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_subcategoria()
    {

        $this->db->where('nombre_servicio_subcategoria', $this->input->post('nombre_servicio_subcategoria'));
        $this->db->where('id_servicio_categoria', $this->input->post('id_servicio_categoria'));
        $this->db->where_not_in('id_servicio_subcategoria', $this->input->post('id_servicio_subcategoria'));
        $query = $this->db->get('cat_servicios_subcategorias');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'id_servicio_categoria' => $this->input->post('id_servicio_categoria'),
                'nombre_servicio_subcategoria' => $this->input->post('nombre_servicio_subcategoria'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_servicio_subcategoria', $this->input->post('id_servicio_subcategoria'));
            $this->db->update('cat_servicios_subcategorias', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Servicios subcategorías', 'Edición de subcategoría: ' . $data_update['nombre_servicio_subcategoria']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function get_unidades_filtro($id_servicio_subcategoria)
    {
        $this->db->where('id_servicio_subcategoria', $id_servicio_subcategoria);
        $this->db->where('active', '1');
        $query = $this->db->get('cat_servicios_unidades');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $array_items[$cont]['id_servicio_unidad'] = $row['id_servicio_unidad'];
                    $array_items[$cont]['nombre_servicio_unidad'] = $row['nombre_servicio_unidad'];
                    $cont++;
            }
            return $array_items;
        }
    }

    function dame_todas_unidades()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $query = $this->db->get('cat_servicios_unidades');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
                    $subcategoria = $this->dame_subcategoria_id($row['id_servicio_subcategoria']);

                    $array_items[$cont]['id_servicio_unidad'] = $row['id_servicio_unidad'];
                    $array_items[$cont]['id_servicio_subcategoria'] = $row['id_servicio_subcategoria'];
                    $array_items[$cont]['nombre_servicio_unidad'] = $row['nombre_servicio_unidad'];
                    $array_items[$cont]['nombre_servicio_subcategoria'] = $subcategoria != '' ? $subcategoria['nombre_servicio_subcategoria'] : '';
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function descarga_unidades()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/unidades.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_servicios_unidades');
        $csv_header = array('#',
                    utf8_decode('Subcategorías del servicio'),
                    utf8_decode('Unidades del servicio'),
                    'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $subcategoria = $this->dame_subcategoria_id($row['id_servicio_subcategoria']);
                $csv = array(
                    utf8_decode($row['id_servicio_unidad']),
                    utf8_decode($subcategoria != '' ? $subcategoria['nombre_servicio_subcategoria'] : ''),
                    utf8_decode($row['nombre_servicio_unidad']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_unidad_id($id)
    {
        $this->db->where('id_servicio_unidad', $id);
        
        $query = $this->db->get('cat_servicios_unidades');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function dame_nombre_servicio_unidad($id)
    {
        $this->db->select('nombre_servicio_unidad');
        $this->db->where('id_servicio_unidad', $id);
        
        $query = $this->db->get('cat_servicios_unidades');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row['nombre_servicio_unidad'];
        }else{
            return '';
        }
    }

    function eliminar_unidad($id)
    {
        $reg_eliminado = $this->dame_unidad_id($id);

        $this->db->where('id_servicio_unidad', $id);
        $this->db->delete('cat_servicios_unidades');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Servicios unidades', 'Eliminación unidad : ' . $reg_eliminado['nombre_servicio_unidad']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_unidad()
    {
        $this->db->where('nombre_servicio_unidad', $this->input->post('nombre_servicio_unidad'));
        $this->db->where('id_servicio_subcategoria', $this->input->post('id_servicio_subcategoria'));
        
        $query = $this->db->get('cat_servicios_unidades');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_servicio_unidad' => '',
                'id_servicio_subcategoria' => $this->input->post('id_servicio_subcategoria'),
                'nombre_servicio_unidad' => $this->input->post('nombre_servicio_unidad'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_servicios_unidades', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Servicios unidades', 'Alta de unidad: ' . $data_new['nombre_servicio_unidad']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_unidad()
    {

        $this->db->where('nombre_servicio_unidad', $this->input->post('nombre_servicio_unidad'));
        $this->db->where('id_servicio_subcategoria', $this->input->post('id_servicio_subcategoria'));
        $this->db->where_not_in('id_servicio_unidad', $this->input->post('id_servicio_unidad'));
        $query = $this->db->get('cat_servicios_unidades');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'id_servicio_subcategoria' => $this->input->post('id_servicio_subcategoria'),
                'nombre_servicio_unidad' => $this->input->post('nombre_servicio_unidad'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_servicio_unidad', $this->input->post('id_servicio_unidad'));
            $this->db->update('cat_servicios_unidades', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Servicios unidades', 'Edición de unidad: ' . $data_update['nombre_servicio_unidad']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_todas_atribuciones()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $query = $this->db->get('cat_so_atribucion');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
    
                    $array_items[$cont]['id_so_atribucion'] = $row['id_so_atribucion'];
                    $array_items[$cont]['nombre_so_atribucion'] = $row['nombre_so_atribucion'];
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function descarga_atribuciones()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/atribuciones.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_so_atribucion');
        $csv_header = array('#',
                    utf8_decode('Funciónes del sujeto obligado'),
                    'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_so_atribucion']),
                    utf8_decode($row['nombre_so_atribucion']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_atribucion_id($id)
    {
        $this->db->where('id_so_atribucion', $id);
        
        $query = $this->db->get('cat_so_atribucion');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function eliminar_atribucion($id)
    {
        $reg_eliminado = $this->dame_atribucion_id($id);

        $this->db->where('id_so_atribucion', $id);
        $this->db->delete('cat_so_atribucion');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Funciones', 'Eliminación función: ' . $reg_eliminado['nombre_so_atribucion']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_atribucion()
    {
        $this->db->where('nombre_so_atribucion', $this->input->post('nombre_so_atribucion'));
        
        $query = $this->db->get('cat_so_atribucion');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_so_atribucion' => '',
                'nombre_so_atribucion' => $this->input->post('nombre_so_atribucion'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_so_atribucion', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Sujetos funciones', 'Alta de la funcion: ' . $data_new['nombre_so_atribucion']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_atribucion()
    {

        $this->db->where('nombre_so_atribucion', $this->input->post('nombre_so_atribucion'));
        $this->db->where_not_in('id_so_atribucion', $this->input->post('id_so_atribucion'));
        $query = $this->db->get('cat_so_atribucion');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_so_atribucion' => $this->input->post('nombre_so_atribucion'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_so_atribucion', $this->input->post('id_so_atribucion'));
            $this->db->update('cat_so_atribucion', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Sujetos funciones', 'Edición de la funcion: ' . $data_update['nombre_so_atribucion']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_todos_estados()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $query = $this->db->get('cat_so_estados');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
    
                    $array_items[$cont]['id_so_estado'] = $row['id_so_estado'];
                    $array_items[$cont]['nombre_so_estado'] = $row['nombre_so_estado'];
                    $array_items[$cont]['codigo_so_estado'] = $row['codigo_so_estado'];
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function descarga_estados()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/estados.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_so_estados');
        $csv_header = array('#',
                    utf8_decode('Nombre del estado'),
                    utf8_decode('Código del estado'),
                    'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_so_estado']),
                    utf8_decode($row['nombre_so_estado']),
                    utf8_decode($row['codigo_so_estado']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_estado_id($id)
    {
        $this->db->where('id_so_estado', $id);
        
        $query = $this->db->get('cat_so_estados');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function eliminar_estado($id)
    {
        $reg_eliminado = $this->dame_estado_id($id);

        $this->db->where('id_so_estado', $id);
        $this->db->delete('cat_so_estados');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Estados', 'Eliminación estado: ' . $reg_eliminado['nombre_so_estado']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_estado()
    {
        $this->db->where('nombre_so_estado', $this->input->post('nombre_so_estado'));
        
        $query = $this->db->get('cat_so_estados');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_so_estado' => '',
                'nombre_so_estado' => $this->input->post('nombre_so_estado'),
                'codigo_so_estado' => $this->input->post('codigo_so_estado'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_so_estados', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Estados', 'Alta del estado: ' . $data_new['nombre_so_estado']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_estado()
    {

        $this->db->where('nombre_so_estado', $this->input->post('nombre_so_estado'));
        $this->db->where_not_in('id_so_estado', $this->input->post('id_so_estado'));
        $query = $this->db->get('cat_so_estados');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_so_estado' => $this->input->post('nombre_so_estado'),
                'codigo_so_estado' => $this->input->post('codigo_so_estado'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_so_estado', $this->input->post('id_so_estado'));
            $this->db->update('cat_so_estados', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Estados', 'Edición del estado: ' . $data_update['nombre_so_estado']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_todos_ordenes()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $query = $this->db->get('cat_so_ordenes_gobierno');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
    
                    $array_items[$cont]['id_so_orden_gobierno'] = $row['id_so_orden_gobierno'];
                    $array_items[$cont]['nombre_so_orden_gobierno'] = $row['nombre_so_orden_gobierno'];
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function descarga_ordenes()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/ordenes_gobierno.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_so_ordenes_gobierno');
        $csv_header = array('#',
                    utf8_decode('Órdenes de gobierno'),
                    'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_so_orden_gobierno']),
                    utf8_decode($row['nombre_so_orden_gobierno']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_orden_id($id)
    {
        $this->db->where('id_so_orden_gobierno', $id);
        
        $query = $this->db->get('cat_so_ordenes_gobierno');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function eliminar_orden($id)
    {
        $reg_eliminado = $this->dame_orden_id($id);

        $this->db->where('id_so_orden_gobierno', $id);
        $this->db->delete('cat_so_ordenes_gobierno');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Órdenes de gobierno', 'Eliminación orden de gobierno: ' . $reg_eliminado['nombre_so_orden_gobierno']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_orden()
    {
        $this->db->where('nombre_so_orden_gobierno', $this->input->post('nombre_so_orden_gobierno'));
        
        $query = $this->db->get('cat_so_ordenes_gobierno');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_so_orden_gobierno' => '',
                'nombre_so_orden_gobierno' => $this->input->post('nombre_so_orden_gobierno'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_so_ordenes_gobierno', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Órdenes de gobierno', 'Alta del orden de gobierno: ' . $data_new['nombre_so_orden_gobierno']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_orden()
    {

        $this->db->where('nombre_so_orden_gobierno', $this->input->post('nombre_so_orden_gobierno'));
        $this->db->where_not_in('id_so_orden_gobierno', $this->input->post('id_so_orden_gobierno'));
        $query = $this->db->get('cat_so_ordenes_gobierno');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_so_orden_gobierno' => $this->input->post('nombre_so_orden_gobierno'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_so_orden_gobierno', $this->input->post('id_so_orden_gobierno'));
            $this->db->update('cat_so_ordenes_gobierno', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Órdenes de gobierno', 'Edición del orden de gobierno: ' . $data_update['nombre_so_orden_gobierno']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_todos_presupuestos()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $query = $this->db->get('cat_presupuesto_conceptos');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
    
                    $array_items[$cont]['id_presupesto_concepto'] = $row['id_presupesto_concepto'];
                    $array_items[$cont]['capitulo'] = $row['capitulo'];
                    $array_items[$cont]['concepto'] = $row['concepto'];
                    $array_items[$cont]['partida'] = $row['partida'];
                    $array_items[$cont]['denominacion'] = $row['denominacion'];
                    $array_items[$cont]['descripcion'] = $row['descripcion'];
                    $array_items[$cont]['id_captura'] = $this->get_estatus_captura($row['id_captura']);
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function dame_todos_presupuestos_active()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $this->db->where('active', '1');
        $query = $this->db->get('vcap_conceptos');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
    
                    $array_items[$cont]['id_presupesto_concepto'] = $row['id_presupesto_concepto'];
                    $array_items[$cont]['capitulo'] = $row['capitulo'];
                    $array_items[$cont]['concepto'] = $row['concepto'];
                    $array_items[$cont]['partida'] = $row['partida'];
                    $array_items[$cont]['denominacion'] = $row['denominacion'];
                    $array_items[$cont]['descripcion'] = $row['descripcion'];
                    $array_items[$cont]['id_captura'] = $this->get_estatus_captura($row['id_captura']);
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function dame_presupuestos_concepto_nombre($id)
    {
        $this->db->where('id_presupesto_concepto', $id);
        $query = $this->db->get('cat_presupuesto_conceptos');
        

        if ($query->num_rows() == 1)
        {
            $registro = [];
            foreach ($query->result_array() as $row) { 
                $registro['id_presupesto_concepto'] = $row['id_presupesto_concepto'];
                $registro['nombre'] = $row['capitulo'] . ' - ' . $row['concepto'] . ' - ' . $row['partida'] . ' - ' . $row['denominacion'];
            }
            return $registro['nombre'];
        }else{
            return '';
        }
    }


    function descarga_presupuestos()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/partidas_presupuestarias.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_presupuesto_conceptos');
        $csv_header = array('#',
                    utf8_decode('Capítulo'),
                    utf8_decode('Concepto'),
                    utf8_decode('Partida'),
                    utf8_decode('Denominación'),
                    utf8_decode('Descripción'),
                    utf8_decode('Captura'),
                    'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_presupesto_concepto']),
                    utf8_decode($row['capitulo']),
                    utf8_decode($row['concepto']),
                    utf8_decode($row['partida']),
                    utf8_decode($row['denominacion']),
                    utf8_decode($row['descripcion']),
                    utf8_decode($this->get_estatus_captura($row['id_captura'])),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_presupuesto_id($id)
    {
        $this->db->where('id_presupesto_concepto', $id);
        
        $query = $this->db->get('cat_presupuesto_conceptos');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function get_presupuesto_id($id)
    {
        $this->load->model('tpoadminv1/Generales_model');

        $this->db->where('id_presupesto_concepto', $id);
        
        $query = $this->db->get('cat_presupuesto_conceptos');
        
        if ($query->num_rows() == 1)
        {
            $presupuesto = '';
            foreach ($query->result_array() as $row) { 
                $presupuesto = array( 
                    'id_presupesto_concepto' => $row['id_presupesto_concepto'],
                    'capitulo' => $row['capitulo'],
                    'concepto' => $row['concepto'],
                    'partida' =>$row['partida'],
                    'denominacion' => $row['denominacion'],
                    'descripcion' => $row['descripcion'],
                    'id_captura' => $this->get_estatus_captura($row['id_captura']),
                    'active' => $this->Generales_model->get_estatus_name($row['active'])
                ); 
            }
            return $presupuesto;
        }else{
            return '';
        }
    }

    function eliminar_presupuesto($id)
    {
        $reg_eliminado = $this->dame_presupuesto_id($id);

        $this->db->where('id_presupesto_concepto', $id);
        $this->db->delete('cat_presupuesto_conceptos');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Partidas presupuestarias', 'Eliminación partida presupuestaria: ' . $reg_eliminado['partida']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_presupuesto()
    {
        $this->db->where('partida', $this->input->post('partida'));
        $this->db->or_where('denominacion', $this->input->post('denominacion'));

        $query = $this->db->get('cat_presupuesto_conceptos');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_presupesto_concepto' => '',
                'capitulo' => trim($this->input->post('capitulo')),
                'concepto' => trim($this->input->post('concepto')),
                'partida' => trim($this->input->post('partida')),
                'denominacion' => trim($this->input->post('denominacion')),
                'descripcion' => trim($this->input->post('descripcion')),
                'id_captura' => $this->input->post('id_captura'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_presupuesto_conceptos', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Partidas presupuestarias', 'Alta de la partida presupuestaria: ' . $data_new['partida']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_presupuesto()
    {
        //$this->db->where_not_in('id_presupesto_concepto', $this->input->post('id_presupesto_concepto'));
        $this->db->where('partida', $this->input->post('partida'));
        $this->db->or_where('denominacion', $this->input->post('denominacion'));
        $query = $this->db->get('cat_presupuesto_conceptos');
        $existe = true;
        if($query->num_rows() > 0){
            if($query->num_rows() == 1){
                foreach ($query->result_array() as $row) {
                    if($row['id_presupesto_concepto'] == $this->input->post('id_presupesto_concepto')){
                        $existe = false;
                    }
                }
            }
        }else{
            $existe = false;
        }

        if($existe){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'capitulo' => trim($this->input->post('capitulo')),
                'concepto' => trim($this->input->post('concepto')),
                'partida' => trim($this->input->post('partida')),
                'denominacion' => trim($this->input->post('denominacion')),
                'descripcion' => trim($this->input->post('descripcion')),
                'id_captura' => $this->input->post('id_captura'),
                'active' => $this->input->post('active')
            );
    
            $this->db->where('id_presupesto_concepto', $this->input->post('id_presupesto_concepto'));
            $this->db->update('cat_presupuesto_conceptos', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Partidas presupuestarias', 'Edición de la partida presupuestaria: ' . $data_update['partida']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_todos_trimestres($activos)
    {   
        $this->load->model('tpoadminv1/Generales_model');

        if($activos == true)
            $this->db->where('active', '1');
        $query = $this->db->get('cat_trimestres');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
    
                    $array_items[$cont]['id_trimestre'] = $row['id_trimestre'];
                    $array_items[$cont]['trimestre'] = $row['trimestre'];
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function descarga_trimestres()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/trimestres.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_trimestres');
        $csv_header = array('#',
                    utf8_decode('Trimestre'),
                    'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_trimestre']),
                    utf8_decode($row['trimestre']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_trimestre_id($id)
    {
        $this->db->where('id_trimestre', $id);
        
        $query = $this->db->get('cat_trimestres');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function dame_nombre_trimestre($id)
    {
        $this->db->select('trimestre');
        $this->db->where('id_trimestre', $id);
        
        $query = $this->db->get('cat_trimestres');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row['trimestre'];
        }else{
            return '';
        }
    }

    function eliminar_trimestre($id)
    {
        $reg_eliminado = $this->dame_trimestre_id($id);

        $this->db->where('id_trimestre', $id);
        $this->db->delete('cat_trimestres');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Trimestres', 'Eliminación trimestre: ' . $reg_eliminado['trimestre']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_trimestre()
    {
        $this->db->where('trimestre', $this->input->post('trimestre'));
        
        $query = $this->db->get('cat_trimestres');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_trimestre' => '',
                'trimestre' => $this->input->post('trimestre'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_trimestres', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Trimestres', 'Alta trimestre: ' . $data_new['trimestre']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_trimestre()
    {

        $this->db->where('trimestre', $this->input->post('trimestre'));
        $this->db->where_not_in('id_trimestre', $this->input->post('id_trimestre'));
        $query = $this->db->get('cat_trimestres');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'trimestre' => $this->input->post('trimestre'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_trimestre', $this->input->post('id_trimestre'));
            $this->db->update('cat_trimestres', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Trimestres', 'Edición trimestre: ' . $data_update['trimestre']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_todos_ejercicios($activos)
    {
        $this->load->model('tpoadminv1/Generales_model');

        if($activos == true){
            $this->db->where('active', '1');
        }
        $query = $this->db->get('cat_ejercicios');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
    
                    $array_items[$cont]['id_ejercicio'] = $row['id_ejercicio'];
                    $array_items[$cont]['ejercicio'] = $row['ejercicio'];
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function dame_nombre_ejercicio($id)
    {
        $this->db->select('ejercicio');
        $this->db->where('id_ejercicio', $id);
        $query = $this->db->get('cat_ejercicios');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row){
            }
            return $row['ejercicio'];
        }else
        {
            return '';
        }
    }

    function descarga_ejercicios()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/ejercicios.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_ejercicios');
        $csv_header = array('#',
                    utf8_decode('Ejercicio'),
                    'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_ejercicio']),
                    utf8_decode($row['ejercicio']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_ejercicio_id($id)
    {
        $this->db->where('id_ejercicio', $id);
        
        $query = $this->db->get('cat_ejercicios');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function eliminar_ejercicio($id)
    {
        $reg_eliminado = $this->dame_ejercicio_id($id);

        $this->db->where('id_ejercicio', $id);
        $this->db->delete('cat_ejercicios');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Ejercicios', 'Eliminación ejercicio: ' . $reg_eliminado['ejercicio']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_ejercicio()
    {
        $this->db->where('ejercicio', $this->input->post('ejercicio'));
        
        $query = $this->db->get('cat_ejercicios');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_ejercicio' => '',
                'ejercicio' => $this->input->post('ejercicio'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_ejercicios', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Ejercicios', 'Alta ejercicio: ' . $data_new['ejercicio']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_ejercicio()
    {

        $this->db->where('ejercicio', $this->input->post('ejercicio'));
        $this->db->where_not_in('id_ejercicio', $this->input->post('id_ejercicio'));
        $query = $this->db->get('cat_ejercicios');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'ejercicio' => $this->input->post('ejercicio'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_ejercicio', $this->input->post('id_ejercicio'));
            $this->db->update('cat_ejercicios', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Ejercicios', 'Edición ejercicio: ' . $data_update['ejercicio']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_todos_personalidades($activos)
    {
        $this->load->model('tpoadminv1/Generales_model');

        if($activos == true){
            $this->db->where('active', '1');
        }
        $query = $this->db->get('cat_personalidad_juridica');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
    
                    $array_items[$cont]['id_personalidad_juridica'] = $row['id_personalidad_juridica'];
                    $array_items[$cont]['nombre_personalidad_juridica'] = $row['nombre_personalidad_juridica'];
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function descarga_personalidades()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/personalidad_juridica.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_personalidad_juridica');
        $csv_header = array('#',
                    utf8_decode('Personalidad jurídica'),
                    'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_personalidad_juridica']),
                    utf8_decode($row['nombre_personalidad_juridica']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_personalidad_id($id)
    {
        $this->db->where('id_personalidad_juridica', $id);
        
        $query = $this->db->get('cat_personalidad_juridica');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function eliminar_personalidad($id)
    {
        $reg_eliminado = $this->dame_personalidad_id($id);

        $this->db->where('id_personalidad_juridica', $id);
        $this->db->delete('cat_personalidad_juridica');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Personalidades jurídicas del proveedor', 'Eliminación personalidad jurídica: ' . $reg_eliminado['nombre_personalidad_juridica']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_personalidad()
    {
        $this->db->where('nombre_personalidad_juridica', $this->input->post('nombre_personalidad_juridica'));
        
        $query = $this->db->get('cat_personalidad_juridica');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_personalidad_juridica' => '',
                'nombre_personalidad_juridica' => $this->input->post('nombre_personalidad_juridica'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_personalidad_juridica', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Personalidades jurídicas del proveedor', 'Alta personalidad jurídica: ' . $data_new['nombre_personalidad_juridica']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_personalidad()
    {

        $this->db->where('nombre_personalidad_juridica', $this->input->post('nombre_personalidad_juridica'));
        $this->db->where_not_in('id_personalidad_juridica', $this->input->post('id_personalidad_juridica'));
        $query = $this->db->get('cat_personalidad_juridica');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_personalidad_juridica' => $this->input->post('nombre_personalidad_juridica'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_personalidad_juridica', $this->input->post('id_personalidad_juridica'));
            $this->db->update('cat_personalidad_juridica', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Personalidades jurídicas del proveedor', 'Edición personalidad jurídica: ' . $data_update['nombre_personalidad_juridica']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_nombre_personalidad_juridica($id)
    {
        $this->db->select('nombre_personalidad_juridica');
        $this->db->where('id_personalidad_juridica', $id);
        $this->db->where('active', '1');
        $query = $this->db->get('cat_personalidad_juridica');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row){
            }
            return $row['nombre_personalidad_juridica'];
        }else
        {
            return '';
        }
    }

    function dame_todos_procedimientos($activos)
    {
        $this->load->model('tpoadminv1/Generales_model');

        if($activos == true){
            $this->db->order_by('nombre_procedimiento', 'ASC');
            $this->db->where('active', '1');
        }
        $query = $this->db->get('cat_procedimientos');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
    
                    $array_items[$cont]['id_procedimiento'] = $row['id_procedimiento'];
                    $array_items[$cont]['nombre_procedimiento'] = $row['nombre_procedimiento'];
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function descarga_procedimientos()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/procedimientos_contratacion.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_procedimientos');
        $csv_header = array('#',
                    utf8_decode('Procedimiento de contratación'),
                    'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_procedimiento']),
                    utf8_decode($row['nombre_procedimiento']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_nombre_procedimiento($id)
    {
        $this->db->select('nombre_procedimiento');
        $this->db->where('id_procedimiento', $id);
        
        $query = $this->db->get('cat_procedimientos');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row['nombre_procedimiento'];
        }else{
            return '';
        }
    }

    function dame_procedimiento_id($id)
    {
        $this->db->where('id_procedimiento', $id);
        
        $query = $this->db->get('cat_procedimientos');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function eliminar_procedimiento($id)
    {
        $reg_eliminado = $this->dame_procedimiento_id($id);

        $this->db->where('id_procedimiento', $id);
        $this->db->delete('cat_procedimientos');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Procedimientos de contratación', 'Eliminación procedimiento de contratación: ' . $reg_eliminado['nombre_procedimiento']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_procedimiento()
    {
        $this->db->where('nombre_procedimiento', $this->input->post('nombre_procedimiento'));
        
        $query = $this->db->get('cat_procedimientos');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_procedimiento' => '',
                'nombre_procedimiento' => $this->input->post('nombre_procedimiento'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_procedimientos', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Procedimientos de contratación', 'Alta procedimiento de contratación: ' . $data_new['nombre_procedimiento']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_procedimiento()
    {

        $this->db->where('nombre_procedimiento', $this->input->post('nombre_procedimiento'));
        $this->db->where_not_in('id_procedimiento', $this->input->post('id_procedimiento'));
        $query = $this->db->get('cat_procedimientos');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'nombre_procedimiento' => $this->input->post('nombre_procedimiento'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_procedimiento', $this->input->post('id_procedimiento'));
            $this->db->update('cat_procedimientos', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Procedimientos de contratación', 'Edición procedimiento de contratación: ' . $data_update['nombre_procedimiento']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }

    function dame_todos_ligas($activos)
    {
        $this->load->model('tpoadminv1/Generales_model');

        if($activos == true){
            $this->db->where('active', '1');
        }
        $query = $this->db->get('cat_tipo_liga');
        
        if($query->num_rows() > 0)
        {
            $array_items = [];
            $cont = 0;
            foreach ($query->result_array() as $row) 
            {
    
                    $array_items[$cont]['id_tipo_liga'] = $row['id_tipo_liga'];
                    $array_items[$cont]['tipo_liga'] = $row['tipo_liga'];
                    $array_items[$cont]['active'] = $this->Generales_model->get_estatus_name($row['active']);
                    $cont++;
            }
            return $array_items;
        }
    }

    function descarga_ligas()
    {
        $this->load->model('tpoadminv1/Generales_model');

        $filename = 'dist/csv/tipos_ligas.csv';
        $myfile = fopen(FCPATH . $filename, 'w');
        
        $query = $this->db->get('cat_tipo_liga');
        $csv_header = array('#',
                    utf8_decode('Tipo de liga'),
                    'Estatus');
        fputcsv($myfile, $csv_header);
        
        $csv = [];
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row) 
            {
                $csv = array(
                    utf8_decode($row['id_tipo_liga']),
                    utf8_decode($row['tipo_liga']),
                    utf8_decode($this->Generales_model->get_estatus_name($row['active']))
                );
                fputcsv($myfile, $csv);
            }
        }

        fclose($myfile);

        return $filename;
    }

    function dame_liga_id($id)
    {
        $this->db->where('id_tipo_liga', $id);
        
        $query = $this->db->get('cat_tipo_liga');
        
        if ($query->num_rows() == 1)
        {
            foreach ($query->result_array() as $row) { }
            return $row;
        }else{
            return '';
        }
    }

    function eliminar_liga($id)
    {
        $reg_eliminado = $this->dame_liga_id($id);

        $this->db->where('id_tipo_liga', $id);
        $this->db->delete('cat_tipo_liga');
        
        if($this->db->affected_rows() > 0)
        {
            if(!empty($reg_eliminado))
                $this->registro_bitacora('Catálogos/Ligas', 'Eliminación tipo de liga: ' . $reg_eliminado['tipo_liga']);
            return 1; // is correct
        }else
        {
            return 0; // sometime is wrong
        }
    }

    function agregar_liga()
    {
        $this->db->where('tipo_liga', $this->input->post('tipo_liga'));
        
        $query = $this->db->get('cat_tipo_liga');

        if($query->num_rows() > 0){
            return 2; // field is duplicated
        }else{
            $data_new = array(
                'id_tipo_liga' => '',
                'tipo_liga' => $this->input->post('tipo_liga'),
                'active' => $this->input->post('active'),
            );
            
            $this->db->insert('cat_tipo_liga', $data_new);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Ligas', 'Alta tipo de liga: ' . $data_new['tipo_liga']);
                return 1; // is correct
            }else
            {
                return 0; // sometime is wrong
            }
        }
    }

    function editar_liga()
    {

        $this->db->where('tipo_liga', $this->input->post('tipo_liga'));
        $this->db->where_not_in('id_tipo_liga', $this->input->post('id_tipo_liga'));
        $query = $this->db->get('cat_tipo_liga');

        if($query->num_rows() > 0 ){
            return 2; // field is duplicated
        }else{
            $data_update = array(
                'tipo_liga' => $this->input->post('tipo_liga'),
                'active' => $this->input->post('active'),
            );
    
            $this->db->where('id_tipo_liga', $this->input->post('id_tipo_liga'));
            $this->db->update('cat_tipo_liga', $data_update);
    
            if($this->db->affected_rows() > 0)
            {
                $this->registro_bitacora('Catálogos/Ligas', 'Edición tipo de liga: ' . $data_update['tipo_liga']);
                return 1;
            }else
            {
                return 0;
            }
        }
    }
}

?>