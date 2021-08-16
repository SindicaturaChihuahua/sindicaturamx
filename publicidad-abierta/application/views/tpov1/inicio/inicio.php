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
    <button id="descargabtnPNT" type="button" class="btn btn-default pull-right" onclick="descargar_archivo('Datos Plataforma Nacional de Trasparencia', '#link_descarga_pnt', '#descargabtnPNT')" style="margin-left: 10px;" title="<?php echo $indicadores_ayuda['btn_descarga_pnt']?>" data-placement="bottom" data-toggle="tooltip"><i class="fa fa-file"></i>&nbsp;&nbsp;Datos Plataforma Nacional de Trasparencia</button>
    <input type="hidden" id="link_descarga_pnt" value="<?php echo $link_descarga_pnt; ?>"/>
    <button id="descargabtn" type="button" class="btn btn-success pull-right" onclick="descargar_archivo('Descarga de datos', '#link_descarga', '#descargabtn')" title="<?php echo $indicadores_ayuda['btn_download']?>" data-placement="bottom" data-toggle="tooltip"><i class="fa fa-file"></i>&nbsp;&nbsp;Descarga de datos</button>
    <input type="hidden" id="link_descarga" value="<?php echo $link_descarga; ?>"/>
</div>
<div class="row"> <!-- Cabecera de filtrado-->
    <div class="col-md-2">
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
    <div class="col-md-2">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['presupuesto_n']?>" data-placement="bottom" data-toggle="tooltip">
                Presupuesto($) 
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="presupuesto"  class="info-box-number">$0.00</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-2">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['modificacion_n']?>" data-placement="bottom" data-toggle="tooltip">
                Modificaci&oacute;n($) 
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="modificacion" class="info-box-number">$0.00</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-2">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['ejercido_n']?>" data-placement="bottom" data-toggle="tooltip">
                Ejercido($) 
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="ejercido" class="info-box-number">$0.00</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-2">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['proveedores_inicio']?>" data-placement="bottom" data-toggle="tooltip">
                Proveedores
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="proveedores" class="info-box-number">0</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
    <div class="col-md-2">
        <div class="box box-default bg-box-black">
            <div class="box-header header" title="<?php echo $indicadores_ayuda['campanas_avisos_n']?>" data-placement="bottom" data-toggle="tooltip">
                Campa&ntilde;as/avisos 
            </div> <!-- / .box-header-->
            <div class="box-body with-border body">
                <span id="campanas_avisos" class="info-box-number">0</span>
            </div> <!-- / .box-body-->
        </div> <!-- / .box-->
    </div> <!-- / .col-->
</div> <!-- / .row cabecera -->

<div class="row"> <!-- Gráficas -->
    <!-- Primera gráfica recursos ejercicios-->
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['recursos_ejercidos_n']?>" data-placement="bottom" data-toggle="tooltip"></i>
                RECURSOS EJERCIDOS &nbsp;&nbsp;<a href="<?php echo base_url() . 'index.php/tpov1/erogaciones' ?>" target="_blank"><i class="fa fa-chevron-right"></i></a>
            </div><!-- / .box-header-->
            <div class="box-body with-border">
                <div id="load_container1" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div id="container1" style="min-height: 300px; margin: 0 auto"></div>
            </div><!-- / .box-body-->
        </div><!-- / .box-->
    </div><!-- / .col -->

    <!-- Segunda gráfica Gastos partida-->
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['gasto_por_partida_n']?>" data-placement="bottom" data-toggle="tooltip"></i>
                GASTO POR PARTIDA &nbsp;&nbsp;<a href="<?php echo base_url() . 'index.php/tpov1/presupuesto' ?>" target="_blank"><i class="fa fa-chevron-right"></i></a>
            </div><!-- / .box-header-->
            <div class="box-body with-border">
                <div id="load_container2" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div id="container2" style="min-height: 300px; margin: 0 auto"></div>
            </div><!-- / .box-body-->
        </div><!-- / .box-->
    </div><!-- / .col -->

    <!-- Tercera gráfica gastos por tipo de servicio-->
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['gasto_por_servicio_n']?>" data-placement="bottom" data-toggle="tooltip"></i>
                GASTO POR TIPO DE SERVICIO  &nbsp;&nbsp;<a href="<?php echo base_url() . 'index.php/tpov1/gasto_tipo_servicio' ?>" target="_blank"><i class="fa fa-chevron-right"></i></a>
            </div><!-- / .box-header-->
            <div class="box-body with-border">
                <div id="load_container3" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div id="container3" style="min-height: 300px; margin: 0 auto"></div>
            </div><!-- / .box-body-->
        </div><!-- / .box-->
    </div><!-- / .col -->

    <!-- Cuarta gráfica campañas o aviso-->
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['campanas_avisos_g_n']?>" data-placement="bottom" data-toggle="tooltip"></i>
                CAMPA&Ntilde;A O AVISOS INSTITUCIONALES &nbsp;&nbsp;<a href="<?php echo base_url() . 'index.php/tpov1/campana_aviso' ?>" target="_blank"><i class="fa fa-chevron-right"></i></a>
            </div><!-- / .box-header-->
            <div class="box-body with-border">
                <div id="load_container4" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div id="container4" style="min-height: 300px; margin: 0 auto"></div>
            </div><!-- / .box-body-->
        </div><!-- / .box-->
    </div><!-- / .col -->

    <!-- Quinta gráfica Top 10 campañas o avisos-->
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['top_10_campanas_n']?>" data-placement="bottom" data-toggle="tooltip"></i>
                TOP 10 CAMPA&Ntilde;A O AVISOS INSTITUCIONALES &nbsp;&nbsp;<a href="<?php echo base_url() . 'index.php/tpov1/campana_aviso' ?>" target="_blank"><i class="fa fa-chevron-right"></i></a>
            </div><!-- / .box-header-->
            <div class="box-body with-border">
                <div id="load_container5" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div id="container5" style="min-height: 300px; margin: 0 auto"></div>
            </div><!-- / .box-body-->
        </div><!-- / .box-->
    </div><!-- / .col -->

    <!-- Quinta gráfica Top 10 proveedores-->
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="fa fa-info-circle text-primary" title="<?php echo $indicadores_ayuda['top_10_proveedores_n']?>" data-placement="bottom" data-toggle="tooltip"></i>
                TOP 10 PROVEEDORES &nbsp;&nbsp;<a href="<?php echo base_url() . 'index.php/tpov1/proveedores' ?>" target="_blank"><i class="fa fa-chevron-right"></i></a>
            </div><!-- / .box-header-->
            <div class="box-body with-border">
                <div id="load_container6" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div id="container6" style="min-height: 300px; margin: 0 auto"></div>
            </div><!-- / .box-body-->
        </div><!-- / .box-->
    </div><!-- / .col -->

