<?php
if($disponible == true){ /* se muestra la info*/ 
    $url_a = "";
    if(!empty($so['url'])){
        $url_a = "<a href='" . $so['url'] . "' target='_blank'>" . $so['url'] . "</a>";
    }
?> 
<style>
    #info_factura td{
        padding: 2px !important;
        font-size: 12px;
    }

    #table_modal td{
        padding: 0px !important;
    }

    #table_modal p{
        padding: 0 0 0px !important;
        margin: 0 0 5px !important;
    }
</style>

<input name="id_sujeto_obligado" type="hidden" value="<?php echo $so['id_sujeto_obligado'] ?>">
<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Detalle del sujeto obligado</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <table id="info_factura" class="table table-striped">
                <tbody>
                    <tr>
                        <td class="text-right">Funci&oacute;n <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['sin_definir']?>"></i>: </td>
                        <td width="80%"><?php echo $so['atribucion']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Orden <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['sin_definir']?>"></i>: </td>
                        <td width="80%"><?php echo $so['orden']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Estado <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['sin_definir']?>"></i>: </td>
                        <td width="80%"><?php echo $so['estado']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Nombre del sujeto obligado <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['sin_definir']?>"></i>: </td>
                        <td width="80%"><?php echo $so['nombre']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Siglas del sujeto obligado <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['sin_definir']?>"></i>: </td>
                        <td width="80%"><?php echo $so['siglas']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">URl de la p&aacute;gina del sujeto obligado <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['sin_definir']?>"></i>: </td>
                        <td width="80%"><?php echo $url_a?></td>
                    </tr>
                    <tr>
                        <td class="text-right">&Oacute;rdenes de compra asociadas <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['sin_definir']?>"></i>: </td>
                        <td id="total_ordenes" width="80%">0</td>
                    </tr>
                    <tr>
                        <td class="text-right">Contratos asociados <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['sin_definir']?>"></i>: </td>
                        <td id="total_contratos" width="80%">0</td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- / .box-body --> 
    </div><!-- / .box --> 
</div><!-- / .row --> 

<div class="row"> <!-- Contratos -->
    <div class="box box-default">
        <div class="box-header header">
            <h3 class="box-title">Contratos asociados al sujeto obligado</h3>
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
                        <th>Contrato <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['numero_contrato']?>"></i></th>
                        <th>Sujeto obligado contratante <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['so_contratante']?>"></i></th>
                        <th>Sujeto obligado solicitante <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['so_solicitante']?>"></i></th>
                        <th>Proveedor <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['proveedor']?>"></i></th>
                        <th>Monto total del contrato <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_original']?>"></i></th>
                        <th>Monto total ejercido <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_pagado']?>"></i></th>
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
                    </tr>
                </tfoot>
            </table>
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div> <!-- / .row contratos-->

<div class="row"> <!-- Ordenes de compra-->
    <div class="box box-info">
        <div class="box-header header">
            <h3 class="box-title">&Oacute;rdenes de compra asociadas al sujeto obligado</h3>
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
                            <th>Sujeto obligado contratante <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['so_contratante']?>"></i></th>
                            <th>Sujeto obligado solicitante <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['so_solicitante']?>"></i></th>
                            <th>Proveedor<i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['proveedor']?>"></i></th>
                            <th>Total ejercido</th>            
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
                        </tr>
                    </tfoot>
                </table>
            </div> <!-- /. table-responsive -->
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>
<script>

    var table_contratos;
    var table_ordenes; 
    var init = function(){
        get_contratos();
        get_ordenes();
    }

    var get_contratos = function(){

        if(table_contratos !== undefined && table_contratos != null){
            table_contratos.fnDestroy();
        }

        $('#contratos').find('tbody').empty();
        $('#contratos').find('tbody').append('<tr><td colspan="10" class="text-center">Cargando informaci&oacute;n ...</td></tr>');

        var form_data = new FormData();                  
        form_data.append('id_sujeto_obligado', $('input[name="id_sujeto_obligado"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/sujetos_obligados/get_contratos_so' ?>';
        buscar(url, form_data, set_valores_table, 'contratos');
    }

    var get_ordenes = function(){

        if(table_ordenes !== undefined && table_ordenes != null){
            table_ordenes.fnDestroy();
        }

        $('#ordenes').find('tbody').empty();
        $('#ordenes').find('tbody').append('<tr><td colspan="9" class="text-center">Cargando informaci&oacute;n ...</td></tr>');

        var form_data = new FormData();                  
        form_data.append('id_sujeto_obligado', $('input[name="id_sujeto_obligado"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/sujetos_obligados/get_ordenes_compra_so' ?>';
        buscar(url, form_data, set_valores_table, 'ordenes');
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

    var set_valores_table = function(response, container){
        var aux = '#' + container;
        var total_i = "#total_" + container;
        $(aux).find('tbody').empty();
        if(Array.isArray(response)){
            $(total_i).html(response.length);
            response.map(function (e){
                if(container == 'contratos'){
                    var html = "<tr>" +
                                "<td>"+e.id+"</td>" +
                                "<td>"+e.ejercicio+"</td>" +
                                "<td>"+e.trimestre+"</td>" +
                                "<td>"+e.numero_contrato+"</td>" +
                                "<td>"+e.nombre_so_contratante+"</td>" +
                                "<td>"+e.nombre_so_solicitante+"</td>" +
                                "<td>"+e.nombre_proveedor+"</td>" +
                                "<td>"+e.monto_contrato+"</td>" +
                                "<td>"+e.monto_ejercido+"</td>" +
                                "<td><a href='" + e.link + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle' target='_blank'><i class='fa fa-link'></i></a></td>" + 
                           "</tr>";
                    $(aux).find('tbody').append(html);
                }else if(container == 'ordenes'){
                    var html = "<tr>" +
                                "<td>"+e.id+"</td>" +
                                "<td>"+e.ejercicio+"</td>" +
                                "<td>"+e.trimestre+"</td>" +
                                "<td>"+e.numero_orden_compra+"</td>" +
                                "<td>"+e.nombre_so_contratante+"</td>" +
                                "<td>"+e.nombre_so_solicitante+"</td>" +
                                "<td>"+e.nombre_proveedor+"</td>" +
                                "<td>"+e.monto_ejercido+"</td>" +
                                "<td><a href='" + e.link + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle' target='_blank'><i class='fa fa-link'></i></a></td>" + 
                           "</tr>";

                    $(aux).find('tbody').append(html);
                }
            });
        }

        if(container == 'contratos'){
            initDataTable(table_contratos, aux, [9] );
            
        }else if(container == 'ordenes'){
            initDataTable(table_ordenes, aux, [8] );
            
        }

    }

    var error_datos = function(container){
        var aux = '#' + container;
        $(aux).find('tbody').empty();
        if(container == 'contratos'){
            initDataTable(table_contratos, aux, [9] );
        }else if(container == 'ordenes'){
            initDataTable(table_ordenes, aux, [8] );
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
                }

                if(container == '#contratos'){

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
                }
            }
        });
    }

    var print_btn_orden = function(){
        var url = $('#link_print_orden').val() + $('input[name="id_sujeto_obligado"]').val();
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
        var url = $('#link_print_contrato').val() + $('input[name="id_sujeto_obligado"]').val();
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
<?php }else {
    echo "Informaci&oacute;n no disponible";
} ?>