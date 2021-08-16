<?php
$reload=false;
$response_json=true;
$errores=array();
$message="";
$rawdata="";
$accion	= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'editar';

if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	if(isset($_GET['beta']) && $_GET['beta']=='valor'){
		if(isset($_GET['gama']) && $_GET['gama']=='nuevo' && isset($_GET['delta']) && is_numeric($_GET['delta']) && isset($_GET['epsilon']) && is_numeric($_GET['epsilon']) && $elpasoid = $controlador->newObject($_GET['delta'], "valores", $_GET['epsilon'])){
			$response_json=false;
			if($elpaso = $controlador->getObject($elpasoid, "valor_id", "valores", $_GET['epsilon'])){

				$deleteurl=$url->createUrl($controlador->static_urls['valor']).'/eliminar/';
				$controlador->templateElpaso($elpaso, $deleteurl,$_GET["epsilon"]);

			}else{
				echo "#001";
			}
		}else if(isset($_GET['gama']) && $_GET['gama']=='eliminar' && isset($_GET['delta']) && is_numeric($_GET['delta'])){

			$objeto = $controlador->getObject($_GET['delta'], "valor_id", "valores", $_GET['epsilon']);
			if($objeto){
				if($accion=='delete-object'){
					$response_json=false;
					$delete_url=$url->createUrl($controlador->static_urls['valor']).'/eliminar/'.$_GET['delta'].'/'.$_GET["epsilon"].'?action=delete-object-confirm';
					?>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title">Eliminar valor</h4>
					</div>
					<div class="modal-body">
						¿Estás seguro que deseas eliminar permanentemente este valor?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-danger delete-object-btn-confirm" data-url="<?=$delete_url;?>">Eliminar</button>
					</div>
					<?php
				}else if($accion=='delete-object-confirm'){
					$stmt = $db->prepare("DELETE FROM ".DB_PREFIX.$controlador->databaseprefix."_valores WHERE valor_id = ?");
					if($stmt->execute(array($objeto['valor_id']))){
						$message='#elpasoeliminada';
						$rawdata=$objeto['valor_id'];
					}else{
						$errores[]="Error al eliminar";
					}
				}else{
					$errores[]="Error Code: 000003258";
				}
			}else{
				$errores[]="Error Code: 000017231";
			}
		}else{
			echo "#000";
		}
	}else{
		echo "#000";
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
