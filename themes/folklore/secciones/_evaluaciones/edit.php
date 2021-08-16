<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){

	$action=array();
	$errores=array();
	$cHeaderData=array(
		'icono' => 'bar-chart',
	);
	$bodys->set( 'bottomjs', URLPLUGINS.'jqueryui/jquery-ui.min.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'ckeditor/ckeditor.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'selectize/selectize.min.js' );
	$bodys->set( 'bottomjs', 'pack-fileupload' );
	$bodys->set( 'bottomjs', URLPLUGINS.'fileupload/single-upload.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'fileupload/gallery-upload.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'timepicker/jquery-ui-timepicker-addon.js' );

	//Para BreadCrumbs
	if(isset($_GET['beta']) && $_GET['beta']=='editar' && isset($_GET['gama']) && $blog_post = $controlador->getPost($_GET['gama'])){
		$cHeaderData['titulo'] = 'Evaluaciones externas';
		$url->addBreadcrumb(array('nombre' => 'Evaluaciones externas', 'link' => 's/'.$controlador->name.'/editar/'.$blog_post['post_id']));
	}else if(isset($_GET['beta']) && $_GET['beta']=='nuevo' && $post_id = $controlador->newPost($user->uid)){
		$blog_post = $controlador->getPost($post_id);
		$cHeaderData['titulo'] = 'Nuevas evaluaciones';
		$url->addBreadcrumb(array('nombre' => 'Nuevas evaluaciones', 'link' => 's/'.$controlador->name.'/nuevo'));
	}else{
		header("Location: ".$url->createUrl($controlador->static_urls['lista']));
		exit;
	}

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
        <input type="hidden" name="id" value="<?=$blog_post['post_id'];?>" />
		<input type="hidden" class="form-control" name="titulo" value="<?=cleanOutput($blog_post['titulo']);?>">
        <?php /* <div class="row">
        	<div class="col-sm-3">
            	<h4>Imagen</h4>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Titulo (control interno)</label>
                            <input type="text" class="form-control" name="titulo" value="<?=cleanOutput($blog_post['titulo']);?>">
                        </div>
                    </div>
                </div>

        	</div>
		</div><!-- end big row -->
        <hr> */ ?>
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Enlaces</h4>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">CIMTRA</label>
                            <input type="text" class="form-control" name="btn_link" value="<?=cleanOutput($blog_post['btn_link']);?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">IMCO</label>
                            <input type="text" class="form-control" name="btn_link2" value="<?=cleanOutput($blog_post['btn_link2']);?>">
                        </div>
                    </div>
                </div>

        	</div>
		</div><!-- end big row -->
        <hr />
        <div class="row object-image-con">
        	<div class="col-sm-3">
            	<h4>Recursos</h4>
                <p></p>
            </div>
            <div class="col-sm-9">
                <div class="frecursos cfix">
					<?php
					$url_default=SIGA.'images/siga_default_file.png';
                    foreach($controlador->single_files as $key => $data){
                        echo '<div class="fsingle-upload" data-url="'.$url->createUrl($controlador->static_urls['single-upload-handler']).'" data-estilo="'.$key.'" data-id="'.$blog_post['post_id'].'">';
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
            	<h4>Visibilidad</h4>
                <p>Controla si esta imagen puede ser vista en tu sitio web.</p>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                        	<?php
							$estados_visibilidad=array(
								array("Oculto","oculto"),
								array("Publico","publico")
							);
							foreach($estados_visibilidad as $ev){
								echo '<div class="radio"><label>';
									if($blog_post['visibilidad']==$ev[1]){
										echo '<input type="radio" name="estadosVisibilidad" value="'.$ev[1].'" checked="checked">';
									}else{
										echo '<input type="radio" name="estadosVisibilidad" value="'.$ev[1].'">';
									}
								echo $ev[0].'</label></div>';
							}
							?>
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
					$delete_url=$url->createUrl($controlador->static_urls['nuevo-handler']).'?action=delete-object&id='.$blog_post['post_id'];
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
