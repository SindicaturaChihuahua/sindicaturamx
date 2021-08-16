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

	if($user->hasRole("Editor") && isset($_POST['action']) && $_POST['action']=="reorder" && isset($_POST['order']) && count($_POST['order'])>0){
		$campos = array();
		parse_str($_POST['order'], $campos);
		$stmt = $db->prepare("UPDATE ".$options['db_table']." SET orden = :orden WHERE categoria_id = :objid");
		$stmt->BindParam(":orden",$index);
		$stmt->BindParam(":objid",$objid);
		foreach($campos['obj'] as $index => $objid){
			if(isset($objid) && is_numeric($objid) && $objid!=0){
				$stmt->execute();
			}
		}
		$message="##ok";
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