</div><!-- / .row gráficas-->

<script>
    var init = function(){
        get_erogaciones();  
        get_presupuestos();
        get_Campanas_Avisos();
        get_top10_proveedores();
        get_top10_campanas();
        get_gasto_porservicio();
    }
    
    var get_erogaciones = function()
    {
        $('#ejercido').html('$0.00');
        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/inicio/getErogacionesMes' ?>';

        buscar(url, form_data, area_chart, 'container1');
    }

    var get_presupuestos = function()
    {
        $('#presupuesto').html('$0.00');
        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/inicio/getPresupuestos' ?>';

        buscar(url, form_data, bar_chart, 'container2', 'Partida');
    }

    var get_gasto_porservicio = function()
    {
        $('#modificacion').html('$0.00');
        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/inicio/getGastoPorServicio' ?>';

        buscar(url, form_data, bar_chart, 'container3', 'Tipos de servicio');
    }

    var get_Campanas_Avisos = function(){
        
        $('#campanas_avisos').html('0');
        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/inicio/getCampanasAvisos' ?>';

        buscar(url, form_data, pie_chart, 'container4');
    }

    var get_top10_campanas = function()
    {
        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/inicio/getTop10Campanas' ?>';

        buscar(url, form_data, column_chart, 'container5');
    }

    var get_top10_proveedores = function()
    {
        $('#proveedores').html('0');
        var form_data = new FormData();                  
        form_data.append('id_ejercicio', $('select[name="id_ejercicio"]').val());
        var url = '<?php echo base_url() . 'index.php/tpov1/inicio/getTop10Proveedores' ?>';

        buscar(url, form_data, column_chart, 'container6');
    }


    var buscar = function(url_server, formData, callback, container, title){
        var id_tag = '#load_' + container;                         
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
                $('#'+container).addClass('overlay');
                $('#'+container).html('Informacion no disponible');
            },
            success: function(response){
                $(id_tag).html('');
                if(response && callback){
                    callback(response, container, title);
                }
            }
        });
    }

    var load_box = function(mostrar)
    {
        if(mostrar == true){
            $('#load_container1').html('<i class="fa fa-refresh fa-spin"></i>');
            $('#load_container2').html('<i class="fa fa-refresh fa-spin"></i>');
        }else{
            $('#load_container1').html('');
            $('#load_container2').html(''); 
        }
    }

    var column_chart = function(response, container){
        
        if(container == 'container6'){
            $('#proveedores').html(response['total']);
        }

        Highcharts.chart(container, {
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
                categories: response['categorias'],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Monto ejercido'
                },
                labels:{
                    formatter: function () {
                        var result = this.value;
                        if (this.value >= 1000000000000) { result = Math.floor(this.value / 1000000000000) + "B" }
                        else if (this.value >= 1000000) { result = Math.floor(this.value / 1000000) + "M" }
                        else if (this.value >= 1000) { result = Math.floor(this.value / 1000) + "K" }
                        return result;// clean, unformatted number for year
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormatter: function(){
                    var n = parseFloat(this.y, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
                    return '<tr><td style="color:' + this.color +';padding:0">Monto ejercido </td>' +
                        '<td style="padding:0"><b> $ ' + n + '</b></td></tr>';
                },
                /*pointFormat: '<tr><td style="color:{series.color};padding:0">Monto ejercido </td>' +
                    '<td style="padding:0"><b>$ {point.y:,.2f}</b></td></tr>',*/
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    useHTML: false,
                    formatter: function(){
                        var n = parseFloat(this.y, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
                        return '$ ' + n;
                    },
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                showInLegend: false,
                name: 'Proveedores',
                data: response['montos']

            }]
        });
    }

    var pie_chart = function(response, container){
        
        $('#campanas_avisos').html(response['total']);
        
        Highcharts.chart(container, {
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
                headerFormat: '<span style="font-size:10px"><b>{point.key}</b><br/></span>',
                pointFormatter: function(){
                    var n = parseFloat(this.y, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
                    return '<span style="color:' + this.color +'">\u25CF</span>' + this.series.name + ' <b>$ ' + n + '</b>';
                }
                //pointFormat: '{series.name}: <b>$ {point.y:,.2f}</b>'
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
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Monto ejercido',
                colorByPoint: true,
                data: response['data']
            }]
        });
    }

    var area_chart = function(response, container){

        var data = response;
        //var e =  (parseFloat(data['total']) / 1000000).toFixed(2);
        //$('#ejercido').html(e.replace(/(\d)(?=(\d{3})+\.)/g, "$1, ").toString() + ' mdp');
        var e = get_monto_format(data['total']);
        $('#ejercido').html(e);

        Highcharts.chart(container, {
            chart: {
                type: 'area'
            },
            title: {
                text: null
            },
            subtitle: {
                text: null
            },
            xAxis: {
                categories: data['meses'], //['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                allowDecimals: true,
                labels: {
                }
            },
            yAxis: {
                title: {
                    text: 'Monto ejercido'
                },
                labels: {
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
                    return '<span style="color:' + this.color +'">\u25CF</span>' + this.series.name + ' <b>$ ' + n + '</b>';
                }
                //pointFormat: 'Monto ejercido: <b>$ {point.y:,.2f}</b><br/>'
            },
            plotOptions: {
                area: {
                    //pointStart: 1940,
                    marker: {
                        enabled: false,
                        symbol: 'circle',
                        radius: 2,
                        states: {
                            hover: {
                                enabled: true
                            }
                        }
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Ejercido por mes',
                data: data['monto']
            }]
        });
    }

    var bar_chart = function(response, container, titley)
    {
        var data = response;//$.parseJSON(response);
        
        if(data['presupuesto'] !== undefined){
            //var e =  (parseFloat(data['presupuesto']) / 1000000).toFixed(2);
            //$('#presupuesto').html(e.replace(/(\d)(?=(\d{3})+\.)/g, "$1, ").toString() + ' mdp');
            var e = get_monto_format(data['presupuesto']);
            $('#presupuesto').html(e);
        }

        if(data['total'] !== undefined && titley == 'Partida' ){
            //var m =  (parseFloat(data['total']) / 1000000).toFixed(2);
            //$('#modificacion').html(m.replace(/(\d)(?=(\d{3})+\.)/g, "$1, ").toString() + ' mdp');
            var m = get_monto_format(data['total']);
            $('#modificacion').html(m);
        }

        var validColor = false;
        var colors = [];
        if(data['colores'] !== undefined){
            validColor = true;
            colors = data['colores']
        }
        
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
                categories: data['categorias'],
                title: {
                    text: titley
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
                    overflow: 'justify'
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
                        },
                    },
                    colors: colors,
                    colorByPoint: validColor
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
            series: [{
                showInLegend: false,
                name: 'Monto ejercido',
                data: data['montos']
            }]
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

    var descargar_archivo = function(nombre_btn, container, btn){
        var url_server = $(container).val();
        $(btn).empty();
        $(btn).append('<i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;' + nombre_btn); 
        $.ajax({
            url: url_server, // point to server-side PHP script 
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: '',                         
            type: 'post',
            complete: function(){
                $(btn).empty();
                $(btn).append('<i class="fa fa-file"></i>&nbsp;&nbsp;' + nombre_btn); 
            },
            error:function(){
            },
            success: function(response){
                window.open(response, '_blank');
            }
        });
    }


</script>
