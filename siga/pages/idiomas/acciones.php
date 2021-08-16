<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	$goto=SIGA.'p/idiomas/';
	$action=array();
	$errores=array();
	
	//PROCESAMIENTO
	if(isset($_GET['beta']) && $_GET['beta']=='acciones' && isset($_GET['gama'])){
		if($stmt = $db->prepare("SELECT * FROM ".DB_PREFIX."idiomas WHERE idioma_id = ? LIMIT 1")){
			$stmt->execute(array($_GET['gama']));
			$idioma=$stmt->Fetch();
		}else{
			$errores[]='Código de error #: pia-002';
		}
		
		if(empty($errores) && !empty($idioma)){
			if(isset($_GET['activo'])){
				if($_GET['activo']=='no'){
					if($opcionesfull['opciones']['idioma_default']!=$idioma['slug']){
						$nuevostatus = 'borrador';
					}else{
						$errores[]='No puedes desactivar un idioma que está marcado como idioma principal.';
					}
				}else{
					$nuevostatus = 'publicado';
				}
				if(empty($errores) && $stmt = $db->prepare("UPDATE ".DB_PREFIX."idiomas SET status = ? WHERE idioma_id = ?")){
					$stmt->execute(array($nuevostatus,$idioma['idioma_id']));
					c_OkeySession(array(__('general_successful')));
				}
			}else if(isset($_GET['default']) && $_GET['default']=='si'){
				if($idioma['status']=='publicado'){
					if($stmt = $db->prepare("UPDATE ".DB_PREFIX."opciones SET opcion_valor = ? WHERE opcion_nombre = ? LIMIT 1")){
						$stmt->execute(array($idioma['slug'],'idioma_default'));
						c_OkeySession(array(__('general_successful')));
					}
				}else{
					$errores[]='Debes activar el idioma para marcarlo como idioma principal.';
				}
			}else{
				$errores[]='Código de error #: pia-0004';
			}
		}
	}else{
		$errores[]='Código de error #: pia-0001';
	}
	//FIN PROCESAMIENTO


	if(!empty($errores)){
		c_ErrorSession($errores);	
	}
	header("Location: ".$goto);exit;
}else{
	include('noaccess.php');
}
?>