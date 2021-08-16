<?php

/* 
INAI / ALTA CAMPANA NIVEL EDUCATIVO
*/

?>

<?php
//utilizaremos este array para eliminar los espacios
$c_replace = array('\'', '"');

$sel_cat_nivel_educativo = '';
for($z = 0; $z < sizeof($cat_niveles_educativos); $z++)
{
    $sel_cat_nivel_educativo .= '<option value="'.$cat_niveles_educativos[$z]['id_poblacion_nivel_educativo'].'">' . $cat_niveles_educativos[$z]['nombre_poblacion_nivel_educativo'] . '</option>';
}
?>

<!-- Mostramos los detalles de los grupos de niveles_educativos dados de alta -->
<div class="form-group table-responsive no-padding">
    <div class="box-header">
        <a class="btn btn-success" onclick="agregarNivelEducativo(<?php echo $id_campana_aviso ?>)"><i class="fa fa-plus-circle"></i> Agregar</a>
    </div><!-- /.box-header -->
    <table id="example5" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Nivel Educativo</th>
                <th> </th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($niveles_educativos))
            {
                for($z = 0; $z < sizeof($niveles_educativos); $z++)
                {
                    echo '<tr>';
                        echo '<td>' . $niveles_educativos[$z]['nombre_nivel_educativo'] . '</td>';
                        echo "<td> <span class='btn-group btn btn-warning btn-sm' onclick=\"editarEducacion(" . $niveles_educativos[$z]['id_rel_campana_nivel_educativo'] . ", '". str_replace($c_replace, "", $niveles_educativos[$z]['nombre_nivel_educativo']). "', ". $niveles_educativos[$z]['id_campana_aviso'] . ")\"> <i class='fa fa-edit'></i></span></td>";
                        echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarEducacion(" .$niveles_educativos[$z]['id_rel_campana_nivel_educativo'].",'". str_replace($c_replace, "", $niveles_educativos[$z]['nombre_nivel_educativo']) . "')\"> <i class='fa fa-close'></i></span></td>";
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregarNivelEducativo" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Agregar nivel educativo</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/guarda_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="atributo" value="educacion" />

                    <div class="box-body">
                        <div class="form-group">
                            <label>Educaci&oacute;n</label>
                            <select name="id_poblacion_nivel_educativo" id="id_poblacion_nivel_educativo" class="form-control">
                                <?php //echo $sel_cat_nivel_educativo; ?>
                            </select>
                        </div>
                        <div id="error_alta_nivel_educativo">
                            <h4>No hay m&aacute;s valores para agregar.</h4>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" type="submit" id="altaEducacion">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>    
                    </div>            
                </form>                       
            </div>
        </div>
    </div>
</div>

<!--MODAL EDITAR -->
<div class="modal fade" id="modalEditarEducacion" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Detalles nivel educativo</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/actualizar_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="atributo" value="educacion" />
                    <input type="hidden" name="id_rel_campana_nivel_educativo" id="id_rel_campana_nivel_educativo" value="" />
                    <div class="box-body">
                        <div class="form-group">
                            <label>Grupo Edad</label>
                            <select name="id_poblacion_nivel_educativo" id="id_poblacion_nivel_educativo" class="form-control">
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
<div class="modal fade" id="modalEliminarEducacion" role="dialog">
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

    var agregarNivelEducativo = function(id_campana)
    {
        $.ajax({
            url: '<?php echo base_url() . 'index.php/tpoadminv1/campanas/campanas/dame_educacion_disponibles_id/' ?>'+ id_campana,
            data: {action: 'test'},
            //dataType:'JSON',
            
            success: function (response)
            {
                if(response == '')
                {
                    document.getElementById("error_alta_nivel_educativo").style.display = "inline";
                    document.getElementById('id_poblacion_nivel_educativo').style.display = "none";
                    document.getElementById("altaEducacion").disabled = true;
                    $('#modalAgregarNivelEducativo').modal('show');
                }
                else
                {
                    document.getElementById("error_alta_nivel_educativo").style.display = "none";
                    $('#modalAgregarNivelEducativo').find('#id_poblacion_nivel_educativo').html(response);
                    $('#modalAgregarNivelEducativo').modal('show');
                }
            }
        });
    }

    var editarEducacion = function(id_rel,nombre,id_campana)
    {
        $.ajax({
            //url: '<?php echo base_url() . 'index.php/tpoadminv1/capturista/facturas/get_factura/' ?>'+id,
            url: '<?php echo base_url() . 'index.php/tpoadminv1/campanas/campanas/dame_educacion_rel_id/' ?>'+id_rel+'/'+ id_campana,
            data: {action: 'test'},
            //dataType:'JSON',
            
            success: function (response) {
                //alert(response);

                if(response){

                    var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminarRelacion('+id_rel+')">Si</button>' +
                        '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
                    $('#modalEditarEducacion').find('#footer_btns').html(html_btns);
                    $('#modalEditarEducacion').find('#id_poblacion_nivel_educativo').html(response);
                    $('#modalEditarEducacion').find('#id_rel_campana_nivel_educativo').val(id_rel);
                    $('#modalEditarEducacion').modal('show');
                }
            }
        });
    }

    var eliminarEducacion = function(id_rel,nombre){
        var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminarRelacion('+id_rel+')">Si</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
        $('#modalEliminarEducacion').find('#footer_btns').html(html_btns);
        $('#modalEliminarEducacion').find('#mensaje_modal').html('¿Desea eliminar este nivel educativo: <b>' + nombre+ '</b>?');
        $('#modalEliminarEducacion').modal('show');
    }

    var eliminarRelacion = function (id_rel){
        window.location.href = "eliminar_relacion_campana/" + id_rel+"/educacion";
    }


    $(function () {
        $('#example5').dataTable({
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