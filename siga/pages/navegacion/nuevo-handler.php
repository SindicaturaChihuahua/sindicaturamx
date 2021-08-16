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
	
		$objeto = $controlador->getObjeto($_REQUEST['id']);
		if($objeto){
			if($accion=="editar"){ 
				$updateData=array();
				$updateFields=array('titulo', 'linka', 'target', 'status');
			
				if(!isset($_POST['titulo']) || !validaGeneral($_POST['titulo'],2,100)){
					$errores[]="Ingresa un título de enlace válido";
				}else{
					$updateData['titulo'] = $_POST['titulo'];
				}
				if(!isset($_POST['linka']) || !validaGeneral($_POST['linka'],1,350)){
					$errores[]="Ingresa un enlace válido";
				}else{
					$updateData['linka'] = $_POST['linka'];	
				}
				if(!isset($_POST['target']) || !isset($controlador->enlace_targets[$_POST['target']])){
					$errores[]="Selecciona una acción válida";	
				}else{
					$updateData['target'] = $_POST['target'];	
				}
				
				if(empty($errores)){
					if($objeto['status']=='revision'){
						$updateData['status'] = 'publicado';
						$reload=$url->createUrl($controlador->static_urls['editar']).'/'.$objeto['nav_id'];
					}
				}	
				
				if(!empty($updateData) && empty($errores)){
					$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET ".pdoSet($updateFields,$updateData,$updateData)." WHERE nav_id = :theobjid");
					$updateData['theobjid'] = $objeto['nav_id'];
					if($stmt->execute($updateData)){
						$message='¡El enlace fue actualizado!';
					}
				}

			}else if($accion=='delete-object'){
				$response_json=false;
				$delete_url=$url->createUrl($controlador->static_urls['nuevo-handler']).'?action=delete-object-confirm&id='.$objeto['nav_id'];
				?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Eliminar Enlace</h4>
				</div>
				<div class="modal-body">
					¿Estás seguro que deseas eliminar permanentemente este enlace?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-danger delete-object-btn-confirm" data-url="<?=$delete_url;?>">Eliminar</button>
				</div>
				<?
			}else if($accion=='delete-object-confirm'){
				$stmt = $db->prepare("DELETE FROM ".DB_PREFIX.$controlador->databaseprefix." WHERE nav_id = ?");
				if($stmt->execute(array($objeto['nav_id']))){
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