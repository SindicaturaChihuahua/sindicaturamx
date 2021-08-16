<?php
$response_json=true;
$errores=array();
$data=array();
$message="";
$rawhtml="";
$mustlogin=true;
$reload="";

$mdcontenido = "";
if(isset($_GET['action']) && $_GET['action'] == "sincronizar" && isset($_GET['mc'])){
	// Sincronizar contactos 
	require_once DMINCLUDES . 'Mailchimp/MailChimp.php';
	require_once DMINCLUDES . 'Mailchimp/Batch.php';
	$MailChimp = new \DrewM\MailChimp\MailChimp('f91e1590a2670ad8b7ee3394ac543384-us20');


	$fechamodificado2 = date("Y-m-d H:i:s");
	$fechamodificado1 = strtotime($fechamodificado2) - 3600;
	$fechamodificado1 = date("Y-m-d H:i:s", $fechamodificado1);
	$stmt = $db->prepare("SELECT comision_id, nombre, correo FROM ".DB_PREFIX."s_registros WHERE modificado >= ? AND modificado <= ? LIMIT 5");
	$stmt->execute(array($fechamodificado1, $fechamodificado2));
	$contactos=$stmt->FetchAll();

	$totalcontactosvalidos = count($contactos);
	$operacion = 0;
	if($totalcontactosvalidos>0){
		// $Batch = $MailChimp->new_batch();
		foreach ($contactos as $contacto) {
			$operacion++;
			if($contacto['nombre']==""){
				$nombres = array($contacto['correo'], "");
			}else{
				$nombres = explode(" ",$contacto['nombre']);
				if(!isset($nombres[1])){
					$nombres[1] = $nombres[0];
				}
			}
			$chash = md5($contacto['correo']);
			$mccontacto = $MailChimp->get("lists/7f4fd6e1e9/members/".$chash, [
			]);
			if($MailChimp->success()){
				// Existe
				$creartag = $MailChimp->post("lists/7f4fd6e1e9/members/".$chash."/tags", [
					'tags' => array(array("name" => 'com-'.$contacto['comision_id'], "status" => "active"))
				]);
			}else{
				// echo "Error: " . $MailChimp->getLastError();
				// No existe, crear
				$crearcontacto = $MailChimp->post("lists/7f4fd6e1e9/members", [
					'email_address' => $contacto['correo'],
					'status' => 'subscribed',
					'merge_fields'  => ['FNAME'=>$nombres[0], 'LNAME'=>$nombres[1]],
					'tags' => array('com-'.$contacto['comision_id'])
				]);
			}
			// $Batch->put("op".$operacion, "lists/7f4fd6e1e9/members", [
			// 	'email_address' => $contacto['correo'],
			// 	'status' => 'subscribed',
			// 	'merge_fields'  => ['FNAME'=>$nombres[0], 'LNAME'=>$nombres[1]],
			// 	'tags'			=> array('com-'.$contacto['comision_id'])
			// ]);
		}
		// $result = $Batch->execute();
	}
	echo 'op: '.$operacion;
}
?>
