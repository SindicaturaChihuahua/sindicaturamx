<?php 
    $link_file = '';
    if($disponible == true && !empty($orden['name_file_orden'])){
        $link_file = "<a href='" . $orden['path_file_orden'] . "' download>" . $orden['name_file_orden'] . "</a>";
    }
?>

<?php
if($disponible == true){ /* se muestra la info*/ 
?> 
<style>
    #info_contratos td{
        padding: 2px !important;
        font-size: 12px;
    }
</style>
<input name="id_orden_compra" type="hidden" value="<?php echo $orden['id_orden_compra'] ?>">
<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Informaci&oacute;n de la orden de compra</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <table id="info_contratos" class="table table-striped">
                <tbody>
                    <tr>
                        <td class="text-right">Ejercicio <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['ejercicio']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Trimestre <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['trimestre']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['trimestre']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Clave de la orden <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['orden_compra']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['numero_orden_compra']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Fecha <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['fecha_orden_compra']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['fecha_orden']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Trimestre <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['trimestre']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['trimestre']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Sujeto obligado contratante <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['so_contratante']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['nombre_so_contratante'] . @$link_so_contratante ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Sujeto obligado solicitante <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['so_solicitante']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['nombre_so_solicitante'] . @$link_so_solicitante ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Proveedor <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['proveedor']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['nombre_proveedor'] . @$link_proveedor ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Nombre comercial del proveedor <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['nombre_comercial_proveedor']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['nombre_comercial_proveedor']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Tipo de adjudicaci&oacute;n <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['nombre_procedimiento']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['nombre_procedimiento']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Motivo de adjudicacion <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['descripcion_justificacion']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['descripcion_justificacion']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Monto total <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_oc']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['monto']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Campa&ntilde;a o aviso institucional <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['proveedor']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['nombre_campana_aviso'] . @$link_campana_aviso?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Vinculo al archivo .pdf o .xml de la orden de compra <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['file_contrato']?>"></i>: </td>
                        <td width="80%"><?php echo $link_file?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Nota <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['nota']?>"></i>: </td>
                        <td width="80%"><?php echo $orden['nota']?></td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>

<div class="row"> <!-- Servicios de información-->
    <div class="box box-default">
        <div class="box-header header">
            <h3 class="box-title">Servicios de difusi&oacute;n en medios de comunicaci&oacute;n relacionados con la orden de compra</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border table-responsive">
            <div id="options_servicio" class="row" style="margin-bottom:5px; margin-right:0px;">
                <div class="pull-right">
                    <a class="btn btn-default" onclick="print_btn_servicio()"><i class="fa fa-print"></i> Imprimir</a>
                    <a id="descargabtntableservicio" class="btn btn-default" onclick="descargar_archivo_table_servicio()"><i class="fa fa-file"></i> Exportar datos</a>
                    <input type="hidden" id="link_descarga_table_servicio" value="<?php echo $link_descarga_table_servicio; ?>"/>
                    <input type="hidden" id="link_print_servicio" value="<?php echo $print_url_servicio; ?>"/>
                </div> 
            </div>
            <div class="table-responsive-md">
                <table id="servicios" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ejercicio  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                            <th>Factura <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['numero_factura']?>"></i></th>
                            <th>Fecha <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['fecha_erogacion']?>"></i></th>
                            <th>Proveedor <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['proveedor']?>"></i></th>
                            <th>Categoria <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['categoria']?>"></i></th>
                            <th>Subcategoria <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['subcategoria']?>"></i></th>
                            <th>Tipo </th>
                            <th>Campa&ntilde;a o aviso <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['campana_aviso']?>"></i></th>
                            <th>Monto gastado <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_gastado']?>"></i></th>            
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                                    
                    </tbody>
                    <tfoot>
                        <tr style="font-size:13px; border-top: 2px solid #e8e8e8;">
                            <th>Σ</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div> <!-- /. table-responsive -->
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>

