<?php
$tipo='miembro';
$reload=false;
$response_json=true;
$errores=array();
$message="";
$rawdata="";
$accion	= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'editar';

if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){

	$_POST=limpiarCampos($_POST);
	if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){

		$obj = $controlador->getPost($_REQUEST['id']);
		if($obj && $obj['tipo']==$tipo){

			if($obj['autor_id'] == $user->uid || $user->hasRole("EditorEnJefe")){
				if($accion=="editar"){
					$updateData=array();
					$updateData['extra']=array();
					$updateFields=array('autor_id', 'titulo', 'tagline', 'descripcion','sueldo', 'enlaces', 'slug', 'seo_titulo','seo_descripcion', 'visibilidad', 'creado', 'modificado', 'status', 'extra');

					if(!isset($_POST['titulo']) || !validaGeneral($_POST['titulo'],3,280)){
						$errores[]="Ingresa un título válido";
					}else{
						$updateData['titulo'] = $_POST['titulo'];
						$updateData['tagline'] = $_POST['tagline'];
						$updateData['descripcion'] = $_POST['descripcion'];
	                    $updateData['sueldo'] = $_POST['sueldo'];
						$updateData["enlaces"][0]["url"] = $_POST["enlaces_facebook"];
						$updateData["enlaces"][0]["icon"] = 'facebook-f';
						$updateData["enlaces"][1]["url"] = $_POST["enlaces_twitter"];
						$updateData["enlaces"][1]["icon"] = 'twitter';
						$updateData["enlaces"][2]["url"] = $_POST["enlaces_instagram"];
						$updateData["enlaces"][2]["icon"] = 'instagram';
						$updateData["enlaces"][3]["url"] = $_POST["enlaces_linkedin"];
						$updateData["enlaces"][3]["icon"] = 'linkedin-in';
						$updateData["enlaces"][4]["url"] = $_POST["enlaces_web"];
						$updateData["enlaces"][4]["icon"] = 'globe';
						$updateData['enlaces'] = json_encode($updateData["enlaces"], true);


						if(!isset($_POST['slug']) || $_POST['slug']==""){
							$_POST['slug']=crea_amigableUrl($_POST['titulo'],80);
						}
						if(!validaGeneral($_POST['slug'],3,80)){
							$_POST['slug']=crea_amigableUrl($_POST['slug'],80);
						}
						$slug=$_POST['slug'];
						while($controlador->existsUniqueSlug($slug,$obj['post_id'])){
							$slug = $controlador->generateUniqueSlug($slug);
						}
						$updateData['slug'] = $slug;
					}
					if(!isset($_POST['seo_titulo']) || $_POST['seo_titulo']==""){
						$_POST['seo_titulo']=$_POST['titulo'];
					}
					if(!validaGeneral($_POST['seo_titulo'],1,280)){
						$errores[]="Ingresa un título de página válido";
					}else{
						$updateData['seo_titulo'] = $_POST['seo_titulo'];
					}
					if(!isset($_POST['seo_descripcion']) || $_POST['seo_descripcion']==""){
						$_POST['seo_descripcion']=truncar_cadena(strip_tags($_POST['descripcion']),160," ","");
					}
					if(!validaGeneral($_POST['seo_descripcion'],0,210)){
						$errores[]="Ingresa una Meta descripción válida";
					}else{
						$updateData['seo_descripcion'] = $_POST['seo_descripcion'];
					}
					if(isset($_POST['seo_tags'])){
						$updateData['extra']['seo_tags']=$_POST['seo_tags'];
					}
					if(!isset($_POST['estadosVisibilidad']) || $_POST['estadosVisibilidad']==""){
						$errores[]="Selecciona la visibilidad de la entrada";
					}else{
						if(strpos($_POST['estadosVisibilidad'], "principal-")!==false){
							$updateData['visibilidad'] = 'publico';
							$posicion = explode("-", $_POST['estadosVisibilidad']);
							$destacarnota = $posicion[1];
						}else{
							$updateData['visibilidad'] = $_POST['estadosVisibilidad'];
						}
					}
					if(isset($_POST['extra_comentarios'])){
						$updateData['extra']['allowcomments']=$_POST['extra_comentarios'];
					}else{
						$updateData['extra']['allowcomments']=$controlador->estados_comentarios_default;
					}

					//Insertar y remover categorias

					$obj_categorias=$controlador->getPost_Categorias($obj['post_id']);
					$categorias_finales=$controlador->nuevo_categoriasFinales($obj_categorias,$_POST['s_categorias']);

					if(count($categorias_finales['finales'])>24){
						$errores[]="Rebasaste el máximo de categorías permitidas";
					}
					//fin categorias

					if(empty($errores)){
						if($obj['status']=='revision'){
							$updateData['creado'] = getFechaActualSinDiferencia();
							$updateData['modificado'] = getFechaActualSinDiferencia();
							$updateData['status'] = 'publicado';
						}
						//Fijar Fecha
						if(isset($_POST['fijartime']) && datetimeReal($_POST['fijartime'])){
							if($obj['modificado']!=$_POST['fijartime']){
								$updateData['modificado'] = addDiffFormatDate($_POST['fijartime']);
							}
						}else{
							if($obj['status']!='revision'){
								$errores[]="Ingresa la fecha en formato válido";
							}
						}
					}
					if(empty($errores)){
						$controlador->nuevo_insertarcategoriasFinales($obj['post_id'],$categorias_finales);
					}

					if(!empty($updateData) && empty($errores)){
						if(isset($updateData['extra'])){
							$updateData['extra']=json_encode($updateData['extra']);
						}
						$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET ".pdoSet($updateFields,$updateData,$updateData)." WHERE post_id = :theobjid");
						$updateData['theobjid'] = $obj['post_id'];
						if($stmt->execute($updateData)){
							$message='¡La entrada fue actualizada!';
							$reload=$url->createUrl($controlador->static_urls['lista']);

						}
					}

				}else if($accion=='delete-object'){
					$response_json=false;
					$delete_url=$url->createUrl($controlador->static_urls['nuevo-handler']).'/'.$tipo.'?action=delete-object-confirm&id='.$obj['post_id'];
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
					if($stmt->execute(array('eliminado',$obj['post_id']))){
						$message='';
					}
					$reload=$url->createUrl($controlador->static_urls['lista']);
				}else{
					$errores[]="Error Code: 000003258";
				}
			}else{
				$errores[]="No cuentas con los permisos necesarios para modificar este contenido.";
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
