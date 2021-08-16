
<?php

/* 
 * INAI TPO
 */

?>
<?php
    $sel_estatus = '';
    $lista_estatus = ['Activo','Inactivo'];
    $lista_estatus_ids = ['1','2'];
    for($z = 0; $z < sizeof($lista_estatus_ids); $z++)
    {
        if($presupuesto['active'] == $lista_estatus_ids[$z]){
            $sel_estatus .= '<option value="'.$lista_estatus_ids[$z].'" selected>' . $lista_estatus[$z] . '</option>';
        }else{
            $sel_estatus .= '<option value="'.$lista_estatus_ids[$z].'">' . $lista_estatus[$z] . '</option>';
        }
        
    }

    $sel_captura = '';
    $lista_captura = ['-nada-','Si','No'];
    $lista_captura_ids = ['','1','2'];

    for($z = 0; $z < sizeof($lista_captura_ids); $z++)
    {
        if( $lista_captura_ids[$z] == $presupuesto['id_captura'] ){
            $sel_captura .= '<option value="'.$lista_captura_ids[$z].'" selected>' . $lista_captura[$z] . '</option>';
        }else{
            $sel_captura .= '<option value="'.$lista_captura_ids[$z].'">' . $lista_captura[$z] . '</option>';
        }
    }
?>

<!-- Main content -->
<section class="content">
    <?php
        if ($this->session->flashdata('error'))
        {
        ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-ban"></i> ¡Error!</h4>
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
        <h4>	<i class="icon fa fa-check"></i> ¡Exito!</h4>
        <?php echo $this->session->flashdata('exito'); ?>
    </div>
    <?php
    }
    ?>

    <?php
        if ($this->session->flashdata('alert'))
        {
        ?>
        <div class="alert alert-warning alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-ban"></i> ¡Alerta!</h4>
            <?php echo $this->session->flashdata('alert'); $this->session->set_flashdata('alert', ''); ?>
        </div>
        <?php
        }
    ?>

    <div class="row">
        <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/catalogos/catalogos/validate_editar_presupuesto" enctype="multipart/form-data" >
            <div class="box box-primary">
                <div class="box-header">
                </div><!-- /.box-header -->
                <div class="box-body">
                <input type="hidden" value="<?php echo $presupuesto['id_presupesto_concepto']; ?>" class="form-control" name="id_presupesto_concepto"/>
                    <div class="form-group">
                        <label>Cap&iacute;tulo*</label>
                        <?php $class = "form-control";
                            echo form_input(array('type' => 'text', 'name' => 'capitulo', 'value' => $presupuesto['capitulo'], 'class' => $class)); ?>
                    </div>
                    <div class="form-group">
                        <label>Concepto*</label>
                        <?php $class = "form-control";
                            echo form_input(array('type' => 'text', 'name' => 'concepto', 'value' => $presupuesto['concepto'], 'class' => $class)); ?>
                    </div>
                    <div class="form-group">
                        <label>Partida*</label>
                        <?php $class = "form-control";
                            echo form_input(array('type' => 'text', 'name' => 'partida', 'value' => $presupuesto['partida'], 'class' => $class)); ?>
                    </div>
                    <div class="form-group">
                        <label>Denominaci&oacute;n*</label>
                        <?php $class = "form-control";
                            echo form_input(array('type' => 'text', 'name' => 'denominacion', 'value' => $presupuesto['denominacion'], 'class' => $class)); ?>
                    </div>
                    <div class="form-group">
                        <label>Descripci&oacute;n*</label>
                        <?php $class = "form-control";
                            echo form_input(array('type' => 'text', 'name' => 'descripcion', 'value' => $presupuesto['descripcion'], 'class' => $class)); ?>
                    </div>
                    <div class="form-group">
                        <label>Captura*</label>
                        <select class="form-control" name="id_captura">
                            <?php echo $sel_captura; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Estatus*</label>
                        <select class="form-control" name="active">
                            <?php echo $sel_estatus; ?>
                        </select>
                    </div> 
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Guardar</button>
                    <?php echo anchor("tpoadminv1/catalogos/catalogos/busqueda_presupuestos", "<button class='btn btn-default' type='button'>Regresar</button></td>"); ?>
                </div>     
            </div><!-- /.box -->    
            <?php 
                if(validation_errors() == TRUE)
                {
                    echo '<div class="alert alert-danger"><button class="close"  data-dismiss="alert">x</button>
                    <h4><i class="icon fa fa-ban"></i>¡Alerta!</h4>' . validation_errors() . '</div>';  
                }
            ?>
        </form>
    </div>
</section>