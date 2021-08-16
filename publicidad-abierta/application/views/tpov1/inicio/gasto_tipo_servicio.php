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
    <div class="col-md-4">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['ejercicio']?>" data-placement="bottom" data-toggle="tooltip">
                Ejercicio
            </div> <!-- / .box-header-->
            <div class="box-body with-border" style="margin-top:12px;">
                <select id="id_ejercicio" name="id_ejercicio" style="margin-bottom:7px;">
                    <?php echo $sel_ejercicios; ?>
                </select> 
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-4">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['monto_servicio_difucion']?>" data-placement="bottom" data-toggle="tooltip">
                Monto gastado en servicios de difusi&oacute;n en medios de comunicaci&oacute;n($)
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="servicios"  class="info-box-number">0</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-4">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['monto_servicio_otro']?>" data-placement="bottom" data-toggle="tooltip">
                Monto gastado en otros servicios asociados a la comunicaci&oacute;n ($)
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="otros" class="info-box-number">0</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
</div> <!-- / .row cabecera -->

<div class="row">
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header header">
                <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['grafica_servicio']?>" data-placement="bottom" data-toggle="tooltip"></i>
            </div> <!-- / .box-header-->
            <div class="box-body with-border">
                <div id="load_container" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div id="container" style="height: 400px; margin: 0 auto"></div>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div>
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header header">
            &nbsp;
            </div> <!-- / .box-header-->
            <div class="box-body with-border">
                <div id="load_container1" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div id="container1" style="height: 400px;margin: 0 auto"></div>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div>
</div><!-- /. row -->

<div class="row">
    <div id="servicios_categorias" class="col-md-6" style="padding: 0px;">
        
    </div>
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header header">
            </div> <!-- / .box-header-->
            <div class="box-body with-border">
                <table id="datos" class="table table-bordered table-hover table-striped">
                    <tbody>
                       
                    </tbody>
                </table><!-- /. table-->
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div>
</div><!-- /. row -->

<div class="row">
    <div class="box box-default">
        <div class="box-header header">
            <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['tabla_porservicio']?>" data-placement="bottom" data-toggle="tooltip"></i>
            <div class="pull-right">
                <a class="btn btn-default" onclick="print_btn()"><i class="fa fa-print"></i> Imprimir</a>
                <a id="descargabtntable" class="btn btn-default" onclick="descargar_archivo_table()"><i class="fa fa-file"></i> Exportar datos</a>
                <input type="hidden" id="link_descarga_table" value="<?php echo $link_descarga_table; ?>"/>
                <input type="hidden" id="link_print" value="<?php echo $print_url; ?>"/>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border table-responsive">
            <div class="table-responsive-md">
                <table id="servicios_tabla" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ejercicio  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                            <th>Factura <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['numero_factura']?>"></i></th>
                            <th>Fecha <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['fecha_erogacion']?>"></i></th>
                            <th>Proveedor <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['proveedor']?>"></i></th>
                            <th>Clasificaci&oacute;n <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['clasificacion_n']?>"></i></th>
                            <th>Categor&iacute;a <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['categoria']?>"></i></th>
                            <th>Subcategor&iacute;a <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['subcategoria']?>"></i></th>
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
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div> <!-- /. table-responsive -->
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>

