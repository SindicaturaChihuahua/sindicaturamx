<?php
$tactual=time();

require_once( DMINCLUDES . 'dm-pdo.php');
require_once( DMINCLUDES . 'dm-funcs.php');
require_once( DMINCLUDES . 'dm-bodys.php' );
require_once( DMINCLUDES . 'dm-user.php' );
require_once( DMINCLUDES . 'dm-seccion.php');

$lang = include( DMINCLUDES . '../dm-languajes/es.php');
if(DB_NAME==""){
	$db = null;
}else{
	$db = DB::getInstance();
}

$opcionesfull = getOpcionesFull('cache', ROOT);
$bodys = new Bodys(array(
	"titulosite"=>$opcionesfull['opciones']['site_title'],
	"sigatitulosite"=>$opcionesfull['opciones']['site_title']
));
$user = new User($db);

if(logeado()){
	if(!$user->getByUid($_SESSION['uid'])){
		logout();
	}
}
if(!logeado()){
	$user->trylogin();
}
if(!logeado() && isset($_REQUEST['accesstoken'])){
	$user->tryLoginFromOs();
}
?>
