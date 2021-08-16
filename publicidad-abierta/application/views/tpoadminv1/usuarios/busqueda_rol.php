<?php
/* 
INAI / BUSQUEDA ROL
*/
?>

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
                <div class="box">
                    <div class="box-header">
                        <?php
                        if(sizeof($roles) < 2)
                        {
                            ?>
                            <a class="btn btn-success" href="alta_rol"><i class="fa fa-plus-circle"></i> Agregar</a>

                            <?php
                        }
                        ?>                        

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
                                    <th>Rol</th>
                                    <th>Descripci&oacute;n</th>
                                    <th>Estatus</th>
                                    <th style="width: 10px;"> </th>
                                    <th style="width: 10px;"> </th>
                                    <!--<th style="width: 10px;"> </th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($roles > 0)
                                {
                                    for($z = 0; $z < sizeof($roles); $z++)
                                    {
                                        echo '<tr>';
                                        //echo '<td>' . $roles[$z]['id_rol'] . '</td>';
                                        echo '<td>' . $roles[$z]['nombre_rol'] . '</td>';
                                        echo '<td>' . $roles[$z]['descripcion_rol'] . '</td>';
                                        echo '<td>' . $roles[$z]['active'] . '</td>';
                                        echo "<td> <span class='btn-group btn btn-info btn-sm' onclick=\"abrirModal('" . $roles[$z]['nombre_rol'] . "', '". $roles[$z]['descripcion_rol'] . "', '". $roles[$z]['active'] . "')\"> <i class='fa fa-search'></i></span></td>";
                                        echo '<td>' . anchor("tpoadminv1/usuarios/usuarios/edita_rol/".$roles[$z]['id_rol'], "<button class='btn btn-warning btn-sm' title='Editar'><i class=\"fa fa-edit\"></i></button></td>");
                                        //echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarModal(" . $roles[$z]['id_rol'] . ", '". $roles[$z]['nombre_rol'] . "')\"> <i class='fa fa-close'></i></span></td>";
                                    echo '</tr>';
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div>
</section><!-- /.content -->


<!-- Modal -->
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
        $('#myModalDelete').find('#mensaje_modal').html('¿Desea eliminar el rol ' + name+ '?');
        $('#myModalDelete').modal('show');
    }

    var eliminar = function (id){
        window.location.href = "eliminar_rol/" + id;
    }

    var abrirModal = function(name, descripcion, active){

    //alert(id)

    
    $('#myModal').find('#item_1').html(name);
    $('#myModal').find('#item_2').html(descripcion);
    $('#myModal').find('#item_3').html(active);
    $('#myModal').modal('show'); 
    
    }


</script>


<!-- Modal Details-->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Detalles</h3>
        </div>
        <div class="modal-body">
            <div id="loading_modal" ></div>
            <table id="table_modal" class="table form-horizontal">
                <tbody>
                    <tr class="form-group">
                        <td class="control-label col-sm-4"><b>Nombre Rol </b>
                        </td>
                        <td class="col-sm-8" id="item_1"></td>
                    </tr>
                    <tr class="form-group">
                        <td class="control-label col-sm-4"><b>Descripci&oacute;n </b>
                        </td>
                        <td class="col-sm-8" id="item_2"></td>
                    </tr>                        
                    <tr class="form-group">
                        <td class="control-label col-sm-4"><b>Estatus</b></td>
                        <td class="col-sm-8" id="item_3"></td>
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
