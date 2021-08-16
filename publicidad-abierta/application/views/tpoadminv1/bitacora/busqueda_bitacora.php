<?php

/* 
BUSQUEDA BITACORA
 */

?>

<script type="text/javascript">
    $(document).ready(function()
    {
        var busqueda_seleccion = $('#cupon_tipo').val();
        if(busqueda_seleccion == 'selecciona')
        {
            $('#busqueda_general').hide(); //mostrar el elemento de este id
            $('#col0_busqueda').hide(); //ocultar el elemento de este id
            $('#col1_busqueda').hide(); //ocultar el elemento de este id
            $('#col2_busqueda').hide(); //ocultar el elemento de este id
            $('#col3_busqueda').hide(); //ocultar el elemento de este id
        }

        $('#cupon_tipo').on('change',function()
        {
            var cat_id = $(this).val();
            var datos = 'cat_id=' + cat_id;  //pasamos los valores

            if(cat_id == 'general')
            {
                $('#busqueda_general').show(); //mostrar el elemento de este id
                $('#col0_busqueda').hide(); //ocultar el elemento de este id
                $('#col1_busqueda').hide(); //ocultar el elemento de este id
                $('#col2_busqueda').hide(); //ocultar el elemento de este id
                $('#col3_busqueda').hide(); //ocultar el elemento de este id
            }

            if(cat_id == 'usuario')
            {
                $('#busqueda_general').hide(); //mostrar el elemento de este id
                $('#col0_busqueda').show(); //ocultar el elemento de este id
                $('#col1_busqueda').hide(); //ocultar el elemento de este id
                $('#col2_busqueda').hide(); //ocultar el elemento de este id
                $('#col3_busqueda').hide(); //ocultar el elemento de este id
            }

            if(cat_id == 'seccion')
            {
                $('#busqueda_general').hide(); //mostrar el elemento de este id
                $('#col0_busqueda').hide(); //ocultar el elemento de este id
                $('#col1_busqueda').show(); //ocultar el elemento de este id
                $('#col2_busqueda').hide(); //ocultar el elemento de este id
                $('#col3_busqueda').hide(); //ocultar el elemento de este id
            }

            if(cat_id == 'accion')
            {
                $('#busqueda_general').hide(); //mostrar el elemento de este id
                $('#col0_busqueda').hide(); //ocultar el elemento de este id
                $('#col1_busqueda').hide(); //ocultar el elemento de este id
                $('#col2_busqueda').show(); //ocultar el elemento de este id
                $('#col3_busqueda').hide(); //ocultar el elemento de este id
            }

            if(cat_id == 'fecha')
            {
                $('#busqueda_general').hide(); //mostrar el elemento de este id
                $('#col0_busqueda').hide(); //ocultar el elemento de este id
                $('#col1_busqueda').hide(); //ocultar el elemento de este id
                $('#col2_busqueda').hide(); //ocultar el elemento de este id
                $('#col3_busqueda').show(); //ocultar el elemento de este id
            }

            if(cat_id == 'selecciona')
            {
                $('#busqueda_general').hide(); //mostrar el elemento de este id
                $('#col0_busqueda').hide(); //ocultar el elemento de este id
                $('#col1_busqueda').hide(); //ocultar el elemento de este id
                $('#col2_busqueda').hide(); //ocultar el elemento de este id
                $('#col3_busqueda').hide(); //ocultar el elemento de este id
            }
                                    
        });
    });


    function busquedaGeneral () {
        $('#example2').DataTable().search( 
            $('#global_busqueda').val(),
            $('#global_regex').prop('checked'), 
            $('#global_smart').prop('checked')
        ).draw();
    }
    
    function busquedaColumna ( i ) {
        $('#example2').DataTable().column( i ).search( 
            $('#col'+i+'_busqueda').val(),
            $('#col'+i+'_regex').prop('checked'), 
            $('#col'+i+'_smart').prop('checked')
        ).draw();
    }
    
    $(document).ready(function() {
        $('#example2').DataTable({
            'bSort': false,
            'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, 'Todo']],    //Paginacion
            'oLanguage': { 
                'sSearch': 'Búsqueda  ',
                'sInfoFiltered':   '(filtrado de un total de _MAX_ registros)',
                'sInfo':           'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
                'sZeroRecords':    'No se encontraron resultados',
                'EmptyTable':     'Ningún dato disponible en esta tabla',
                'sInfoEmpty':      'Mostrando registros del 0 al 0 de un total de 0 registros',
                'oPaginate': {
                    'sFirst':    'Primero',
                    'sLast':     'Último',
                    'sNext':     'Siguiente',
                    'sPrevious': 'Anterior'
                },
                'sLengthMenu': '_MENU_ Registros por p&aacute;gina'
            }
        });
        
        $('input.global_busqueda').on( 'keyup click', function () {
            busquedaGeneral();
        } );
    
        $('input.busqueda_columna').on( 'keyup click', function () {
            busquedaColumna( $(this).parents('tr').attr('data-column') );
        } );
    });