<script>

    var table;
    var bar_chart;
    var pie_chart;
    var colores = [];
    var init = function(){
        get_gastos();
        get_gastos_tabla();
    }

    var get_gastos = function(){
        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/gasto_tipo_servicio/get_gasto_servicio' ?>';

        buscar(url, form_data, iniciar_componentes);
    }

    var get_gastos_tabla = function(){
        if(table !== undefined && table != null){
            table.fnDestroy();
        }

        $('#servicios_tabla').find('tbody').empty();
        $('#servicios_tabla').find('tbody').append('<tr><td colspan="11" class="text-center">Buscando...</td></tr>');

        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/gasto_tipo_servicio/get_gasto_table_servicio' ?>';

        buscar(url, form_data, iniciar_tabla);
    }
    
    var buscar = function(url_server, formData, callback){   
        load_box(true);                
        $.ajax({
            url: url_server, // point to server-side PHP script 
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: formData,                         
            type: 'post',
            complete: function(){
                load_box(false);
            },
            error:function(){
                error_data();
            },
            success: function(response){
                if(response && callback){
                    callback(response);
                }
            }
        });
    }

    var iniciar_componentes = function(response){
        colores = response['colores'];
        $('#servicios').html(get_monto_format(response['indicador1']));
        $('#otros').html(get_monto_format(response['indicador2']));
        init_grafica_pie(response, 'container');
        column_chart(response, 'container1');
        set_valores_table(response['categorias']);
        set_servicios_categorias(response['servicios']);
    }

    var set_valores_table =  function(valores){
        $('#datos').find('tbody').empty();
        var total = get_total_gasto(valores);
        var i = 0;

        valores.map(function(e){
            var valor = (parseFloat(e.y) / 1).toFixed(2);
            var html = '<tr>' +
                       '<td>' + get_color(i) + '</td>' +
                       '<td>' + e.name + '</td>' +
                       '<td class="text-right">$ ' + valor.replace(/(\d)(?=(\d{3})+\.)/g, "$1, ").toString() + '</td>' +
                       '<td class="text-right">' + calcular_porcentaje(total, e.y) + '</td>' +
                       '</tr>';
            $('#datos').find('tbody').append(html);
            i += 1;
        });
    }

    var get_color = function(position){

        if(position < colores.length ){
            return '<div style="background-color: ' + colores[position] + ';">&nbsp;&nbsp;</div>'
        }

        return '';   
    }

    var column_chart = function(response, container){
        
        var mes_x_categorias = response['mes_x_categorias'];
        var categorias = response['categorias'];

        bar_chart = Highcharts.chart(container, {
            chart: {
                type: 'column'
            },
            title: {
                text: null
            },
            subtitle: {
                text: null
            },
            xAxis: {
                categories: response['meses'],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Monto ejercido'
                }
            },
            tooltip: {
                /*headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">Monto ejercido </td>' +
                    '<td style="padding:0"><b>${point.y:,.2f}</b></td></tr>',
                footerFormat: '</table>',*/
                headerFormat: '<b>{point.key}</b><br/>',
                pointFormatter: function(){
                    var n = parseFloat(this.y, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
                    return '<span style="color:' + this.color +'">\u25CF</span>' + this.series.name + ' <b>$ ' + n + '</b>';
                },
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                showInLegend: false,
                name: 'Gasto',
                data: response['monto'],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '${point.y:,.2f}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                },
                point:{
                    events:{
                        mouseOver: function(e){
                            if(pie_chart !== undefined){
                                pie_chart.series[0].setData(mes_x_categorias[e.target.category], true, true, true);
                                set_valores_table(mes_x_categorias[e.target.category]);
                            }
                        }
                    }
                },
                events: {
                    mouseOut: function(e){
                        if(pie_chart !== undefined){
                            pie_chart.series[0].setData(mes_x_categorias['GENERALES'], true, true, true);
                            set_valores_table(mes_x_categorias['GENERALES']);
                        }
                    }
                },
            }]
        });
    }

    var init_grafica_pie = function(response, container){
        
        var categorias_x_mes = response['categorias_x_mes'];
        
        var validColor = false;
        var colors = [];
        if(response['colores'] !== undefined){
            validColor = true;
            colors = response['colores']
        }

        pie_chart = Highcharts.chart(container, {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: null
            },
            tooltip: {
                headerFormat: '<b>{point.key}</b><br/>',
                pointFormatter: function(){
                    var n = parseFloat(this.y, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
                    return '<span style="color:' + this.color +'">\u25CF</span>' + this.series.name + ' <b>$ ' + n + '</b>';
                }
                /*pointFormat: '{series.name}: <b>${point.y:,.2f}</b>'*/
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    colors: colors,
                    colorByPoint: validColor
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Monto ejercido',
                colorByPoint: true,
                events: {
                    mouseOut: function(e){
                        if(bar_chart !== undefined){
                            bar_chart.series[0].setData(categorias_x_mes['GENERALES'], true, true, true);
                        }
                    }
                },
                point:{
                    events:{
                        mouseOver: function(e){
                            if(bar_chart !== undefined){
                                bar_chart.series[0].setData(categorias_x_mes[e.target.name], true, true, true);
                            }
                        }
                    }
                },
                data: response['categorias'] 
            }]
        });
    }

    var set_servicios_categorias = function(servicios){
        $('#servicios_categorias').empty();
        var div12 = '<div class="col-md-12">';
        var endDiv12 = '</div>';
        var box = '<div class="box box-primary">';
        var endBox = '</div>';
        var boxHeader = '<div class="box-header header">';
        var endBoxHeader = '</div>';
        var boxBody = '<div class="box-body with-border">';
        var endBoxBody = '</div>';

        
        var position = 0;
        servicios.map(function(e){
            var textAyuda = get_text_ayuda(position++);
            var info = '<i class="fa fa-info-circle text-primary" data-placement="bottom" data-toggle="tooltip" title="' + textAyuda+ '"></i>';
            var titleHeader = Object.keys(e);

            var categorias = Object.values(e);
            var lista = '';
            categorias[0].map(function(w){
                lista += '<li>' + w + '</li>';
            });
            var conteinerBody = '<ul>' + lista + '</ul>';
            var servicioDiv = div12 + box + boxHeader + info + titleHeader + endBoxHeader + boxBody + conteinerBody + endBoxBody + endBox + endDiv12;
            $('#servicios_categorias').append(servicioDiv);
        });

        $('[data-toggle=\"tooltip\"]').tooltip();
    
    }

    var get_text_ayuda = function(position){

        var text = '';
        switch(position){
            case 0: 
                text = 'Información relativa a los recursos públicos erogados o utilizados para contratar servicios de difusión en medios de comunicación.​';
                break;
            case 1: 
                text = 'Información relativa a otros servicios asociados a la comunicación como producción de contenidos, estudios e impresiones.';
                break;
        }

        return text;
    }

    var iniciar_tabla = function (response){
        container = '#servicios_tabla';
        $(container).find('tbody').empty();
        if(Array.isArray(response)){
            response.map(function (e){
                var link1 = "<a href='" + e.link_factura + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle Factura' target='_blank'><i class='fa fa-link'></i></a>";
                var link2 = "&nbsp;<a href='" + e.link_proveedor + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle Proveedor' target='_blank'><i class='fa fa-link'></i></a>";
                var link3 = "&nbsp;<a href='" + e.link_campana + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle Campa&ntilde;a o aviso institucional' target='_blank'><i class='fa fa-link'></i></a>";
                var body = "<tr>" +
                    "<td>"+e.id+"</td>" +
                    "<td>"+e.ejercicio+"</td>" +
                    "<td>"+e.factura+"</td>" +
                    "<td>"+e.fecha_erogacion+"</td>" +
                    "<td>"+e.proveedor+"</td>" +
                    "<td>"+e.nombre_servicio_clasificacion+"</td>" +
                    "<td>"+e.nombre_servicio_categoria+"</td>" +
                    "<td>"+e.nombre_servicio_subcategoria+"</td>" +
                    "<td>"+e.tipo+"</td>" +
                    "<td>"+e.nombre_campana_aviso+"</td>" +
                    "<td>"+e.monto_servicio+"</td>" +
                    "<td width='80'>" + link1 + link2 + link3 + "</td>" + 
                    "</tr>"; //definir a donde se va este link

                $(container).find('tbody').append(body);
            });
            initDataTable();
        }else{
            $(container).find('tbody').append('<tr><td colspan="12" class="text-center">No se encontro información</td></tr>');
        }
    }

    var get_total_gasto = function(valores){
        var total = 0;
        valores.map(function(e){
            total += parseFloat(e.y);
        });

        return total == 0 ? 1 : total;
    }

    var calcular_porcentaje = function (total, valor){
        var porcentaje = ((valor * 100) / total).toFixed(1);
        return porcentaje.replace(/(\d)(?=(\d{3})+\.)/g, "$1, ").toString() + ' %';
    }

    var load_box = function(mostrar)
    {
        if(mostrar == true){
            $('#load_container').html('<i class="fa fa-refresh fa-spin"></i>');
            $('#load_container1').html('<i class="fa fa-refresh fa-spin"></i>');
        }else{
            $('#load_container').html('');
            $('#load_container1').html(''); 
        }
    }

    var error_data = function(){
        $('#container').addClass('overlay');
        $('#container').html('Informacion no disponible');
        $('#container1').addClass('overlay');
        $('#container1').html('Informacion no disponible');
    }

    var initDataTable = function(){
        table = $('#servicios_tabla').dataTable({
            'bPaginate': true,
            'bLengthChange': true,
            'bFilter': true,
            'bSort': true,
            'bInfo': true,
            'bAutoWidth': false,
            'columnDefs': [ 
                { 'orderable': false, 'targets': [11] } 
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
                pageTotal10 = api
                    .column( 10 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var colum_10 = '$' + parseFloat(pageTotal10, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
                
                // Update footer
                $( api.column( 10 ).footer() ).html(
                    colum_10 
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

        return '$ ' + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString() + cifra; 
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

    var print_btn = function(){
        var url = $('#link_print').val() + $('select[name="id_ejercicio"]').val();
        window.open(url, '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes');
    }

    var descargar_archivo_table = function(){
        var url_server = $('#link_descarga_table').val() + '/' + $('select[name="id_ejercicio"]').val();
        $('#descargabtntable').empty();
        $('#descargabtntable').append('<i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;Exportando'); 
        $.ajax({
            url: url_server, // point to server-side PHP script 
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: '',                         
            type: 'post',
            complete: function(){
                $('#descargabtntable').empty();
                $('#descargabtntable').append('<i class="fa fa-file"></i>&nbsp;&nbsp;Exportar datos'); 
            },
            error:function(){
            },
            success: function(response){
                window.open(response, '_blank');
            }
        });
    }
</script>