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
    .tooltip.top .tooltip-inner {
        background-color: #fff;
        border: 2px solid #ccc;
        color: black;
        font-size: 15px;
        font-weight: bold;
    }
    .tooltip.top .tooltip-arrow {
        border-top-color: #ccc;
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
            <div class="box-header header" title="<?php echo $indicadores_ayuda['presupuesto_n']?>" data-placement="bottom" data-toggle="tooltip">
                Presupuesto original($) 
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="presupuesto_original"  class="info-box-number">0</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-3">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['modificacion_n']?>" data-placement="bottom" data-toggle="tooltip">
                Presupuesto modificado($) 
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="presupuesto_modificado" class="info-box-number">0</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-3">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['presupuesto_ejercido_presupuesto']?>" data-placement="bottom" data-toggle="tooltip">
                Presupuesto ejercido($)
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="presupuesto_ejercido" class="info-box-number">0</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
</div> <!-- / .row cabecera -->
<div class="row"> <!-- gráfica -->
    <div class="box box-info">
        <div class="box-header text-center">
        <i class="fa fa-info-circle text-primary pull-left" title="<?php echo $indicadores_ayuda['grafica_presupuesto']?>" data-placement="bottom" data-toggle="tooltip"></i>
        <span>Coloca el cursor sobre las gráficas para conocer los detalle de la partida.</span>
        </div>
        <div class="box-body">
            <div id="load_container" class="overlay">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
            <div id="container" style="min-height: 600px; margin: 0 auto"></div>
        </div>
    </div>
</div><!-- /. row gráfica-->

<?php 
    if(isset($grafica_2) && $grafica_2->active == 1){
        echo '
            <input id="graficaActiva" type="hidden" value="true">
            <div class="row"> <!-- gráfica -->
                <div class="box box-info">
                    <div class="box-header">
                        <h4 id="info_title">Desglose de presupuesto por sujeto obligado.</h4>
                        <div id="paginacion_g2"> ' . $select_paginado . '</div>
                    </div>
                    <div class="box-body">
                        <div id="load_container2" class="overlay">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                        <div id="container2" style="min-height: 600px; margin: 0 auto"></div>
                    </div>
                </div>
            </div><!-- /. row gráfica-->      
        ';
    }else{
        echo ' <input id="graficaActiva" type="hidden" value="false">';
    }
?>

    <div class="row">
        <div class="box">
                <div class="box-header">
                    <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['tabla_presupuestos']?>" data-placement="bottom" data-toggle="tooltip"></i>
                    <div class="pull-right">
                        <a class="btn btn-default" onclick="print_btn()"><i class="fa fa-print"></i> Imprimir</a>
                        <a id="descargabtntable" class="btn btn-default" onclick="descargar_archivo_table()"><i class="fa fa-file"></i> Exportar datos</a>
                        <input type="hidden" id="link_descarga_table" value="<?php echo $link_descarga_table; ?>"/>
                        <input type="hidden" id="link_print" value="<?php echo $print_url; ?>"/>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="presupuestos" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Ejercicio</th>
                                <th>Clave de partida</th>
                                <th>Descripci&oacute;n</th>
                                <th>Presupuesto original</th>
                                <th>Monto modificado</th>
                                <th>Presupuesto modificado</th>
                                <th>Presupuesto ejercido</th>
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
                            </tr>
                        </tfoot>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
    </div><!-- /.row -->
<script>

    var table = null; //para el objeto $('#myTable').DataTable();

    var inicializar_componentes = function()
    {
        get_presupuestos();
        get_valores_tabla();
        if($('#graficaActiva').val() == "true"){
            get_valores_grafica_network();
        }
    }
    var get_presupuestos = function()
    {
        $('#presupuesto_ejercido').html('0');
        $('#presupuesto_original').html('0');
        $('#presupuesto_modificado').html('0');

        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/presupuesto/getPresupuestos' ?>';

        buscar(url, form_data, init_grafica, 'container');
    }

    var get_valores_tabla = function()
    {
        if(table !== undefined && table != null){
            table.fnDestroy();
        }

        $('#presupuestos').find('tbody').empty();
        $('#presupuestos').find('tbody').append('<tr><td colspan="7" class="text-center">Buscando...</td></tr>');

        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/presupuesto/getValoresTablaPresupuestos' ?>';

        buscar(url, form_data, init_table, 'presupuestos');
    }

    /*var get_valores_grafica_network = function(){
        $('#info_title').html('Desglose de presupuesto por sujeto obligado.');
        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        form_data.append('pageSize', $('select[name="pageSize_g2"]').val());
        var url = '<?php //echo base_url() . 'index.php/tpov1/presupuesto/get_presupuestos_grafica' ?>';

        buscar(url, form_data, graficar_network, 'container2');
    }*/

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
                }else if(container == 'presupuestos'){
                    $('#presupuestos').find('tbody').empty();
                    initDataTable();
                }
            }
        });
    }

    var init_table = function(response, container){
        $('#presupuestos').find('tbody').empty();
        if(Array.isArray(response)){
            response.map(function (e){
                var body = '<td>' + e.ejercicio + '</td>' +
                           '<td>' + e.partida + '</td>' +
                           '<td>' + e.descripcion + '</td>' +
                           '<td>' + e.original + '</td>' +
                           '<td>' + e.modificaciones + '</td>' +
                           '<td>' + e.presupuesto + '</td>' +
                           '<td>' + e.ejercido + '</td>'; 

                $('#presupuestos').find('tbody').append('<tr>' + body + '</tr>');
            });
        }

        initDataTable();
        

    }


    var init_grafica = function(response, container)
    {
        var e = get_monto_format(response['ejercido']);
        $('#presupuesto_ejercido').html(e);
        //var e =  (parseFloat(response['ejercido']) / 1000000).toFixed(2);
        //$('#presupuesto_ejercido').html(e.replace(/(\d)(?=(\d{3})+\.)/g, "$1, ").toString());

        var o = get_monto_format(response['original']);
        $('#presupuesto_original').html(o);
        //var o =  (parseFloat(response['original']) / 1000000).toFixed(2);
        //$('#presupuesto_original').html(o.replace(/(\d)(?=(\d{3})+\.)/g, "$1, ").toString());

        var m = get_monto_format(response['modificado']);
        $('#presupuesto_modificado').html(m);
        //var m =  (parseFloat(response['modificado']) / 1000000).toFixed(2);
        //$('#presupuesto_modificado').html(m.replace(/(\d)(?=(\d{3})+\.)/g, "$1, ").toString());

        Highcharts.chart(container, {
            chart: {
                type: 'bar'
            },
            title: {
                text: null
            },
            subtitle: {
                text: null
            },
            xAxis: {
                categories: response['categorias'],
                title: {
                    text: 'Partidas'
                },
                labels: {
                    formatter: function () {
                        var text = this.value,
                        formatted = text.length > 5 ? text.substring(0, 5) : text;

                        var p = text.split(' ');

                        if(p.length > 0)
                            formatted = p[0];

                        return '<div class="js-ellipse" style="overflow:hidden" title="' + text + '">' + formatted + '</div>';
                    },
                    useHTML: true
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Monto ejercido',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify',
                    formatter: function () {
                        var result = this.value;
                        if (this.value >= 1000000000000) { result = Math.floor(this.value / 1000000000000) + "B" }
                        else if (this.value >= 1000000) { result = Math.floor(this.value / 1000000) + "M" }
                        else if (this.value >= 1000) { result = Math.floor(this.value / 1000) + "k" }
                        return result;// clean, unformatted number for year
                    }
                }
            },
            tooltip: {
                headerFormat: '<b>{point.key}</b><br/>',
                pointFormatter: function(){
                    var n = parseFloat(this.y, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
                    return '<span style="color:' + this.color +'">\u25CF</span>'+ this.series.name +': <b>$ ' + n + '</b><br/>';
                }
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true,
                        useHTML: false,
                        formatter: function(){
                            var n = parseFloat(this.y, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
                            return '$ ' + n;
                        }
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: response['series']
    });
}

var graficar_network = function (response, container){

    if(response['datos'].length == 0){
        $('#'+container).hide();
        $('#paginacion_g2').hide();
        $('#info_title').html('Sin información a graficar.');
    }else{
        $('#'+container).show();
        $('#paginacion_g2').show();
        $('#info_title').html('Desglose de presupuesto por sujeto obligado.');
    }

    if(response['paginas'].length > 0){
        $('select[name="page_g2"]').empty();
        var i = 0;
        response['paginas'].map(function(e){
            $('select[name="page_g2"]').append($('<option>', {
                value: i,
                text: e
            }));
            i = i + 1;
        });

        $('select[name="page_g2"]').on('change', function(){
            get_valores_grafica_network();
        })
    }


    Highcharts.chart(container, {
        title:{
            text: null
        },
        subtitle: {
            text: null
        },
        credits: {
                enabled: false
        },
        series: [{
            keys: ['from', 'to', 'weight'],
            data: response['datos'],
            type: 'sankey',
            name: null,
            tooltip: {
                //nodeFormat: "{point.name}: <b>{point.sum}</b>",
                nodeFormat: "{point.name}",
                headerFormat: "",
                pointFormatter: function(){
                    var aux = this.description.replace('{0}', this.weight);
                    aux = aux.replace('{1}', this.toNode.name);

                    if(this.weight > 1){
                        aux = aux.replace('{2}', 'corresponden');
                    }else{
                        aux = aux.replace('{2}', 'corresponde');
                    }

                    return this.fromNode.name + ' → ' + this.toNode.name + '<br />' + aux;
                }
                //pointFormat: "{point.fromNode.name} → {point.toNode.name}<br/> {point.toNode.name}: <b>{point.weight}</b><br/>Monto ejercido: <b>{point.description}<br/> </b>"
            }
        }]

    });
}

var initDataTable = function(){
    table = $('#presupuestos').dataTable({
        'bPaginate': true,
        'bLengthChange': true,
        'bFilter': true,
        'bSort': true,
        'bInfo': true,
        'bAutoWidth': false,
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
            /*pageTotal3 = api
                .column( 3 { page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );*/

            // Total over all page
            pageTotal3 = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            
            // Total over this page
            pageTotal4 = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal5 = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            // Total over this page
            pageTotal6 = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            var colum_3 = '$' + parseFloat(pageTotal3, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
            var colum_4 = '$' + parseFloat(pageTotal4, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
            var colum_5 = '$' + parseFloat(pageTotal5, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
            var colum_6 = '$' + parseFloat(pageTotal6, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString(); 
            
            // Update footer
            $( api.column( 3 ).footer() ).html(
                colum_3 /*$ pageTotal +' ( $'+ total +' total)'*/
            );

            // Update footer
            $( api.column( 4 ).footer() ).html(
                colum_4 
            );
            // Update footer
            $( api.column( 5 ).footer() ).html(
                colum_5 
            );
            // Update footer
            $( api.column( 6 ).footer() ).html(
                colum_6 
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
        var url_server = $('#link_descarga_table').val()  + '/' + $('select[name="id_ejercicio"]').val();
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

<?php 
    if(isset($grafica_2) && $grafica_2->active == 1){

        echo "
        <script>
            var get_valores_grafica_network = function(){
        
                var form_data = new FormData();                  
                form_data.append('id_ejercicio', $('select[name=\"id_ejercicio\"]').val());
                form_data.append('pageSize', $('select[name=\"pageSize_g2\"]').val());
                form_data.append('page', $('select[name=\"page_g2\"]').val());
                var url = '" . base_url() . 'index.php/tpov1/presupuesto/get_presupuestos_grafica' . "'; 
                buscar(url, form_data, graficar_network, 'container2');
            }
            $('select[name=\"pageSize_g2\"]').on('change', function(){
                $('select[name=\"page_g2\"]').on('change', function(){
                    
                })
                $('select[name=\"page_g2\"]').empty();
                $('select[name=\"page_g2\"]').append($('<option>', {
                    value: '--',
                    text: '--'
                }));
                get_valores_grafica_network();
            });
        </script>";
    }
?>