<?php

/* 
INAI / Alta Usuario
 */

?>

<?php

/*
$sel_estado = '';

for($z = 0; $z < sizeof($estados); $z++)
{
    $selected = '';

    if($estados[$z]['id_so_estado'] == $this->input->post('id_so_estado'))
    {
        $selected = ' selected="selected"';
    }

    $sel_estado .= '<option value="'.$estados[$z]['id_so_estado'].'" '.$selected.'>' . $estados[$z]['nombre_so_estado'] . '</option>';
}

*/



$sel_sujeto = '';

for($z = 0; $z < sizeof($sujetos); $z++)
{
    $selected = '';
    if($sujetos[$z]['id_sujeto_obligado'] == $this->input->post('record_user'))
    {
        $selected = ' selected="selected"';
    }

    $sel_sujeto .= '<option value="'.$sujetos[$z]['id_sujeto_obligado'].'" '.$selected.'>' . $sujetos[$z]['nombre_sujeto_obligado'] . '</option>';
}



$sel_rol = '';

for($z = 0; $z < sizeof($roles); $z++)
{
    $selected = '';
    if($roles[$z]['id_rol'] == $this->input->post('rol_user'))
    {
        $selected = ' selected="selected"';
    }

    $sel_rol .= '<option value="'.$roles[$z]['id_rol'].'" '.$selected.'>' . $roles[$z]['nombre_rol'] . '</option>';
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
        <h4><i class="icon fa fa-ban"></i> Error!</h4>
        <?php echo $this->session->flashdata('error'); $this->session->set_flashdata('error', ""); ?>
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
        <?php echo $this->session->flashdata('exito'); $this->session->set_flashdata('exito', "");?>
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
        <?php echo $this->session->flashdata('alerta');  $this->session->set_flashdata('alerta', '');  ?>
    </div>
    <?php
    }
    ?>
        
    
    <div class="row">
        <!-- form start -->
        <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/usuarios/usuarios/validate_alta_usuario">
            <div class="col-md-12">

                <?php 
                if(validation_errors() == TRUE)
                {
                    echo '<div class="alert alert-danger"><button class="close"  data-dismiss="alert">x</button>
                    <h4><i class="icon fa fa-ban"></i>Alerta!</h4>' . validation_errors() . '</div>';  
                }
                ?>


                <!-- general form elements -->
                <div class="box box-primary">
                    <!--
                    <div class="box-header">
                        <h3 class="box-title">Datos de acceso</h3>
                    </div> --> <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label for="username">Usuario *</label>
                            <input type="text" placeholder="Ingrese Nombre" name="username" value="<?php echo set_value('username'); ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">Correo electr&oacute;nico *</label>
                            <input type="email" placeholder="Ingrese email" name="email" value="<?php echo set_value('email'); ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="fname">Nombre(s) *</label>
                            <input type="text" placeholder="Ingrese Nombre(s)" name="fname" value="<?php echo set_value('fname'); ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="lname">Apellidos *</label>
                            <input type="text" placeholder="Ingrese Apellido" name="lname" value="<?php echo set_value('lname'); ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Sujeto Obligado *</label>
                            <select class="form-control" name="record_user">
                                <option value="0">- Selecciona -</option>
                                <?php echo $sel_sujeto; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone">Tel&eacute;fono</label>
                            <input type="text" placeholder="Ingrese tel&eacute;fono" name="phone" value="<?php echo set_value('phone'); ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Contrase&ntilde;a *</label>
                            <input type="password" placeholder="Password" name="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="active">Rol *</label>
                            <select class="form-control" id="rol_user" name="rol_user">
                                <option value="0">- Selecciona -</option>
                                <?php echo $sel_rol; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="active">Estatus</label>
                            <select class="form-control" name="active">
                                <option value="a">Activo</option>
                                <option value="i">Inactivo</option>
                            </select>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button class="btn btn-primary" type="submit">Guardar</button>
                        <?php echo anchor("tpoadminv1/usuarios/usuarios/busqueda_usuario", "<button class='btn btn-default' type='button'>Regresar</button></td>"); ?>
                    </div>
                </div><!-- /.box -->
            </div>
        </form>
    </div>
</section>