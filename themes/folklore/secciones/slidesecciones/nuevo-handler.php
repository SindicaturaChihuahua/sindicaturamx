<?php
$reload=false;
$response_json=true;
$errores=array();
$havecover=array();
$message="";
$rawdata="";
$accion	= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'editar';

if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	$_POST=limpiarCampos($_POST);
	if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){

		$blog_post = $controlador->getPost($_REQUEST['id']);
		if($blog_post){
			if($accion=="editar"){
				$updateData=array();
				$updateData['extra']=array();
				$updateFields=array('titulo', 'descripcion', 'btn_texto', 'btn_link', 'slug', 'visibilidad', 'creado', 'modificado', 'status');

				if(!isset($_POST['titulo']) || !validaGeneral($_POST['titulo'],3,280)){
					$errores[]="Ingresa un título válido";
				}else{
					$updateData['titulo'] = $_POST['titulo'];
					$updateData['descripcion'] = $_POST['descripcion'];
					$updateData['btn_texto'] = $_POST['btn_texto'];
					$updateData['btn_link'] = $_POST['btn_link'];
					if(!isset($_POST['slug']) || $_POST['slug']==""){
						$_POST['slug']=crea_amigableUrl($_POST['titulo'],100);
					}
					if(!validaGeneral($_POST['slug'],3,100)){
						$_POST['slug']=crea_amigableUrl($_POST['slug'],100);
					}
					$slug=$_POST['slug'];
					while($controlador->existsUniqueSlug($slug,$blog_post['post_id'])){
						$slug = $controlador->generateUniqueSlug($slug);
					}
					$updateData['slug'] = $slug;
					$updateData['visibilidad'] = $_POST['estadosVisibilidad'];
				}




				if(empty($errores)){
					if($blog_post['status']=='revision'){
						$updateData['creado'] = date("Y-m-d H:i:s");
						$updateData['modificado'] = date("Y-m-d H:i:s");
						$updateData['status'] = 'publicado';
					}
				}

				if(!empty($updateData) && empty($errores)){
					$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET ".pdoSet($updateFields,$updateData,$updateData)." WHERE post_id = :theobjid");
					$updateData['theobjid'] = $blog_post['post_id'];
					if($stmt->execute($updateData)){
						$message='¡La entrada fue actualizada!';
						$reload=$url->createUrl($controlador->static_urls['lista']);
					}
				}

			}else if($accion=='delete-object'){
				$response_json=false;
				$delete_url=$url->createUrl($controlador->static_urls['nuevo-handler']).'?action=delete-object-confirm&id='.$blog_post['post_id'];
				?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Eliminar Entrada</h4>
				</div>
				<div class="modal-body">
					¿Estás seguro que deseas eliminar permanentemente esta entrada?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-danger delete-object-btn-confirm" data-url="<?=$delete_url;?>">Eliminar</button>
				</div>
				<?php
			}else if($accion=='delete-object-confirm'){
				$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET status = ? WHERE post_id = ?");
				if($stmt->execute(array('eliminado',$blog_post['post_id']))){
					$message='';
				}
				$reload=$url->createUrl($controlador->static_urls['lista']);
			}else{
				$errores[]="Error Code: 000003258";
			}
		}else{
			$errores[]="Error Code: 000003252";
		}
	}
}else{
	$errores[]="Error Code: 000003253";
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
