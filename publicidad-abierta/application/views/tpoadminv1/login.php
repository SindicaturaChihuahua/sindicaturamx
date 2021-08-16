<?php
/* 
 LOGIN
 */
?>

<div class="login-box">
    <div class="login-logo logo_login">
        <img src="<?php echo base_url() . 'dist/img/logotop.png' ?>" width="150px;"/>
    </div><!-- /.login-logo -->
    <div class="login-box-body padding_logo">
        <p class="login-box-msg"><?php echo $heading; ?></p>
        <?php echo form_open('tpoadminv1/cms/validate_credentials'); ?>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Usuario" name="username" autocomplete="off" value="<?php echo set_value('username'); ?>"/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input id="fake_password" type="text" class="form-control" placeholder="Contrase&ntilde;a" onKeyUp="HiddePassword()" autocomplete="off"/>
                <input id="real_password" type="hidden" name="password"/>
                <input id="position" type="hidden" value="0" />
                <input id="prev_position" type="hidden" value="0" />
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <?php 
                if(isset($recaptcha) && $recaptcha->active == 1){
                    echo '<div class="g-recaptcha" style="margin-bottom: 10px;" data-callback="recaptchaCallback" data-sitekey="' . $recaptcha->recaptcha. '"></div>';
                }
            ?>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
                </div><!-- /.col -->
            </div>
            
            <?php echo form_close(); ?>

            <br><br>
            <?php 

            if(@$mensaje_error == 'usuario')
            {
                echo '<div class="alert alert-danger"><button class="close"  data-dismiss="alert">x</button>
                <h4><i class="icon fa fa-ban"></i>Alerta!</h4>El usuario no existe.</div>';  
            }

            if(@$mensaje_error == 'contrasena')
            {
                echo '<div class="alert alert-danger"><button class="close"  data-dismiss="alert">x</button>
                <h4><i class="icon fa fa-ban"></i>Alerta!</h4>La contrase&ntilde;a es incorrecta, verifica.</div>';
            }

            if(@$mensaje_error == 'rol')
            {
                echo '<div class="alert alert-danger"><button class="close"  data-dismiss="alert">x</button>
                <h4><i class="icon fa fa-ban"></i>Alerta!</h4>El rol del usuario está inactivo, favor de verificar con el Administrador.</div>';
            }
            
            if(validation_errors() == TRUE || !empty(@$error_recaptcha))
            {
                echo '<div class="alert alert-danger"><button class="close"  data-dismiss="alert">x</button>
                <h4><i class="icon fa fa-ban"></i>Alerta!</h4>' . validation_errors() . @$error_recaptcha . '</div>';  
            }
        ?>
        <div class="text-center">
            ©2015 All Rights Reserved <br>
            Publicidad abierta
			</div>
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
<script type='text/javascript'> 
    var recaptchaCallback = function(){ 
        $('.alert').alert('close');
    };
    var HiddePassword = function(){
        var fake = $('#fake_password').val();
        var real = $('#real_password').val();
        var position = parseInt($('#position').val());
        var size = fake.length;
        if(size > position){
            if(size > 0){
                $('#real_password').val(real+fake.substring(position, fake.length));
            }
            var showText = "";
            for (i = 0; i < size; i++) {
                showText += "*";
            }
            $('#fake_password').val(showText);
            $('#position').val(size);
        }else {
            $('#real_password').val(real.substring(0,size));
            $('#position').val(size);
        }
        
    }
    
</script>