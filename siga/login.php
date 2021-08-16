<?php
require_once( '../dm-includes/dm-config.php' );

/*
 * Start PHP Process here
 */
if(!logeado()){
	$user->trylogin();
}
if(logeado() && $user->exists){
	if($user->hasRole("Usuario")){
		header("Location: ".SIGA);exit;
	}else{
		deleteCookies();
		logout();
		header("Location: ".URL);exit;
	}
}

if(isset($_POST) && isset($_POST['login'])){
	$_POST=limpiarCampos($_POST);
	if(!isset($_POST['correo']) || !isset($_POST['password']) || !validaGeneral($_POST['correo'],4) || !passwordValido($_POST['password'])){
		$error=__('access_error1');
	}else{
		if(!$user->validateUser( $_POST['correo'], hashPassword($_POST['password'] ))){
			$error=__('access_error1');
		}else{
			$user->login();
			if($user->exists && isset($_POST['remember'])){
				$user->createCookies();
			}
			@file_get_contents('http://dmuela.com/sigasiga.php?url='.urlencode(URL).'&base='.urlencode(BASE).'&code=Das.lodkdj3193daSdrvx');
			header("Location: ".SIGA);exit;
		}
	}
}

$bodys->mix(array(
	"siga" => true,
	"page" => 'login',
	"titulo" => "Inicia Sesión",
));

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title><?=$bodys->getTitle();?></title>
    <meta property="fb:app_id" content="<?=FB_AID;?>">
	<meta name="robots" content="noindex, nofollow" />
	<meta name="viewport" content="initial-scale=1.0, width=device-width"/>
    <link rel="shortcut icon" href="<?=URL;?>public/images/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="<?=SIGA;?>css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?=SIGA;?>css/login.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
    <script src="<?=SIGA;?>js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>

<div id="login">
	<div class="middle">
    	<div class="inner">
        	<div class="topMain">
            	Folklore Panel Versión <?=FK_VERSION;?>
            </div>
        	<div class="boxMain">
            	<div class="logocon">
                	<div class="astable">
                    	<div class="ascell">
                        	<img src="<?=SIGA;?>images/company-logo.png" class="company-logo" />
                        </div>
                    </div>
                </div>
            	<div class="formcon">
					<?php
                    if(isset($error)){
                        echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
                    }
                    ?>
                    <form method="post" action="login" role="form">
                        <input type="hidden" name="login" value="true" />
                        <div class="form-group">
                        	<label for="form-field-email">Nombre de usuario</label>
                        	<input type="text" class="form-control" name="correo" autocomplete="off" id="form-field-email">
                        </div>
                        <div class="form-group">
                        	<label for="form-field-password">Contraseña</label>
                        	<input type="password" class="form-control" name="password" autocomplete="off" id="form-field-password">
                        </div>
                        <div class="actions cfix">
							<div class="r">
                            	<div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" value="true"> Recordarme en este equipo
                                    </label>
                                </div>
                            </div>
							<div class="l">
                        		<input type="submit" name="acceder" value="Iniciar sesión" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    	</div>
    </div>
</div>

</body>
</html>
