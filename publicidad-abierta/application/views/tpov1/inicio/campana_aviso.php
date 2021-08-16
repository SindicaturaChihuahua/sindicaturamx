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

<section class="content">
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
                        <!--
                        <option value="5">2019</option>
                        -->
                    </select> 
                </div> <!-- / .box-body-->
            </div> <!-- / .box-->
        </div> <!-- / .col-->
        <div class="col-md-3">
            <div class="box box-default bg-box-black">
                <div class="box-header header" title="<?php echo $indicadores_ayuda['campanas']?>" data-placement="bottom" data-toggle="tooltip">
                    Campa&ntilde;as
                </div> <!-- / .box-header-->
                <div class="box-body with-border body">
                    <span id="conteo_campanas" class="info-box-number">0</span>
                </div> <!-- / .box-body-->
            </div> <!-- / .box-->
        </div> <!-- / .col-->
        <div class="col-md-3">
            <div class="box box-default bg-box-black">
                <div class="box-header header" title="<?php echo $indicadores_ayuda['avisos_institucionales']?>" data-placement="bottom" data-toggle="tooltip">
                    Avisos institucionales 
                </div> <!-- / .box-header-->
                <div class="box-body with-border body">
                    <span id="conteo_avisos"  class="info-box-number">0</span>
                </div> <!-- / .box-body-->
            </div> <!-- / .box-->
        </div> <!-- / .col-->
        <div class="col-md-3">
            <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['monto_total_ejercido']?>" data-placement="bottom" data-toggle="tooltip">
                    Monto total ejercido($)
                </div> <!-- / .box-header-->
                <div class="box-body with-border body">
                    <span id="presupuesto_ejercido" class="info-box-number">0</span>
                </div> <!-- / .box-body-->
            </div> <!-- / .box-->
        </div> <!-- / .col-->
    </div> <!-- / .row cabecera -->

    <div class="row"> <!-- gráfica -->
        <div class="box">
            <div class="box-header header">
                <h3 class="box-title">
                    <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['grafica_campanas_avisos']?>" data-placement="bottom" data-toggle="tooltip"></i>
                    &nbsp; Campa&ntilde;as y Avisos institucionales
                </h3>
            </div>
            <div class="box-body">
                <div id="load_container2" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div id="container2">
                    <!--
                    <div id="container2" style="width: 600px; height: 700px; margin: 0 auto">
                    -->
                </div>
            </div>
        </div>
    </div>

    <div class="row">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['tabla_campanas']?>" data-placement="bottom" data-toggle="tooltip"></i>
                        &nbsp; Campa&ntilde;as
                    </h3>
                    <div id="options_campanas" class="row" style="margin-bottom:5px; margin-right:0px;">
                        <div class="pull-right">
                            <a class="btn btn-default" onclick="print_btn_campanas()"><i class="fa fa-print"></i> Imprimir</a>
                            <a id="descargabtntablecampanas" class="btn btn-default" onclick="descargar_archivo_table_campanas()"><i class="fa fa-file"></i> Exportar datos</a>
                            <input type="hidden" id="link_descarga_table_campanas" value="<?php echo $link_descarga_table_campanas; ?>"/>
                            <input type="hidden" id="link_print_campanas" value="<?php echo $print_url_campanas; ?>"/>
                        </div>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="campanas" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ejercicio</th>
                                <th>Trimestre</th>
                                <th>Tipo</th>
                                <th>Nombre de la campa&ntilde;a o aviso institucional</th>
                                <th>Contratante</th>
                                <th>Solicitante</th>
                                <th>Tiempo oficial</th>
                                <th>Monto total ejercido</th>
                                <th> </th>
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
                </div>
            </div>
    </div>

    <div class="row">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['tabla_avisos']?>" data-placement="bottom" data-toggle="tooltip"></i>
                        &nbsp; Avisos institucionales
                    </h3>
                    <div id="options_avisos" class="row" style="margin-bottom:5px; margin-right:0px;">
                        <div class="pull-right">
                            <a class="btn btn-default" onclick="print_btn_avisos()"><i class="fa fa-print"></i> Imprimir</a>
                            <a id="descargabtntableavisos" class="btn btn-default" onclick="descargar_archivo_table_avisos()"><i class="fa fa-file"></i> Exportar datos</a>
                            <input type="hidden" id="link_descarga_table_avisos" value="<?php echo $link_descarga_table_avisos; ?>"/>
                            <input type="hidden" id="link_print_avisos" value="<?php echo $print_url_avisos; ?>"/>
                        </div>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="avisos_institucionales" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ejercicio</th>
                                <th>Trimestre</th>
                                <th>Tipo</th>
                                <th>Nombre de la campa&ntilde;a o aviso institucional</th>
                                <th>Contratante</th>
                                <th>Solicitante</th>
                                <th>Tiempo oficial</th>
                                <th>Monto total ejercido</th>
                                <th> </th>
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
                </div>
            </div>
    </div>

    <script>

        var table = null; //para el objeto $('#myTable').DataTable();
        var table_avisos = null; //para el objeto $('#myTable').DataTable();

        var inicializar_componentes = function()
        {
            get_campanas();
            get_campanas_det();
            get_valores_tabla();
            get_valores_tabla_avisos();
        }

        var get_campanas = function()
        {
            $('#presupuesto_ejercido').html('0');
            $('#conteo_avisos').html('0');
            $('#conteo_campanas').html('0');

            var form_data = new FormData();                  
            form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
            var url = '<?php echo base_url() . 'index.php/tpov1/campana_aviso/getCampanas' ?>';
            buscar(url, form_data, init_grafica, 'container');

        }

        var get_campanas_det = function()
        {
            var form_data = new FormData();                  
            form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
            var url2 = '<?php echo base_url() . 'index.php/tpov1/campana_aviso/det_campana_avisos' ?>';
            buscar2(url2, form_data, init_grafica2, 'container2');
        }


        var get_valores_tabla = function()
        {
            if(table !== undefined && table != null){
                table.fnDestroy();
            }

            $('#campanas').find('tbody').empty();
            $('#campanas').find('tbody').append('<tr><td colspan="7" class="text-center">Buscando...</td></tr>');

            var form_data = new FormData();                  
            form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
            var url = '<?php echo base_url() . 'index.php/tpov1/campana_aviso/getValoresTablaCampanas' ?>';

            buscar(url, form_data, init_table, 'campanas');
        }

        var get_valores_tabla_avisos = function()
        {
            if(table_avisos !== undefined && table_avisos != null){
                table_avisos.fnDestroy();
            }

            $('#avisos_institucionales').find('tbody').empty();
            $('#avisos_institucionales').find('tbody').append('<tr><td colspan="8" class="text-center">Buscando...</td></tr>');

            var form_data = new FormData();                  
            form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
            var url = '<?php echo base_url() . 'index.php/tpov1/campana_aviso/getValoresTablaAvisos' ?>';

            buscar_avisos(url, form_data, init_table_avisos, 'avisos_institucionales');

        }

        var buscar = function(url_server, formData, callback, container){
            var id_tag = '#load_' + container;     
            $(id_tag).show();                    
            $.ajax({
                url: url_server, // point to server-side PHP script 
                dataType: 'JSON',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,                         
                type: 'post',
                complete: function(){
                    $(id_tag).hide();
                },
                error:function(){
                    $('#'+container).addClass('overlay');
                    $('#'+container).html('Informacion no disponible');
                },
                success: function(response){
                    if(response && callback){
                        callback(response, container);
                    }
                }
            });
        }

        var buscar_avisos = function(url_server, formData, callback, container)
        {
            var id_tag = '#load_' + container;     
            $(id_tag).show();                    
            $.ajax({
                url: url_server, // point to server-side PHP script 
                dataType: 'JSON',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,                         
                type: 'post',
                complete: function(){
                    $(id_tag).hide();
                },
                
                error:function(){
                    $('#'+container).addClass('overlay');
                    $('#'+container).html('Informacion no disponible');
                },
                success: function(response){
                    if(response && callback){
                        callback(response, container);
                    }
                }
            });
        }

        var buscar2 = function(url_server, formData, callback, container){
            var id_tag = '#load_' + container;     
            $(id_tag).show();                    
            $.ajax({
                url: url_server, // point to server-side PHP script 
                dataType: 'JSON',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,                         
                type: 'post',
                complete: function(){
                    $(id_tag).hide();
                },
                error:function(){
                    $('#'+container).addClass('overlay');
                    $('#'+container).html('<h4>Sin información a graficar.</h4>');
                },
                success: function(response){
                    if(response && callback){
                        callback(response, container);
                    }
                }
            });
        }

        var init_table = function(response, container){
            
            if(Array.isArray(response)){
                $('#campanas').find('tbody').empty();
                
                response.map(function (e){
                    var body =
                            '<td>' + e.id + '</td>' + 
                            '<td>' + e.ejercicio + '</td>' +
                            '<td>' + e.trimestre + '</td>' +
                            '<td>' + e.nombre_campana_tipo + '</td>' +
                            '<td>' + e.nombre_campana_aviso + '</td>' +
                            '<td>' + e.contratante + '</td>' +
                            '<td>' + e.solicitante + '</td>' +
                            '<td>' + e.nombre_tipo_tiempo + '</td>' +
                            '<td>' + e.monto_total + '</td>'+
                            '<td><a href="campana_aviso/campana_detalle/'+ e.id_campana_aviso+'" target="_blank" class="btn btn-default btn-xs" data-toggle="tooltip" title="Detalle"><i class="fa fa-link"></i></a></td>';
                            
                    $('#campanas').find('tbody').append('<tr>' + body + '</tr>');
                    
                });


                initDataTable();
            }else{
                $('#campanas').find('tbody').append('<tr><td colspan="7" class="text-center">No se encontro información</td></tr>');
            }
        }

        var init_table_avisos = function(response, container)
        {
            if(Array.isArray(response)){
                $('#avisos_institucionales').find('tbody').empty();
                
                response.map(function (e)
                {
                    var body =
                            '<td>' + e.id + '</td>' + 
                            '<td>' + e.ejercicio + '</td>' +
                            '<td>' + e.trimestre + '</td>' +
                            '<td>' + e.nombre_campana_tipo + '</td>' +
                            '<td>' + e.nombre_campana_aviso + '</td>' +
                            '<td>' + e.contratante + '</td>' +
                            '<td>' + e.solicitante + '</td>' +
                            '<td>' + e.nombre_tipo_tiempo + '</td>'+
                            '<td>' + e.monto_total + '</td>'+
                            '<td><a href="campana_aviso/campana_detalle/'+ e.id_campana_aviso+'" target="_blank" class="btn btn-default btn-xs" data-toggle="tooltip" title="Detalle"><i class="fa fa-link"></i></a></td>';
                            
                    $('#avisos_institucionales').find('tbody').append('<tr>' + body + '</tr>');
                });
            }else
            {
                $('#avisos_institucionales').find('tbody').append('<tr><td colspan="8" class="text-center">No se encontro información</td></tr>');
            }
            initDataTableAvisos();   
        }

        var init_grafica2 = function(response, container)
        {
            if(response['children']['0']['children'] != '')
            {
                var json = response['children']['0']['children'];
                var processed_json = new Array();   
                        
                //Series Campanas
                for(x=0; x<json.length; x++)
                {
                    //processed_json.push([json[x].name, json[x].size]);
                    var obt = {name:json[x].name, value:json[x].size, id:json[x].id, url:json[x].url};
                    processed_json.push(obt);
                }
            }
            else
            {
                processed_json.push('0');
            }
        
            if(response['children']['1']['children'] != '')
            {
                var json_avisos = response['children']['1']['children'];
                var processed_json_avisos = new Array();
                        
                // Series Avisos
                for(x=0; x<json_avisos.length; x++)
                {
                    //processed_json_avisos.push([json_avisos[x].name, json_avisos[x].size, json_avisos[x].id]);
                    var obt = {name:json_avisos[x].name, value:json_avisos[x].size, id:json_avisos[x].id, url:json_avisos[x].url};
                    processed_json_avisos.push(obt);
                }
            }
            else
            {
                processed_json_avisos.push('0');
            }


            
            if(response['size'] !== undefined)
            {
                e = get_monto_format(parseFloat(response['size']));
                $('#presupuesto_ejercido').html(e);
            }
            else
            {
                $('#presupuesto_ejercido').html('0');
            }

            Highcharts.chart('container2', {
                chart: {
                    type: 'packedbubble',
                    height: '35%'
                },
                title: {
                    text: ''//response['name']
                },
                tooltip: {
                    useHTML: true,
                    pointFormat: '<b>{point.name}</b>'
                    //pointFormat: '<b>{point.name}:</b> {point.y}'
                },
                plotOptions: {
                    packedbubble: {
                    dataLabels: {
                        enabled: false, //Colocamos en false para evitar que sea visible en las graficas el nombre
                        format: '{point.name}',
                        filter: {
                            property: 'y',
                            operator: '>',
                            value: 250
                        },
                        style: {
                            color: 'black',
                            textOutline: 'none',
                            fontWeight: 'normal'
                        }
                    },
                    point: {
                        events: {
                            click: function(e){
                                console.log(e.point.name + ' - ' +  e.point.id);
                                window.open(e.point.url, '_blank'); 
                            }  
                        }
                    },
                    minPointSize: 5
                    }
                    
                    /*
                    ,
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    location.href = '';
                                }
                            }
                        }
                    }
                    */
                },
                credits: {
                    enabled: false
                },
                series: [
                    {
                        name: 'Campañas',
                        data: processed_json
                    },
                    {
                        name: 'Avisos institucionales',
                        data: processed_json_avisos
                    }],
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

        
        var init_grafica = function(response, container)
        {
            var a =  response['avisos'];
            $('#conteo_avisos').html(a);

            var c =  response['campanas']
            $('#conteo_campanas').html(c);

            /*if(response['monto_total_ejercido'] != '0')
            {
                var e = get_monto_format(parseFloat(response['monto_total_ejercido']));
                $('#presupuesto_ejercido').html(e);
            }
            else
            {
                $('#presupuesto_ejercido').html('0');
            }*/
        }

        var initDataTable = function()
        {
            table = $('#campanas').dataTable({
                'bPaginate': true,
                'bLengthChange': true,
                'bFilter': true,
                'bSort': true,
                'bInfo': true,
                'bAutoWidth': false,
                'columnDefs': [ 
                    { 'orderable': false, 'targets': [9] } 
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
            });
        }

        
        var initDataTableAvisos = function()
        {
            table_avisos = $('#avisos_institucionales').dataTable({
                'bPaginate': true,
                'bLengthChange': true,
                'bFilter': true,
                'bSort': true,
                'bInfo': true,
                'bAutoWidth': false,
                'columnDefs': [ 
                    { 'orderable': false, 'targets': [9] } 
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

        //DESCARGAR ARCHIVO

        var preparar_exportacion = function ()
        {
            $.ajax({
                url: '<?php echo base_url() . 'index.php/tpoadminv1/capturista/facturas/preparar_exportacion_facturas/' ?>',
                dataType:'JSON',
                beforeSend: function () {
                },
                complete: function () {
            
                },
                error:function () {
                
                },
                success: function (response) {
                    if(response){
                        $('#export_file').attr('href', response)
                    }
                }
            });
        }

        var init = function(){
            //preparar_exportacion();
            var url = $('#url').val();
            $('#facturas').find('tbody').empty();
            $('#facturas').find('tbody').append('<tr><td colspan="13" class="text-center"><i class="fa fa-refresh fa-spin"></i> Cargando...</td></tr>');
            buscar(url, null, set_valores_tabla, 'facturas');

        }
        
        var descargar_archivo = function(){
            var url_server = $('#link_descarga').val();
            $('#descargabtn').empty();
            $('#descargabtn').append('<i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;Exportar a Excel'); 
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
                    $('#descargabtn').append('<i class="fa fa-file"></i>&nbsp;&nbsp;Exportar a Excel'); 
                },
                error:function(){
                },
                success: function(response){
                    window.open(response, '_blank');
                }
            });
        }

    var print_btn_campanas = function(){
        var url = $('#link_print_campanas').val() + $('select[name="id_ejercicio"]').val();
        window.open(url, '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes');
    }

    var descargar_archivo_table_campanas = function(){
        var url_server = $('#link_descarga_table_campanas').val() + '/' + $('select[name="id_ejercicio"]').val();
        $('#descargabtntablecampanas').empty();
        $('#descargabtntablecampanas').append('<i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;Exportando'); 
        $.ajax({
            url: url_server, // point to server-side PHP script 
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: '',                         
            type: 'post',
            complete: function(){
                $('#descargabtntablecampanas').empty();
                $('#descargabtntablecampanas').append('<i class="fa fa-file"></i>&nbsp;&nbsp;Exportar datos'); 
            },
            error:function(){
            },
            success: function(response){
                window.open(response, '_blank');
            }
        });
    }

    var print_btn_avisos = function(){
        var url = $('#link_print_avisos').val() + $('select[name="id_ejercicio"]').val();
        window.open(url, '_blank', 'location=yes,height=670,width=1020,scrollbars=yes,status=yes');
    }

    var descargar_archivo_table_avisos = function(){
        var url_server = $('#link_descarga_table_avisos').val() + '/' + $('select[name="id_ejercicio"]').val();
        $('#descargabtntableavisos').empty();
        $('#descargabtntableavisos').append('<i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;Exportando'); 
        $.ajax({
            url: url_server, // point to server-side PHP script 
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: '',                         
            type: 'post',
            complete: function(){
                $('#descargabtntableavisos').empty();
                $('#descargabtntableavisos').append('<i class="fa fa-file"></i>&nbsp;&nbsp;Exportar datos'); 
            },
            error:function(){
            },
            success: function(response){
                window.open(response, '_blank');
            }
        });
    }

    </script>
</section>