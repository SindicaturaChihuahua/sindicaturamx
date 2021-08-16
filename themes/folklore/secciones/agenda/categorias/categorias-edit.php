<?php
if(logeado() && $user->exists && $user->hasRole("EditorEnJefe")){

	$action=array();
	$errores=array();
	$cHeaderData=array(
		'icono' => 'tag',
	);
	$bodys->set( 'bottomjs', URLPLUGINS.'ckeditor/ckeditor.js' );
	$bodys->set( 'bottomjs', 'pack-fileupload' );
	$bodys->set( 'bottomjs', URLPLUGINS.'fileupload/single-upload.js' );

	//Para BreadCrumbs
	if(isset($_GET['gama']) && $_GET['gama']=='editar' && isset($_GET['delta']) && $object = $controlador->getCategoria($_GET['delta'])){
		$cHeaderData['titulo'] = 'Editar categoría';
		$url->addBreadcrumb(array('nombre' => 'Categorías', 'link' => $controlador->static_urls['categorias']));
		$url->addBreadcrumb(array('nombre' => 'Editar categoría', 'link' => $controlador->static_urls['categorias-editar'].'/'.$object['categoria_id']));
	}else if(isset($_GET['gama']) && $_GET['gama']=='nuevo' && $categoria_id = $controlador->newCategoria()){
		$object = $controlador->getCategoria($categoria_id);
		$cHeaderData['titulo'] = 'Nueva categoría';
		$url->addBreadcrumb(array('nombre' => 'Categorías', 'link' => $controlador->static_urls['categorias']));
		$url->addBreadcrumb(array('nombre' => 'Nueva categoría', 'link' => $controlador->static_urls['categorias-nuevo']));
	}else{
		header("Location: ".$url->createUrl($controlador->static_urls['categorias']));
		exit;
	}

	$action['accion']="editar";
	$action['formaccion']=$url->createUrl($controlador->static_urls['categorias-nuevo-handler']);

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
        <input type="hidden" name="id" value="<?=$object['categoria_id'];?>" />
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Describe la categoría</h4>
                <p>Indica un nombre y otros datos para tu categoría.</p>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input type="text" class="form-control" name="categoria_nombre" value="<?=cleanOutput($object['categoria_nombre']);?>">
                        </div>
						<?php /*
						<div class="form-group">
                            <label class="control-label">Descripción</label>
                            <textarea name="categoria_descripcion" class="aneditor" autocomplete="off"><?=$object['categoria_descripcion'];?></textarea>
                        </div> */ ?>
                        <div class="form-group">
                            <label class="control-label">URL Amigable</label>
                            <input type="text" class="form-control" name="categoria_slug" value="<?=cleanOutput($object['categoria_slug']);?>">
                        </div>
                    </div>
                </div>
        	</div>
		</div><!-- end big row -->
		<hr>
		<div class="row">
        	<div class="col-sm-3">
            	<h4>Color</h4>
                <p>Elige un color que distinga la categoría.</p>
            </div>
            <div class="col-sm-9">
				<div class="row">
					<?php
					foreach ($controlador->colores as $color => $hex){
						$checked = '';
						if($object["categoria_formato"] == $color){
							$checked = " checked";
						}
						echo '<div class="col-sm-3">
							<label style="display:block; width:100%; text-align:center; cursor:pointer;">
								<div style="width:100%; padding-top:35%; background:'.$hex.';"></div>
								<input type="radio" name="categoria_formato" value="'.$color.'"'.$checked.'>
							</label>
						</div>';
					}
					?>
				</div>
        	</div>
		</div><!-- end big row -->
		<?php /*
		<hr>
        <div class="row object-image-con">
        	<div class="col-sm-3">
            	<h4>Recursos</h4>
                <p>Selecciona los distintos recursos para la categoría.</p>
            </div>
            <div class="col-sm-9">
                <div class="frecursos cfix">
					<?php
					$url_default=SIGA.'images/siga_default_file.png';
                    foreach($controlador->categorias_single_files as $key => $data){
                        echo '<div class="fsingle-upload" data-url="'.$url->createUrl($controlador->static_urls['categorias-single-upload-handler']).'" data-estilo="'.$key.'" data-id="'.$object['categoria_id'].'">';
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
		*/ ?>
        <hr />
        <div class="row">
        	<div class="col-sm-3">
            	<?php
				if(false && $_GET['gama']=='editar'){
					$delete_url=$url->createUrl($controlador->static_urls['categorias-nuevo-handler']).'?action=delete-object&id='.$object['categoria_id'];
					echo '<a href="'.$delete_url.'" class="btn btn-default blockatsubmit action delete-object-btn">Eliminar Categoría</a>';
				}
				?>
            </div>
        	<div class="col-sm-9">
                <div class="form-group">
                	<button type="submit" class="btn btn-primary blockatsubmit" name="save">Guardar Categoría</button> <img src="<?=SIGA;?>images/loading.gif" class="floading" />
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
