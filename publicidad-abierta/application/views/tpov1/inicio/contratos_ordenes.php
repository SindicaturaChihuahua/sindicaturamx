<?php
    $sel_ejercicios = '<option value="">Todos</option>';
    for($z = 0; $z < sizeof($ejercicios); $z++)
    {
        if($id_ejercicio_ultimo == $ejercicios[$z]['id_ejercicio']){
            $sel_ejercicios .= '<option value="'.$ejercicios[$z]['id_ejercicio'].'" selected>' . $ejercicios[$z]['ejercicio'] . '</option>';
        }else{
            $sel_ejercicios .= '<option value="'.$ejercicios[$z]['id_ejercicio'].'">' . $ejercicios[$z]['ejercicio'] . '</option>';
        }
    }
?>
<style>
    .tooltip.bottom .tooltip-inner {
        background-color: #fff;
        border: 2px solid #ccc;
        color: black;
        font-size: 15px;
        font-weight: bold;
    }
    .tooltip.bottom .tooltip-arrow {
        border-bottom-color: #ccc;
    }
</style>
<div class="row tpo-div-descarga">
    <button id="descargabtn" type="button" class="btn btn-success pull-right" onclick="descargar_archivo()" title="<?php echo $indicadores_ayuda['btn_download']?>" data-placement="bottom" data-toggle="tooltip"><i class="fa fa-file"></i>&nbsp;&nbsp;Descarga de datos</button>
    <input type="hidden" id="link_descarga" value="<?php echo $link_descarga; ?>"/>
</div>
<div class="row"> <!-- Cabecera de filtrado-->
    <div class="col-md-3">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['ejercicio']?>" data-placement="bottom" data-toggle="tooltip">
                Ejercicio
            </div> <!-- / .box-header-->
            <div class="box-body with-border">
                <select id="id_ejercicio" name="id_ejercicio">
                    <?php echo $sel_ejercicios; ?>
                </select> 
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-3">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['numero_contratos']?>" data-placement="bottom" data-toggle="tooltip">
                Contratos 
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="tab_contratos"  class="info-box-number">0</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-3">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['numero_ordenes']?>" data-placement="bottom" data-toggle="tooltip">
                &Oacute;rdenes de compra 
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="tab_ordenes_compra" class="info-box-number">0</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-3">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['monto_contratos_ordenes']?>" data-placement="bottom" data-toggle="tooltip">
                Monto total gastado ($)
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="monto_gastado" class="info-box-number">0</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
</div> <!-- / .row cabecera -->

<div class="row">
    <div class="col-md-7">
        <div class="box box-info">
            <div class="box-header header">
                <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['grafica_co_meses']?>" data-placement="bottom" data-toggle="tooltip"></i>
            </div> <!-- / .box-header-->
            <div class="box-body with-border">
                <div id="load_container" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div id="container" style="height: 400px; margin: 0 auto"></div>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div>
    <div class="col-md-5">
        <div class="box box-info">
            <div class="box-header header">
                <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['grafica_co_total']?>" data-placement="bottom" data-toggle="tooltip"></i>
            </div> <!-- / .box-header-->
            <div class="box-body with-border">
                <div id="load_container1" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div id="container1" style="height: 400px; margin: 0 auto"></div>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div>
</div><!-- /. row -->

<div class="row">
    <div class="box box-default">
        <div class="box-header header">
            <h3 class="box-title">
                <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['tabla_contratos']?>" data-placement="bottom" data-toggle="tooltip"></i>
                &nbsp; Contratos
            </h3>
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
                        <th>Ejercicio<i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Trimestre<i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['trimestre']?>"></i></th>
                        <th>Contrato<i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['numero_contrato']?>"></i></th>
                        <th>Sujeto obligado contratante<i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['so_contratante']?>"></i></th>
                        <th>Sujeto obligado solicitante<i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['so_solicitante']?>"></i></th>
                        <th>Proveedor<i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['proveedor']?>"></i></th>
                        <th>Monto total de contrato<i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_total']?>"></i></th>
                        <th>Monto total ejercido<i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['sin_definir']?>"></i></th>
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
</div>

