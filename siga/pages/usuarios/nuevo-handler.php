<?php
$reload=false;
$response_json=true;
$errores=array();
$message="";
$rawdata="";
$accion	= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'editar';
$manageuser = new User($db);

if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	$_POST=limpiarCampos($_POST);
	if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
		$manageuser->getByUid($_REQUEST['id']);

		if($manageuser->exists && $manageuser->uid==$_REQUEST['id']){
			if($manageuser->nivel_acc<100 || $manageuser->uid==$user->uid){
				if($accion=="editar"){ 
					$updateData=array();
					if($manageuser->status==3){
						$updateFields=array('email', 'nombre', 'pseudonimo', 'username', 'password', 'status', 'registrado', 'nivel_acc', 'perms');
					}else{
						$updateFields=array('nombre', 'pseudonimo', 'password', 'nivel_acc', 'perms');
					}
					
					if(!isset($_POST['nombre']) || !validaGeneral($_POST['nombre'],2,100)){
						$errores[]="Por favor ingresa un nombre válido.";
					}else{
						$updateData['nombre'] = $_POST['nombre'];	
					}
					if(!isset($_POST['pseudonimo']) || !validaGeneral($_POST['pseudonimo'],2,38)){
						$errores[]="Por favor ingresa un seudónimo válido.";
					}else{
						$updateData['pseudonimo'] = $_POST['pseudonimo'];	
					}

					if($manageuser->status==3){//Nuevo usuario
						if(!isset($_POST['usuario']) || !usernameValido($_POST['usuario'])){
							$errores[]="Por favor ingresa un nombre de usuario válido.";
						}else{
							$usernameya=usernameExists($_POST['usuario']);	
							if($usernameya){
								$errores[]="El nombre de usuario ya está registrado, indique otro.";	
							}else{
								$updateData['username'] = $_POST['usuario'];	
							}
						}
						if(!isset($_POST['email']) || !validaMail($_POST['email'])){
							$errores[]="Por favor ingresa un correo electrónico válido.";
						}else{
							$emailya=emailExists($_POST['email']);	
							if($emailya){
								$errores[]="El correo electrónico ya está en uso, indique otro.";	
							}else{
								$updateData['email'] = $_POST['email'];		
							}
						}
					}
					
					if(empty($errores)){
						if(isset($_POST['contrasenanueva']) && isset($_POST['contrasenanueva2']) && passwordValido($_POST['contrasenanueva'])){
							if($_POST['contrasenanueva']==$_POST['contrasenanueva2']){
								$updateData['password'] = hashPassword($_POST['contrasenanueva']);
								$reload=$url->createUrl($controlador->static_urls['lista']);
							}else{
								$errores[]="Las contraseñas no coinciden.";	
							}
						}else{
							if($manageuser->status==3){
								$errores[]="Por favor ingresa una contraseña valida.";
							}
						}
					}	
					
					if(empty($errores)){
						$nivel_acc=50;
						$theperms="";
						if(isset($_POST['p']) && count($_POST['p'])>0){
							foreach($_POST['p'] as $p => $on){
								if(isset($controlador->permisos[$on])){
									if(($on > 50 && $on<100) || ($on==100 && $user->isFolklore())){
										$theperms.="##".$p;
										if($nivel_acc<$on){$nivel_acc=$on;}
									}
								}
							}
						}
						$updateData['nivel_acc'] = $nivel_acc;
						$updateData['perms'] = $theperms;	
						if($manageuser->status==3){
							$updateData['status'] = 1;
							$updateData['registrado'] = date("Y-m-d H:i:s");
						}
					}
					
					if(!empty($updateData) && empty($errores)){
						$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET ".pdoSet($updateFields,$updateData,$updateData)." WHERE uid = :theobjid");
						$updateData['theobjid'] = $manageuser->uid;
						if($stmt->execute($updateData)){
							$message='¡Tus datos fueron actualizados!';
						}
					}
					
				}else if($accion=='delete-object'){
					$response_json=false;
					$delete_url=$url->createUrl($controlador->static_urls['nuevo-handler']).'?action=delete-object-confirm&id='.$manageuser->uid;
					?>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title">Eliminar Usuario</h4>
					</div>
					<div class="modal-body">
						¿Estás seguro que deseas eliminar permanentemente al usuario <b><?=$manageuser->nombre;?></b>?
	                    <p>Esta operacion no podrá ser deshecha. Las siguientes acciones se llevaran a cabo:</p>
	                    <ul>
	                    	<li>Se revocara por completo el acceso al sistema a esta cuenta</li>
	                        <li>El nombre de usuario y correo electrónico se liberaran para un futuro uso</li>
	                        <li>Las notas creadas por esta cuenta seguirán mostrando el seudónimo <b><?=$manageuser->pseudonimo;?></b></li>
	                    </ul>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-danger delete-object-btn-confirm" data-url="<?=$delete_url;?>">Eliminar</button>
					</div>
					<?php
				}else if($accion=='delete-object-confirm'){
					$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET username = ?, email = ?, password = ?, nivel_acc = ?, perms = ?, status = ? WHERE uid = ?");
					if($stmt->execute(array('eliminado_'.$manageuser->username, 'eliminado_'.$manageuser->email, hashPassword(crearCadena(15)), 0, '', 0, $manageuser->uid))){
						$message='';
					}
					$reload=$url->createUrl($controlador->static_urls['lista']);
				}else{
					$errores[]="Error Code: 000003258";	
				}
			}else{
				$errores[]="No puedes manipular la informacion de este usuario.";	
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