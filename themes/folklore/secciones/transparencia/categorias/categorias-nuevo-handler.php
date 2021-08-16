<?php
$reload=false;
$response_json=true;
$errores=array();
$havecover=array();
$message="";
$rawdata="";
$accion	= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'editar';

if(logeado() && $user->exists && $user->hasRole("EditorEnJefe")){
	$_POST=limpiarCampos($_POST);
	if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){

		$object = $controlador->getCategoria($_REQUEST['id']);
		if($object){
			if($accion=="editar"){
				$updateData=array();
				$updateFields=array('categoria_nombre', 'categoria_slug', 'status');


				if(!isset($_POST['categoria_nombre']) || !validaGeneral($_POST['categoria_nombre'],2,48)){
					$errores[]="Ingresa un nombre válido";
				}else{
					$updateData['categoria_nombre'] = $_POST['categoria_nombre'];
					if(!isset($_POST['categoria_slug']) || $_POST['categoria_slug']==""){
						$_POST['categoria_slug']=crea_amigableUrl($_POST['categoria_nombre'],58);
					}
					if(!validaGeneral($_POST['categoria_slug'],2,58)){
						$_POST['categoria_slug']=crea_amigableUrl($_POST['categoria_slug'],100);
					}
					$slug=$_POST['categoria_slug'];
					while($controlador->existsUniqueSlugCategoria($slug,$object['categoria_id'])){
						$slug = $controlador->generateUniqueSlug($slug);
					}
					$updateData['categoria_slug'] = $slug;
				}


				if(empty($errores)){
					if($object['status']=='revision'){
						$updateData['status'] = 'publicado';
						$reload=$url->createUrl($controlador->static_urls['categorias']);
					}
				}

				if(!empty($updateData) && empty($errores)){
					$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix."_categorias SET ".pdoSet($updateFields,$updateData,$updateData)." WHERE categoria_id = :theobjid");
					$updateData['theobjid'] = $object['categoria_id'];
					if($stmt->execute($updateData)){
						$message='¡La categoría fue actualizada!';
					}
				}

			}else if($accion=='delete-object' && false){
				$response_json=false;
				$delete_url=$url->createUrl($controlador->static_urls['categorias-nuevo-handler']).'?action=delete-object-confirm&id='.$object['categoria_id'];
				?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Eliminar Categoría</h4>
				</div>
				<div class="modal-body">
					¿Estás seguro que deseas eliminar permanentemente esta categoría?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-danger delete-object-btn-confirm" data-url="<?=$delete_url;?>">Eliminar</button>
				</div>
				<?php
			}else if($accion=='delete-object-confirm' && false){
				$stmt = $db->prepare("DELETE FROM ".DB_PREFIX.$controlador->databaseprefix."_relation WHERE categoria_id = ?");
				if($stmt->execute(array($object['categoria_id']))){
					$stmt = $db->prepare("DELETE FROM ".DB_PREFIX.$controlador->databaseprefix."_categorias WHERE categoria_id = ?");
					if($stmt->execute(array($object['categoria_id']))){
						$message='';
					}
				}
				$reload=$url->createUrl($controlador->static_urls['categorias']);
			}else{
				$errores[]="Error Code: 000004258";
			}
		}else{
			$errores[]="Error Code: 000004255";
		}
	}
}else{
	$errores[]="Error Code: 000004253";
}

if(count($errores)>0){$message=implode("<br>",$errores);}
if($response_json){
	echo json_encode(
		array(
			"message"=>$message,
			"errores"=>count($errores),
			"rawdata"=>$rawdata,
			"reload"=>$reload
		)
	);
}
?>
