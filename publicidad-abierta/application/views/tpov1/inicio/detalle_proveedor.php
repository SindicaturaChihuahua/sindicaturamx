<?php    
if($disponible == true){ /* se muestra la info*/ ?> 
<input name="id_proveedor" type="hidden" value="<?php echo $proveedor['id_proveedor'] ?>">
<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Informacion del proveedor</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Nombre o raz&oacute;n social: </td>
                        <td width="80%"><?php echo $proveedor['nombre_razon_social']?></td>
                    </tr>
                    <tr>
                        <td>Nombre Comercial: </td>
                        <td><?php echo $proveedor['nombre_comercial']?></td>
                    </tr>
                    <tr>
                        <td>R.F.C: </td>
                        <td><?php echo $proveedor['rfc']?></td>
                    </tr>
                    <tr>
                        <td>Personalidad jur&iacute;dica: </td>
                        <td><?php echo $proveedor['nombre_personalidad_juridica']?></td>
                    </tr>
                    <tr>
                        <td>Nombres: </td>
                        <td><?php echo $proveedor['nombres']?></td>
                    </tr>
                    <tr>
                        <td>Primer apellido: </td>
                        <td><?php echo $proveedor['primer_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Segundo apellido: </td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Nota: </td>
                        <td><?php echo $proveedor['nota']?></td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>

<div class="row">
    <div class="box box-default">
        <div class="box-header header">
            <h3 class="box-title">&Oacute;rdenes de compra asociadas al proveedor</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border table-responsive">
            <div id="options_ordenes" class="row" style="margin-bottom:5px; margin-right:0px;">
                <div class="pull-right">
                    <a class="btn btn-default" onclick="print_btn_orden()"><i class="fa fa-print"></i> Imprimir</a>
                    <a id="descargabtntableorden" class="btn btn-default" onclick="descargar_archivo_table_orden()"><i class="fa fa-file"></i> Exportar datos</a>
                    <input type="hidden" id="link_descarga_table_orden" value="<?php echo $link_descarga_table_orden; ?>"/>
                    <input type="hidden" id="link_print_orden" value="<?php echo $print_url_orden; ?>"/>
                </div> 
            </div>
            <div class="table-responsive-md">
                <table id="ordenes" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ejercicio  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                            <th>Trimestre <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['trimestre']?>"></i></th>                            
                            <th>Orden de compra <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['orden_compra']?>"></i></th>
                            <th>Proveedor <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['proveedor']?>"></i></th>
                            <th>Campa&ntilde;a o aviso institucional <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['campana']?>"></i></th>
                            <th>Monto</th>            
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
                        </tr>
                    </tfoot>
                </table>
            </div> <!-- /. table-responsive -->
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>


<div class="row">
    <div class="box box-default">
        <div class="box-header header">
            <h3 class="box-title">Contratos asociados al proveedor</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border table-responsive">
            <div id="options_contratos" class="row" style="margin-bottom:5px; margin-right:0px;">
                <div class="pull-right">
                    <a class="btn btn-default" onclick="print_btn_contrato()"><i class="fa fa-print"></i> Imprimir</a>
                    <a id="descargabtntablecontrato" class="btn btn-default" onclick="descargar_archivo_table_contrato()"><i class="fa fa-file"></i> Exportar datos</a>
                    <input type="hidden" id="link_descarga_table_contrato" value="<?php echo $link_descarga_table_contrato; ?>"/>
                    <input type="hidden" id="link_print_contrato" value="<?php echo $print_url_contrato; ?>"/>
                </div> 
            </div>
            <table id="contratos" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ejercicio  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Trimestre <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['trimestre']?>"></i></th>
                        <th>Contratante <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['so_contratante']?>"></i></th>
                        <th>Solicitante <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['so_solicitante']?>"></i></th>
                        <th>Contrato <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['numero_contrato']?>"></i></th>
                        <th>Monto original del contrato <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_original']?>"></i></th>
                        <th>Monto modificado <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_modificado']?>"></i></th>            
                        <th>Monto total <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_total']?>"></i></th>
                        <th>Monto pagado <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_pagado']?>"></i></th>
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
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>
<script>
    
    var table_ordenes = null;
    var table_contratos = null;
    var init_tables = function(){
        get_valores_table_ordenes();
        get_valores_table_contratos();
        
    }

    var get_valores_table_ordenes = function(){
        
        if(table_ordenes !== undefined && table_ordenes != null){
            table_ordenes.fnDestroy();
        }

        $('#ordenes').find('tbody').empty();
        $('#ordenes').find('tbody').append('<tr><td colspan="7" class="text-center">Cargando informaci&oacute;n ...</td></tr>');


        var form_data = new FormData();                  
        form_data.append('id_proveedor', $('input[name="id_proveedor"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/proveedores/getDatosProveedorOrdenes' ?>';
        buscar(url, form_data, set_valores_table_ordenes, 'ordenes');
    }

    var get_valores_table_contratos = function(){
        
        if(table_contratos !== undefined && table_contratos != null){
            table_contratos.fnDestroy();
        }

        $('#contratos').find('tbody').empty();
        $('#contratos').find('tbody').append('<tr><td colspan="11" class="text-center">Cargando informaci&oacute;n ...</td></tr>');

        var form_data = new FormData();                  
        form_data.append('id_proveedor', $('input[name="id_proveedor"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/proveedores/getDatosProveedorContratos' ?>';
        buscar(url, form_data, set_valores_table_contratos, 'contratos');
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
                }
            }
        });
    }

    var error_datos = function(container){
        if(container == 'ordenes'){
            $('#ordenes').find('tbody').empty();
            $('#ordenes').find('tbody').append('<tr><td colspan="7" class="text-center">No se encontr&oacute; ning&uacute;n registro</td></tr>');
        }else if(container == 'contratos'){
            $('#contratos').find('tbody').empty();
            $('#contratos').find('tbody').append('<tr><td colspan="11" class="text-center">No se encontr&oacute; ning&uacute;n registro</td></tr>');
        }
    }

    var set_valores_table_contratos = function(response, container){
        $('#contratos').find('tbody').empty();
        if(Array.isArray(response)){
            
            response.map(function (e){
                var html = "<tr>" +
                                "<td>"+e.id+"</td>" +
                                "<td>"+e.ejercicio+"</td>" +
                                "<td>"+e.trimestre+"</td>" +
                                "<td>"+e.so_contratante+"</td>" +
                                "<td>"+e.so_solicitante+"</td>" +
                                "<td>"+e.numero_contrato+"</td>" +
                                "<td>"+e.monto_contrato+"</td>" +
                                "<td>"+e.monto_modificado+"</td>" +
                                "<td>"+e.monto_total+"</td>" +
                                "<td>"+e.monto_pagado+"</td>" +
                                "<td><a href='" + e.link + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle' target='_blank'><i class='fa fa-link'></i></a></td>" + 
                           "</tr>";
                $('#contratos').find('tbody').append(html);
            });
            var no_filter = [10];
            initDataTable(table_contratos, '#contratos', no_filter);
        }else{
            $('#contratos').find('tbody').append('<tr><td colspan="11" class="text-center">No se encontr&oacute; ning&uacute;n registro</td></tr>');
        }

    }

    var set_valores_table_ordenes = function(response, container){
        $('#ordenes').find('tbody').empty();
        if(Array.isArray(response)){
            response.map(function (e){
                var html = "<tr>" +
                                "<td>"+e.id+"</td>" +
                                "<td>"+e.ejercicio+"</td>" +
                                "<td>"+e.trimestre+"</td>" +
                                "<td>"+e.numero_orden_compra+"</td>" +
                                "<td>"+e.proveedor+"</td>" +
                                "<td>"+e.nombre_campana_aviso+"</td>" +
                                "<td>"+e.monto+"</td>" +
                                "<td><a href='" + e.link + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle' target='_blank'><i class='fa fa-link'></i></a></td>" + 
                           "</tr>";
                $('#ordenes').find('tbody').append(html);
            });
            var no_filter = [7];
            initDataTable(table_ordenes, '#ordenes', no_filter);
        }else{
            $('#ordenes').find('tbody').append('<tr><td colspan="7" class="text-center">No se encontr&oacute; ning&uacute;n registro</td></tr>');
        }
    }

    var initDataTable = function(table, container, no_filter){
        table = $(container).dataTable({
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

                if(container == '#ordenes'){
                    // Total over this page
                    pageTotal6 = api
                        .column( 6 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    var colum_6 = '$' + parseFloat(pageTotal6, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
                    
                    // Update footer
                    $( api.column( 6 ).footer() ).html(
                        colum_6 
                    );
                }

                if(container == '#contratos'){
                    // Total over this page
                    pageTotal6 = api
                        .column( 6 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    var colum_6 = '$' + parseFloat(pageTotal6, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
                    
                    // Update footer
                    $( api.column( 6 ).footer() ).html(
                        colum_6 
                    );

                    // Total over this page
                    pageTotal7 = api
                        .column( 7 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    var colum_7 = '$' + parseFloat(pageTotal7, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
                    
                    // Update footer
                    $( api.column( 7 ).footer() ).html(
                        colum_7 
                    );

                     // Total over this page
                     pageTotal8 = api
                        .column( 8 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    var colum_8 = '$' + parseFloat(pageTotal8, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
                    
                    // Update footer
                    $( api.column( 8 ).footer() ).html(
                        colum_8 
                    );

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

    var print_btn_orden = function(){
        var url = $('#link_print_orden').val() + $('input[name="id_proveedor"]').val();
        window.open(url, '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes');
    }

    var descargar_archivo_table_orden = function(){
        var url_server = $('#link_descarga_table_orden').val();
        $('#descargabtntableorden').empty();
        $('#descargabtntableorden').append('<i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;Exportando'); 
        $.ajax({
            url: url_server, // point to server-side PHP script 
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: '',                         
            type: 'post',
            complete: function(){
                $('#descargabtntableorden').empty();
                $('#descargabtntableorden').append('<i class="fa fa-file"></i>&nbsp;&nbsp;Exportar datos'); 
            },
            error:function(){
            },
            success: function(response){
                window.open(response, '_blank');
            }
        });
    }

    var print_btn_contrato = function(){
        var url = $('#link_print_contrato').val() + $('input[name="id_proveedor"]').val();
        window.open(url, '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes');
    }

    var descargar_archivo_table_contrato = function(){
        var url_server = $('#link_descarga_table_contrato').val();
        $('#descargabtntablecontrato').empty();
        $('#descargabtntablecontrato').append('<i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;Exportando'); 
        $.ajax({
            url: url_server, // point to server-side PHP script 
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: '',                         
            type: 'post',
            complete: function(){
                $('#descargabtntablecontrato').empty();
                $('#descargabtntablecontrato').append('<i class="fa fa-file"></i>&nbsp;&nbsp;Exportar datos'); 
            },
            error:function(){
            },
            success: function(response){
                window.open(response, '_blank');
            }
        });
    }

</script>


<?php   }else {
    echo "Proveedor no encontrado";
} ?>
