<?php
/* 
INAI / ALTA CAMPANA AUDIOS
*/
?>


<?php
//utilizaremos este array para eliminar los espacios
$c_replace = array('\'', '"');

$sel_cat_liga = '';
for($z = 0; $z < sizeof($cat_tipo_liga); $z++)
{
    $sel_cat_liga .= '<option value="'.$cat_tipo_liga[$z]['id_tipo_liga'].'">' . $cat_tipo_liga[$z]['tipo_liga'] . '</option>';
}
?>

<style>
    .tooltip.top .tooltip-inner {
        background-color: #fff;
        border: 2px solid #ccc;
        color: black;
    }
    .tooltip.top .tooltip-arrow {
        border-top-color: #ccc;
    }
    .has-error {
        box-shadow: 0 0 1px red, 0 0 3px red !important;
        background: #FFEBEB !important;
    }
</style>

<!-- Mostramos los detalles de los grupos de lugares dados de alta -->
<div class="form-group table-responsive no-padding">
    <div class="box-header">
        <a class="btn btn-success" onclick="agregarImagen()"><i class="fa fa-plus-circle"></i> Agregar</a>
    </div><!-- /.box-header -->
    <table id="example8" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Tipo de Liga</th>
                <th>Nombre Imagen</th>
                <th>URL Imagen</th>
                <th>Archivo Imagen</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($imagenes))
            {
                for($z = 0; $z < sizeof($imagenes); $z++)
                {
                    echo '<tr>';
                        echo '<td>' . $imagenes[$z]['nombre_tipo_liga'] . '</td>';
                        echo '<td>' . $imagenes[$z]['nombre_campana_mimagen'] . '</td>';
                        echo '<td>' . $imagenes[$z]['url_imagen'] . '</td>';
                        echo '<td>' . $imagenes[$z]['file_imagen'] . '</td>';
                        echo "<td> <span class='btn-group btn btn-warning btn-sm' onclick=\"editarImagen(" .$imagenes[$z]['id_campana_mimagen'].",'".$imagenes[$z]['nombre_campana_mimagen']. "','".$imagenes[$z]['url_imagen']. "','".$imagenes[$z]['file_imagen']. "','".$imagenes[$z]['id_tipo_liga']. "')\"> <i class='fa fa-edit'></i></span></td>";
                        echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarImagen(" .$imagenes[$z]['id_campana_mimagen'].",'". str_replace($c_replace, "", $imagenes[$z]['nombre_campana_mimagen']) . "')\"> <i class='fa fa-close'></i></span></td>";
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div><!-- /.box-body -->

<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregarImagen" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Agregar imagen</h3>
            </div>
            <div class="modal-body">
            <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/guarda_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="atributo" value="imagenes" />
                    
                    <div class="box-body">
                        <div class="form-group">
                            <label>Tipo de liga*</label>
                            <select name="id_tipo_liga" id="id_tipo_liga" class="form-control">
                                <?php echo $sel_cat_liga; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nombre de la imagen*</label>
                            <input type="text" class="form-control" name="nombre_campana_mimagen" id="nombre_campana_mimagen" onInput="validarNombreAltaImagen()"/>
                        </div>
                        <div class="form-group">
                            <label>URL de la imagen</label>
                            <input type="text" class="form-control" name="url_imagen" id="url_imagen"/>
                        </div>

                        <!-- CODIGO PARA CARGAR ARCHIVOS -->
                        <div class="form-group">
                            <label class="custom-file-label"> Archivo de la imagen</label>
                        </div>
                        <div class="input-group">
                            <div id="file_by_save_agregar_imagen" class="input-group-btn" style="<?php if($control_update['file_by_save']) echo 'display:none;' ?>">
                                <button class="btn btn-success" type="button" onclick="ImagenTriggerClick('lanzarImagen')">Subir archivo</button>
                            </div>
                            <div id="file_see_agregar_imagen" class="input-group-btn" style="<?php if($control_update['file_see']) echo 'display:none;' ?>">
                                <button class="btn btn-info" type="button" onclick="ImagenTriggerClick('ver')" >Ver archivo</button>
                            </div>
                            <div id="file_load_agregar_imagen" class="input-group-btn" style="<?php if($control_update['file_load']) echo 'display:none;' ?>">
                                <button class="btn btn-success" type="button" ><i class="fa fa-refresh fa-spin"></i></button>
                            </div>
                            <input type="text" id="name_file_agregar_imagen" placeholder="Ning&uacute;n archivo seleccionado" value="<?php echo $registro['name_file_campana_imagen']; ?>"  class="form-control" readonly/>
                            <input type="hidden" id="name_file_campana_imagen" name="name_file_campana_imagen" value="<?php echo $registro['name_file_campana_imagen']; ?>" />
                            <input type="file" name="file_campana_imagen" id="file_campana_imagen" class="hide" accept=".jpg,.png,.pdf"/>
                            <div id="file_saved_agregar_imagen" class="input-group-btn" style="<?php if($control_update['file_saved']) echo 'display:none;' ?>">
                                <button class="btn btn-danger" type="button" onclick="ImagenTriggerClick('eliminarImagen')" >Eliminar archivo</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <p class="help-block" id="result_upload"><?php echo @$control_update['mensaje_file_imagenes']; ?> </p>
                        </div>

                        <div class="box-footer">
                            <button class="btn btn-primary" type="submit" id="btn_GuardarAgregarImagen">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cancelar('eliminar')">Cancelar</button>
                        </div>
                    </div><!-- /.box -->
                    <script type="text/javascript">
                        function validarNombreAltaImagen() {
                            document.getElementById("btn_GuardarAgregarImagen").disabled = !document.getElementById("nombre_campana_mimagen").value.length;
                        }
                        //if($("input[name='file_campana_imagen']")[0].files.length > 0){

                        $('input:file').change(function (){
                            //file_campana_imagen
                        //$("input[id='file_campana_imagen']").change(function (){
                            upload_file_imagen();
                        });
                    </script>
                </form>                      
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditarImagen" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Detalles imagen</h3>
            </div>
            <!--
            <div id="myDiv">Append here</div>
            -->
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/actualizar_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="id_campana_mimagen" id="id_campana_mimagen" value="" />
                    <input type="hidden" name="atributo" value="imagenes" />
                    <div class="box-body">
                        <div class="form-group">
                            <label>Tipo de liga*</label>
                            <select name="id_tipo_liga_edita" id="id_tipo_liga_edita" class="form-control">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nombre de la imagen*</label>
                            <input type="text" class="form-control" name="nombre_campana_mimagen_edita" id="nombre_campana_mimagen_edita" onInput="validarEditaImagen()"/>
                        </div>
                        <div class="form-group">
                            <label>URL de la imagen</label>
                            <input type="text" class="form-control" name="url_edita_imagen" id="url_edita_imagen"/>
                        </div>

                        <!-- CODIGO PARA CARGAR ARCHIVOS -->
                        <div class="form-group">
                            <label class="custom-file-label"> Archivo de la imagen</label>
                            <input type="text" id="name_file_imagen_edita" name="name_file_imagen_edita" placeholder="Ning&uacute;n archivo seleccionado" value="<?php echo $registro['name_file_campana_imagen_edita']; ?>"  class="form-control" readonly/>
                        </div>

                        <div class="input-group">
                            <div class="row">
                                <div id="file_by_save_edita_imagen" class="col-xs-4">
                                    <button class="btn btn-success" type="button" onclick="ImagenTriggerClickEdita('lanzarImagen')">Subir archivo</button>
                                </div>
                                <div id="file_load_edita_imagen" class="col-xs-2">
                                    <button class="btn btn-success" type="button" ><i class="fa fa-refresh fa-spin"></i></button>
                                </div>
                                <div id="file_see_edita_imagen" class="col-xs-5">
                                    <a class="btn btn-warning" id="imagen_ref" target="_blank"> Ver archivo </a>
                                </div>
                                <div id="file_saved_edita_imagen" class="col-xs-5">
                                    <button class="btn btn-success" type="button" onclick="ImagenTriggerClickEdita('eliminarImagen')" >Eliminar archivo</button>
                                </div>
                            </div>
                            <input type="hidden" id="name_file_campana_edita_imagen" name="name_file_campana_edita_imagen" value="<?php echo $registro['name_file_campana_imagen_edita']; ?>" />
                            <input type="file" name="file_campana_edita_imagen" id="file_campana_edita_imagen" class="hide" accept=".jpg,.png,.pdf"/>
                        </div>
                        <div class="form-group">
                            <p class="help-block" id="result_upload_edita2"></p>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" type="submit" id="btn_GuardarEditaImagen">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetear()">Cancelar</button>
                        </div>
                    </div>

                    <script type="text/javascript">
                        $('input:file').change(function (){
                            upload_file_edita_imagen();
                        });

                        function validarEditaImagen() {
                            document.getElementById("btn_Guardar").disabled = !document.getElementById("nombre_campana_mimagen_edita").value.length;
                        }
                    </script>
                </form>                       
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR -->
<div class="modal fade" id="modalEliminarImagen" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
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

    var agregarImagen = function()
    {
        document.getElementById("btn_GuardarAgregarImagen").disabled = true;
        $('#modalAgregarImagen').modal('show');
    }


    var cancelar = function(action)
    {
        if(action == 'eliminar') {
            $('#modalAgregarImagen').find('#nombre_campana_mimagen').val('');
            $('#modalAgregarImagen').find('#url_imagen').val('');

            eliminar_archivo_imagen();
        }
    }

    /*
    function resetear() {
        $('#name_file_audio').val('');
        $('#name_file_input').val('');
    }
    */

    var eliminarImagen = function(id_rel,nombre){
        var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminarRelacion('+id_rel+')">Si</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
        $('#modalEliminarImagen').find('#footer_btns').html(html_btns);
        $('#modalEliminarImagen').find('#mensaje_modal').html('¿Desea eliminar esta imagen: <b>' + nombre+ '</b>?');
        $('#modalEliminarImagen').modal('show');
    }

    var eliminarRelacion = function (id_rel){
        window.location.href = "eliminar_relacion_campana/" + id_rel+"/imagenes";
    }


/*
    $('input:file').change(function (){
        upload_file();
    }); 
*/
    

    $(function () {
        $('#example8').dataTable({
        'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, 'Todo']],    //Paginacion
        'bPaginate': true,
        'bLengthChange': true,
        'bFilter': true,
        'bSort': true,
        'bInfo': true,
        'bAutoWidth': false,
        'columnDefs': [
            { 'orderable': false, 'targets': [4,5] }
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


    var editarImagen = function(id_campana_mimagen,nombre_campana,url,file,id_tipo_liga)
    {
        $.ajax({
            url: '<?php echo base_url() . 'index.php/tpoadminv1/campanas/campanas/dame_tipo_liga_imagen/' ?>'+id_campana_mimagen,
            data: {action: 'test'},
            //dataType:'JSON',
            
            success: function (response)
            {
                if(response)
                {
                    var imagen_link = '<?php echo base_url() . 'data/campanas/imagenes/' ?>'+file

                    $('#modalEditarImagen').find('#id_tipo_liga_edita').html(response);
                    $('#modalEditarImagen').find('#id_campana_mimagen').val(id_campana_mimagen);
                    $('#modalEditarImagen').find('#nombre_campana_mimagen_edita').val(nombre_campana);
                    $('#modalEditarImagen').find('#url_edita_imagen').val(url);
                    $('#modalEditarImagen').find('#name_file_input2').val(file);
                    $("#imagen_ref").attr("href", imagen_link);
                    $('#modalEditarImagen').modal('show');

                    if(file != '')
                    {
                        document.getElementById("file_by_save_edita_imagen").style.display = "none";
                        document.getElementById("file_see_edita_imagen").style.display = "inline";
                        document.getElementById("file_load_edita_imagen").style.display = "none";
                        document.getElementById("file_saved_edita_imagen").style.display = "inline";
                        document.getElementById("result_upload_edita2").innerHTML = "Archivo cargado";
                        $('#modalEditarImagen').find('#name_file_campana_edita_imagen').val(file);
                        $('#modalEditarImagen').find('#name_file_imagen_edita').val(file);
                        $('#name_file_video').val(file);
                    }
                    else
                    {
                        document.getElementById("file_by_save_edita_imagen").style.display = "inline";
                        document.getElementById("file_see_edita_imagen").style.display = "none";
                        document.getElementById("file_load_edita_imagen").style.display = "none";
                        document.getElementById("file_saved_edita_imagen").style.display = "none";
                        document.getElementById("result_upload_edita2").innerHTML = "Formatos permitidos PDF, JPG y PNG.";
                    }

                    document.getElementById("btn_GuardarEditaImagen").disabled = false;

                    $('#modalEditarAudio').modal('show');
                }
            }
        });
    }

</script>