<?php
$redirect = $url->createUrl($controlador->static_urls['lista']);
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	$redirect = SIGA."s/plan/editar/1";
}
header("Location: ".$redirect);
?>
