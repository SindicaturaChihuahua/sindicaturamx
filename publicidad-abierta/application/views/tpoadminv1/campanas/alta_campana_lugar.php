<?php
/* 
INAI / ALTA CAMPANA LUGAR
*/

//utilizaremos este array para eliminar los espacios
$c_replace = array('\'', '"');
?>

<!-- Mostramos los detalles de los grupos de lugares dados de alta -->
<div class="form-group table-responsive no-padding">
    <div class="box-header">
        <a class="btn btn-success" onclick="agregarLugar()"><i class="fa fa-plus-circle"></i> Agregar</a>
    </div><!-- /.box-header -->

    <table id="example4" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Lugar</th>
                <th> </th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($lugares))
            {
                for($z = 0; $z < sizeof($lugares); $z++)
                {
                    echo '<tr>';
                        echo '<td>' . $lugares[$z]['poblacion_lugar'] . '</td>';
                        echo "<td> <span class='btn-group btn btn-warning btn-sm' onclick=\"editarLugar(" .$lugares[$z]['id_campana_lugar'].",'".$lugares[$z]['poblacion_lugar']. "')\"> <i class='fa fa-edit'></i></span></td>";
                        //echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarLugar(" .$lugares[$z]['id_campana_lugar'].",'".$lugares[$z]['poblacion_lugar']. "')\"> <i class='fa fa-close'></i></span></td>";
                        echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarLugar(" . $lugares[$z]['id_campana_lugar'] . ", '". str_replace($c_replace, "", $lugares[$z]['poblacion_lugar']) . "')\"> <i class='fa fa-close'></i></span></td>";
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div><!-- /.box-body -->


<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregarLugar" role="dialog" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Agregar lugar</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/guarda_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="atributo" value="lugar" />
                    <div class="box-body">
                        <div class="form-group">
                            <label>Lugar</label>
                            <input type="text" name="poblacion_lugar"  id="poblacion_lugar"  class="form-control" value="" onInput="validarInput3()" />
                        </div>
                        <div class="box-footer">
                            <script type="text/javascript">
                                function validarInput3() {
                                    document.getElementById("btn_Validar2").disabled = !document.getElementById("poblacion_lugar").value.length;
                                } 
                            </script>
                            <button type="submit" name="add_to_cart" id="btn_Validar2" class="btn btn-primary add_to_cart">
                                Guardar
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetear()">Cancelar</button>
                        </div>  
                    </div>
                </form>                      
            </div>
        </div>
    </div>
</div>


<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditarLugar" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Detalles lugar</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/actualizar_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="id_rel" id="id_rel" value="" />
                    <input type="hidden" name="atributo" value="lugar" />
                    <div class="box-body">
                        <div class="form-group">
                            <label>Lugar</label>
                            <input class="form-control" type="text" name="poblacion_lugar" id="poblacion_lugar" value="" />
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
<div class="modal fade" id="modalEliminarLugar" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Eliminar registro</h3>
        </div>
        <div class="modal-body">
            <div id="mensaje_modal"></div>
        </div>
        <div class="modal-footer" id="footer_btns"></div>
    </div>
</div>


<script type="text/javascript">

    function resetear() {
        $('#poblacion_lugar').val('');
    }

    var agregarLugar = function()
    {
        
        var texto_input = $('#poblacion_lugar').val();
        if(texto_input == '')
        {
            document.getElementById("btn_Validar2").disabled = true;
        }
        else
        {
            document.getElementById("btn_Validar2").disabled = false;
        }

        $('#modalAgregarLugar').modal('show');
    }

    var eliminarLugar = function(id_rel,nombre){
        var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminarRelacion('+id_rel+')">Si</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
        $('#modalEliminarLugar').find('#footer_btns').html(html_btns);
        $('#modalEliminarLugar').find('#mensaje_modal').html('¿Desea eliminar este lugar <b>' + nombre+ '</b>?');
        $('#modalEliminarLugar').modal('show');
    }

    var eliminarRelacion = function (id_rel){
        window.location.href = "eliminar_relacion_campana/" + id_rel+"/lugar";
    }

    var editarLugar = function(id_rel,nombre){
        $('#modalEditarLugar').find('#id_rel').val(id_rel);
        $('#modalEditarLugar').find('#poblacion_lugar').val(nombre);
        $('#modalEditarLugar').find('#mensaje_modal').html('Actualizar lugar <b>' + nombre+ '</b>?');
        $('#modalEditarLugar').modal('show');
    }


    $(function () {
        $('#example4').dataTable({
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