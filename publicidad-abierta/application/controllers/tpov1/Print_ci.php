<?php


/**
 * Description of Print_CI
 *
 * INAI TPO
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
     }

     function print_presupuestos(){

       $this->load->model('tpov1/graficas/Graficas_model');
       
       $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
       $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 

       $data['title'] = "Presupuesto";
       $data['registros'] = $this->Graficas_model->get_desglose_partidas_presupuesto($ejercicio);
       
       //lista de nombre de las columnas como se carga en el array de registros
       $data['registros_columnas'] = array(
           'ejercicio',
           'partida',
           'descripcion',
           'original',
           'modificaciones',
           'presupuesto',
           'ejercido'
       );
       //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
       $data['nombre_columnas'] = array(
           'Ejercicio',
           'Clave de partida',
           'Descripción',
           'Presupuesto original',
           'Monto modificado',
           'Presupuesto modificado',
           'Presupuesto ejercido'
       );

       $this->load->view('tpoadminv1/includes/print_template', $data);
     }

     function print_proveedores(){

        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 
 
        $data['title'] = "Gasto por proveedores";
        $data['registros'] = $this->Tablas_model->get_valores_tabla_proveedores($ejercicio);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'nombre',
            'contratos',
            'ordenes',
            'monto',
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Proveedor',
            'Contratos',
            'Órdenes de compra',
            'Monto total pagado'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_ordenes_proveedor(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
 
        $data['title'] = "&Oacute;rdenes de compra asociadas al proveedor";
        $data['registros'] = $this->Tablas_model->get_ordenes_proveedor($str);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'trimestre',
            'numero_orden_compra',
            'proveedor',
            'nombre_campana_aviso',
            'monto',
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Trimestre',
            'Orden de compra',
            'Proveedor',
            'Campa&ntilde;a o aviso institucional',
            'Monto'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_contratos_proveedor(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
 
        $data['title'] = "Contratos asociadas al proveedor";
        $data['registros'] = $this->Tablas_model->get_contratos_proveedor($str);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'trimestre',
            'so_contratante',
            'so_solicitante',
            'numero_contrato',
            'monto_contrato',
            'monto_modificado',
            'monto_total',
            'monto_pagado'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Trimestre',
            'Contratante',
            'Solicitante',
            'Contrato',
            'Monto original del contrato',
            'Monto modificado',
            'Monto total',
            'Monto pagado'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_tiposervicios(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
 
        $data['title'] = "Gasto por tipo de servicio";
        $data['registros'] = $this->Tablas_model->get_servicios_gasto($ejercicio);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'factura',
            'fecha_erogacion',
            'proveedor',
            'nombre_servicio_clasificacion',
            'nombre_servicio_categoria',
            'nombre_servicio_subcategoria',
            'tipo',
            'nombre_campana_aviso',
            'monto_servicio');
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Factura',
            'Fecha',
            'Proveedor',
            'Clasificaci&oacute;n',
            'Categor&iacute;a',
            'Subcategor&iacute;a',
            'Tipo',
            'Campa&ntilde;a o aviso',
            'Monto gastado'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_contratos(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
 
        $registros = $this->Graficas_model->get_contratos($ejercicio);
        $data['title'] = "Contratos";
        $data['registros'] = $registros['data'];
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'trimestre',
            'numero_contrato',
            'so_contratante',
            'so_solicitante',
            'proveedor',
            'monto_contrato',
            'monto_ejercido');
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Trimestre',
            'Contrato',
            'Sujeto obligado contratante',
            'Sujeto obligado solicitante',
            'Proveedor',
            'Monto total del contrato',
            'Monto total ejercido'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_ordenes(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
 
        $registros = $this->Graficas_model->get_ordenes_compra($ejercicio);
        $data['title'] = "&Oacute;rdenes de compra";
        $data['registros'] = $registros['data'];
       
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'trimestre',
            'numero_orden_compra',
            'so_solicitante',
            'so_contratante',
            'proveedor',
            'monto_ejercido');
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Trimestre',
            'Orden de compra',
            'Sujeto obligado solicitante',
            'Sujeto obligado contratante',
            'Proveedor',
            'Monto total ejercido'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_ordenes_otros(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
 
        $data['title'] = "Otros servicios asociados a la comunicaci&oacute;n relacionados con la orden de compra";
        $data['registros'] = $this->Tablas_model->get_servicios_ordenes_gasto($str, 2);
       
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'factura',
            'fecha_erogacion',
            'proveedor',
            'nombre_servicio_categoria',
            'nombre_servicio_subcategoria',
            'tipo',
            'nombre_campana_aviso',
            'monto_servicio');
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Factura',
            'Fecha',
            'Proveedor',
            'Categor&iacute;a',
            'Subcategor&iacute;a',
            'Tipo',
            'Campa&ntilde;a o aviso',
            'Monto gastado'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_ordenes_servicios(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
 
        $data['title'] = "Servicios de difusi&oacute;n en medios de comunicaci&oacute;n relacionados con la orden de compra";
        $data['registros'] = $this->Tablas_model->get_servicios_ordenes_gasto($str, 1);
       
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'factura',
            'fecha_erogacion',
            'proveedor',
            'nombre_servicio_categoria',
            'nombre_servicio_subcategoria',
            'tipo',
            'nombre_campana_aviso',
            'monto_servicio');
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Factura',
            'Fecha',
            'Proveedor',
            'Categor&iacute;a',
            'Subcategor&iacute;a',
            'Tipo',
            'Campa&ntilde;a o aviso',
            'Monto gastado'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_contratos_otros(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
 
        $data['title'] = "Otros servicios asociados a la comunicaci&oacute;n relacionados con el contrato";
        $data['registros'] = $this->Tablas_model->get_servicios_contratos_gasto($str, 2);
       
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'factura',
            'fecha_erogacion',
            'proveedor',
            'nombre_servicio_categoria',
            'nombre_servicio_subcategoria',
            'tipo',
            'nombre_campana_aviso',
            'monto_servicio');
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Factura',
            'Fecha',
            'Proveedor',
            'Categor&iacute;a',
            'Subcategor&iacute;a',
            'Tipo',
            'Campa&ntilde;a o aviso',
            'Monto gastado'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_contratos_servicios(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
 
        $data['title'] = "Servicios de difusi&oacute;n en medios de comunicaci&oacute;n relacionados con el contrato";
        $data['registros'] = $this->Tablas_model->get_servicios_contratos_gasto($str, 1);
       
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'factura',
            'fecha_erogacion',
            'proveedor',
            'nombre_servicio_categoria',
            'nombre_servicio_subcategoria',
            'tipo',
            'nombre_campana_aviso',
            'monto_servicio');
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Factura',
            'Fecha',
            'Proveedor',
            'Categor&iacute;a',
            'Subcategor&iacute;a',
            'Tipo',
            'Campa&ntilde;a o aviso',
            'Monto gastado'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_contratos_convenios(){
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpoadminv1/capturista/Contratos_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
 
        $data['title'] = "Convenios modificatorios asociados al contrato";
        $data['registros'] = $this->Contratos_model->dame_todos_convenios_modificatorios($str);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'trimestre',
            'numero_convenio',
            'monto_convenio_format');
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Trimestre',
            'Convenio modificatorio',
            'Monto'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_contratos_ordenes(){
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpoadminv1/capturista/Ordenes_Compra_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
 
        $data['title'] = "&Oacute;rdenes de compra asociadas al contrato";
        $data['registros'] = $this->Ordenes_Compra_model->dame_todos_ordenes_compra_by_contrato($str);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'trimestre',
            'numero_orden_compra',
            'fecha_orden',
            'nombre_so_solicitante',
            'campana_aviso',
            'monto'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Trimestre',
            'Orden de compra',
            'Fecha',
            'Solicitante',
            'Campa&ntilde;a o aviso',
            'Monto'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_sujetos_obligados(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
 
        $data['title'] = "Sujetos obligados";
        $data['registros'] = $this->Tablas_model->get_sujetos_montos($str);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'funcion',
            'orden',
            'estado',
            'nombre_sujeto_obligado',
            'siglas_sujeto_obligado',
            'monto_formato'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Función',
            'Orden',
            'Estado',
            'Nombre',
            'Siglas',
            'Monto total'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_so_ordenes(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
        $ejercicio = null; 
        $so = $this->Sujeto_model->get_sujeto_id($str);
        $nombre = empty($so) ? '' : $so['nombre'];
        $data['title'] = "&Oacute;rdenes de compra asociadas al sujeto obligado";
        $data['registros'] = $this->Tablas_model->get_ordenes_compra_so($nombre, $ejercicio);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'trimestre',
            'numero_orden_compra',
            'nombre_so_contratante',
            'nombre_so_solicitante',
            'nombre_proveedor',
            'monto_ejercido'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Trimestre',
            'Orden de compra',
            'Sujeto obligado contratante',
            'sujeto obligado solicitante',
            'Proveedor',
            'Total ejercido'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_so_contratos(){
        $this->load->model('tpov1/Generales_vp_model');
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        $this->load->model('tpoadminv1/sujetos/Sujeto_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
        $ejercicio = null; 
        $so = $this->Sujeto_model->get_sujeto_id($str);
        $nombre = empty($so) ? '' : $so['nombre'];
        $data['title'] = "Contratos asociados al sujeto obligado";
        $data['registros'] = $this->Tablas_model->get_contratos_so($nombre, $ejercicio);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'trimestre',
            'numero_contrato',
            'nombre_so_contratante',
            'nombre_so_solicitante',
            'nombre_proveedor',
            'monto_contrato',
            'monto_ejercido'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Trimestre',
            'Contrato',
            'Sujeto obligado contratante',
            'sujeto obligado solicitante',
            'Proveedor',
            'Monto total del contrato',
            'Monto total ejercido'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_erogaciones(){
        $this->load->model('tpov1/graficas/Tablas_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str);
        
        $data['title'] = "Erogaciones";
        $data['registros'] = $this->Tablas_model->get_erogaciones($ejercicio);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'trimestre',
            'proveedor',
            'numero_factura',
            'fecha_erogacion',
            'monto'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Trimestre',
            'Proveedor',
            'Clave &uacute;nica',
            'Fecha',
            'Monto total'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_subconceptos_erogacion(){
        $this->load->model('tpoadminv1/capturista/Facturas_model');
        $this->load->model('tpov1/graficas/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
        
        $data['title'] = "Subconceptos de factura asociados a la erogaci&oacute;n";
        $data['registros'] = $this->Facturas_model->dame_todas_facturas_desglose($str, true);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'nombre_campana_aviso',
            'nombre_servicio_clasificacion',
            'nombre_servicio_categoria',
            'monto_desglose_format'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Campa&ntilde;a o aviso institucional',
            'Clasificaci&oacute;n',
            'Categor&iacute;a del servicio',
            'Monto del subconcepto'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_campanas(){
        $this->load->model('tpov1/campanas_v1/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 

        $data['title'] = "Campa&ntilde;as";
        $data['registros'] =  $this->Graficas_model->get_desglose_campanas_avisos($ejercicio);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'trimestre',
            'nombre_campana_tipo',
            'nombre_campana_aviso',
            'contratante',
            'solicitante',
            'nombre_tipo_tiempo',
            'monto_total'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Trimestre',
            'Tipo',
            'Nombre de la campa&ntilde;a o aviso institucional',
            'Contratante',
            'Solicitante',
            'Tiempo oficial',
            'Monto total ejercido'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_avisos(){
        $this->load->model('tpov1/campanas_v1/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));
        $ejercicio = $this->Graficas_model->dame_nombre_ejercicio($str); 

        $data['title'] = "Campa&ntilde;as";
        $data['registros'] =  $this->Graficas_model->get_desglose_avisos($ejercicio);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'id',
            'ejercicio',
            'trimestre',
            'nombre_campana_tipo',
            'nombre_campana_aviso',
            'contratante',
            'solicitante',
            'nombre_tipo_tiempo',
            'monto_total'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            '#',
            'Ejercicio',
            'Trimestre',
            'Tipo',
            'Nombre de la campa&ntilde;a o aviso institucional',
            'Contratante',
            'Solicitante',
            'Tiempo oficial',
            'Monto total ejercido'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_campanas_servicios(){
        $this->load->model('tpov1/campanas_v1/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));

        $data['title'] = "Servicio de difusi&oacute;n en medios de comunicaci&oacute;n relacionados con la campa&ntilde;a";
        $data['registros'] =  $this->Graficas_model->dame_serv_dif_campana_id($str);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'ejercicio',
            'factura',
            'fecha_erogacion',
            'proveedor',
            'nombre_servicio_categoria',
            'nombre_servicio_subcategoria',
            'tipo',
            'nombre_campana_aviso',
            'monto_servicio'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            'Ejercicio',
            'Factura',
            'Fecha',
            'Proveedor',
            'Categor&iacute;a',
            'Subcategor&iacute;a',
            'Tipo',
            'Campa&ntilde;a o aviso',
            'Monto gastado'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }

      function print_campanas_otros(){
        $this->load->model('tpov1/campanas_v1/Graficas_model');
        
        $str = $this->Graficas_model->limpiar_Cadena($this->uri->segment(4));

        $data['title'] = "Otros servicios asociados a la comunicaci&oacute;n relacionados con la campa&ntilde;a";
        $data['registros'] =  $this->Graficas_model->dame_otros_serv_dif_campana_id($str);
        
        //lista de nombre de las columnas como se carga en el array de registros
        $data['registros_columnas'] = array(
            'ejercicio',
            'factura',
            'fecha_erogacion',
            'proveedor',
            'nombre_servicio_categoria',
            'nombre_servicio_subcategoria',
            'tipo',
            'nombre_campana_aviso',
            'monto_servicio'
        );
        //lista de nombre de los <th> para la tabla, debe ser el mismo numero de registros que el array de registros_columnas
        $data['nombre_columnas'] = array(
            'Ejercicio',
            'Factura',
            'Fecha',
            'Proveedor',
            'Categor&iacute;a',
            'Subcategor&iacute;a',
            'Tipo',
            'Campa&ntilde;a o aviso',
            'Monto gastado'
        );
 
        $this->load->view('tpoadminv1/includes/print_template', $data);
      }
}

?>