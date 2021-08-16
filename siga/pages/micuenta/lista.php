<?php
if(logeado() && $user->exists){
	
	$action=array();
	$errores=array();
	$cHeaderData=array(
		'icono' => 'user',
		'titulo' => $user->pseudonimo
	);
	$bodys->set( 'bottomjs', URLPLUGINS.'jqueryui/jquery-ui.min.js' );
	$bodys->set( 'bottomjs', 'pack-fileupload' );
	$bodys->set( 'bottomjs', URLPLUGINS.'fileupload/single-upload.js' );

	$url->addBreadcrumb(array('nombre' => $user->pseudonimo, 'link' => $controlador->static_urls['lista']));
	$action['accion']="editar";
	$action['formaccion']=$url->createUrl($controlador->static_urls['nuevo-handler']);
	
	include( DMSIGA .'breadcrumbs.php' );
	include( DMSIGA .'cHeader.php' );

	echo '<div class="boxcon">';
	if(count($errores)){
		echo '<div class="alert alert-danger">';
			echo implode("<br>",$errores);
		echo '</div>';
	}
	echo '<div class="box">';
		?>
        <form role="form" method="post" action="<?=$action['formaccion'];?>" class="object-ajax">
        <input type="hidden" name="manage" value="manage" />
        <input type="hidden" name="action" value="<?=$action['accion'];?>" />
        <input type="hidden" name="id" value="<?=$user->uid;?>" />
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Información de la cuenta</h4>
                <p>Toda la información relevante de la cuenta.</p>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" value="<?=cleanOutput($user->nombre);?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Seudónimo</label>
                            <input type="text" class="form-control" value="<?=cleanOutput($user->pseudonimo);?>" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Nombre de usuario</label>
                            <input type="text" class="form-control" value="<?=cleanOutput($user->username);?>" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Correo electrónico</label>
                            <input type="text" class="form-control" value="<?=cleanOutput($user->email);?>" disabled="disabled">
                        </div>
                    </div>
                </div>
        	</div>
		</div><!-- end big row -->
        <hr />
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Contraseña</h4>
                <p>Completa estos campos si deseas cambiar la contraseña para acceder a tu cuenta</p>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Contraseña actual</label>
                            <input type="password" class="form-control" value="" name="contrasena">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Nueva contraseña</label>
                            <input type="password" class="form-control" value="" name="contrasenanueva">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Repite tu nueva contraseña</label>
                            <input type="password" class="form-control" value="" name="contrasenanueva2">
                        </div>
                    </div>
                </div>
        	</div>
		</div><!-- end big row -->
        <hr />
        <div class="row object-image-con">
        	<div class="col-sm-3">
            	<h4>Imagen de perfil</h4>
                <p>Selecciona una imagen de perfil que te represente, recuerda que esta será visible al público en general.</p>
            </div>
            <div class="col-sm-9">
                <div class="frecursos cfix">
					<?php
					$url_default=SIGA.'images/siga_default_file.png';
                    foreach($controlador->single_files as $key => $data){
                        echo '<div class="fsingle-upload" data-url="'.$url->createUrl($controlador->static_urls['single-upload-handler']).'" data-estilo="'.$key.'" data-id="'.$user->uid.'">';
							echo '<div id="sfp-carga-'.$key.'" class="carga-barra"></div>';
							echo '<div id="sfp-image-'.$key.'" class="coverimage" style="background-image:url('.$url_default.');"></div>';
							echo '<div class="btn btn-default btn-xs btn-fileuploader"><input class="fileupload" type="file" name="file_'.$key.'">'.$data['info_btn'].'</div>';
							echo '<div id="sfp-options-'.$key.'" class="sfp-options cfix">';
								echo '<div class="sfpo-refresh"></div>';
								if(isset($data['info'])){
									echo '<span class="fa fa-info-circle bstooltip" title="'.$data['info'].'" data-placement="bottom"></span>';
								}
							echo '</div>';
                        echo '</div>';	
                    }
                    ?>
                </div>
        	</div>
		</div><!-- end big row -->
        <hr />
        <div class="row">
        	<div class="col-sm-3">
            </div>
        	<div class="col-sm-9">
                <div class="form-group">
                	<button type="submit" class="btn btn-primary blockatsubmit" name="save">Guardar</button> <img src="<?=SIGA;?>images/loading.gif" class="floading" />
                </div>
			</div> 
        </div><!-- end big row -->
        </form>
        
		<?php
		include_once(PLUGINS.'fileupload/gallery-upload-template.php');
	echo '</div>';

}else{
	include('noaccess.php');
}
?>