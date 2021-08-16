<?php

/* 
INAI / ALTA CAMPANA NIVEL
*/
?>

<?php
//utilizaremos este array para eliminar los espacios
$c_replace = array('\'', '"');

$sel_cat_niveles = '';
for($z = 0; $z < sizeof($cat_niveles); $z++)
{
    $sel_cat_niveles .= '<option value="'.$cat_niveles[$z]['id_poblacion_nivel'].'">' . $cat_niveles[$z]['nombre_poblacion_nivel'] . '</option>';
}
?>

<!-- Mostramos los detalles de los grupos de niveles dados de alta -->

    <div class="form-group table-responsive no-padding">
        <div class="box-header">
            <a class="btn btn-success" onclick="agregarNivelSocio(<?php echo $id_campana_aviso ?>)"><i class="fa fa-plus-circle"></i> Agregar</a>
        </div><!-- /.box-header -->
        <table id="example3" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Nivel Socioeconómico</th>
                    <th> </th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($niveles))
                {
                    for($z = 0; $z < sizeof($niveles); $z++)
                    {
                        echo '<tr>';
                        echo '<td>' . $niveles[$z]['nombre_poblacion_nivel'] . '</td>';
                        echo "<td> <span class='btn-group btn btn-warning btn-sm' onclick=\"editarNivel(" . $niveles[$z]['id_rel_campana_nivel'] . ", '". str_replace($c_replace, "", $niveles[$z]['nombre_poblacion_nivel']). "', ". $niveles[$z]['id_campana_aviso'] . ")\"> <i class='fa fa-edit'></i></span></td>";
                        echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarNivel(" . $niveles[$z]['id_rel_campana_nivel'] . ", '". str_replace($c_replace, "", $niveles[$z]['nombre_poblacion_nivel']) . "')\"> <i class='fa fa-close'></i></span></td>";
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->


<!-- Modales -->
<div class="modal fade" id="modalAgregarNivelSocio" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Agregar nivel socioeconómico</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/guarda_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="atributo" value="nivel" />
                    <div class="box-body">
                        <div class="form-group">
                            <label>Nivel Socioeconómico</label>
                            <select name="id_poblacion_nivel" id="id_poblacion_nivel" class="form-control">
                                <?php //echo $sel_cat_niveles; ?>
                            </select>
                        </div>
                        <div id="error_alta_nivel">
                            <h4>No hay m&aacute;s valores para agregar.</h4>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" type="submit" id="altaNivel">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>    
                    </div>
                </form>                       
            </div>
        </div>
    </div>
</div>


<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditarNivel" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Detalles nivel Socioeconómico</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/actualizar_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="id_rel_campana_nivel" id="id_rel_campana_nivel" value="" />
                    <input type="hidden" name="atributo" value="nivel" />
                    <div class="box-body">
                        <div class="form-group">
                            <label>Nivel Socioeconómico</label>
                            <select name="id_poblacion_nivel" id="id_poblacion_nivel" class="form-control">
                                
                            </select>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>    
                    </div><!-- /.box -->
                </form>                       
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR -->
<div class="modal fade" id="modalEliminarNivel" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Eliminar registro</h3>
            </div>
            <div class="modal-body">
                <div id="mensaje_modal"></div>
            </div>
            <div class="modal-footer" id="footer_btns">
                
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    var agregarNivelSocio = function(id_campana)
    {
        $.ajax({
            url: '<?php echo base_url() . 'index.php/tpoadminv1/campanas/campanas/dame_nivel_disponibles_id/' ?>'+ id_campana,
            data: {action: 'test'},
            //dataType:'JSON',
            
            success: function (response)
            {
                //alert(response)

                if(response == '')
                {
                    document.getElementById("error_alta_nivel").style.display = "inline";
                    document.getElementById('id_poblacion_nivel').style.display = "none";
                    document.getElementById("altaNivel").disabled = true;
                    $('#modalAgregarNivelSocio').modal('show');
                }
                else
                {
                    document.getElementById("error_alta_nivel").style.display = "none";
                    $('#modalAgregarNivelSocio').find('#id_poblacion_nivel').html(response);
                    $('#modalAgregarNivelSocio').modal('show');
                }
            }
        });
    }

    var eliminarNivel = function(id_rel, nombre){
        var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminarRelacion('+id_rel+')">Si</button>' +
                   '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
        $('#modalEliminarNivel').find('#footer_btns').html(html_btns);
        $('#modalEliminarNivel').find('#mensaje_modal').html('¿Desea eliminar este nivel socioeconómico: <b>' + nombre + '</b>?');
        $('#modalEliminarNivel').modal('show');
    }

    var eliminarRelacion = function (id_rel){
        window.location.href = "eliminar_relacion_campana/" + id_rel+"/nivel";
    }

    var editarNivel = function(id_rel,nombre,id_campana)
    {
        $.ajax({
            url: '<?php echo base_url() . 'index.php/tpoadminv1/campanas/campanas/dame_nivel_rel_id/' ?>'+id_rel+'/'+ id_campana,
            data: {action: 'test'},
            //dataType:'JSON',

            success: function (response)
            {
                if(response)
                {
                    $('#modalEditarNivel').find('#id_poblacion_nivel').html(response);
                    $('#modalEditarNivel').find('#id_rel_campana_nivel').val(id_rel);
                    $('#modalEditarNivel').find('#poblacion_lugar').val(nombre);
                    $('#modalEditarNivel').modal('show');
                }
            }
        });
    }
                            
     $(function () {
        $('#example3').dataTable({
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