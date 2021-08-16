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
        <a class="btn btn-success" onclick="agregarAudio()"><i class="fa fa-plus-circle"></i> Agregar</a>
    </div><!-- /.box-header -->
    <table id="example7" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Tipo de Liga</th>
                <th>Nombre Audio</th>
                <th>URL Audio</th>
                <th>Archivo Audio</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($audios))
            {
                for($z = 0; $z < sizeof($audios); $z++)
                {
                    echo '<tr>';
                        echo '<td>' . $audios[$z]['nombre_tipo_liga'] . '</td>';
                        echo '<td>' . $audios[$z]['nombre_campana_maudio'] . '</td>';
                        echo '<td>' . $audios[$z]['url_audio'] . '</td>';
                        echo '<td>' . $audios[$z]['file_audio'] . '</td>';
                        echo "<td> <span class='btn-group btn btn-warning btn-sm' onclick=\"editarAudio(" .$audios[$z]['id_campana_maudio'].",'".$audios[$z]['nombre_campana_maudio']. "','".$audios[$z]['url_audio']. "','".$audios[$z]['file_audio']. "','".$audios[$z]['id_tipo_liga']. "')\"> <i class='fa fa-edit'></i></span></td>";
                        echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarAudio(" .$audios[$z]['id_campana_maudio'].",'". str_replace($c_replace, "", $audios[$z]['nombre_campana_maudio']) . "')\"> <i class='fa fa-close'></i></span></td>";
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div><!-- /.box-body -->

<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregarAudio" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Agregar audio</h3>
            </div>
            <div class="modal-body">
            <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/guarda_valor_camp">
                <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                <input type="hidden" name="atributo" value="audios" />
                <div class="box-body">
                    <div class="form-group">
                        <label>Tipo de liga*</label>
                        <select name="id_tipo_liga" id="id_tipo_liga" class="form-control">
                            <?php echo $sel_cat_liga; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nombre del audio*</label>
                        <input type="text" class="form-control" name="nombre_campana_maudio" id="nombre_campana_maudio" onInput="validarInput3()"/>
                    </div>
                    <div class="form-group">
                        <label>URL del audio</label>
                        <input type="text" class="form-control" name="url_audio" id="url_audio"/>
                    </div>
                        
                    <!-- CODIGO PARA CARGAR ARCHIVOS -->
                    <div class="form-group">
                        <label class="custom-file-label"> Archivo del audio</label>
                    </div>
                    <div class="input-group">
                        <div id="file_by_save_audio_agrega" class="input-group-btn" style="<?php if($control_update['file_by_save']) echo 'display:none;' ?>">
                            <button class="btn btn-success" type="button" onclick="triggerClick('lanzar')">Subir archivo</button>
                        </div>
                        <div id="file_see_audio_agrega" class="input-group-btn" style="<?php if($control_update['file_see']) echo 'display:none;' ?>">
                            <button class="btn btn-info" type="button" onclick="triggerClick('ver')" >Ver archivo</button>
                        </div>
                        <div id="file_load_audio_agrega" class="input-group-btn" style="<?php if($control_update['file_load']) echo 'display:none;' ?>">
                            <button class="btn btn-success" type="button" ><i class="fa fa-refresh fa-spin"></i></button>
                        </div>
                        <input type="text" id="name_file_input" placeholder="Ning&uacute;n archivo seleccionado" value="<?php echo $registro['name_file_audio_edita']; ?>"  class="form-control" />
                        <input type="hidden" id="name_file_programa_anual" name="name_file_programa_anual" value="<?php echo $registro['name_file_audio_edita']; ?>" />
                        <input type="file" name="file_programa_anual" id="file_programa_anual" class="hide" accept=".mp3,.acc,.wma,.wav"/>
                        <div id="file_saved_audio_agrega" class="input-group-btn" style="<?php if($control_update['file_saved']) echo 'display:none;' ?>">
                            <button class="btn btn-danger" type="button" onclick="triggerClick('eliminar')" >Eliminar archivo</button>
                         </div>
                    </div>
                    <div class="form-group">
                        <p class="help-block" id="result_upload_agrega"><?php echo @$control_update['mensaje_file_audios']; ?> </p>
                    </div>

                    <div class="box-footer">
                        <button class="btn btn-primary" type="submit" id="btn_Guardar">Guardar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cancelar('eliminar')" >Cancelar</button>
                    </div>
                </div><!-- /.box -->
                <script type="text/javascript">
                    $('input:file').change(function (){
                        upload_file();
                    });

                    function validarInput3() {
                        document.getElementById("btn_Guardar").disabled = !document.getElementById("nombre_campana_maudio").value.length;
                    }
                </script>
                </form>                      
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditarAudio" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Detalles audio</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/campanas/campanas/actualizar_valor_camp">
                    <input type="hidden" name="id_campana_aviso" value="<?php echo $id_campana_aviso ?>" />
                    <input type="hidden" name="id_campana_maudio" id="id_campana_maudio" value="" />
                    <input type="hidden" name="atributo" value="audios" />
                    <div class="box-body">
                        <div class="form-group">
                            <label>Tipo de liga*</label>
                            <select name="id_tipo_liga_edita" id="id_tipo_liga_edita" class="form-control">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nombre del audio*</label>
                            <input type="text" class="form-control" name="nombre_campana_maudio_edita" id="nombre_campana_maudio_edita" onInput="validarNombreAudioEdita()"/>
                        </div>
                        <div class="form-group">
                            <label>URL del audio</label>
                            <input type="text" class="form-control" name="url_audio_edita" id="url_audio_edita"/>
                        </div>

                        <!-- CODIGO PARA CARGAR ARCHIVOS -->
                        <div class="form-group">
                            <label class="custom-file-label"> Archivo del audio</label>
                            <input type="text" id="name_file_audio_edita" name="name_file_audio_edita" placeholder="Ning&uacute;n archivo seleccionado" value="<?php echo $registro['name_file_audio_edita']; ?>"  class="form-control" />
                        </div>

                        <div class="input-group">
                            <div class="row">
                                <div id="file_by_save_audio_edita" class="col-xs-4">
                                    <button class="btn btn-success" type="button" onclick="triggerClickEdita('lanzar')">Subir archivo</button>
                                </div>            
                                <div id="file_load_audio_edita" class="col-xs-2">
                                    <button class="btn btn-success" type="button" ><i class="fa fa-refresh fa-spin"></i></button>
                                </div>
                                <div id="file_see_audio_edita" class="col-xs-5">
                                    <a class="btn btn-warning" id="audio_ref" target="_blank"> Ver archivo </a>
                                </div>
                                <div id="file_saved_audio_edita" class="col-xs-5">
                                    <button class="btn btn-success" type="button" onclick="triggerClickEdita('eliminar')" >Eliminar archivo</button>
                                </div>
                            </div>
                            <input type="hidden" id="campana_file_audio_edita" name="campana_file_audio_edita" value="<?php echo $registro['campana_file_audio_edita']; ?>" />
                            <input type="file" name="file_programa_anual_edita" id="file_programa_anual_edita" class="hide" accept=".acc,.wma,.wav"/>
                        </div>
                        <div class="form-group">
                            <p class="help-block" id="result_upload_edita2"></p>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" type="submit" id="btn_GuardarAudioEdita">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetear()">Cancelar</button>
                        </div>
                    </div>
                    <script type="text/javascript">

                        function validarNombreAudioEdita() {
                            document.getElementById("btn_GuardarAudioEdita").disabled = !document.getElementById("nombre_campana_maudio_edita").value.length;
                        }
                        
                        $('input:file').change(function (){
                            upload_file_edita_audio();
                            //upload_file_video('audio');
                        });
                        
                        var upload_file_edita_audio = function ()
                        {
                            if($("input[name='file_programa_anual_edita']")[0].files.length > 0){
                                $('#name_file_input').val($("input[name='file_programa_anual_edita']")[0].files[0].name );
                                $('#file_load_audio_edita').show();
                                $('#file_by_save_audio_edita').hide();
                                var file_data = $('#file_programa_anual_edita').prop('files')[0];   
                                var form_data = new FormData();                  
                                form_data.append('file_programa_anual_edita', file_data);                           
                                $.ajax({
                                    url: '<?php echo base_url() . 'index.php/tpoadminv1/campanas/campanas/upload_file_edita_audio' ?>', // point to server-side PHP script 
                                    dataType: 'text',  // what to expect back from the PHP script, if anything
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: form_data,                         
                                    type: 'post',
                                    complete: function(){
                                    },
                                    success: function(response)
                                    {
                                        if (response.match('Error')) 
                                        {
                                            //alert('encontrado');
                                            $('#file_by_save_audio_edita').show();
                                            $('#file_saved_audio_edita').hide();
                                            $('#file_load_audio_edita').hide();
                                            $('#file_see_audio_edita').hide();
                                            $('#result_upload_audio_edita').html('<span class="text-warning">El tamaño máximo para la carga de archivos es de 20MB.</span>');
                                            $('#name_file_input').val('');
                                            $("input[name='campana_file_audio_edita']").val(null);
                                            $('#name_file_audio_edita').val('');
                                        }

                                        if(response != ''){
                                            var data = $.parseJSON(response);
                                            if(data[0] == 'exito')
                                            {
                                                document.getElementById("file_by_save_audio_edita").style.display = "none";
                                                document.getElementById("file_see_audio_edita").style.display = "none";
                                                document.getElementById("file_load_audio_edita").style.display = "none";
                                                document.getElementById("file_saved_audio_edita").style.display = "inline";
                                                document.getElementById("result_upload_edita2").innerHTML = "Archivo cargado correctamente";
                                                $('#name_file_audio_edita').val(data[1]);
                                                $('#campana_file_audio_edita').val(data[1]);    //para el input hidden
                                            }
                                            else
                                            {
                                                $('#file_by_save_audio_edita').show();
                                                $('#file_saved_audio_edita').hide();
                                                $('#file_load_audio_edita').hide();
                                                $('#file_see_audio_edita').hide();
                                                $('#result_upload_edita').html(data[1]);
                                                $('#name_file_input_edita').val('');
                                                $("input[name='file_campana_audio_edita']").val(null);
                                                $('#campana_file_audio_edita').val('');
                                            }
                                        }
                                    },
                                    error: function(){
                                        document.getElementById("file_by_save_audio_edita").style.display = "inline";
                                        document.getElementById("file_see_audio_edita").style.display = "none";
                                        document.getElementById("file_load_audio_edita").style.display = "none";
                                        document.getElementById("result_upload_edita2").innerHTML = "Error";
                                        $('#name_file_audio').val(data[1]);
                                    }
                                });
                            }
                        }
                    </script>
                </form>                       
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR -->
<div class="modal fade" id="modalEliminarAudio" role="dialog" data-backdrop="static" data-keyboard="false" href="#">
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

    var agregarAudio = function()
    {
        document.getElementById("btn_Guardar").disabled = true;
        $('#modalAgregarAudio').modal('show');
    }

    var cancelar = function(action)
    {
        if(action == 'eliminar') {
            $('#modalAgregarAudio').find('#nombre_campana_maudio').val('');
            $('#modalAgregarAudio').find('#url_audio').val('');

            eliminar_archivo();
        }
    }

    function resetear() {
        $('#name_file_audio').val('');
        $('#name_file_input').val('');
    }

    $('input:file').change(function (){
        upload_file();
    }); 

    $(function () {
        $('#example7').dataTable({
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

    var editarAudio = function(id_campana_maudio,nombre_campana,url,file,id_tipo_liga)
    {
        $.ajax({
            url: '<?php echo base_url() . 'index.php/tpoadminv1/campanas/campanas/dame_tipo_liga_audio/' ?>'+id_campana_maudio,
            data: {action: 'test'},
            //dataType:'JSON',
            
            success: function (response)
            {
                if(response)
                {
                    var audio_link = '<?php echo base_url() . 'data/campanas/audios/' ?>'+file

                    $('#modalEditarAudio').find('#id_tipo_liga_edita').html(response);
                    $('#modalEditarAudio').find('#id_campana_maudio').val(id_campana_maudio);
                    $('#modalEditarAudio').find('#nombre_campana_maudio_edita').val(nombre_campana);
                    $('#modalEditarAudio').find('#url_audio_edita').val(url);
                    $('#modalEditarAudio').find('#name_file_input2').val(file);
                    $("#audio_ref").attr("href", audio_link);
                    $('#modalEditarAudio').modal('show');

                    if(file != '')
                    {
                        document.getElementById("file_by_save_audio_edita").style.display = "none";
                        document.getElementById("file_see_audio_edita").style.display = "inline";
                        document.getElementById("file_load_audio_edita").style.display = "none";
                        document.getElementById("file_saved_audio_edita").style.display = "inline";
                        document.getElementById("result_upload_edita2").innerHTML = "Archivo cargado";
                        $('#modalEditarAudio').find('#name_file_audio_edita').val(file);
                        $('#modalEditarAudio').find('#campana_file_audio_edita').val(file);
                        $('#name_file_video').val(file);
                    }
                    else
                    {
                        document.getElementById("file_by_save_audio_edita").style.display = "inline";
                        document.getElementById("file_see_audio_edita").style.display = "none";
                        document.getElementById("file_load_audio_edita").style.display = "none";
                        document.getElementById("file_saved_audio_edita").style.display = "none";
                        document.getElementById("result_upload_edita2").innerHTML = "Formatos permitidos MP3, ACC, WMA y WAV.";
                    }

                    document.getElementById("btn_GuardarEdita").disabled = false;

                    $('#modalEditarAudio').modal('show');
                }
            }
        });
    }

    var eliminarAudio = function(id_rel,nombre){
        var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminarRelacion('+id_rel+')">Si</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
        $('#modalEliminarAudio').find('#footer_btns').html(html_btns);
        $('#modalEliminarAudio').find('#mensaje_modal').html('¿Desea eliminar este audio: <b>' + nombre+ '</b>?');
        $('#modalEliminarAudio').modal('show');
    }

    var eliminarRelacion = function (id_rel){
        window.location.href = "eliminar_relacion_campana/" + id_rel+"/audios";
    }
    
</script>