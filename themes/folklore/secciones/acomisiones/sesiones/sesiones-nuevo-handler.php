<?php
$reload=false;
$response_json=true;
$errores=array();
$message="";
$rawdata="";
$accion	= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'editar';

if(logeado() && $user->exists && $user->hasRole("EditorEnJefe")){

	$_POST=limpiarCampos($_POST);
	if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){

		$obj = $controlador->getSesion($_REQUEST['id']);

		if($obj){
			if($accion=="editar"){
				$updateData=array();
				$updateFields=array('comision_id','sesion_nombre', 'sesion_video', 'sesion_slug', 'visibilidad', 'creado', 'modificado', 'status');

				if(!isset($_POST['sesion_nombre']) || !validaGeneral($_POST['sesion_nombre'],2,280)){
					$errores[]="Ingresa un nombre válido";
				}else{
					$updateData['comision_id'] = $_POST['comision_id'];
					$updateData['sesion_nombre'] = $_POST['sesion_nombre'];
					$updateData['sesion_video'] = $_POST['sesion_video'];


					if(!isset($_POST['sesion_slug']) || $_POST['sesion_slug']==""){
						$_POST['sesion_slug']=crea_amigableUrl($_POST['titulo'],80);
					}
					if(!validaGeneral($_POST['sesion_slug'],3,80)){
						$_POST['sesion_slug']=crea_amigableUrl($_POST['sesion_slug'],80);
					}
					$slug=$_POST['sesion_slug'];
					while($controlador->existsUniqueSlugSesion($slug,$obj['sesion_id'])){
						$slug = $controlador->generateUniqueSlug($slug);
					}
					$updateData['sesion_slug'] = $slug;
				}
				if(!isset($_POST['estadosVisibilidad']) || $_POST['estadosVisibilidad']==""){
					$errores[]="Selecciona la visibilidad de la sesión";
				}else{
					if(strpos($_POST['estadosVisibilidad'], "principal-")!==false){
						$updateData['visibilidad'] = 'publico';
						$posicion = explode("-", $_POST['estadosVisibilidad']);
						$destacarnota = $posicion[1];
					}else{
						$updateData['visibilidad'] = $_POST['estadosVisibilidad'];
					}
				}

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

				if(!empty($updateData) && empty($errores)){
					$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix."_sesiones SET ".pdoSet($updateFields,$updateData,$updateData)." WHERE sesion_id = :theobjid");
					$updateData['theobjid'] = $obj['sesion_id'];
					if($stmt->execute($updateData)){
						$message='¡La sesión fue actualizada!';
						$reload=$url->createUrl($controlador->static_urls['sesiones']);

					}
				}

			}else if($accion=='delete-object'){
				$response_json=false;
				$delete_url=$url->createUrl($controlador->static_urls['sesiones-nuevo-handler']).'?action=delete-object-confirm&id='.$obj['sesion_id'];
				?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Eliminar Entrada</h4>
				</div>
				<div class="modal-body">
					¿Estás seguro que deseas eliminar permanentemente esta sesión?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-danger delete-object-btn-confirm" data-url="<?=$delete_url;?>">Eliminar</button>
				</div>
				<?php
			}else if($accion=='delete-object-confirm'){
				$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix."_sesiones SET status = ? WHERE sesion_id = ?");
				if($stmt->execute(array('eliminado',$obj['sesion_id']))){
					$message='';
				}
				$reload=$url->createUrl($controlador->static_urls['sesiones']);
			}else{
				$errores[]="Error Code: 000003258";
			}
		}else{
			$errores[]="Error Code: 000004255";
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