<div class="row"> <!-- Otros medios de información-->
    <div class="box box-default">
        <div class="box-header header">
            <h3 class="box-title">Otros servicios asociados a la comunicaci&oacute;n relacionados con la orden de compra</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border table-responsive">
            <div id="options_otros" class="row" style="margin-bottom:5px; margin-right:0px;">
                <div class="pull-right">
                    <a class="btn btn-default" onclick="print_btn_otros()"><i class="fa fa-print"></i> Imprimir</a>
                    <a id="descargabtntableotros" class="btn btn-default" onclick="descargar_archivo_table_otros()"><i class="fa fa-file"></i> Exportar datos</a>
                    <input type="hidden" id="link_descarga_table_otros" value="<?php echo $link_descarga_table_otros; ?>"/>
                    <input type="hidden" id="link_print_otros" value="<?php echo $print_url_otros; ?>"/>
                </div> 
            </div>
            <div class="table-responsive-md">
                <table id="otros" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ejercicio  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                            <th>Factura <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['numero_factura']?>"></i></th>
                            <th>Fecha <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['fecha_erogacion']?>"></i></th>
                            <th>Proveedor <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['proveedor']?>"></i></th>
                            <th>Categoria <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['categoria']?>"></i></th>
                            <th>Subcategoria <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['subcategoria']?>"></i></th>
                            <th>Tipo </th>
                            <th>Campa&ntilde;a o aviso <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['campana_aviso']?>"></i></th>
                            <th>Monto gastado <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_gastado']?>"></i></th>            
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                                    
                    </tbody>
                    <tfoot>
                        <tr style="font-size:13px; border-top: 2px solid #e8e8e8;">
                            <th>Σ</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div> <!-- /. table-responsive -->
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>