</script>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box table-responsive no-padding">
                <div class="box-body">
                    <div class="form-group">
                        <label>B&uacute;squeda por:</label>
                        <select class="form-control" id="cupon_tipo" name="cupon_tipo">
                            <option value="selecciona" selected="selected">- Selecciona -</option>
                            <option value="general">General</option>
                            <option value="usuario">Usuario</option>
                            <option value="accion">Acci&oacute;n</option>
                            <option value="fecha">Fecha y Hora</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <table border="0" style="width: 100%; margin: 0 auto 2em auto;">
                            <tbody>
                                <tr id="busqueda_general">
                                    <td align="center">
                                        <input type="text" placeholder="Busqueda General" class="global_busqueda form-control" id="global_busqueda">
                                    </td>
                                </tr>
                                <tr id="busqueda_columna1" data-column="0">
                                    <td>
                                        <input type="text" placeholder="Busqueda por Usuario" class="busqueda_columna form-control" id="col0_busqueda">
                                    </td>
                                </tr>
                                <tr id="busqueda_columna2" data-column="1">
                                    <td align="center">
                                        <input type="text" placeholder="Busqueda por Secci&oacute;n" class="busqueda_columna form-control" id="col1_busqueda">
                                    </td>
                                </tr>
                                <tr id="busqueda_columna3" data-column="2">
                                    <td align="center">
                                        <input type="text" placeholder="Busqueda por Acci&oacute;n" class="busqueda_columna form-control" id="col2_busqueda">
                                    </td>
                                </tr>
                                <tr id="busqueda_columna3" data-column="3">
                                    <td align="center">
                                        <input type="text" placeholder="Busqueda por Fecha y Hora" class="busqueda_columna form-control" id="col3_busqueda">
                                    </td>
                                </tr>
                            
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box table-responsive no-padding">
                <div class="box-header">
                    <div class="pull-right">
                        <a class="btn btn-default" <?php echo $print_onclick   ?>><i class="fa fa-print"></i> Imprimir</a>
                        <a class="btn btn-default" href="<?php echo base_url() . $path_file_csv ?>" download="<?php echo $name_file_csv ?>"><i class="fa fa-file"></i> Exportar a Excel</a>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th id="usuario" name="usuario">Usuario</th>
                                <th>Secci&oacute;n</th>
                                <th>Acci&oacute;n</th>
                                <th>Fecha y Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($bitacora != '')
                            {
                                for($z = 0; $z < sizeof($bitacora); $z++)
                                {
                                    echo '<tr>';
                                    echo '<td>' . $bitacora[$z]['usuario_nombre'] . '</td>';
                                    echo '<td>' . $bitacora[$z]['seccion_bitacora'] . '</td>';
                                    echo '<td>' . $bitacora[$z]['accion_bitacora'] . '</td>';
                                    echo '<td>' . $bitacora[$z]['fecha_bitacora'] . '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->