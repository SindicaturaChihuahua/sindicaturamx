<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	$action=array();
	$errores=array();
	$cHeaderData=array(
		'icono' => 'address-book',
	);
	$bodys->set( 'bottomjs', URLPLUGINS.'jqueryui/jquery-ui.min.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'ckeditor/ckeditor.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'selectize/selectize.min.js' );
	$bodys->set( 'bottomjs', 'pack-fileupload' );
	$bodys->set( 'bottomjs', URLPLUGINS.'fileupload/single-upload.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'fileupload/gallery-upload.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'timepicker/jquery-ui-timepicker-addon.js' );

	//Para BreadCrumbs
	if(isset($_GET['beta']) && $_GET['beta']=='editar' && isset($_GET['delta']) && $obj = $controlador->getPost($_GET['delta'], $_GET['gama'])){
		$cHeaderData['titulo'] = 'Editar comisión';
		$url->addBreadcrumb(array('nombre' => 'Editar comisión', 'link' => 's/'.$controlador->name.'/editar/'.$obj['tipo'].'/'.$obj['post_id']));
	}else if(isset($_GET['beta']) && $_GET['beta']=='nuevo' && $post_id = $controlador->newPost($user->uid, $_GET['gama'])){
		$obj = $controlador->getPost($post_id, $_GET['gama']);
		$cHeaderData['titulo'] = 'Nueva comisión';
		$url->addBreadcrumb(array('nombre' => 'Nueva comisión', 'link' => 's/'.$controlador->name.'/nuevo/'.$obj['tipo']));
	}else{
		header("Location: ".$url->createUrl($controlador->static_urls['lista']));
		exit;
	}

	if($obj['autor_id'] != $user->uid && !$user->hasRole("EditorEnJefe")){
		header("Location: ".$url->createUrl($controlador->static_urls['lista']));
		exit;
	}

	$action['accion']="editar";
	$action['formaccion']=$url->createUrl($controlador->static_urls['nuevo-handler']).'/'.$_GET['gama'];

	include( DMSIGA .'breadcrumbs.php' );
	include( DMSIGA .'cHeader.php' );

	echo '<div class="boxcon">';
	echo '<div class="box">';
		?>
        <form role="form" method="post" action="<?=$action['formaccion'];?>" class="object-ajax">
        <input type="hidden" name="manage" value="manage" />
        <input type="hidden" name="action" value="<?=$action['accion'];?>" />
        <input type="hidden" name="id" value="<?=$obj['post_id'];?>" />
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Comisión</h4>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input type="text" class="form-control" name="titulo" value="<?=cleanOutput($obj['titulo']);?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Descripción</label>
                            <textarea name="descripcion" class="aneditor" autocomplete="off"><?=$obj['descripcion'];?></textarea>
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
                <p>Selecciona los distintos recursos asignados a esta comisión.</p>
            </div>
            <div class="col-sm-9">
                <div class="frecursos cfix">
					<?php
					$url_default=SIGA.'images/siga_default_file.png';
                    foreach($controlador->single_files as $key => $data){
                        echo '<div class="fsingle-upload" data-url="'.$url->createUrl($controlador->static_urls['single-upload-handler']).'" data-estilo="'.$key.'" data-id="'.$obj['post_id'].'">';
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
		<?php /*
		<hr>
		<div class="row">
        	<?php
			$gfu_galeria=csrfCreate('gfu-galeria',
				array(
					'uid' => $user->uid,
					'tipo' => 'gfu-',
					'id' => 'galeria',
					'options' => array(
						'user_objeto_id' => $obj['post_id'],
						'user_tipo' => "galeria",
						'upload_dir' => "../../../../cargas/".$controlador->name."/".$obj['folder'],
						'upload_url' => URL."public/cargas/".$controlador->name."/".$obj['folder'],
						'folder' => $obj['folder'],
						'db_table' => DB_PREFIX.$controlador->databaseprefix.'_archivos'
					)
				)
			);
			$gfuurl=URL.'public/plugins/fileupload/server/php/index.php?token='.$gfu_galeria."&futipo=gfu-&fuid=galeria&takedata=true";
			?>
        	<div class="col-sm-3">
            	<h4>Galería</h4>
                <p>Arrastra los elementos para cambiar el orden.</p>
                <div class="btn btn-default btn-sm btn-fileuploader"><input id="gfu-galeria-files" class="fileupload" type="file" name="files[]" multiple><span class="fa fa-file-image-o"></span> &nbsp;Cargar Imágenes</div>
            </div>
            <div class="col-sm-9">
            	<div id="gfu-galeria" class="gallery-upload-presentation cfix" data-url="<?=$gfuurl;?>" data-reorder="true" data-pie="true"></div>
            </div>
        </div>
		<hr>
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Categorías</h4>
                <p></p>
                <a href="<?=$url->createUrl($controlador->static_urls['categorias']);?>" class="btn btn-default btn-sm"><span class="fa fa-tag"></span> &nbsp;Administrar categorías</a>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <select id="blog_post_categorias" name="s_categorias[]" multiple="multiple">
                            	<?php
								foreach($obj_categorias as $categoria){
									if(isset($blog_categorias[$categoria['categoria_id']])){
										echo '<option value="'.$categoria['categoria_id'].'" selected="selected">'.$blog_categorias[$categoria['categoria_id']]['categoria_nombre'].'</option>';
									}
								}
								?>
                            </select>
                        </div>
                    </div>
                </div>
        	</div>
		</div><!-- end big row -->
		*/ ?>
		<hr>
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Visibilidad</h4>
                <p>Controla si esta comisión estará visible en el sitio.</p>
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
        <a href="#motores-de-busqueda-row" class="mostrar-informacion"><i class="fa fa-angle-right"></i> Editar información de motores de búsqueda</a>
        <div class="row informacion-comprimida" id="motores-de-busqueda-row">
        	<div class="col-sm-3">
            	<h4>Motores de búsqueda</h4>
                <p>Establece el título de tu página, la meta descripción y la URL amigable. Esto ayuda a definir como la comisión se muestra en los motores de búsqueda.</p>
            </div>
            <div class="col-sm-9">
				<div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">URL amigable</label>
                            <div class="input-group">
                              <span class="input-group-addon"><?=URL;?>ayuntamiento/comisiones/</span>
                              <input type="text" class="form-control" name="slug" value="<?=cleanOutput($obj['slug']);?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Título de la página</label>
                            <input type="text" class="form-control" name="seo_titulo" value="<?=cleanOutput($obj['seo_titulo']);?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Meta descripción</label>
                            <textarea class="form-control no-resize" rows="3" name="seo_descripcion"><?=cleanOutput($obj['seo_descripcion']);?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Palabras clave</label>
                            <input id="seo_tags" type="text" class="" name="seo_tags" value="<?=isset($obj['extra']['seo_tags']) ? cleanOutput($obj['extra']['seo_tags']) : '';?>">
                            <p class="help-block">Ingresa hasta 20 palabras clave separadas por una coma.</p>
                        </div>
                    </div>
                </div>
			</div>
		</div><!-- end big row -->

        <hr />
        <div class="row">
        	<div class="col-sm-3">
            	<?php
				if($_GET['beta']=='editar'){
					$delete_url=$action['formaccion'].'?action=delete-object&id='.$obj['post_id'];
					echo '<a href="'.$delete_url.'" class="btn btn-default blockatsubmit action delete-object-btn">Eliminar Entrada</a>';
				}
				?>
            </div>
        	<div class="col-sm-9">
                <div class="form-group">
                	<button type="submit" class="btn btn-primary blockatsubmit" name="save">Guardar Entrada</button> <img src="<?=SIGA;?>images/loading.gif" class="floading" />
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
			$('#seo_tags').selectize({
				maxItems: 20,
				persist: false,
				createOnBlur: true,
				create: true
			});
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
		include_once(PLUGINS.'fileupload/gallery-upload-template.php');
	echo '</div>';

}else{
	include('noaccess.php');
}
?>
