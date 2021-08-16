<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	$action=array();
	$errores=array();
	$cHeaderData=array(
		'icono' => 'users',
	);
	$bodys->set( 'bottomjs', URLPLUGINS.'jqueryui/jquery-ui.min.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'ckeditor/ckeditor.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'selectize/selectize.min.js' );
	$bodys->set( 'bottomjs', 'pack-fileupload' );
	$bodys->set( 'bottomjs', URLPLUGINS.'fileupload/single-upload.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'fileupload/gallery-upload.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'timepicker/jquery-ui-timepicker-addon.js' );

	//Para BreadCrumbs
	if(isset($_GET['gama']) && $_GET['gama']=='editar' && isset($_GET['delta']) && $obj = $controlador->getSesion($_GET['delta'])){
		$cHeaderData['titulo'] = 'Editar sesión';
		$url->addBreadcrumb(array('nombre' => 'Sesiones', 'link' => $controlador->static_urls['sesiones']));
		$url->addBreadcrumb(array('nombre' => 'Editar sesión', 'link' => $controlador->static_urls['sesiones-editar'].'/'.$obj['sesion_id']));
	}else if(isset($_GET['gama']) && $_GET['gama']=='nuevo' && $categoria_id = $controlador->newSesion()){
		$obj = $controlador->getSesion($categoria_id);
		$cHeaderData['titulo'] = 'Nueva sesión';
		$url->addBreadcrumb(array('nombre' => 'Sesiones', 'link' => $controlador->static_urls['sesiones']));
		$url->addBreadcrumb(array('nombre' => 'Nueva sesión', 'link' => $controlador->static_urls['sesiones-nuevo']));
	}else{
		header("Location: ".$url->createUrl($controlador->static_urls['sesiones']));
		exit;
	}

	if($obj['autor_id'] != $user->uid && !$user->hasRole("EditorEnJefe")){
		header("Location: ".$url->createUrl($controlador->static_urls['sesiones']));
		exit;
	}

	$action['accion']="editar";
	$action['formaccion']=$url->createUrl($controlador->static_urls['sesiones-nuevo-handler']);

	$comision=$controlador->getPost($o["comision_id"]);

	$proSQL="SELECT * FROM ".DB_PREFIX.$controlador->databaseprefix." WHERE status = 'publicado' ORDER BY titulo ASC";
	$stmt = $db->prepare($proSQL);
	$stmt->execute();
	$comisiones=$stmt->FetchAll();


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
        <input type="hidden" name="id" value="<?=$obj['sesion_id'];?>" />
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Sesión</h4>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Nombre (fecha de la sesión)</label>
                            <input type="text" class="form-control" name="sesion_nombre" value="<?=cleanOutput($obj['sesion_nombre']);?>">
                        </div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5">
						<div class="form-group">
                            <label class="control-label">Comisión</label>
							<select name="comision_id" class="form-control">
                            <?php
							foreach($comisiones as $com){
								$selected = '';
								if($com["post_id"] == $obj["comision_id"]){
									$selected = ' selected';
								}
								echo '<option value="'.$com["post_id"].'"'.$selected.'>'.$com["titulo"].'</option>';
							}
							?>
							</select>
                        </div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
                            <label class="control-label">Video</label>
                            <input type="text" class="form-control" name="sesion_video" value="<?=cleanOutput($obj['sesion_video']);?>">
                        </div>
                        <div class="form-group">
                        	<label class="control-label">Fijar hasta la fecha:</label>
                            <input type="text" class="form-control" name="fijartime" id="timepicker" value="<?=datetimepickerFormatDate($obj['modificado']);?>" />
                            <p class="help-block">Utilice este campo para manipular el orden.</p>
                        </div>
                    </div>
                </div>
        	</div>
		</div><!-- end big row -->
        <hr>
        <div class="row object-image-con">
        	<div class="col-sm-3">
            	<h4>Recursos</h4>
                <p>Selecciona los distintos recursos asignados a esta sesión.</p>
            </div>
            <div class="col-sm-9">
                <div class="frecursos cfix">
					<?php
					$url_default=SIGA.'images/siga_default_file.png';
                    foreach($controlador->sesiones_single_files as $key => $data){
                        echo '<div class="fsingle-upload" data-url="'.$url->createUrl($controlador->static_urls['sesiones-single-upload-handler']).'" data-estilo="'.$key.'" data-id="'.$obj['sesion_id'].'">';
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
		<hr>
		<div class="row">
        	<?php
			$gfu_galeria=csrfCreate('gfu-galeria',
				array(
					'uid' => $user->uid,
					'tipo' => 'gfu-',
					'id' => 'galeria',
					'options' => array(
						'user_objeto_id' => $obj['sesion_id'],
						'user_tipo' => "galeria",
						'upload_dir' => "../../../../cargas/".$controlador->name."/sesiones/obj".$obj['sesion_id']."/",
						'upload_url' => URL."public/cargas/".$controlador->name."/sesiones/obj".$obj['sesion_id']."/",
						'folder' => "obj".$obj['sesion_id']."/",
						'db_table' => DB_PREFIX.$controlador->databaseprefix.'_sesiones_archivos'
					)
				)
			);
			$gfuurl=URL.'public/plugins/fileupload/server/php/index.php?token='.$gfu_galeria."&futipo=gfu-&fuid=galeria&takedata=true";
			?>
        	<div class="col-sm-3">
            	<h4>Dictámenes</h4>
                <p>Arrastra los elementos para cambiar el orden.</p>
                <div class="btn btn-default btn-sm btn-fileuploader"><input id="gfu-galeria-files" class="fileupload" type="file" name="files[]" multiple><span class="fa fa-file-pdf-o"></span> &nbsp;Cargar Archivos</div>
            </div>
            <div class="col-sm-9">
            	<div id="gfu-galeria" class="gallery-upload-presentation cfix" data-url="<?=$gfuurl;?>" data-reorder="true" data-pie="true"></div>
            </div>
        </div>
		<hr>
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Visibilidad</h4>
                <p>Controla si esta sesión estará visible en el sitio.</p>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
							<label class="control-label">Visibilidad</label>
                        	<?php
							$estados_visibilidad=array(
								array("Oculto","oculto"),
								array("Publico","publico"),
							);
							echo '<select class="form-control" name="estadosVisibilidad">';
							foreach($estados_visibilidad as $ev){
								if($ev[1]==$obj['visibilidad']){
									echo '<option value="'.$ev[1].'" selected="selected">'.$ev[0].'</option>';
								}else{
									echo '<option value="'.$ev[1].'">'.$ev[0].'</option>';
								}
							}
                            echo '</select>';
							?>
                        </div>
                    </div>
                </div>
        	</div>
		</div><!-- end big row -->
        <hr>
        <div class="row">
        	<div class="col-sm-3">
            	<?php
				if($_GET['gama']=='editar'){
					$delete_url=$action['formaccion'].'?action=delete-object&id='.$obj['sesion_id'];
					echo '<a href="'.$delete_url.'" class="btn btn-default blockatsubmit action delete-object-btn">Eliminar Sesión</a>';
				}
				?>
            </div>
        	<div class="col-sm-9">
                <div class="form-group">
                	<button type="submit" class="btn btn-primary blockatsubmit" name="save">Guardar Sesión</button> <img src="<?=SIGA;?>images/loading.gif" class="floading">
                </div>
			</div>
        </div><!-- end big row -->
        </form>

        <script>
		$(document).ready(function(){
			$('#timepicker').datetimepicker({
				timeText: 'Tiempo',
				hourText: 'Hora',
				minuteText: 'Minuto',
				secondText: 'Segundo',
				currentText: 'Ahora',
				closeText: 'Listo',
				dateFormat: $.datepicker.ISO_8601,
				separator: ' ',
				timeFormat: 'HH:mm:ss',
				ampm: false
			});
		});
		</script>

		<?php
		include_once(PLUGINS.'fileupload/files-upload-template.php');
	echo '</div>';

}else{
	include('noaccess.php');
}
?>
