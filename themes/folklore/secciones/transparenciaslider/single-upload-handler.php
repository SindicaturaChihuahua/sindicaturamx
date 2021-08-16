<?php
require_once( DMINCLUDES . 'controlimagen.php' );

$access_granted=false;
$errores=array();
$attach=false;
$file_generated=false;
$preview_file=false;
$response_json=true;
$message="";
$path="../public/cargas/".$controlador->name."/";
$accion	= isset($_REQUEST['accion']) ? $_REQUEST['accion'] : 'uploadfile';
$thisurl=$url->createUrl($controlador->static_urls['single-upload-handler']);

if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	if(isset($_REQUEST['id']) && isset($_REQUEST['estilo'])){
		
		$blog_post = $controlador->getPost($_REQUEST['id']);

		if($blog_post && isset($controlador->single_files) && isset($controlador->single_files[$_REQUEST['estilo']])){
			$tipo=$_REQUEST['estilo'];
			$access_granted=true;
			$folderfinal=$path."obj".$blog_post['post_id']."/";
			
			if($accion=='checkfile'){
				$preview_file=SIGA.'images/siga_default_file.png';
				$delete_url=$thisurl.'?accion=delete&estilo='.$_REQUEST['estilo'].'&id='.$blog_post['post_id'];
				if(validaGeneral($blog_post[$_REQUEST['estilo']],4)){
					$file_generated=URLCARGAS.$controlador->name.'/obj'.$blog_post['post_id'].'/'.$blog_post[$_REQUEST['estilo']];
					if($controlador->single_files[$_REQUEST['estilo']]['tipoarchivo'] == 'image'){
						$preview_file=URLCARGAS.$controlador->name.'/obj'.$blog_post['post_id'].'/small_'.$blog_post[$_REQUEST['estilo']];
					}else{
						$preview_file=SIGA.'images/siga_default_file_up.png';	
					}
					$message='<a href="'.$file_generated.'" class="action" target="_blank"><span class="fa fa-eye"></span></a><a href="'.$delete_url.'" class="action single-upload-delete-file"><span class="fa fa-trash"></span></a>';
				}else{
					$message='';
				}
			}else if($accion=='delete'){
				$response_json=false;
				$confirm_delete_url=$thisurl.'?accion=confirmdelete&estilo='.$_REQUEST['estilo'].'&id='.$blog_post['post_id'];
				?>
                <div class="modal-header">
                	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                	<h4 class="modal-title">Eliminar Recurso</h4>
                </div>
                <div class="modal-body">
                	¿Estás seguro que deseas eliminar permanentemente este recurso?
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                	<button type="button" class="btn btn-danger single-upload-confirmdelete-file" data-url="<?=$confirm_delete_url;?>">Eliminar</button>
                </div>
                <?php
			}else if($accion=='confirmdelete'){
				$response_json=false;
				if(validaGeneral($blog_post[$_REQUEST['estilo']],4)){
					if($controlador->single_files[$_REQUEST['estilo']]['tipoarchivo']=='image'){
						deleteFiles($blog_post[$_REQUEST['estilo']],$folderfinal,array('big_','medium_','small_','xsmall_'));
					}else{
						deleteFiles($blog_post[$_REQUEST['estilo']],$folderfinal,array());	
					}
				}
				$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET ".$_REQUEST['estilo']." = :cover WHERE post_id = :theobjid");
				if($stmt->execute(array(":cover"=>'',":theobjid"=>$blog_post['post_id']))){
					$message='';
				}
				echo json_encode(
					array(
						"message"=>'',
						"errores"=>0,
						"sendto"=>$thisurl,
						"id"=>$blog_post['post_id'],
						"estilo"=>$_REQUEST['estilo']
					)
				);
			}else if($accion=='uploadfile'){
				$havecover=array();
				if(isset($_FILES)){
					foreach($_FILES as $type => $file){
						if(isset($file) && "file_".$tipo==$type && isset($controlador->single_files[$tipo]) && strlen($file['tmp_name'])>2){
							$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
							if(in_array($ext,$controlador->single_files[$tipo]['required']) && ($file['size']/MEGABYTE)<=$controlador->single_files[$tipo]['maxfilesize']){
								$coverName=crearCadena(8).'.'.$ext;
								$folderpath_tmp=$path."tmp/";
								crearDirectorio($folderpath_tmp);
								if(!move_uploaded_file($file['tmp_name'],$folderpath_tmp.$coverName)){
									$errores[]='Error en el servidor';
								}else{
									$havecover[]=array($tipo, $coverName);
								}
								cerrarDirectorio($folderpath_tmp);
							}else{
								$errores[]='Extensión  o peso máximo inválido';
							}
						}else{
							$errores[]='No se encontró referencia al archivo';	
						}
					}
				}
			}
		}
	}
}else{
	$errores[]="Error code: 100000204";	
}
if($accion=='uploadfile'){
	if($access_granted && count($errores)==0 && isset($havecover) && count($havecover)>0){
		crearDirectorio($folderfinal);			
		foreach($havecover as $newfile){
			if(rename($folderpath_tmp.$newfile[1],$folderfinal.$newfile[1])){
				
				$file_generated=URLCARGAS.$controlador->name.'/obj'.$blog_post['post_id'].'/'.$newfile[1];
				if($controlador->single_files[$newfile[0]]['tipoarchivo']=='image'){
					exportimg($folderfinal.$newfile[1], $folderfinal."big_".$newfile[1], 1600, 1600, 88, 1, 0);
					exportimg($folderfinal.$newfile[1], $folderfinal."medium_".$newfile[1], 600, 600, 86, 1, 0);
					exportimg($folderfinal.$newfile[1], $folderfinal."small_".$newfile[1], 140, 140, 84, 0, 1);
					exportimg($folderfinal.$newfile[1], $folderfinal."xsmall_".$newfile[1], 60, 60, 82, 0, 1);
					deleteFiles($blog_post[$newfile[0]],$folderfinal,array('big_','medium_','small_','xsmall_'));
					$preview_file=URLCARGAS.$controlador->name.'/obj'.$blog_post['post_id'].'/small_'.$newfile[1];
				}else{
					deleteFiles($blog_post[$newfile[0]],$folderfinal,array());
					$preview_file=SIGA.'images/siga_default_file_up.png';
				}
				
				$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET ".$newfile[0]." = :cover WHERE post_id = :theobjid");
				if($stmt->execute(array(":cover"=>$newfile[1],":theobjid"=>$blog_post['post_id']))){
					$message='';
				}
			}else{
				$errores[]="Error code: 0000096521";	
			}
		}
		cerrarDirectorio($folderfinal);	
	}
	if(count($errores)>0 || !isset($havecover) || count($havecover)==0){
		$message=implode("<br>",$errores);
	}
}

if($response_json){
	echo json_encode(
		array(
			"message"=>$message,
			"errores"=>count($errores),
			"attach"=>$attach,
			"preview_file"=>$preview_file,
			"file"=>$file_generated
		)
	);
}
?>