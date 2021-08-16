<?php

/* 
    INAI - SUJETOS
 */

?>


<!-- Estilos para el input que va a almacenar la variable URL -->
<style>
.sinborde {
  border: 0;
  width: 100%;

background-color:transparent;
}
</style>

<!-- Main content -->
<section class="content">
<?php
    if ($this->session->flashdata('error'))
    {
    ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-ban"></i> Error!</h4>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
    <?php
    }
    ?>

    <?php
    if ($this->session->flashdata('exito'))
    {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4>	<i class="icon fa fa-check"></i> Exito!</h4>
        <?php echo $this->session->flashdata('exito'); ?>
    </div>
    <?php
    }
    ?>

    <?php
    if ($this->session->flashdata('alerta'))
    {
    ?>
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4>	<i class="icon fa fa-check"></i> Exito!</h4>
        <?php echo $this->session->flashdata('alerta'); ?>
    </div>
    <?php
    }
    ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="box table-responsive no-padding">
                <div class="box-header">
                <a class="btn btn-success" href="alta_sujeto"><i class="fa fa-plus-circle"></i> Agregar</a>
                            
                    <div class="pull-right">
                        <a class="btn btn-default" <?php echo $print_onclick   ?>><i class="fa fa-print"></i> Imprimir</a>
                        <a class="btn btn-default" href="<?php echo base_url() . $path_file_csv ?>" download="<?php echo $name_file_csv ?>"><i class="fa fa-file"></i> Exportar a Excel</a>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <!--<th>#</th>-->
                                <th>Funci&oacute;n <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['funcion']?>"></i></th>
                                <th>Orden</th>
                                <th>Estado</th>
                                <th>Nombre <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['nombre']?>"></i></th>
                                <th>Siglas</th>
                                <th>Url Pagina</th>
                                <th>Activo <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['activo']?>"></i></th>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                for($z = 0; $z < sizeof($usuarios); $z++)
                                {
                                    echo '<tr>';
                                    //echo '<td>' . $usuarios[$z]['id_sujeto_obligado'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['funcion'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['orden'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['estado'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['nombre_sujeto_obligado'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['siglas_sujeto_obligado'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['url_sujeto_obligado'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['estatus'] . '</td>';
                                    echo "<td> <span class='btn-group btn btn-info btn-sm' onclick=\"abrirModal(" . $usuarios[$z]['id_sujeto_obligado'] . ")\"> <i class='fa fa-search'></i></span></td>";
                                    echo '<td>' . anchor("tpoadminv1/sujetos/sujetos/edita_sujeto/".$usuarios[$z]['id_sujeto_obligado'], "<button class='btn btn-warning btn-sm' title='Editar'><i class=\"fa fa-edit\"></i></button></td>");
                                    echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarModal(" . $usuarios[$z]['id_sujeto_obligado'] . ", '". $usuarios[$z]['nombre_sujeto_obligado'] . "')\"> <i class='fa fa-close'></i></span></td>";
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
</section><!-- /.content -->


<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Sujetos Obligados</h4>
                </div>
                <div class="modal-body">
                <table class="table form-horizontal">
                    <tbody>
                        <tr class="form-group">
                           <td class="control-label col-sm-3">Función* 
                                <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['activo']?>"></i>
                            </td>
                            <td class="col-sm-8" id="item_1"></td>
                        </tr>
                        <tr class="form-group">
                            <td class="control-label col-sm-3">Orden *</td>
                            <td class="col-sm-8" id="item_2"></td>
                        </tr>
                        <tr class="form-group">
                            <td class="control-label col-sm-3">Estado *</td>
                            <td class="col-sm-8" id="item_3"></td>
                        </tr>
                        <tr class="form-group">
                            <td class="control-label col-sm-3">Nombre *
                                <a href="javascript:;" class="xcrud-tooltip xcrud-button-link" title="Son sujetos obligados a transparentar y permitir el acceso a su información y proteger los datos personales que obren en su poder: cualquier autoridad, entidad, órgano y organismo de los Poderes Ejecutivo, Legislativo y Judicial, órganos autónomos, partidos políticos, fideicomisos y fondos públicos, así como cualquier persona física, moral o sindicato que reciba y ejerza recursos públicos, así como cualquier persona física, moral o sindicato que reciba y ejerza recursos públicos o realice actos de autoridad en los ámbitos federal, de las Entidades Federativas y municipal.">
                                    <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['nombre']?>"></i>
                                </a>
                            </td>
                            <td class="col-sm-8" id="item_4"></td>
                        </tr>
                        <tr class="form-group">
                            <td class="control-label col-sm-3">Siglas</td>
                            <td class="col-sm-8" id="item_5"></td>
                        </tr>
                        <tr class="form-group">
                            <td class="control-label col-sm-3">URL de página</td>
                            <td class="col-sm-9">
                                <a target="_blank"  id="item_7" ><input type="text" maxlength="120" class="sinborde" id="item_6" disabled></a>
                            </td>
                        </tr>
                        <tr class="form-group">
                            <td class="control-label col-sm-3">Activo
                                <a href="javascript:;" class="xcrud-tooltip xcrud-button-link" title="Indica el estatus del usuario: a=Activo, i=Inactivo.">
                                    <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['activo']?>"></i>
                                </a>
                            </td>
                            <td class="col-sm-8" id="item_8"></td>
                        </tr>
                    </tbody>
                </table>        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>



<!-- Modal Eliminar -->
<div class="modal fade" id="myModalDelete" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Eliminar registro</h3>
        </div>
        <div class="modal-body">
            <div id="mensaje_modal">
                ¿Desea eliminar el registro?
            </div>
        </div>
        <div class="modal-footer" id="footer_btns">
            
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    var eliminarModal = function(id, name){
        var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminar('+id+')">Si</button>' +
                   '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
        $('#myModalDelete').find('#footer_btns').html(html_btns);
        $('#myModalDelete').find('#mensaje_modal').html('¿Desea eliminar al sujeto<b> ' + name+ '</b>?');
        $('#myModalDelete').modal('show');
    }

    var eliminar = function (id){
        window.location.href = "eliminar_sujeto/" + id;
    }

    var abrirModal = function(id)
    {
        //utlizar cuando haya más campos
        $.ajax({
            url: '<?php echo base_url() . 'index.php/tpoadminv1/sujetos/sujetos/get_sujeto/' ?>'+id,
            data: {action: 'test'},
            dataType:'JSON',
            success: function (response)
            {
                if(response)
                {
                    $('#myModal').find('#item_1').html(response.atribucion);
                    $('#myModal').find('#item_2').html(response.orden);
                    $('#myModal').find('#item_3').html(response.estado);
                    $('#myModal').find('#item_4').html(response.nombre);
                    $('#myModal').find('#item_5').html(response.siglas);
                    $('#myModal').find('#item_6').val(response.url)
                    $('#myModal').find('#item_7').attr('href', response.url);
                    $('#myModal').find('#item_8').html(response.estatus);
                    $('#myModal').modal('show'); 
                }
            }
        });
    }
    
</script>