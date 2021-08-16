<?php

/* 
INAI / ALTA CAMPANA EDAD
*/
?>

<?php
//utilizaremos este array para eliminar los espacios
$c_replace = array('\'', '"');

$sel_cat_edad = '';
for($z = 0; $z < sizeof($cat_edad); $z++)
{
    if($this->input->post('id_poblacion_grupo_edad') == $cat_edad[$z]['id_poblacion_grupo_edad']){
        $sel_cat_edad .= '<option value="'.$cat_edad[$z]['id_poblacion_grupo_edad'].'" selected>' . $cat_edad[$z]['nombre_poblacion_grupo_edad'] . '</option>';
    }else{
        $sel_cat_edad .= '<option value="'.$cat_edad[$z]['id_poblacion_grupo_edad'].'">' . $cat_edad[$z]['nombre_poblacion_grupo_edad'] . '</option>';
    }
}
?>


<!-- Mostramos los detalles de los grupos de edades dados de alta -->
<div class="form-group table-responsive no-padding">
    <div class="box-header">
        <a class="btn btn-success" onclick="agregarGpoEdad(<?php echo $id_campana_aviso ?>)"><i class="fa fa-plus-circle"></i> Agregar</a>
    </div><!-- /.box-header -->
                    
    <table id="example2" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Grupo edad</th>
                <th> </th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($edades))
            {
                for($z = 0; $z < sizeof($edades); $z++)
                {
                    echo '<tr>';
                        echo '<td>' . $edades[$z]['nombre_grupo_edad'] . '</td>';
                        echo "<td> <span class='btn-group btn btn-warning btn-sm' onclick=\"editarGpoEdad(" . $edades[$z]['id_rel_campana_grupo_edad'] . ", '". str_replace($c_replace, "", $edades[$z]['nombre_grupo_edad']). "', ". $edades[$z]['id_campana_aviso'] . ")\"> <i class='fa fa-edit'></i></span></td>";
                        echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarGpoEdad(" . $edades[$z]['id_rel_campana_grupo_edad'] . ", '". str_replace($c_replace, "", $edades[$z]['nombre_grupo_edad']) . "')\"> <i class='fa fa-close'></i></span></td>";
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregarGpoEdad" role="dialog"  data-backdrop="static" data-keyboard="false" href="#">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Agregar grupo de edad</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/guarda_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="atributo" value="edad" />
                    <div class="box-body">
                        <div class="form-group">
                            <label>Grupo Edad</label>
                            <select name="id_poblacion_grupo_edad" id="id_poblacion_grupo_edad" class="form-control">
                                <!--
                                <?php echo $sel_cat_edad; ?>
                                -->
                            </select>
                        </div>
                        <div id="error_alta_edad">
                            <h4>No hay m&aacute;s valores para agregar.</h4>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" type="submit" id="altaEdad">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>    
                    </div><!-- /.box -->
                </form>                       
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditarGpoEdad" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Detalles grupo de edad</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/actualizar_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="atributo" value="edad" />
                    <input type="hidden" name="id_rel_campana_grupo_edad" id="id_rel_campana_grupo_edad" value="" />
                    <div class="box-body">
                        <div class="form-group">
                            <label>Grupo Edad</label>
                            <select name="id_poblacion_grupo_edad" id="id_poblacion_grupo_edad" class="form-control">
                                <!-- Los valores son cargador mediante la funcion --> 
                            </select>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>    
                    </div>
                </form>                       
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR -->
<div class="modal fade" id="modalEliminarGpoEdad" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Eliminar registro</h3>
        </div>
        <div class="modal-body">
            <div id="mensaje_modal">
                
            </div>
        </div>
        <div class="modal-footer" id="footer_btns">
            
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var agregarGpoEdad = function(id_campana)
    {
        $.ajax({
            url: '<?php echo base_url() . 'index.php/tpoadminv1/campanas/campanas/dame_edad_disponibles_id/' ?>'+ id_campana,
            data: {action: 'test'},
            //dataType:'JSON',
            
            success: function (response)
            {
                if(response == '')
                {
                    document.getElementById("error_alta_edad").style.display = "inline";
                    document.getElementById('id_poblacion_grupo_edad').style.display = "none";
                    document.getElementById("altaEdad").disabled = true;
                    $('#modalAgregarGpoEdad').modal('show');
                }
                else
                {
                    document.getElementById("error_alta_edad").style.display = "none";
                    var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminarRelacion('+id_campana+')">Si</button>' +
                        '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
                    $('#modalAgregarGpoEdad').find('#footer_btns').html(html_btns);
                    $('#modalAgregarGpoEdad').find('#id_poblacion_grupo_edad').html(response);
                    //$('#modalAgregarGpoEdad').find('#id_rel_campana_grupo_edad').val(id_rel);
                    $('#modalAgregarGpoEdad').modal('show');
                }
            }
        });
    }

    var editarGpoEdad = function(id_rel,nombre,id_campana)
    {
        $.ajax({
            url: '<?php echo base_url() . 'index.php/tpoadminv1/campanas/campanas/dame_edad_rel_id/' ?>'+id_rel+'/'+ id_campana,
            data: {action: 'test'},
            //dataType:'JSON',
            
            success: function (response) {
                //alert(response);

                if(response){

                    var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminarRelacion('+id_rel+')">Si</button>' +
                        '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
                    $('#modalEditarGpoEdad').find('#footer_btns').html(html_btns);
                    $('#modalEditarGpoEdad').find('#id_poblacion_grupo_edad').html(response);
                    $('#modalEditarGpoEdad').find('#id_rel_campana_grupo_edad').val(id_rel);
                    $('#modalEditarGpoEdad').modal('show');
                }
            }
        });
    }

    var eliminarGpoEdad = function(id_rel,nombre){
        var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminarRelacion('+id_rel+')">Si</button>' +
                   '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
        $('#modalEliminarGpoEdad').find('#footer_btns').html(html_btns);
        $('#modalEliminarGpoEdad').find('#mensaje_modal').html('¿Desea eliminar este grupo de edad <b>' + nombre+ '</b>?');
        $('#modalEliminarGpoEdad').modal('show');
    }

    var eliminarRelacion = function (id_rel){
        window.location.href = "eliminar_relacion_campana/" + id_rel+"/edad";
    }
    
    $(function () {
        $('#example2').dataTable({
        'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, 'Todo']],    //Paginacion
        'bPaginate': true,
        'bLengthChange': true,
        'bFilter': true,
        'bSort': true,
        'bInfo': true,
        'bAutoWidth': false,
        'columnDefs': [
            { 'orderable': false, 'targets': [1,2] }
        ], 
        'oLanguage': {
            'sSearch': 'B&uacute;squeda  ',
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

});
</script>