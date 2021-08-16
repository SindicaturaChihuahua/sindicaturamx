<?php
$options = array();
$access_granted=false;
$reload=false;
$response_json=true;
$errores=array();
$message="";

if(logeado() && $user->exists && isset($_REQUEST['takedata']) && isset($_REQUEST['fuid']) && isset($_REQUEST['token'])){
	$tokendata = csrfVerify($_REQUEST['futipo'].''.$_REQUEST['fuid'], $_REQUEST['token']);
	if(!empty($tokendata)){
		$options = array_merge($options, $tokendata['options']);
		if($tokendata['uid']==$user->uid && $_REQUEST['fuid']==$tokendata['id'] && $_REQUEST['futipo']==$tokendata['tipo']){
			$access_granted=true;
		}
	}
}

if($access_granted){
	
	if($user->hasRole($controlador->permiso) && isset($_POST['action']) && $_POST['action']=="reorder" && isset($_POST['order']) && count($_POST['order'])>0){
		$arbol=array();
		$campos = array();
		parse_str($_POST['order'], $campos);
		$arbol = $controlador->generateSigaMenu($campos);

		$arbol=json_encode($arbol);
		$stmt = $db->prepare("UPDATE ".$options['db_table']." SET opcion_valor = :orden WHERE opcion_nombre = :opcionnombre");
		$stmt->BindParam(":orden",$arbol);
		$stmt->BindParam(":opcionnombre",$options['opcion_nombre']);
		$stmt->execute();
		
		$message="¡Los cambios se guardaron exitosamente!";
	}
	
}

if(count($errores)>0){$message=implode("<br>",$errores);}
if($response_json){
	echo json_encode(
		array(
			"message"=>$message,
			"errores"=>count($errores),
			"reload"=>$reload
		)
	);
}
?>