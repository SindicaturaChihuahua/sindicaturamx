<?php
require_once( '../dm-includes/dm-config.php' );
require_once( DMSIGA . 'url.php' );
$url = new url();
require_once( DMSIGA . 'init.php' );
require_once( ROOT . 'functions.php' );
require_once( DMSIGA . 'exclusive_functions.php' );

/*
 * Start PHP Process here
*/

$access=false;
$controlador=false;
$controladordata=array();

if(!logeado() || !$user->exists || !$user->hasRole("Usuario")){
	header("Location: ".SIGA."login");exit;
}

$controladorfolder='';
if(isset($_GET['tipo']) && $_GET['tipo']=='pages'){
	$controladorfolder='pages/';
}else if(isset($_GET['tipo']) && $_GET['tipo']=='secciones'){
	$controladorfolder=THEME_SECCIONES;
}

if(isset($_GET['alfa'])){
	$controladorfolder.=$_GET['alfa'].'/';

	if(!isset($_GET['beta'])){ $_GET['beta']='lista'; }
	if(!isset($_GET['gama'])){ $_GET['gama']=false; }
	if(!isset($_GET['delta'])){ $_GET['delta']=false; }
	if(!isset($_GET['epsilon'])){ $_GET['epsilon']=false; }
	$url->setPiezas(
		array(
			'tipo' => $_GET['tipo'],
			'alfa' => $_GET['alfa'],
			'beta' => $_GET['beta'],
			'gama' => $_GET['gama'],
			'delta' => $_GET['delta'],
			'epsilon' => $_GET['epsilon']
		)
	);

	if(file_exists($controladorfolder.$_GET['alfa'].'.php')){
		$access=true;
		include_once($controladorfolder.$_GET['alfa'].'.php');
		$classname = "back_".$_GET['alfa'];
		$controlador = new $classname($db);
		$controladordata = $controlador->call($_GET['beta'],$_GET['gama'],$_GET['delta']);
	}
}
if(!$access || empty($controladordata)){
	header('Location: '.SIGA.'p/portada');
	exit;
}

$themeconfig=include(THEME.'config.php');

if(isset($controladordata['ajax'])){
	include($controladorfolder.$controladordata['file'].'.php');
}else{
	require_once( DMSIGA .'head.php' );

	include($controladorfolder.$controladordata['file'].'.php');

	require_once( DMSIGA .'footer.php' );
}
?>
