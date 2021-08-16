<?php
$reload=false;
$response_json=true;
$errores=array();
$havecover=array();
$message="";
$rawdata="";
$accion	= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'editar';

if(logeado() && $user->exists){
	$_POST=limpiarCampos($_POST);
	if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){

		if($user->uid==$_REQUEST['id']){
			if($accion=="editar"){ 
				$updateData=array();
				$updateFields=array('password', 'nombre');
				
				if(!isset($_POST['nombre']) || !validaGeneral($_POST['nombre'],2)){
					$errores[]="Por favor ingresa un nombre válido.";
				}else{
					$updateData['nombre'] = $_POST['nombre'];	
				}
				
				if(empty($errores)){
					if(isset($_POST['contrasena']) && passwordValido($_POST['contrasena'])){
						if(isset($_POST['contrasenanueva']) && isset($_POST['contrasenanueva2']) && passwordValido($_POST['contrasenanueva'])){
							if($_POST['contrasenanueva']==$_POST['contrasenanueva2']){
								if(hashPassword($_POST['contrasena'])==$user->password){
									$updateData['password'] = hashPassword($_POST['contrasenanueva']);
									$reload=$url->createUrl($controlador->static_urls['lista']);
								}else{
									$errores[]="La contraseña actual no coincide con la de tu cuenta";
								}
							}else{
								$errores[]="Las contraseñas no coinciden.";	
							}
						}else{
							$errores[]="Por favor ingresa una nueva contraseña valida.";	
						}
					}
				}	
				
				if(!empty($updateData) && empty($errores)){
					$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET ".pdoSet($updateFields,$updateData,$updateData)." WHERE uid = :theobjid");
					$updateData['theobjid'] = $user->uid;
					if($stmt->execute($updateData)){
						$message='¡Tus datos fueron actualizados!';
					}
				}

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