
<?php

/* 
 * INAI TPO
 */

?>
<?php
    $sel_estatus = '';
    $lista_estatus = ['-Seleccione-','Activo','Inactivo'];
    $lista_estatus_ids = ['','1','2'];
    for($z = 0; $z < sizeof($lista_estatus_ids); $z++)
    {
        if( $lista_estatus_ids[$z] == $registro['active'] ){
            $sel_estatus .= '<option value="'.$lista_estatus_ids[$z].'" selected>' . $lista_estatus[$z] . '</option>';
        }else{
            $sel_estatus .= '<option value="'.$lista_estatus_ids[$z].'">' . $lista_estatus[$z] . '</option>';
        }
        
    }

    $sel_conceptos = '<option value="">-Seleccione-</option>';
    for($z = 0; $z < sizeof($conceptos); $z++)
    {
        $partida = $conceptos[$z]['capitulo'] . ' - ' . $conceptos[$z]['concepto'] . ' - ' . $conceptos[$z]['partida'] . ' - ' . $conceptos[$z]['denominacion'];
        if($registro['id_presupuesto_concepto'] == $conceptos[$z]['id_presupesto_concepto']){
            $sel_conceptos .= '<option value="'.$conceptos[$z]['id_presupesto_concepto'].'" selected>' . $partida . '</option>';
        }else{
            $sel_conceptos .= '<option value="'.$conceptos[$z]['id_presupesto_concepto'].'">' . $partida  . '</option>';
        }
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
    .validation-error {
        box-shadow: 0 0 1px red, 0 0 3px red !important;
        background: #FFEBEB !important;
    }
    .has-error {
        box-shadow: 0 0 1px red, 0 0 3px red !important;
        background: #FFEBEB !important;
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
            <h4><i class="icon fa fa-ban"></i> ¡Error!</h4>
            <?php echo $this->session->flashdata('error'); $this->session->set_flashdata('error', '');?>
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
        <?php echo $this->session->flashdata('exito'); $this->session->set_flashdata('exito', ''); ?>
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

    <link href="<?php echo base_url(); ?>editors/tinymce/skins/lightgray/skin.min.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url();?>editors/tinymce/tinymce.min.js"></script>
    <div class="row">
        <?php 
            /*** El siguiente código es para colocar la clase .validation_error cuando hay un error en el form_validate 
             * form_error regresa una cadena, si es vacía significa que no hay error, si trae texto se marca el error
             * ***/
            $error_ipc = !empty(form_error('id_presupuesto_concepto', '<div class="text-danger">', '</div>'));
            $error_mp = !empty(form_error('monto_presupuesto', '<div class="text-danger">', '</div>'));
            $error_mm = !empty(form_error('monto_modificacion', '<div class="text-danger">', '</div>'));
            $error_ff = !empty(form_error('fuente_federal', '<div class="text-danger">', '</div>'));
            $error_mff = !empty(form_error('monto_fuente_federal', '<div class="text-danger">', '</div>'));
            $error_fl = !empty(form_error('fuente_local', '<div class="text-danger">', '</div>'));
            $error_mfl = !empty(form_error('monto_fuente_local', '<div class="text-danger">', '</div>'));
            $error_active = !empty(form_error('active', '<div class="text-danger">', '</div>'));
            if(validation_errors() == TRUE)
            {
                echo '<div class="alert alert-danger"><button class="close"  data-dismiss="alert">x</button>
                <h4><i class="icon fa fa-ban"></i>¡Alerta!</h4>' . validation_errors() .'</div>';  
            }

            /** si se sa clic en el boton de regresar, se active el tab de desgloses **/
            $this->session->set_flashdata('tab_flag', "desglose");
        ?>
        <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/capturista/presupuestos/validate_editar_presupuesto_partida" enctype="multipart/form-data" >
            <div class="box box-primary">
                <div class="box-header">
                </div><!-- /.box-header -->
                <div class="box-body">
                    <input type="hidden" value="<?php echo $registro['id_presupuesto']; ?>" class="form-control" name="id_presupuesto"/>
                    <input type="hidden" value="<?php echo $registro['id_presupuesto_desglose']; ?>" class="form-control" name="id_presupuesto_desglose"/>
                    <div class="form-group">
                        <label>Partida presupuestal* 
                            <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['id_presupesto_concepto']?>"></i>
                        </label>
                        <select name="id_presupuesto_concepto" class="form-control <?php if($error_ipc) echo 'has-error' ?>">
                            <?php echo $sel_conceptos; ?>
                        </select>
                    </div> 
                    <h3 class="text-primary">Fuente presupuestaria</h3>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Fuente presupuestaria federal*
                                <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['fuente_federal']?>"></i>
                            </label>
                            <?php $class = "form-control"; if($error_ff) $class = "form-control has-error";
                                    echo form_input(array('type' => 'text', 'name' => 'fuente_federal', 'value' => $registro['fuente_federal'], 'class' => $class)); ?>
                        </div>
                        <div class="form-group">
                            <label>Monto asignado*
                                <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_fuente_federal']?>"></i>
                            </label>
                            <?php $class = "form-control"; if($error_mff) $class = "form-control has-error";
                                    echo form_input(array('type' => 'number', 'step'=>'0.01', 'name' => 'monto_fuente_federal', 'value' => $registro['monto_fuente_federal'], 'class' => $class)); ?>
                        </div>
                        <div class="form-group">
                            <label>Fuente presupuestaria local*
                                <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['fuente_local']?>"></i>
                            </label>
                            <?php $class = "form-control"; if($error_fl) $class = "form-control has-error";
                                    echo form_input(array('type' => 'text', 'name' => 'fuente_local', 'value' => $registro['fuente_local'], 'class' => $class)); ?>
                        </div>
                        <div class="form-group">
                            <label>Monto asignado*
                                <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_fuente_local']?>"></i>
                            </label>
                            <?php $class = "form-control"; if($error_mfl) $class = "form-control has-error";
                                    echo form_input(array('type' => 'number', 'step'=>'0.01', 'name' => 'monto_fuente_local', 'value' => $registro['monto_fuente_local'], 'class' => $class)); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Monto asignado total*
                            <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_asignado']?>"></i>
                        </label>
                        <?php $class = "form-control"; if($error_mp) $class = "form-control has-error";
                                    echo form_input(array('type' => 'number', 'step'=>'0.01', 'name' => 'monto_presupuesto', 'value' => $registro['monto_presupuesto'], 'class' => $class)); ?>
                    </div>
                    <div class="form-group">
                        <label>Monto de modificaci&oacute;n* 
                            <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['monto_modificacion']?>"></i>
                        </label>
                        <?php $class = "form-control"; if($error_mm) $class = "form-control has-error";
                                echo form_input(array('type' => 'number', 'step'=>'0.01', 'name' => 'monto_modificacion', 'value' => $registro['monto_modificacion'], 'class' => $class)); ?>
                    </div>
                    <div class="form-group">
                        <label>Fecha de validaci&oacute;n
                            <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['fecha_validacion']?>"></i>
                        </label>
                        <?php $class = "form-control datepicker";
                                echo form_input(array('type' => 'text', 'name' => 'fecha_validacion', 'value' => $registro['fecha_validacion'], 'class' => $class)); ?>
                    </div>
                    <div class="form-group">
                        <label>&Aacute;rea responsable de la informaci&oacute;n
                            <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['area_responsable']?>"></i>
                        </label>
                        <?php $class = "form-control";
                                echo form_input(array('type' => 'text', 'name' => 'area_responsable', 'value' => $registro['area_responsable'], 'class' => $class)); ?>
                    </div>
                    <div class="form-group">
                        <label>A&ntilde;o
                            <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['periodo']?>"></i>
                        </label>
                        <?php $class = "form-control";
                                echo form_input(array('type' => 'text', 'name' => 'periodo', 'value' => $registro['periodo'], 'class' => $class)); ?>
                    </div>
                    <div class="form-group">
                        <label>Fecha de actualizaci&oacute;n
                            <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['fecha_actualizacion']?>"></i>
                        </label>
                        
                        <?php $class = "form-control datepicker";
                                echo form_input(array('type' => 'text', 'name' => 'fecha_actualizacion', 'value' => $registro['fecha_actualizacion'], 'class' => $class)); ?>
                    </div>
                    <div class="form-group">
                        <label>Nota
                            <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['nota']?>"></i>
                        </label>
                        <textarea class="form-control" name="nota" id="nota"><?php echo $registro['nota']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Estatus*
                            <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="<?php echo $texto_ayuda['estatus']?>"></i>
                        </label>
                        <select class="form-control" name="active" class="form-control <?php if($error_active) echo 'has-error' ?>">
                            <?php echo $sel_estatus; ?>
                        </select>
                    </div> 
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Guardar</button>
                    <?php echo anchor("/tpoadminv1/capturista/presupuestos/editar_presupuesto/" . $registro['id_presupuesto'] , "<button class='btn btn-default' type='button'>Regresar</button></td>"); ?>
                </div>    
            </div><!-- /.box -->    
        </form>
    </div>
</section>

<script>
    var monto_total = function(){

        var mf = 0.00, ml = 0.00;
        if(!isNaN( $('input[name="monto_fuente_local"]').val())){
            ml = parseFloat($('input[name="monto_fuente_local"]').val());
        }

        if(!isNaN( $('input[name="monto_fuente_federal"]').val())){
            mf = parseFloat($('input[name="monto_fuente_federal"]').val());
        }
        var n = parseFloat((ml + mf), 10).toFixed(2);
        $('input[name="monto_presupuesto"]').val(n);
    }
</script>