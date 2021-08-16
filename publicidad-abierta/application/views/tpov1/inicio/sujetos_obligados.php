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

    #container {
        max-width: 800px;
        margin: 0 auto;
    }

    #container2 {
        min-height: 600px;
        margin: 1em auto;
    }

    #ex1Slider .slider-selection {
        background-image: -webkit-linear-gradient(to bottom, #337ab7 0%, #3c8dbc 100%);
        background-image: -o-linear-gradient(to bottom, #337ab7 0%, #3c8dbc 100%);
        background-image: linear-gradient(to bottom, #337ab7 0%, #3c8dbc 100%);
    }

</style>
<div class="row tpo-div-descarga">
    <button id="descargabtn" type="button" class="btn btn-success pull-right" onclick="descargar_archivo()" title="<?php echo $indicadores_ayuda['btn_download']?>" data-placement="bottom" data-toggle="tooltip"><i class="fa fa-file"></i>&nbsp;&nbsp;Descarga de datos</button>
    <input type="hidden" id="link_descarga" value="<?php echo $link_descarga; ?>"/>
</div>
<div class="row"> <!-- Cabecera de filtrado-->
    <div class="col-md-3">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['ejercicio_n']?>" data-placement="bottom" data-toggle="tooltip">
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
            <div class="box-header header" title="<?php echo $indicadores_ayuda['so_contratante_n']?>" data-placement="bottom" data-toggle="tooltip">
                Sujetos obligados contratantes
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="so_contratantes"  class="info-box-number"><?php echo $contratantes; ?></span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-3">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['so_solicitante_n']?>" data-placement="bottom" data-toggle="tooltip">
                Sujetos obligados solicitantes
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="so_solicitantes" class="info-box-number"><?php echo $solicitantes; ?></span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-3">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['so_contratantes_solicitantes']?>" data-placement="bottom" data-toggle="tooltip">
                SO solicitantes y contratantes
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="so_contratantes_solicitantes" class="info-box-number"><?php echo $contratantes_solicitantes; ?></span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
</div> <!-- / .row cabecera -->

<div class="row">
    <div class="col-md-12" style="padding: 0px;">
        <div class="box box-info">
            <div class="box-header header" >
                <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['so_grafica']?>" data-placement="bottom" data-toggle="tooltip"></i>
                <h4 id="info_not_data"></h4>
            </div> <!-- / .box-header-->
            <div class="box-body with-border">
                <div id="container"></div>
                <div id="load_container" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div>
</div><!-- /. row -->

<div class="row">
    <div class="col-md-12" style="padding: 0px;">
        <div class="box box-info">
            <div class="box-header header" >
                <i class="fa fa-info-circle text-primary" title="" data-placement="bottom" data-toggle="tooltip"></i>
                <button id="regresar" type="button" class="btn btn-default btn-sm pull-right" onclick="regresar()"><i class="fa fa-chevron-left"></i> Regresar a Sujetos obligados</button>
            </div> <!-- / .box-header-->
            <div class="box-body with-border">
                <div id="container2"></div>
                <div id="load_container2" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div>
</div><!-- /. row -->

<div class="row">
    <div class="box box-default">
        <div class="box-header header">
            <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['tabla_so']?>" data-placement="bottom" data-toggle="tooltip"></i>
            <div id="options_so" class="pull-right">
                <a class="btn btn-default" onclick="print_btn()"><i class="fa fa-print"></i> Imprimir</a>
                <a id="descargabtntable" class="btn btn-default" onclick="descargar_archivo_table()"><i class="fa fa-file"></i> Exportar datos</a>
                <input type="hidden" id="link_descarga_table" value="<?php echo $link_descarga_table; ?>"/>
                <input type="hidden" id="link_print" value="<?php echo $print_url; ?>"/>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border table-responsive">
            <div class="table-responsive-md">
                <table id="sujetos_obligados" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ejercicio </th>
                            <th>Funci&oacute;n  </th>
                            <th>Orden </th>
                            <th>Estado </th>
                            <th>Nombre </th>
                            <th>Siglas </th>            
                            <th>Monto total</th>
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
    
    var table; 
    var myslider;
    var so_atribuciones;
    var so_detalle;

    var init = function(){
        get_so_campanas();
        get_valores_table();
        get_sujeto_obligados();
        $('#regresar').hide();
    }

    var get_so_campanas = function(){
        load_box(true);
        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/sujetos_obligados/get_so_campanas' ?>';
        buscar(url, form_data, grafica_bubble, 'container');
    }

    var get_sujeto_obligados = function(){

        load_box2(true);
        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        form_data.append('valor_actual', $('#actual').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/sujetos_obligados/get_sujetos_obligados' ?>';
        buscar(url, form_data, preparar_grafica_so, 'container2');
    }

    var get_valores_table = function(){
        
        if(table !== undefined && table != null){
            table.fnDestroy();
        }

        $('#sujetos_obligados').find('tbody').empty();
        $('#sujetos_obligados').find('tbody').append('<tr><td colspan="8" class="text-center">B&uacute;scando...</td></tr>');


        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/sujetos_obligados/get_sujetos_montos' ?>';

        buscar(url, form_data, set_valores_table, 'sujetos_obligados');
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
                if(container == "container"){
                    load_box(false);
                }else if(container == "container2"){
                    load_box2(false);
                }

            },
            error:function(){
                //error_data(container);
            },
            success: function(response){
                if(response && callback){
                    //$('#container').show();
                    callback(response, container);
                }
            }
        });
    }

    var load_box = function(mostrar)
    {
        if(mostrar == true){
            $('#load_container').html('<i class="fa fa-refresh fa-spin"></i>');
            //$('#container').hide();
        }else{
            //$('#container').show();
            $('#load_container').html('');
        }
    }

    var load_box2 = function(mostrar)
    {
        if(mostrar == true){
            $('#load_container2').html('<i class="fa fa-refresh fa-spin"></i>');
        }else{
            $('#load_container2').html('');
        }
    }

    var grafica_bubble = function(response, container){

        if(response['datos_disponibles'] == false){
            $('#'+container).hide();
            $('#info_not_data').html('Sin información a graficar.');
        }else{
            $('#'+container).show();
            $('#info_not_data').empty();
        }

        Highcharts.chart(container, {
            chart: {
                type: 'packedbubble',
                height: '35%'
            },
            title: {
                text: null
            },
            subtitle: {
                text: ''
            },
            tooltip: {
                useHTML: true,
                /*pointFormat: 'Sujeto obligado:<b>{point.description}</b><br/>Campa&ntilde;as o aviso institucionales: <b>{point.name}</b><br/> Monto: <b>$ {point.y:,.2f}</b>'*/
                pointFormatter: function(){
                    var n = parseFloat(this.y, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
                    return 'Sujeto obligado:<b>' +this.description + '</b><br/>Campa&ntilde;as o aviso institucionales: <b>' + this.name + '</b><br/> Monto: <b>$ ' + n + '</b>';
                }
            },
            plotOptions: {
                packedbubble: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.description_2}',
                        filter: {
                            property: 'y',
                            operator: '>',
                            value: 80000
                        },
                        style: {
                            color: 'black',
                            textOutline: 'none',
                            fontWeight: 'normal'
                        }
                    },
                    minPointSize: 0,
                    point: {
                        events: {
                            click: function(e){
                                window.open(e.point.url, '_blank'); 
                            }  
                        }
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: response['datos'], 
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            align: 'right',
                            verticalAlign: 'middle',
                            layout: 'vertical'
                        }
                    }
                }]
            }
        });

    }

    var preparar_grafica_so =  function(response, container){
        $('#regresar').hide();
        so_atribuciones = response['data_serie'];
        so_detalle = response['drilldown'];
        graficar_sujeto_obligados(so_atribuciones, container);
    }

    var graficar_sujeto_obligados = function(response, container){
        
        // Create the chart
        Highcharts.chart(container, {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Sujetos obligados'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
            },
            yAxis: {
                title: {
                    text: ''
                },
                label:{
                    enabled: false,
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                column:{
                    events:{
                        click: function(e){
                            $('#regresar').show();
                            segunda_grafica(so_detalle[e.point.name], 'container2', e.point.name);
                        }
                    }
                },
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '',
                //pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
                pointFormatter:function (){
                    return this.description;
                }
            },
            series: [
                {
                    name: "Sujetos obligados",
                    colorByPoint: true,
                    data: response
                }
            ]
        });
    }

    var segunda_grafica = function(response, container, atribucion){

        if(response !== undefined && response != null){
            atribucion += " (" + response.length + ")";
        }
        // Create the chart
        Highcharts.chart(container, {
            chart: {
                type: 'column'
            },
            title: {
                text: atribucion
            },
            subtitle: {
                text: null
            },
            xAxis: {
                type: 'category',
                min: 0,
                max: 15,
                scrollbar: {
                    enabled: true,
                    showfull: false
                }
            },
            yAxis: {
                title: {
                    text: false
                },
                label:{
                    enabled: false,
                },
                visible: false
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: false,
                        format: '{point.y:.1f}%'
                    }
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '',
                //pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
                pointFormatter:function (){
                    return this.description;
                }
            },
            series: [
                {
                    name: "Sujetos obligados",
                    colorByPoint: true,
                    data: response 
                }
            ]
        });
    }

    var regresar = function(){
        $('#regresar').hide();
        graficar_sujeto_obligados(so_atribuciones, 'container2');
    }

    var set_valores_table = function(response, container){
        $('#sujetos_obligados').find('tbody').empty();
        
        if(Array.isArray(response)){
            response.map(function(e){
                var html = '<tr>' +
                        '<td>' + e.id + '</td>' +
                        '<td>' + e.ejercicio + '</td>' +
                        '<td>' + e.funcion + '</td>' +
                        '<td>' + e.orden + '</td>' +
                        '<td>' + e.estado + '</td>' +
                        '<td>' + e.nombre_sujeto_obligado+ '</td>' +
                        '<td>' + e.siglas_sujeto_obligado + '</td>' +
                        '<td>' + e.monto_formato + '</td>' +
                        '<td><a href="' + e.link + '" class="btn btn-default btn-xs" data-toggle="tooltip" title="Detalle" target="_blank"><i class="fa fa-link"></i></a></td>' +
                        '</tr>';
                $('#sujetos_obligados').find('tbody').append(html);
                
            });

            initDataTable();
        }
    }

    var initDataTable = function(){
        table = $('#sujetos_obligados').dataTable({
            'bPaginate': true,
            'bLengthChange': true,
            'bFilter': true,
            'bSort': true,
            'bInfo': true,
            'bAutoWidth': false,
            'columnDefs': [ 
                { 'orderable': false, 'targets': [8] } 
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