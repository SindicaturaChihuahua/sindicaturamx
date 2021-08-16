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

	if($accion=="red"){
		$response_json = false;
		$r = array(
			'red' => '',
			'enlace' => ''
		);
		$controlador->templateRedes($r);
	}else if($accion=="editar"){
		$updateData=array();
		$updateFields=array('site_title', 'contacto_email', 'contacto_nombre', 'ga_id_seguimiento', 'redes', 'keywords', 'description');

		if(!isset($_POST['site_title']) || !validaGeneral($_POST['site_title'],2,80)){
			$errores[]="Ingresa un título de página válido";
		}else{
			$updateData['site_title'] = $_POST['site_title'];
		}
		if(isset($_POST['contacto_nombre']) && validaGeneral($_POST['contacto_nombre'],1)){
			$updateData['contacto_nombre'] = $_POST['contacto_nombre'];
		}
		if(isset($_POST['contacto_email']) && validaGeneral($_POST['contacto_email'],3)){
			if(!validaMail($_POST['contacto_email'])){
				$errores[]="Ingresa un email de contacto válido";
			}else{
				$updateData['contacto_email'] = $_POST['contacto_email'];
			}
		}
		if(isset($_POST['ga_id_seguimiento']) && validaGeneral($_POST['ga_id_seguimiento'],5)){
			$updateData['ga_id_seguimiento'] = $_POST['ga_id_seguimiento'];
		}
		if(isset($_POST['disqus_id']) && validaGeneral($_POST['disqus_id'],1)){
			$updateData['disqus_id'] = $_POST['disqus_id'];
		}
		if(isset($_POST['keywords']) && validaGeneral($_POST['keywords'],1)){
			$updateData['keywords'] = $_POST['keywords'];
		}
		if(isset($_POST['description']) && validaGeneral($_POST['description'],1)){
			$updateData['description'] = $_POST['description'];
		}

		/* REDES */
		$redes=array();
		if(empty($errores) && isset($_POST['red_orden'])){
			$totalreds = count($_POST['red_orden']);
			$numerored = 0;
			if($totalreds>0){
				foreach($_POST['red_orden'] as $key => $orden){
					$numerored++;
					$redData=array();
					if(!isset($_POST['red_enlace'][$key]) || !validateURL($_POST['red_enlace'][$key])){
						$errores[]="Ingresa un enlace para la red #".$numerored;
					}else{
						$redData[] = $_POST['red_red'][$key];
						$redData[] = $_POST['red_enlace'][$key];
					}
					if(!empty($redData) && empty($errores)){
						$redes[] = $redData;
					}
				}
			}
			$updateData['redes'] = json_encode($redes);
		}

		if(!empty($updateData) && empty($errores)){
			$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET opcion_valor = :opcionvalor WHERE opcion_nombre = :opcionnombre");
			$stmt->BindParam(":opcionvalor",$data);
			$stmt->BindParam(":opcionnombre",$u);
			foreach($updateData as $u => $data){
				$stmt->execute();
			}
			$message='¡Los datos fueron actualizados!';
			$controlador->saveOpcionesJSON();
		}

	}else{
		$errores[]="Error Code: 000003258";
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
