<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	
	$action=array();
	$errores=array();
	$manageuser = new User($db);
	$cHeaderData=array(
		'icono' => 'user',
	);
	$bodys->set( 'bottomjs', URLPLUGINS.'jqueryui/jquery-ui.min.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'selectize/selectize.min.js' );
	$bodys->set( 'bottomjs', 'pack-fileupload' );
	$bodys->set( 'bottomjs', URLPLUGINS.'fileupload/single-upload.js' );
	$bodys->set( 'bottomjs', SIGA.'pages/'.$controlador->name.'/functions.js' );

	//Para BreadCrumbs
	if(isset($_GET['beta']) && $_GET['beta']=='editar' && isset($_GET['gama']) && $manageuser->getByUid($_GET['gama'])){
		$cHeaderData['titulo'] = 'Editar usuario';
		$url->addBreadcrumb(array('nombre' => 'Editar usuario', 'link' => $controlador->static_urls['editar'].'/'.$manageuser->uid));
	}else if(isset($_GET['beta']) && $_GET['beta']=='nuevo' && $uid = $manageuser->newUser()){
        $manageuser->getByUid($uid);
        $cHeaderData['titulo'] = 'Añadir nuevo usuario';
        $url->addBreadcrumb(array('nombre' => 'Añadir nuevo usuario', 'link' => $controlador->static_urls['nuevo']));
    }
	
	if(!$manageuser->exists || $manageuser->status==0){
		header("Location: ".$url->createUrl($controlador->static_urls['lista']));
		exit;	
	}

	$myperms=$manageuser->getAllPrivileges();
	$manageuser->getDetails();
	$action['accion']="editar";
	$action['formaccion']=$url->createUrl($controlador->static_urls['nuevo-handler']);

	include( DMSIGA .'breadcrumbs.php' );
	include( DMSIGA .'cHeader.php' );

	echo '<div class="boxcon">';
	echo '<div class="box">';
		?>
        <form role="form" method="post" action="<?=$action['formaccion'];?>" class="object-ajax">
        <input type="hidden" name="manage" value="manage" />
        <input type="hidden" name="action" value="<?=$action['accion'];?>" />
        <input type="hidden" name="id" value="<?=$manageuser->uid;?>" />
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Perfil del usuario</h4>
                <p>Información básica del usuario</p>
            </div>
            <div class="col-sm-9">
            	<div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" value="<?=cleanOutput($manageuser->nombre);?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Seudónimo</label>
                            <input type="text" class="form-control" name="pseudonimo" value="<?=cleanOutput($manageuser->pseudonimo);?>">
                        </div>
                    </div>
                    <?php
					if($_GET['beta']=='editar'){
					?>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Nombre de usuario</label>
                            <input type="text" class="form-control" value="<?=cleanOutput($manageuser->username);?>" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Correo electrónico</label>
                            <input type="text" class="form-control" value="<?=cleanOutput($manageuser->email);?>" disabled="disabled">
                        </div>
                    </div>
					<?php
					}else{
					?>
					<div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Nombre de usuario</label>
                            <input type="text" class="form-control" name="usuario" value="<?=cleanOutput($manageuser->username);?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Correo electrónico</label>
                            <input type="text" class="form-control" name="email" value="<?=cleanOutput($manageuser->email);?>">
                        </div>
                    </div>
                    <?php
					}
					?>
                </div>
        	</div>
		</div><!-- end big row -->
		<hr />
        <div class="row">
        	<div class="col-sm-3">
            	<?php
				if($_GET['beta']=='editar'){
            		echo '<h4>Editar contraseña</h4>',
                	'<p>Completa estos campos si deseas cambiar la contraseña de este usuario.</p>';
				}else{
            		echo '<h4>Generar contraseña</h4>',
                	'<p>Ingresa la contraseña para acceder a esta cuenta</p>';
				}
				?>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label">Contraseña</label>
                            <input type="password" class="form-control" value="" name="contrasenanueva">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label">Repita la contraseña</label>
                            <input type="password" class="form-control" value="" name="contrasenanueva2">
                        </div>
                    </div>
                </div>
        	</div>
		</div><!-- end big row -->
		<hr />
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Permisos</h4>
                <p>Selecciona las capacidades del usuario</p>
            </div>
            <div class="col-sm-9" id="permisos">
				<?php
                foreach($controlador->permisos as $key => $permiso){
					if(($key > 50 && $key<100) || ($key==100 && $user->isFolklore())){
                	?>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="checkbox <?=$permiso['permiso'];?>" data-recomendados="<?=$permiso['recomendados'];?>" data-obligatorios="<?=$permiso['obligatorios'];?>" data-permiso="<?=$permiso['permiso'];?>">
                            <label>
                                <?php
                                if(isset($myperms[$permiso['permiso']]) && $myperms[$permiso['permiso']]==1){
                                    echo '<input type="checkbox" name="p['.$permiso['permiso'].']" value="'.$permiso['nivel'].'" checked="checked" class="check" >';
                                }else{
                                    echo '<input type="checkbox" name="p['.$permiso['permiso'].']" value="'.$permiso['nivel'].'" class="check" >';
                                }
								echo cleanOutput($permiso['titulo'].": ".$permiso['descripcion']);
                                ?>
                            </label>
                            </div>
                        </div>
                    </div>
                	<?php
					}
                }
                ?>
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
                        echo '<div class="fsingle-upload" data-url="'.$url->createUrl($controlador->static_urls['single-upload-handler']).'" data-estilo="'.$key.'" data-id="'.$manageuser->uid.'">';
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
            	<?php
				if($_GET['beta']=='editar'){
					$delete_url=$url->createUrl($controlador->static_urls['nuevo-handler']).'?action=delete-object&id='.$manageuser->uid;
					echo '<a href="'.$delete_url.'" class="btn btn-default blockatsubmit action delete-object-btn">Eliminar Usuario</a>';
				}
				?>
            </div>
        	<div class="col-sm-9">
                <div class="form-group">
                	<button type="submit" class="btn btn-primary blockatsubmit" name="save">Guardar Usuario</button> <img src="<?=SIGA;?>images/loading.gif" class="floading" />
                </div>
			</div> 
        </div><!-- end big row -->
        </form>
        
        <script>
		$(document).ready(function(){
			$('.single-selector').selectize({
				persist: false,
				create: false
			});
		});
		</script>
        
		<?php
		include_once(PLUGINS.'fileupload/gallery-upload-template.php');
	echo '</div>';

}else{
	include('noaccess.php');
}
?>