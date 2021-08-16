<?php

/* 
 INAI - ROL
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
        <!-- form start -->
        <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/usuarios/usuarios/validate_alta_rol">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Rol</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                    <div class="form-group">
                            <label for="fname">Nombre</label>
                            <input type="text" placeholder="Ingrese Nombre" name="nombre_rol" value="<?php echo set_value('nombre_rol'); ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="lname">Descripcion</label>
                            <input type="text" placeholder="Ingrese Descripcion" name="descripcion_rol" value="<?php echo set_value('descripcion_rol'); ?>" class="form-control">
                        </div>
                        
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <button class="btn btn-primary" type="submit">Guardar</button>
                        <?php echo anchor("tpoadminv1/usuarios/usuarios/busqueda_rol", "<button class='btn btn-default' type='button'>Regresar</button></td>"); ?>
                    </div>
                </div><!-- /.box -->
                
                <?php 
                if(validation_errors() == TRUE)
                {
                    echo '<div class="alert alert-danger"><button class="close"  data-dismiss="alert">x</button>
                    <h4><i class="icon fa fa-ban"></i>Alerta!</h4>' . validation_errors() . '</div>';  
                }
                ?>
            </div>
        </form>
    </div>
</section>