<div class="row">
    <div class="box box-default">
        <div class="box-header header" >
            <h3 class="box-title">
                <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['tabla_ordenes']?>" data-placement="bottom" data-toggle="tooltip"></i>
                &nbsp;&Oacute;rdenes de compra</h3>
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
                            <th>Sujeto obligado solicitante <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['so_solicitante']?>"></i></th>
                            <th>Sujeto obligado contratante <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['so_contratante']?>"></i></th>
                            <th>Proveedor</th>
                            <th>Monto ejercido</th>            
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
    
    var table_ordenes = null;
    var table_contratos = null;
    var pie_chart;
    var init = function(){
        get_contratos();
        get_ordenes_compra();
        get_datos_graficas();
    }

    var get_contratos = function()
    {
        $('#tab_contratos').html('0');
        if(table_contratos !== undefined && table_contratos != null){
            table_contratos.fnDestroy();
        }

        $('#contratos').find('tbody').empty();
        $('#contratos').find('tbody').append('<tr><td colspan="11" class="text-center">Cargando informaci&oacute;n ...</td></tr>');


        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/contratos_ordenes/get_contratos' ?>';

        buscar(url, form_data, set_valores_table_contratos, 'contratos');
    }
    
    var get_ordenes_compra = function()
    {
        $('#tab_ordenes_compra').html('0');
        if(table_ordenes !== undefined && table_ordenes != null){
            table_ordenes.fnDestroy();
        }

        $('#ordenes').find('tbody').empty();
        $('#ordenes').find('tbody').append('<tr><td colspan="7" class="text-center">Cargando informaci&oacute;n ...</td></tr>');

        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/contratos_ordenes/get_ordenes_compra' ?>';

        buscar(url, form_data, set_valores_table_ordenes, 'ordenes');
    }

    var get_datos_graficas = function(){
        $('#monto_gastado').html('$ 0.00');
        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/contratos_ordenes/get_valores_graficas' ?>';

        buscar(url, form_data, init_grafica, 'graficas');
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

    var set_valores_table_ordenes = function(response, container){
        $('#ordenes').find('tbody').empty();
        $('#tab_ordenes_compra').html(response.total);
        if(Array.isArray(response.data)){
            response.data.map(function (e){
                var html = "<tr>" +
                                "<td>"+e.id+"</td>" +
                                "<td>"+e.ejercicio+"</td>" +
                                "<td>"+e.trimestre+"</td>" +
                                "<td>"+e.numero_orden_compra+"</td>" +
                                "<td>"+e.so_solicitante+"</td>" +
                                "<td>"+e.so_contratante+"</td>" +
                                "<td>"+e.proveedor+"</td>" +
                                "<td>"+e.monto_ejercido+"</td>" +
                                "<td><a href='" + e.link + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle' target='_blank'><i class='fa fa-link'></i></a></td>" + 
                           "</tr>";
                $('#ordenes').find('tbody').append(html);
            });
            var no_filter = [8];
            initDataTableOrdenes('#ordenes', no_filter);
        }else{
            $('#ordenes').find('tbody').append('<tr><td colspan="7" class="text-center">No se encontr&oacute; ning&uacute;n registro</td></tr>');
        }
    }

    var set_valores_table_contratos = function(response, container){
        $('#contratos').find('tbody').empty();
        $('#tab_contratos').html(response.total);
        if(Array.isArray(response.data)){
            response.data.map(function (e){
                var html = "<tr>" +
                                "<td>"+e.id+"</td>" +
                                "<td>"+e.ejercicio+"</td>" +
                                "<td>"+e.trimestre+"</td>" +
                                "<td>"+e.numero_contrato+"</td>" +
                                "<td>"+e.so_contratante+"</td>" +
                                "<td>"+e.so_solicitante+"</td>" +
                                "<td>"+e.proveedor+"</td>" +
                                "<td>"+e.monto_contrato+"</td>" +
                                "<td>"+e.monto_ejercido+"</td>" +
                                "<td><a href='" + e.link + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle' target='_blank'><i class='fa fa-link'></i></a></td>" + 
                           "</tr>";
                $('#contratos').find('tbody').append(html);
            });
            
            var no_filter = [9];
            initDataTableContratos('#contratos', no_filter);
        }else{
            $('#contratos').find('tbody').append('<tr><td colspan="11" class="text-center">No se encontr&oacute; ning&uacute;n registro</td></tr>');
        }

    }

    var init_grafica = function(response){
        
        $('#monto_gastado').html(get_monto_format(response['total_monto']));

        var pie_datos = response['datos_pie'];

        Highcharts.chart('container', {
            chart: {
                type: 'line'
            },
            title: {
                text: null
            },
            subtitle: {
                text: null
            },
            xAxis: {
                categories: response['categorias']
            },
            yAxis: {
                title: {
                    text: 'Total'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true,
                    point:{
                        events:{
                            mouseOver: function(e){
                                if(pie_chart !== undefined){
                                    pie_chart.series[0].setData(pie_datos[e.target.category], true, true, true);
                                }
                            }
                        }
                    },
                    events:{
                        mouseOut: function(e){
                            if(pie_chart !== undefined){
                                pie_chart.series[0].setData(pie_datos['DEFAULT'], true, true, true);
                            }
                        }
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: response['datos_grafica']
        });

        init_grafica_pie(response['datos_pie']['TOTAL']);
    }

    var init_grafica_pie = function(datos){
        
        pie_chart = Highcharts.chart('container1', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Monto ejercido'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f} %</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        //format: '<b>{point.name}</b>:<br/> $ {point.y:,.2f}',
                        formatter: function(){
                            var n = parseFloat(this.y, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
                            return '<b>' + this.point.name +'</b>:<br><b>$ ' + n + '</b>';
                        },
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Monto ejercido',
                colorByPoint: true,
                data: datos
            }]
        });
    }

    var error_datos = function(container){
        if(container == 'ordenes'){
            $('#ordenes').find('tbody').empty();
            $('#ordenes').find('tbody').append('<tr><td colspan="7" class="text-center">No se encontr&oacute; ning&uacute;n registro</td></tr>');
        }else if(container == 'contratos'){
            $('#contratos').find('tbody').empty();
            $('#contratos').find('tbody').append('<tr><td colspan="11" class="text-center">No se encontr&oacute; ning&uacute;n registro</td></tr>');
        }else if(container == 'graficas'){
            var valores = {categorias: [], datos_grafica: [], datos_pie: [], total_monto: 0};
            init_grafica(valores);
        }
    }

    var initDataTableContratos = function(container, no_filter){
        table_contratos = $(container).dataTable({
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

                // Total over this page
                pageTotal7 = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var colum_7 = '$' + parseFloat(pageTotal7, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
                
                // Total over this page
                pageTotal8 = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var colum_8 = '$' + parseFloat(pageTotal8, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
                
                // Update footer
                $( api.column( 7 ).footer() ).html(
                    colum_7 
                );

                // Update footer
                $( api.column( 8 ).footer() ).html(
                    colum_8 
                );
            }
        });
    }

    var initDataTableOrdenes = function(container, no_filter){
        table_ordenes = $(container).dataTable({
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
        });
    }

    var get_monto_format = function(monto){
        var total = 0, cifra = '';

        if(monto <= 1000){
            //nada
        }else if(monto <= 1000000)// miles K
        {
            total = monto / 1000;
            cifra = ' K';
        }else if(monto <= 1000000000000){ // millones M
            total = monto / 1000000;
            cifra = ' M';
        }else{ // billones B
            total = monto / 1000000000000;
            cifra = ' B';
        }

        return '$' + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString() + cifra; 
    }

    var descargar_archivo = function(){
        var url_server = $('#link_descarga').val();
        $('#descargabtn').empty();
        $('#descargabtn').append('<i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;Descarga de datos'); 
        $.ajax({
            url: url_server, // point to server-side PHP script 
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: '',                         
            type: 'post',
            complete: function(){
                $('#descargabtn').empty();
                $('#descargabtn').append('<i class="fa fa-file"></i>&nbsp;&nbsp;Descarga de datos'); 
            },
            error:function(){
            },
            success: function(response){
                window.open(response, '_blank');
            }
        });
    }

    var print_btn_orden = function(){
        var url = $('#link_print_orden').val() + $('select[name="id_ejercicio"]').val();
        window.open(url, '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes');
    }

    var descargar_archivo_table_orden = function(){
        var url_server = $('#link_descarga_table_orden').val() + '/' + $('select[name="id_ejercicio"]').val();
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
        var url = $('#link_print_contrato').val() + $('select[name="id_ejercicio"]').val();
        window.open(url, '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes');
    }

    var descargar_archivo_table_contrato = function(){
        var url_server = $('#link_descarga_table_contrato').val() + '/' + $('select[name="id_ejercicio"]').val();
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