<script>

    var init = function(){
        get_servicios();
        get_servicios_otros();
    }

    var get_servicios = function(){

        $('#servicios').find('tbody').empty();
        $('#servicios').find('tbody').append('<tr><td colspan="9" class="text-center">Cargando informaci&oacute;n ...</td></tr>');

        var form_data = new FormData();                  
        form_data.append('id_orden_compra', $('input[name="id_orden_compra"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/contratos_ordenes/get_servicio_ordenes' ?>';

        buscar(url, form_data, set_valores_table, 'servicios');
    }

    var get_servicios_otros = function(){

        $('#otros').find('tbody').empty();
        $('#otros').find('tbody').append('<tr><td colspan="9" class="text-center">Cargando informaci&oacute;n ...</td></tr>');

        var form_data = new FormData();                  
        form_data.append('id_orden_compra', $('input[name="id_orden_compra"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/contratos_ordenes/get_servicio_otros_ordenes' ?>';

        buscar(url, form_data, set_valores_table, 'otros');
    }

    var buscar = function(url_server, formData, callback, container){              
        $.ajax({
            url: url_server, // point to server-side PHP script 
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: formData,                         
            type: 'post',
            complete: function(){
            },
            error:function(){
                error_datos(container);
            },
            success: function(response){
                if(response && callback){
                    callback(response, container);
                }else{
                    error_datos(container);
                }
            }
        });
    }

    var error_datos =  function(container){
        var tag = '#' + container;
        var num = get_numero_columnas(container);

        $(tag).find('tbody').empty();
        $(tag).find('tbody').append('<tr><td colspan="' + num + '">No se encontraron resultados</td></tr>');
        
    }

    var set_valores_table = function(response, container){
        var tag = '#' + container;
        $(tag).find('tbody').empty();
        if(Array.isArray(response)){
            response.map(function (e){
                var html = get_row_table(container, e);
                $(tag).find('tbody').append(html);
            });
        }
        var num = get_numero_columnas(container);
        var no_filter = [num - 1];
        initDataTable(tag, no_filter);
    }

    var get_row_table = function(container, e){
        var html = '<tr></tr>';
        switch(container){ 
            case 'convenios':
                html = "<tr>" +
                    "<td>"+e.id+"</td>" +
                    "<td>"+e.ejercicio+"</td>" +
                    "<td>"+e.trimestre+"</td>" +
                    "<td>"+e.numero_convenio+"</td>" +
                    "<td>"+e.monto_convenio_formato+"</td>" +
                    "<td><a href='" + e.id_convenio_modificatorio + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle'><i class='fa fa-link'></i></a></td>" + 
                    "</tr>"; 
                break;
            case 'ordenes':
                html = "<tr>" +
                    "<td>"+e.id+"</td>" +
                    "<td>"+e.ejercicio+"</td>" +
                    "<td>"+e.trimestre+"</td>" +
                    "<td>"+e.numero_orden_compra+"</td>" +
                    "<td>"+e.fecha_orden+"</td>" +
                    "<td>"+e.nombre_so_solicitante+"</td>" +
                    "<td>"+e.campana_aviso+"</td>" +
                    "<td>"+e.monto+"</td>" +
                    "<td><a href='" + e.id_orden_compra + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle'><i class='fa fa-link'></i></a></td>" + 
                    "</tr>"; //definir a donde se va este link
                break;
            case 'servicios':
            case 'otros':
                var link1 = "<a href='" + e.link_factura + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle Factura' target='_blank'><i class='fa fa-link'></i></a>";
                var link2 = "&nbsp;<a href='" + e.link_proveedor + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle Proveedor' target='_blank'><i class='fa fa-link'></i></a>";
                var link3 = "&nbsp;<a href='" + e.link_campana + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle Campa&ntilde;a o aviso institucional' target='_blank'><i class='fa fa-link'></i></a>";
                html = "<tr>" +
                    "<td>"+e.id+"</td>" +
                    "<td>"+e.ejercicio+"</td>" +
                    "<td>"+e.factura+"</td>" +
                    "<td>"+e.fecha_erogacion+"</td>" +
                    "<td>"+e.proveedor+"</td>" +
                    "<td>"+e.nombre_servicio_categoria+"</td>" +
                    "<td>"+e.nombre_servicio_subcategoria+"</td>" +
                    "<td>"+e.tipo+"</td>" +
                    "<td>"+e.nombre_campana_aviso+"</td>" +
                    "<td>"+e.monto_servicio+"</td>" +
                    "<td width='80'>" + link1 + link2 + link3 + "</td>" + 
                    "</tr>"; //definir a donde se va este link
                break;
        }

        return html;
    }

    var get_numero_columnas = function(container){
        var num = 0;
        switch(container){ //obtiene numero de columnas
            case 'convenios':
                num = 6; 
                break;
            case 'ordenes':
                num = 9;
                break;
            case 'servicios':
            case 'otros':
                num = 11;
                break;
        }

        return num;
    }

    var initDataTable = function(container, no_filter){
        $(container).dataTable({
            'bPaginate': true,
            'bLengthChange': true,
            'bFilter': true,
            'bSort': true,
            'bInfo': true,
            'bAutoWidth': false,
            'columnDefs': [ 
                { 'orderable': false, 'targets': no_filter } 
            ],
            'aLengthMenu': [[10, 25, 50, 100], [10, 25, 50, 100]],  //Paginacion
            'oLanguage': { 
                'sSearch': 'B&uacute;squeda ',
                'sInfoFiltered': '(filtrado de un total de _MAX_ registros)',
                'sInfo': 'Mostrando registros del <b>_START_</b> al <b>_END_</b> de un total de <b>_TOTAL_</b> registros',
                'sZeroRecords': 'No se encontraron resultados',
                'EmptyTable': 'Ning&uacute;n dato disponible en esta tabla',
                'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros',
                'sLoadingRecords': 'Cargando...',
                'sProcessing': 'Cargando...',
                'oPaginate': {
                    'sFirst': 'Primero',
                    'sLast': '&Uacute;ltimo',
                    'sNext': 'Siguiente',
                    'sPrevious': 'Anterior'
                },
                'sLengthMenu': '_MENU_ Registros por p&aacute;gina'
            },
            'footerCallback': function(row, data, start, end, display){
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                if(container == '#servicios'){
                    // Total over this page
                    pageTotal9 = api
                        .column( 9 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    var colum_9 = '$' + parseFloat(pageTotal9, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
                    
                    // Update footer
                    $( api.column( 9 ).footer() ).html(
                        colum_9 
                    );
                }

                if(container == '#otros'){
                    // Total over this page
                    pageTotal9 = api
                        .column( 9 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    var colum_9 = '$' + parseFloat(pageTotal9, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
                    
                    // Update footer
                    $( api.column( 9 ).footer() ).html(
                        colum_9 
                    );
                }
            }
        });
    }

    var print_btn_servicio = function(){
        var url = $('#link_print_servicio').val() + $('input[name="id_orden_compra"]').val();
        window.open(url, '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes');
    }

    var descargar_archivo_table_servicio = function(){
        var url_server = $('#link_descarga_table_servicio').val();
        $('#descargabtntableservicio').empty();
        $('#descargabtntableservicio').append('<i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;Exportando'); 
        $.ajax({
            url: url_server, // point to server-side PHP script 
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: '',                         
            type: 'post',
            complete: function(){
                $('#descargabtntableservicio').empty();
                $('#descargabtntableservicio').append('<i class="fa fa-file"></i>&nbsp;&nbsp;Exportar datos'); 
            },
            error:function(){
            },
            success: function(response){
                window.open(response, '_blank');
            }
        });
    }

    var print_btn_otros = function(){
        var url = $('#link_print_otros').val() + $('input[name="id_orden_compra"]').val();
        window.open(url, '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes');
    }

    var descargar_archivo_table_otros = function(){
        var url_server = $('#link_descarga_table_otros').val();
        $('#descargabtntableotros').empty();
        $('#descargabtntableotros').append('<i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;Exportando'); 
        $.ajax({
            url: url_server, // point to server-side PHP script 
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: '',                         
            type: 'post',
            complete: function(){
                $('#descargabtntableotros').empty();
                $('#descargabtntableotros').append('<i class="fa fa-file"></i>&nbsp;&nbsp;Exportar datos'); 
            },
            error:function(){
            },
            success: function(response){
                window.open(response, '_blank');
            }
        });
    }

</script>

<?php 
}else{
    echo "Orden de compra no encontrada. ";
}

?>