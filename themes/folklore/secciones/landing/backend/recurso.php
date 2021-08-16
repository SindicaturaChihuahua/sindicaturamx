<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	$action=array();
	$errores=array();
	$cHeaderData=array(
		'icono' => 'columns',
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
		$cHeaderData['titulo'] = 'Editar recurso';
		$url->addBreadcrumb(array('nombre' => 'Editar recurso', 'link' => 's/'.$controlador->name.'/editar/'.$obj['tipo'].'/'.$obj['post_id']));
	}else if(isset($_GET['beta']) && $_GET['beta']=='nuevo' && $post_id = $controlador->newPost($user->uid, $_GET['gama'])){
		$obj = $controlador->getPost($post_id, $_GET['gama']);
		$cHeaderData['titulo'] = 'Nuevo recurso';
		$url->addBreadcrumb(array('nombre' => 'Nuevo recurso', 'link' => 's/'.$controlador->name.'/nuevo/'.$obj['tipo']));
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
	$obj_categorias=$controlador->getPost_Categorias($obj['post_id']);
	$blog_categorias=$controlador->get_Categorias();

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
            	<h4>Recurso</h4>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Título</label>
                            <input type="text" class="form-control" name="titulo" value="<?=cleanOutput($obj['titulo']);?>">
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
                <p>Selecciona los distintos recursos asignados a esta página.</p>
            </div>
            <div class="col-sm-9">
                <div class="frecursos cfix">
					<?php
					$url_default=SIGA.'images/siga_default_file.png';
                    foreach($controlador->single_files_recurso as $key => $data){
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
		<hr>
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Visibilidad</h4>
                <p>Controla si el recurso estará disponible públicamente.</p>
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
                <p>Establece el título de tu página, la meta descripción y la URL amigable. Esto ayuda a definir como la página se muestra en los motores de búsqueda.</p>
            </div>
            <div class="col-sm-9">
				<div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">URL amigable</label>
                            <div class="input-group">
                              <span class="input-group-addon"><?=URL;?>informacion/</span>
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
			var $select = $('#blog_post_categorias').selectize({
				maxItems: 1,
				valueField: 'id',
				labelField: 'title',
				searchField: 'title',
				sortField: 'orden',
				options: [
					<?php
					$obc = 0;
					foreach($blog_categorias as $categoria){
						echo "{id: ".$categoria['categoria_id'].", title: '".$categoria['categoria_nombre']."', orden: ".$obc."},";
						$obc++;
					}
					?>
				],
				create: false
			});
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
