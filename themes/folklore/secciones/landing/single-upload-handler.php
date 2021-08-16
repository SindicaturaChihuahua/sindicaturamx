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

		if($blog_post){
			if($blog_post['tipo']=='landing'){
				$single_files = $controlador->single_files;
			}elseif($blog_post['tipo']=='recurso'){
				$single_files = $controlador->single_files_recurso;
			}
			if(isset($single_files) && isset($single_files[$_REQUEST['estilo']])){
				$tipo=$_REQUEST['estilo'];
				$access_granted=true;
				$folderfinal=$path.$blog_post['folder'];

				if($single_files[$tipo]['extra']==true){
					$isextra=true;
					if(isset($blog_post['extra'][$tipo])){
						$archivoactual=$blog_post['extra'][$tipo];
					}else{
						$archivoactual="";
					}
				}else{
					$isextra=false;
					$archivoactual=$blog_post[$tipo];
				}

				if($accion=='checkfile'){
					$preview_file=SIGA.'images/siga_default_file.png';
					$delete_url=$thisurl.'?accion=delete&estilo='.$tipo.'&id='.$blog_post['post_id'];
					if(validaGeneral($archivoactual,4)){
						$file_generated=URLCARGAS.$controlador->name.'/'.$blog_post['folder'].$archivoactual;
						if($single_files[$tipo]['tipoarchivo'] == 'image'){
							$preview_file=URLCARGAS.$controlador->name.'/'.$blog_post['folder'].'small_'.$archivoactual;
						}else{
							$preview_file=SIGA.'images/siga_default_file_up.png';
						}
						$message='<a href="'.$file_generated.'" class="action" target="_blank"><span class="fa fa-eye"></span></a><a href="'.$delete_url.'" class="action single-upload-delete-file"><span class="fa fa-trash"></span></a>';
					}else{
						$message='';
					}
				}else if($accion=='delete'){
					$response_json=false;
					$confirm_delete_url=$thisurl.'?accion=confirmdelete&estilo='.$tipo.'&id='.$blog_post['post_id'];
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
					if(validaGeneral($archivoactual,4)){
						if($single_files[$tipo]['tipoarchivo']=='image'){
							deleteFiles($archivoactual,$folderfinal,array('big_','medium_','mediumcuadro_','small_','xsmall_'));
						}else{
							deleteFiles($archivoactual,$folderfinal,array());
						}
					}
					if($isextra){
						$blog_post['extra'][$tipo]='';
						$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET extra = ? WHERE post_id = ?");
						if($stmt->execute(array(json_encode($blog_post['extra']),$blog_post['post_id']))){
							$message='';
						}
					}else{
						$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET ".$tipo." = :cover WHERE post_id = :theobjid");
						if($stmt->execute(array(":cover"=>'',":theobjid"=>$blog_post['post_id']))){
							$message='';
						}
					}
					echo json_encode(
						array(
							"message"=>'',
							"errores"=>0,
							"sendto"=>$thisurl,
							"id"=>$blog_post['post_id'],
							"estilo"=>$tipo
						)
					);
				}else if($accion=='uploadfile'){
					$havecover=array();
					if(isset($_FILES)){
						foreach($_FILES as $type => $file){
							if(isset($file) && "file_".$tipo==$type && isset($single_files[$tipo]) && strlen($file['tmp_name'])>2){
								$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
								if(in_array($ext,$single_files[$tipo]['required']) && ($file['size']/MEGABYTE)<=$single_files[$tipo]['maxfilesize']){
									$coverName=crearCadena(8).'.'.$ext;
									$folderpath_tmp=$path."tmp/";
									crearDirectorio($folderpath_tmp);
									if(!move_uploaded_file($file['tmp_name'],$folderpath_tmp.$coverName)){
										$errores[]='Error en el servidor';
									}else{
										$havecover[]=array($tipo, $coverName, $single_files[$tipo]['extra']);
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
		}//hay blog_post
	}
}else{
	$errores[]="Error code: 100000204";
}
if($accion=='uploadfile'){
	if($access_granted && count($errores)==0 && isset($havecover) && count($havecover)>0){
		crearDirectorio($folderfinal);
		foreach($havecover as $newfile){
			if(rename($folderpath_tmp.$newfile[1],$folderfinal.$newfile[1])){

				$file_generated=URLCARGAS.$controlador->name.'/'.$blog_post['folder'].$newfile[1];
				if($single_files[$newfile[0]]['tipoarchivo']=='image'){
					exportimg($folderfinal.$newfile[1], $folderfinal."big_".$newfile[1], 1200, 1200, 90, 1, 0);
					exportimg($folderfinal.$newfile[1], $folderfinal."medium_".$newfile[1], 600, 600, 88, 1, 0);
					exportimg($folderfinal.$newfile[1], $folderfinal."mediumcuadro_".$newfile[1], 600, 600, 88, 0, 1);
					exportimg($folderfinal.$newfile[1], $folderfinal."small_".$newfile[1], 140, 140, 84, 0, 1);
					exportimg($folderfinal.$newfile[1], $folderfinal."xsmall_".$newfile[1], 60, 60, 82, 0, 1);
					deleteFiles($archivoactual,$folderfinal,array('big_','medium_','mediumcuadro_','small_','xsmall_'));
					$preview_file=URLCARGAS.$controlador->name.'/'.$blog_post['folder'].'small_'.$newfile[1];
				}else{
					deleteFiles($archivoactual,$folderfinal,array());
					$preview_file=SIGA.'images/siga_default_file_up.png';
				}
				if($isextra){
					$blog_post['extra'][$newfile[0]]=$newfile[1];
					$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET extra = ? WHERE post_id = ?");
					if($stmt->execute(array(json_encode($blog_post['extra']),$blog_post['post_id']))){
						$message='';
					}
				}else{
					$stmt = $db->prepare("UPDATE ".DB_PREFIX.$controlador->databaseprefix." SET ".$newfile[0]." = :cover WHERE post_id = :theobjid");
					if($stmt->execute(array(":cover"=>$newfile[1],":theobjid"=>$blog_post['post_id']))){
						$message='';
					}
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
