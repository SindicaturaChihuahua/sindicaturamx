<?php
$redirect = $url->createUrl($controlador->static_urls['lista']);
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	$obj = $controlador->getPost($_GET['gama']);
	if($obj){
		if($obj['tipo']=="cuuenimagenes"){
			$redirect = SIGA."s/cuuenimagenes/editar/".$obj['tipo'].'/'.$obj['post_id'];
		}else if($obj['tipo']=="revistaomnia"){
			$redirect = SIGA."s/revistaomnia/editar/".$obj['tipo'].'/'.$obj['post_id'];
		}else if($obj['tipo']=="omniatv"){
			$redirect = SIGA."s/omniatv/editar/".$obj['tipo'].'/'.$obj['post_id'];
		}else if($obj['tipo']=="encuestas"){
			$redirect = SIGA."s/encuestas/editar/".$obj['post_id'];
		}else{
			$edit_url=$url->createUrl($controlador->static_urls['editar']).'/';
			$redirect = $edit_url.$obj['tipo'].'/'.$obj['post_id'];
		}
	}
}
header("Location: ".$redirect);
?>
