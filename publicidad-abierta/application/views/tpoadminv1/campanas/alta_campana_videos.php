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
        <a class="btn btn-success" onclick="agregarVideo()"><i class="fa fa-plus-circle"></i> Agregar</a>
    </div><!-- /.box-header -->
    <table id="example9" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Tipo de liga</th>
                <th>Nombre vídeo</th>
                <th>URL vídeo</th>
                <th>Archivo vídeo</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($videos))
            {
                for($z = 0; $z < sizeof($videos); $z++)
                {
                    echo '<tr>';
                        echo '<td>' . $videos[$z]['nombre_tipo_liga'] . '</td>';
                        echo '<td>' . $videos[$z]['nombre_campana_mvideo'] . '</td>';
                        echo '<td>' . $videos[$z]['url_video'] . '</td>';
                        echo '<td>' . $videos[$z]['file_video'] . '</td>';
                        echo "<td> <span class='btn-group btn btn-warning btn-sm' onclick=\"editarVideo(" .$videos[$z]['id_campana_mvideo'].",'".$videos[$z]['nombre_campana_mvideo']. "','".$videos[$z]['url_video']. "','".$videos[$z]['file_video']. "','".$videos[$z]['id_tipo_liga']. "')\"> <i class='fa fa-edit'></i></span></td>";
                        echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarVideos(" .$videos[$z]['id_campana_mvideo'].",'". str_replace($c_replace, "", $videos[$z]['nombre_campana_mvideo']) . "')\"> <i class='fa fa-close'></i></span></td>";
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div><!-- /.box-body -->

<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregarVideo" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Agregar video</h3>
            </div>
            <div class="modal-body">
            <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/guarda_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="atributo" value="videos" />
                    <div class="box-body">
                        <div class="form-group">
                            <label>Tipo de liga*</label>
                            <select name="id_tipo_liga" id="id_tipo_liga" class="form-control">
                                <?php echo $sel_cat_liga; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nombre del vídeo*</label>
                            <input type="text" class="form-control" name="nombre_campana_mvideo" id="nombre_campana_mvideo" onInput="validarInput3()"/>
                        </div>
                        <div class="form-group">
                            <label>URL del vídeo</label>
                            <input type="text" class="form-control" name="url_video" id="url_video"/>
                        </div>

                        <!-- CODIGO PARA CARGAR ARCHIVOS -->
                        <div class="form-group">
                            <label class="custom-file-label"> Archivo del vídeo</label>
                        </div>
                        <div class="input-group">
                            <div id="file_by_save" class="input-group-btn" style="<?php if($control_update['file_by_save']) echo 'display:none;' ?>">
                                <button class="btn btn-success" type="button" onclick="VideoTriggerClick('lanzarVideo')">Subir archivo</button>
                            </div>
                            <div id="file_see" class="input-group-btn" style="<?php if($control_update['file_see']) echo 'display:none;' ?>">
                                <button class="btn btn-info" type="button" onclick="VideoTriggerClick('ver')" >Ver archivo</button>
                            </div>
                            <div id="file_load" class="input-group-btn" style="<?php if($control_update['file_load']) echo 'display:none;' ?>">
                                <button class="btn btn-success" type="button" ><i class="fa fa-refresh fa-spin"></i></button>
                            </div>
                            <input type="text" id="name_file_agregar_video" placeholder="Ning&uacute;n archivo seleccionado" value="<?php echo $registro['name_file_campana_video']; ?>"  class="form-control" readonly/>
                            <input type="hidden" id="name_file_campana_video" name="name_file_campana_video" value="<?php echo $registro['name_file_campana_video']; ?>" />
                            <input type="file" name="file_campana_video" id="file_campana_video" class="hide" accept=".avi,.mpeg,.mov,.wmv"/>
                            <div id="file_saved" class="input-group-btn" style="<?php if($control_update['file_saved']) echo 'display:none;' ?>">
                                <button class="btn btn-danger" type="button" onclick="VideoTriggerClick('eliminarVideo')" >Eliminar archivo</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <p class="help-block" id="result_upload"><?php echo @$control_update['mensaje_file_videos']; ?> </p>
                        </div>

                        <div class="box-footer">
                            <button class="btn btn-primary" type="submit" id="btn_Guardar">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetear_alta()">Cancelar</button>
                        </div>
                    </div><!-- /.box -->
                    <script type="text/javascript">
                        $('input:file').change(function (){
                            upload_file_video();
                        });

                        function validarInput3() {
                            document.getElementById("btn_Guardar").disabled = !document.getElementById("nombre_campana_mvideo").value.length;
                        }
                    </script>
                </form>                      
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditarVideo" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Detalles video</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/actualizar_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="id_campana_mvideo" id="id_campana_mvideo" value="" />
                    <input type="hidden" name="atributo" value="videos" />
                    <div class="box-body">
                        <div class="form-group">
                            <label>Tipo de liga*</label>
                            <select name="id_tipo_liga_edita" id="id_tipo_liga_edita" class="form-control">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nombre del vídeo*</label>
                            <input type="text" class="form-control" name="nombre_campana_mvideo_edita" id="nombre_campana_mvideo_edita" onInput="validarInput4()"/>
                        </div>
                        <div class="form-group">
                            <label>URL del video</label>
                            <input type="text" class="form-control" name="url_video" id="url_video"/>
                        </div>

                        <!-- CODIGO PARA CARGAR ARCHIVOS -->
                        <div class="form-group">
                            <label class="custom-file-label"> Archivo del vídeo</label>
                            <input type="text" id="name_file_video" placeholder="Ning&uacute;n archivo seleccionado" value="<?php echo $registro['name_file_campana_video_edita']; ?>"  class="form-control" readonly/>
                        </div>

                        <div class="input-group">
                            <div class="row">
                                <div id="file_by_save_video_edita" class="col-xs-4">
                                    <button class="btn btn-success" type="button" onclick="VideoTriggerClickEdita('lanzarVideo')">Subir archivo</button>
                                </div>
                                <div id="file_load_video_edita" class="col-xs-2">
                                    <button class="btn btn-success" type="button" ><i class="fa fa-refresh fa-spin"></i></button>
                                </div>
                                <div id="file_see_video_edita" class="col-xs-5">
                                    <a class="btn btn-warning" id="video_ref" target="_blank"> Ver archivo </a>
                                </div>
                                <div id="file_saved_video_edita" class="col-xs-5">
                                    <button class="btn btn-success" type="button" onclick="VideoTriggerClickEdita('eliminarVideo')" >Eliminar archivo</button>
                                </div>
                            </div>
                            <input type="hidden" id="name_file_campana_video_edita" name="name_file_campana_video_edita" value="<?php echo $registro['name_file_campana_video_edita']; ?>" />
                            <input type="file" name="file_campana_video_edita" id="file_campana_video_edita" class="hide" accept=".avi,.mpeg,.mov,.wmv"/>
                        </div>
                        <div class="form-group">
                            <p class="help-block" id="result_upload_edita2"></p>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" type="submit" id="btn_GuardarEdita">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetear_edita()">Cancelar</button>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $('input:file').change(function (){
                            upload_file_edita_video();
                        });

                        function validarInput4() {
                            document.getElementById("btn_GuardarEdita").disabled = !document.getElementById("nombre_campana_mvideo_edita").value.length;
                        }
                    </script>
                </form>                       
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR -->
<div class="modal fade" id="modalEliminarVideo" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
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

    var agregarVideo = function()
    {
        document.getElementById("btn_Guardar").disabled = true;
        $('#modalAgregarVideo').modal('show');
    }

    function resetear_alta()
    {
        $('#nombre_campana_mvideo').val('');
        $('#url_video').val('');
        eliminar_archivo_video();
    }

    var eliminarVideos = function(id_rel,nombre){
        var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminarRelacion('+id_rel+')">Si</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
        $('#modalEliminarVideo').find('#footer_btns').html(html_btns);
        $('#modalEliminarVideo').find('#mensaje_modal').html('¿Desea eliminar este video: <b>' + nombre+ '</b>?');
        $('#modalEliminarVideo').modal('show');
    }

    var eliminarRelacion = function (id_rel){
        window.location.href = "eliminar_relacion_campana/" + id_rel+"/videos";
    }

    function resetear_edita() {
        $('#name_file_video').val('');
        $('#name_file_input').val('');
    }

    $(function () {
        $('#example9').dataTable({
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

    var editarVideo = function(id_campana_mvideo,nombre_campana,url,file,id_tipo_liga)
    {
        $.ajax({
            url: '<?php echo base_url() . 'index.php/tpoadminv1/campanas/campanas/dame_tipo_liga_video_rel_id/' ?>'+id_campana_mvideo,
            data: {action: 'test'},
            //dataType:'JSON',
            
            success: function (response)
            {
                if(response)
                {
                    var video_link = '<?php echo base_url() . 'data/campanas/videos/' ?>'+file

                    $('#modalEditarVideo').find('#id_tipo_liga_edita').html(response);
                    $('#modalEditarVideo').find('#id_campana_mvideo').val(id_campana_mvideo);
                    $('#modalEditarVideo').find('#nombre_campana_mvideo_edita').val(nombre_campana);
                    $('#modalEditarVideo').find('#name_file_input2').val(file);
                    $("#video_ref").attr("href", video_link);
                    $('#modalEditarVideo').modal('show');

                    if(file != '')
                    {
                        document.getElementById("file_by_save_video_edita").style.display = "none";
                        document.getElementById("file_see_video_edita").style.display = "inline";
                        document.getElementById("file_load_video_edita").style.display = "none";
                        document.getElementById("file_saved_video_edita").style.display = "inline";
                        document.getElementById("result_upload_edita2").innerHTML = "Archivo cargado";
                        $('#modalEditarVideo').find('#name_file_campana_video_edita').val(file);
                        $('#name_file_video').val(file);
                    }
                    else
                    {
                        document.getElementById("file_by_save_video_edita").style.display = "inline";
                        document.getElementById("file_see_video_edita").style.display = "none";
                        document.getElementById("file_load_video_edita").style.display = "none";
                        document.getElementById("file_saved_video_edita").style.display = "none";
                        document.getElementById("result_upload_edita2").innerHTML = "Formatos permitidos AVI, MPEG, MOV y WMV.";
                    }

                    document.getElementById("btn_GuardarEdita").disabled = false;

                    $('#modalEditarVideo').modal('show');
                }
            }
        });
    }
</script>