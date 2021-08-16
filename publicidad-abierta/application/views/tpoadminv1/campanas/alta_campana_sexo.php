<?php

/* 
INAI / ALTA CAMPANA EDAD
*/
?>

<?php
//utilizaremos este array para eliminar los espacios
$c_replace = array('\'', '"');

$sel_cat_sexos = '';
for($z = 0; $z < sizeof($cat_sexos); $z++)
{
    if($this->input->post('id_poblacion_sexo') == $cat_sexos[$z]['id_poblacion_sexo']){
        $sel_cat_sexos .= '<option value="'.$cat_sexos[$z]['id_poblacion_sexo'].'" selected>' . $cat_sexos[$z]['nombre_poblacion_sexo'] . '</option>';
    }else{
        $sel_cat_sexos .= '<option value="'.$cat_sexos[$z]['id_poblacion_sexo'].'">' . $cat_sexos[$z]['nombre_poblacion_sexo'] . '</option>';
    }
}
?>

<!-- Mostramos los detalles de los grupos de sexos dados de alta -->
<div class="form-group table-responsive no-padding">
    <div class="box-header">
        <a class="btn btn-success" onclick="agregarSexo(<?php echo $id_campana_aviso ?>)"><i class="fa fa-plus-circle"></i> Agregar</a>
    </div><!-- /.box-header -->
    <table id="example6" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Sexo</th>
                <th> </th>
                 <th> </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($sexos))
            {
                for($z = 0; $z < sizeof($sexos); $z++)
                {
                    echo '<tr>';
                    echo '<td>' . $sexos[$z]['nombre_sexo'] . '</td>';
                    echo "<td> <span class='btn-group btn btn-warning btn-sm' onclick=\"editarSexo(" . $sexos[$z]['id_rel_campana_sexo'] . ", '". str_replace($c_replace, "", $sexos[$z]['nombre_sexo']). "', ". $sexos[$z]['id_campana_aviso'] . ")\"> <i class='fa fa-edit'></i></span></td>";
                    echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarSexo(" .$sexos[$z]['id_rel_campana_sexo'].",'". str_replace($c_replace, "", $sexos[$z]['nombre_sexo']) . "')\"> <i class='fa fa-close'></i></span></td>";
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>
                
<!-- AGREGAR  MODAL -->
<div class="modal fade" id="modalAgregarSexo" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Agregar sexo</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/guarda_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="atributo" value="sexo" />

                    <div class="box-body">
                        <div class="form-group">
                            <label>Sexo</label>
                            <select name="id_poblacion_sexo" id="id_poblacion_sexo" class="form-control">
                                
                            </select>
                        </div>
                        <div id="error_alta_sexo">
                            <h4>No hay m&aacute;s valores para agregar.</h4>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" type="submit" id="altaSexo">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>    
                    </div>            
                </form>                       
            </div>
        </div>
    </div>
</div>

<!-- EDITAR MODAL -->
<div class="modal fade" id="modalEditarSexo" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Sexo</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/actualizar_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="atributo" value="sexo" />
                    <input type="hidden" name="id_rel_campana_sexo" id="id_rel_campana_sexo" value="" />
                    <div class="box-body">
                        <div class="form-group">
                            <label>Sexo</label>
                            <select name="id_poblacion_sexo" id="id_poblacion_sexo" class="form-control">
                                
                            </select>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>    
                    </div><!-- /.box -->
                </form>                       
            </div>
        </div>
    </div>
</div>

<!-- ELIMINAR MODAL -->
<div class="modal fade" id="modalEliminarSexo" role="dialog">
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

    var agregarSexo2 = function(){
        $('#modalAgregarSexo').modal('show');
    }

    var agregarSexo = function(id_campana)
    {
        $.ajax({
            url: '<?php echo base_url() . 'index.php/tpoadminv1/campanas/campanas/dame_sexo_disponibles_id/' ?>'+ id_campana,
            data: {action: 'test'},
            //dataType:'JSON',
            
            success: function (response)
            {
                if(response == '')
                {
                    document.getElementById("error_alta_sexo").style.display = "inline";
                    document.getElementById('id_poblacion_sexo').style.display = "none";
                    document.getElementById("altaSexo").disabled = true;
                    $('#modalAgregarSexo').modal('show');
                }
                else
                {
                    document.getElementById("error_alta_sexo").style.display = "none";
                    $('#modalAgregarSexo').find('#id_poblacion_sexo').html(response);
                    $('#modalAgregarSexo').modal('show');
                }
            }
        });
    }


    var editarSexo = function(id_rel,nombre,id_campana)
    {
        $.ajax({
            //url: '<?php echo base_url() . 'index.php/tpoadminv1/capturista/facturas/get_factura/' ?>'+id,
            url: '<?php echo base_url() . 'index.php/tpoadminv1/campanas/campanas/dame_sexo_rel_id/' ?>'+id_rel+'/'+ id_campana,
            data: {action: 'test'},
            //dataType:'JSON',
            
            success: function (response) {
                //alert(response);

                if(response){

                    var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminarRelacion('+id_rel+')">Si</button>' +
                        '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
                    $('#modalEditarSexo').find('#footer_btns').html(html_btns);
                    $('#modalEditarSexo').find('#id_poblacion_sexo').html(response);
                    $('#modalEditarSexo').find('#id_rel_campana_sexo').val(id_rel);
                    $('#modalEditarSexo').modal('show');
                }
            }
        });
    }


    var eliminarSexo = function(id_rel,nombre){
        var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminarRelacion('+id_rel+')">Si</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
        $('#modalEliminarSexo').find('#footer_btns').html(html_btns);
        $('#modalEliminarSexo').find('#mensaje_modal').html('¿Desea eliminar este grupo de sexo: <b>' + nombre+ '</b>?');
        $('#modalEliminarSexo').modal('show');
    }

    var eliminarRelacion = function (id_rel){
        window.location.href = "eliminar_relacion_campana/" + id_rel+"/sexo";
    }

    $(function () {
        $('#example6').dataTable({
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




    
