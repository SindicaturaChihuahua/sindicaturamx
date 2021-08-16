<?php

/* 
INAI / ALTA CAMPANA 
*/
?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box table-responsive no-padding">
                <div class="box-header">
                    <a class="btn btn-success" href="alta_campanas_avisos"><i class="fa fa-plus-circle"></i> Agregar</a>
                    <div class="pull-right">
                        <!--      
                        <a class="btn btn-default" <?php echo $print_onclick   ?>><i class="fa fa-print"></i> Imprimir</a>
                        <a class="btn btn-default" href="<?php echo base_url() . $path_file_csv ?>" download="<?php echo $name_file_csv ?>"><i class="fa fa-file"></i> Exportar a Excel</a>
                        -->
                        <!--
                        <button class="btn btn-default"><i class="fa fa-print"></i> Imprimir</button>
                        <a class="btn btn-default" href="/inai_tpo/tpov1/dist/csv/usuarios.csv" download="imp_usuarios.csv"><i class="fa fa-file"></i> Exportar a Excel</a>
                        -->

                    </div>
                </div><!-- /.box-header -->

                <div class="form-group">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Grupo edad</th>
                                
                                <th> </th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for($z = 0; $z < sizeof($edades); $z++)
                            {

                                echo '<tr>';
                                    echo '<td>'.$edades[$z]['id_rel_campana_grupo_edad'].'</td>';
                                    echo '<td>'.$edades[$z]['nombre_grupo_edad'].'</td>';
                                    //echo '<td>'.$edades[$z]['id_poblacion_grupo_edad'].'</td>';
                                    echo '<td>' . anchor("tpoadminv1/campanas/campanas/edita_campanas_avisos/".$edades[$z]['id_rel_campana_grupo_edad'], "<button class='btn btn-warning btn-sm' title='Editar'><i class=\"fa fa-edit\"></i></button></td>"); 
                                    echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarEdadModal(" . $edades[$z]['id_rel_campana_grupo_edad']. ")\"> <i class='fa fa-close'></i></span></td>";
                                    //echo '<td>'.$edades[$z]['id_poblacion_grupo_edad'].'</td>';
                                echo '</tr>';    
                        
                                   
                                    
                                    
                                /*    echo '<td>' . $camp_avisos[$z]['nombre_ejercicio'] . '</td>';
                                    echo '<td>' . $camp_avisos[$z]['nombre_trimestre'] . '</td>';
                                    echo '<td>' . $camp_avisos[$z]['nombre_so_contratante'] . '</td>';
                                    echo '<td>' . $camp_avisos[$z]['nombre_so_solicitante'] . '</td>';
                                    echo '<td>' . $camp_avisos[$z]['active'] . '</td>';
                                    echo "<td> <span class='btn-group btn btn-info btn-sm' onclick=\"abrirModal(" . $camp_avisos[$z]['id_campana_aviso'] . ")\"> <i class='fa fa-search'></i></span></td>";
                                    echo '<td>' . anchor("tpoadminv1/campanas/campanas/edita_campanas_avisos/".$camp_avisos[$z]['id_campana_aviso'], "<button class='btn btn-warning btn-sm' title='Editar'><i class=\"fa fa-edit\"></i></button></td>"); 
                                    echo "<td> <span class='btn-group btn btn-danger btn-sm' onclick=\"eliminarModal(" . $camp_avisos[$z]['id_campana_aviso']. ")\"> <i class='fa fa-close'></i></span></td>";
                                */
                               
                            }
                            ?>
                            </tbody>
                    </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
                
            </div><!-- /.col -->
        </div><!-- /.row -->



<!-- Modal -->
<div class="modal fade" id="modalAgregar" role="dialog">
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
</script>






    
