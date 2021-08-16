<?php

/* 
 INAI / EDITA USUARIO
 */

?>

<?php

//SUJETO OBLIGADO
$sel_sujeto = '';
for($z = 0; $z < sizeof($sujetos); $z++)
{
    $selected = '';
    if($sujetos[$z]['id_sujeto_obligado'] == $usuario['record_user'])
    {
        $selected = ' selected="selected"';
    }
    $sel_sujeto .= '<option value ="'.$sujetos[$z]['id_sujeto_obligado'].'" '.$selected.'>' . $sujetos[$z]['nombre_sujeto_obligado'] . '</option>';
}

//USUARIO ROL
$sel_rol = '';
for($z = 0; $z < sizeof($roles); $z++)
{
    $selected = '';
    if($roles[$z]['id_rol'] == $usuario['rol_user'])
    {
        $selected = ' selected="selected"';
    }
    $sel_rol .= '<option value ="'.$roles[$z]['id_rol'].'" '.$selected.'>' . $roles[$z]['nombre_rol'] . '</option>';
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
        <?php echo $this->session->flashdata('error'); $this->session->set_flashdata('error', "");?>
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
        <?php echo $this->session->flashdata('alerta'); $this->session->set_flashdata('alerta', "");?>
    </div>
    <?php
    }
    ?>
        
    <div class="row">
        <!-- form start -->
        <form role="form" method="post" action="<?php echo base_url(); ?>index.php/tpoadminv1/usuarios/usuarios/validate_edita_usuario" enctype="multipart/form-data">
        <input type="hidden" name="id_user" value="<?php echo $usuario['id_user']; ?>">
            
            <div class="col-md-12">
                <!-- general form elements -->
                <?php 
                if(validation_errors() == TRUE)
                {
                    echo '<div class="alert alert-danger"><button class="close"  data-dismiss="alert">x</button>
                    <h4><i class="icon fa fa-ban"></i>Alerta!</h4>' . validation_errors() . '</div>';  
                }
                ?>

                <div class="box box-primary">
                <div class="box-header">
                        <h3 class="box-title">Datos de usuario</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label for="username">Usuario *</label>
                            <input type="text" placeholder="Ingrese Nombre" name="username" class="form-control" value="<?php echo $usuario['username']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Correo electr&oacute;nico *</label>
                            <input type="email" placeholder="Ingrese correo" name="email" class="form-control" value="<?php echo $usuario['email']; ?>">
                        </div>
                        <div class="form-group">    
                            <label for="fname">Nombre(s) *</label>
                            <input type="text" placeholder="Ingrese Nombre(s)" name="fname" class="form-control"  value="<?php echo $usuario['fname']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="lname">Apellidos *</label>
                            <input type="text" placeholder="Ingrese Apellido" name="lname" class="form-control"  value="<?php echo $usuario['lname']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Sujeto Obligado *</label>
                            <select class="form-control" name="record_user">
                                <option value="1">- Selecciona -</option>
                                <?php echo $sel_sujeto; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone">Tel&eacute;fono</label>
                            <input type="text" placeholder="Ingrese extension" name="phone" class="form-control" value="<?php echo $usuario['phone']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" placeholder="Ingresar contraseña solo si deseas modificar la existente" name="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="active">Rol *</label>
                            <select class="form-control" name="rol_user">
                                <option value="0">- Selecciona -</option>
                                <?php echo $sel_rol; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="active">Estatus</label>
                            <select class="form-control" name="active">
                                <option value="0">- Selecciona -</option>
                                <option value="a" <?php if($usuario['active'] == 'a') { ?>  selected="selected"; <?php } ?> >Activo</option>
                                <option value="i" <?php if($usuario['active'] == 'i') { ?>  selected="selected"; <?php } ?>>Inactivo</option>
                            </select>
                        </div>

                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <?php if($this->session->userdata('usuario_nombre') != $usuario['username']) { ?>
                            <button class="btn btn-primary" type="submit">Guardar</button>
                        <?php } else{ ?>
                            <p style="color:red">No se permite la edición del usuario que se encuentra logeado</p>
                        <?php } ?>
                        <?php echo anchor("tpoadminv1/usuarios/usuarios/busqueda_usuario", "<button class='btn btn-default' type='button'>Regresar</button></td>"); ?>
                    </div>     
                </div><!-- /.box -->
            </div>
        </form>
    </div>
</section>