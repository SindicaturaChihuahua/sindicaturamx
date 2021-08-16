<?php    
if($disponible == true){ /* se muestra la info*/ ?> 
<input name="id_proveedor" type="hidden" value="<?php echo $proveedor['id_proveedor'] ?>">
<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Detalle de la campan&tilde;a o aviso institucional </h3>
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
                        <td>Tipo* </td>
                        <td width="80%"><?php echo $proveedor['nombre_razon_social']?></td>
                    </tr>
                    <tr>
                        <td>Subtipo* </td>
                        <td><?php echo $proveedor['nombre_comercial']?></td>
                    </tr>
                    <tr>
                        <td>Nombre* </td>
                        <td><?php echo $proveedor['rfc']?></td>
                    </tr>
                    <tr>
                        <td>Clave de campaña o aviso	</td>
                        <td><?php echo $proveedor['nombre_personalidad_juridica']?></td>
                    </tr>
                    <tr>
                        <td>Autoridad que proporcionó la clave 	</td>
                        <td><?php echo $proveedor['nombres']?></td>
                    </tr>
                    <tr>
                        <td>Ejercicio*</td>
                        <td><?php echo $proveedor['primer_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Trimestre*</td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Sujeto obligado contratante*</td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>

                    <tr>
                        <td>Sujeto obligado solicitante* </td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Tema*</td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Objetivo institucional*</td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Objetivo de comunicación</td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Cobertura*</td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Ámbito geográfico</td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Fecha de inicio</td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Fecha de término </td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Tiempo oficial*</td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Fecha de inicio tiempo oficial </td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Fecha de término tiempo oficial </td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Publicación SEGOB. </td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Documento del PACS </td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Fecha de publicación* </td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Estatus* </td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                    <tr>
                        <td>Monto total ejercido </td>
                        <td><?php echo $proveedor['segundo_apellido']?></td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>

<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Nivel socioeconómico</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <div class="table-responsive-md">
                <table id="ordenes" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nivel socioeconómico</th>
                        </tr>
                    </thead>
                    <tbody>
                                    
                    </tbody>
                </table>
            </div> <!-- /. table-responsive -->
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>


<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Grupos edad</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <table id="contratos" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ejercicio  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                                
                </tbody>
            </table>
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>


<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Lugares</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <table id="contratos" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lugar  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                                
                </tbody>
            </table>
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>


<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Nivel educativo</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <table id="contratos" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nivel educativo  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                                
                </tbody>
            </table>
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>


<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Sexo</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <table id="contratos" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sexo  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                                
                </tbody>
            </table>
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>

<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Audios</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <table id="contratos" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Audios  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                                
                </tbody>
            </table>
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>

<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Imagenes</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <table id="contratos" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Imagenes  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                                
                </tbody>
            </table>
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>



<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Videos</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <table id="contratos" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Videos  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                                
                </tbody>
            </table>
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>


<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Servicio de difusión en medios de comunicación relacionados con la campaña</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <table id="contratos" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ejercicio</th>
                        <th>Factura  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Fecha  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Proveedor  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Categoria  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Subcategoria  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Tipo</th>
                        <th>Campana o aviso  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Monto gastado  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                                
                </tbody>
            </table>
        </div> <!-- / .box-body-->
    </div> <!-- / .box-->
</div>


<div class="row">
    <div class="box box-info box-solid">
        <div class="box-header header">
            <h3 class="box-title">Otros servicios asociados a la comunicación relacionados con la campaña</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div> <!-- / .box-header-->
        <div class="box-body with-border">
            <table id="contratos" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ejercicio</th>
                        <th>Factura  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Fecha  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Proveedor  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Categoria  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Subcategoria  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Tipo</th>
                        <th>Campana o aviso  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th>Monto gastado  <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['ejercicio']?>"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                                
                </tbody>
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
                                "<td><a href='" + e.link + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle'><i class='fa fa-link'></i></a></td>" + 
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
                                "<td>"+e.nombre_campana_aviso+"</td>" +
                                "<td>"+e.monto+"</td>" +
                                "<td><a href='" + e.link + "' class='btn btn-default btn-xs' data-toggle='tooltip' title='Detalle'><i class='fa fa-link'></i></a></td>" + 
                           "</tr>";
                $('#ordenes').find('tbody').append(html);
            });
            var no_filter = [6];
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
            }
        });
    }

</script>


<?php   }else {
    echo "Proveedor no encontrado";
} ?>
