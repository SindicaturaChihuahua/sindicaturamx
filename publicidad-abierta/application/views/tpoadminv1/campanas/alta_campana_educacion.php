<?php

/* 
INAI / ALTA CAMPANA EDAD
*/
?>

<?php

$sel_cat_niveles_educativos = '<option value="0">- Selecciona -</option>';
for($z = 0; $z < sizeof($cat_niveles_educativos); $z++)
{
    $sel_cat_niveles_educativos .= '<option value="'.$cat_niveles_educativos[$z]['id_poblacion_nivel_educativo'].'">' . $cat_niveles_educativos[$z]['nombre_poblacion_nivel_educativo'] . '</option>';
}
?>

<!-- Main content -->
<div class="box-body">
    <div class="form-group">
        <label>Educaci&oacute;n</label>
        <select name="id_campana_tipo" class="form-control">
            <?php echo $sel_cat_niveles_educativos; ?>
        </select>
    </div>
    <div class="box-footer">
        <button class="btn btn-primary" type="submit">Guardar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
    </div>    
</div><!-- /.box -->
                
        
<!-- Modal -->
<div class="modal fade" id="modalAgregar" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Agregar grupo de edad</h3>
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



<div class="modal fade" id="modalDelete" role="dialog">
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
    var eliminarEdadModal = function(id, id2){
        var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminar123('+id+', '+ id2 +')">Si</button>' +
                   '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
        $('#modalDelete').find('#footer_btns').html(html_btns);
        $('#modalDelete').find('#mensaje_modal').html('¿Desea eliminar este grupo de edad <b>' + name+ '</b>?');
        $('#modalDelete').modal('show');
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
</script>






    
