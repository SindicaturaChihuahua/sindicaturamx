<?php
/* 
INAI
*/
?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box table-responsive no-padding">
                <div class="box">
                    <div class="box-header">
                        
                    </div><!-- /.box-header -->
                
                    <div class="box-body">
                        <table id="imprimir" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Correo Electronico</th>
                                    <th>Nombre(s)</th>
                                    <th>Apellidos</th>
                                    <th>Sujeto Obligado</th>
                                    <th>Estatus</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for($z = 0; $z < sizeof($usuarios); $z++)
                                {
                                    echo '<tr>';
                                    echo '<td>' . $usuarios[$z]['username'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['email'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['fname'] . '</td>';
                                    echo '<td>' . $usuarios[$z]['lname'] . '</td>';
									echo '<td>Instituto Nacional de Transparencia</td>';
                                    echo '<td>ACTIVO</td>';
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