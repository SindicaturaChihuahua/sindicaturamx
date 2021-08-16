<?php

/*
* INAI / Tablas información de vista publica
* Se listan los metodos que hacen uso de vistas/mysql
*/

class Tablas_model extends CI_Model
{

    /* Sección de Contratos y ordenes de compra  -- detalle */
    function get_servicios_ordenes_gasto($id_orden_compra, $tipo_servicio)
    {
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->model('tpoadminv1/capturista/Ordenes_compra_model');
        $this->load->model('tpoadminv1/Generales_model');

        $this->db->where('id_orden_compra', $id_orden_compra);
        $this->db->where('id_servicio_clasificacion', $tipo_servicio);
        $query = $this->db->get('vgasto_clasf_servicio');

        $array_items = [];
        $total = $query->num_rows(); 
        if($query->num_rows() > 0){
            $cont = 0;
            foreach($query->result_array() as $row){
                $array_items[$cont]['id'] = $cont + 1;
                $array_items[$cont]['ejercicio'] = $row['ejercicio'];
                $array_items[$cont]['factura'] = $row['factura'];
                $array_items[$cont]['fecha_erogacion'] = $this->Generales_model->dateToString($row['fecha_erogacion']);
                $array_items[$cont]['proveedor'] = $row['proveedor'];
                $array_items[$cont]['nombre_servicio_categoria'] = $row['nombre_servicio_categoria'];
                $array_items[$cont]['nombre_servicio_subcategoria'] = $row['nombre_servicio_subcategoria'];
                $array_items[$cont]['tipo'] = $row['tipo'];
                $array_items[$cont]['nombre_campana_aviso'] = $row['nombre_campana_aviso'];
                $array_items[$cont]['monto_servicio'] = $this->Generales_model->money_format("%.2n", $row['monto_servicio']);
                $array_items[$cont]['link_factura'] = base_url(); /* completar */ 
                $array_items[$cont]['link_proveedor'] = base_url() . "index.php/tpov1/proveedores/proveedor_detalle/" .$row['id_proveedor'];
                $array_items[$cont]['link_campana'] = base_url(); /* completar */
                $cont++;
            }
        }

        return $array_items;
    }

    /* Sección de Contratos y ordenes de compra -- detalle */
    function get_servicios_contratos_gasto($id_contrato, $tipo_servicio)
    {
        $this->load->model('tpoadminv1/catalogos/Catalogos_model');
        $this->load->model('tpoadminv1/capturista/Ordenes_compra_model');
        $this->load->model('tpoadminv1/Generales_model');

        $this->db->where('id_contrato', $id_contrato);
        $this->db->where('id_servicio_clasificacion', $tipo_servicio);
        $query = $this->db->get('vgasto_clasf_servicio');

        $array_items = [];
        $total = $query->num_rows(); 
        if($query->num_rows() > 0){
            $cont = 0;
            foreach($query->result_array() as $row){
                $array_items[$cont]['id'] = $cont + 1;
                $array_items[$cont]['ejercicio'] = $row['ejercicio'];
                $array_items[$cont]['factura'] = $row['factura'];
                $array_items[$cont]['fecha_erogacion'] = $this->Generales_model->dateToString($row['fecha_erogacion']);
                $array_items[$cont]['proveedor'] = $row['proveedor'];
                $array_items[$cont]['nombre_servicio_categoria'] = $row['nombre_servicio_categoria'];
                $array_items[$cont]['nombre_servicio_subcategoria'] = $row['nombre_servicio_subcategoria'];
                $array_items[$cont]['tipo'] = $row['tipo'];
                $array_items[$cont]['nombre_campana_aviso'] = $row['nombre_campana_aviso'];
                $array_items[$cont]['monto_servicio'] = $this->Generales_model->money_format("%.2n", $row['monto_servicio']);
                $array_items[$cont]['link_factura'] = base_url(); /* completar */ 
                $array_items[$cont]['link_proveedor'] = base_url() . "index.php/tpov1/proveedores/proveedor_detalle/" .$row['id_proveedor'];
                $array_items[$cont]['link_campana'] = base_url(); /* completar */
                $cont++;
            }
        }

        return $array_items;
    }
}

?>