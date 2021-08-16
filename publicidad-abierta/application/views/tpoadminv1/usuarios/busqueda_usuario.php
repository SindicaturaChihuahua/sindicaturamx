<?php
/* 
INAI
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
                        <a class="btn btn-success" href="alta_usuario"><i class="fa fa-plus-circle"></i> Agregar</a>
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
                                    <th>Usuario</th>
                                    <th>Correo Electr&oacute;nico</th>
                                    <th>Nombre(s)</th>
                                    <th>Apellidos</th>
                                    <th>Sujeto Obligado</th>
                                    <th>Rol</th>
                                    <th>Estatus</th>
                                    <th> </th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for($z = 0; $z < sizeof($usuarios); $z++)
                                {
                                    echo '<tr>';
                                    //echo '<td>' . $usuarios[$z]['id_user'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['username'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['email'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['fname'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['lname'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['sujeto_nombre'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['rol_user'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['active'] . '</td>';
                                    if($this->session->userdata('usuario_nombre') == $usuarios[$z]['username']){
                                        // no se debe permitir la autoedición de un usuario logeado
                                        echo '<td></td>';
                                        echo '<td></td>';
                                    }else{
                                        echo '<td>' . anchor("tpoadminv1/usuarios/usuarios/edita_usuario/".$usuarios[$z]['id_user'], "<button class='btn btn-warning btn-sm' title='Editar'><i class=\"fa fa-edit\"></i></button></td>");
                                        echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarModal(" . $usuarios[$z]['id_user'] . ", '". $usuarios[$z]['username'] . "')\"> <i class='fa fa-close'></i></span></td>";    
                                    }
                                    echo '</tr>';
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

<!-- Modal Details-->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Detalle Usuario</h3>
        </div>
        <div class="modal-body">
            <div id="loading_modal" ></div>
            <table id="table_modal" class="table form-horizontal">
                <tbody>
                    <tr class="form-group">
                        <td class="control-label col-sm-4"><b>Nombre Rol* </b>
                        </td>
                        <td class="col-sm-8" id="item_1"></td>
                    </tr>                        
                    <tr class="form-group">
                        <td class="control-label col-sm-4"><b>Estatus*</b></td>
                        <td class="col-sm-8" id="item_2"></td>
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
    
    var eliminarModal = function(id, username){
        var html_btns = '<button type="button" class="btn btn-danger" onclick="eliminar('+id+')">Si</button>' +
                   '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
        $('#myModalDelete').find('#footer_btns').html(html_btns);
        $('#myModalDelete').find('#mensaje_modal').html('¿Desea eliminar al usuario <b>' + username+ '</b>?');
        $('#myModalDelete').modal('show');
    }

    var eliminar = function (id){
        window.location.href = "eliminar_usuario/" + id;
    }

    var abrirModal = function(id){

        //alert(id)

        /*
        $('#myModal').find('#item_1').html(name);
        $('#myModal').find('#item_2').html(active);
        $('#myModal').modal('show'); 
        */
        //utlizar cuando haya más campos
        $.ajax({
            url: '<?php echo base_url() . 'index.php/tpoadminv1/usuarios/usuarios/get_usuario/' ?>'+id,
            data: {action: 'test'},
            dataType:'JSON',
            /*
            beforeSend: function () {
                //Loading('Buscando');
                $('#myModal').find('#loading_modal').html('<span><i class="fa fa-spinner"><i> Cargando...</span>'); 
            },
            complete: function () {
                //Loading();
                //$('#myModal').find('#loading_modal').html(''); 
            },
            error:function () {
                $('#myModal').modal('hide');
            },
            */
            success: function (response) {
                //alert(response)

                if(response){
                    //alert(username)
                    $('#myModal').find('#item_1').html(response.username);
                    $('#myModal').find('#item_2').html(response.fname);
                    $('#myModal').modal('show'); 
                }
            }
        });
    }
    
</script>