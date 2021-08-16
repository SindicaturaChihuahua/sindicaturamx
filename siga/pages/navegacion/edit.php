<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	
	$action=array();
	$errores=array();
	$cHeaderData=array(
		'icono' => 'plus-square',
	);

	//Para BreadCrumbs
	if(isset($_GET['beta']) && $_GET['beta']=='editar' && isset($_GET['gama']) && $objeto = $controlador->getObjeto($_GET['gama'])){
		$action['real-accion']='editar';
		$cHeaderData['titulo'] = 'Editar enlace';
		$url->addBreadcrumb(array('nombre' => 'Editar enlace', 'link' => 's/'.$controlador->name.'/editar/'.$objeto['nav_id']));
	}else if(isset($_GET['beta']) && $_GET['beta']=='nuevo' && $post_id = $controlador->newObjeto($user->uid)){
		$objeto = $controlador->getObjeto($post_id);
		$action['real-accion']='nuevo';
		$cHeaderData['titulo'] = 'Nuevo enlace';
		$url->addBreadcrumb(array('nombre' => 'Nuevo enlace', 'link' => 's/'.$controlador->name.'/nuevo'));
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
        <input type="hidden" name="id" value="<?=$objeto['nav_id'];?>" />
        <input type="hidden" name="tipo" value="web-address" />
        <div class="row">
        	<div class="col-sm-3">
            	<h4>Describe tu enlace</h4>
                <p>Indica el título, dirección y acción del enlace.</p>
                <p>Puedes agregar enlaces a páginas internas y externas.</p>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Titulo del enlace</label>
                            <input type="text" class="form-control" name="titulo" value="<?=cleanOutput($objeto['titulo']);?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Enlaza a...</label>
                            <input type="text" class="form-control" name="linka" value="<?=cleanOutput($objeto['linka']);?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Acción</label>
                            <select class="form-control" name="target">
                            	<?php
								foreach($controlador->enlace_targets as $key => $accion){
									if($key==$objeto['target']){
										echo '<option value="'.$key.'" selected="selected">'.$accion.'</option>';
									}else{
										echo '<option value="'.$key.'">'.$accion.'</option>';
									}
								}
								?>
                            </select>
                        </div>
                    </div>
                </div>
        	</div>
		</div><!-- end big row -->
        <hr />
        <div class="row">
        	<div class="col-sm-3">
            	<?php
				if($action['real-accion']=='editar'){
					$delete_url=$url->createUrl($controlador->static_urls['nuevo-handler']).'?action=delete-object&id='.$objeto['nav_id'];
					echo '<a href="'.$delete_url.'" class="btn btn-default blockatsubmit action delete-object-btn">Eliminar Enlace</a>';
				}
				?>
            </div>
        	<div class="col-sm-9">
                <div class="form-group">
                	<button type="submit" class="btn btn-primary blockatsubmit" name="save">Guardar Enlace</button> <img src="<?=SIGA;?>images/loading.gif" class="floading" />
                </div>
			</div> 
        </div><!-- end big row -->
        </form>
        
		<?php
	echo '</div>';

}else{
	include('noaccess.php');
}
?>