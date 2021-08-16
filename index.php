<?php
require_once( './dm-includes/dm-config.php' );
require_once( ROOT . 'functions.php' );
require_once( ROOT . 'url.php' );
$url = new url();

/*
 * Start PHP Process here
 */

$access=false;
$controlador=false;
$controladordata=array();

$controladorfolder='dm-secciones/';
if(!isset($_GET['alfa'])){
	$_GET['alfa']='portada';
}

if(isset($_GET['alfa'])){
	$controladorfolder.=$_GET['alfa'].'/';

	if(!isset($_GET['beta'])){ $_GET['beta']='lista'; }
	if(!isset($_GET['gama'])){ $_GET['gama']=false; }
	if(!isset($_GET['delta'])){ $_GET['delta']=false; }
	if(!isset($_GET['epsilon'])){ $_GET['epsilon']=false; }
	$url->setPiezas(
		array(
			'tipo' => '',
			'alfa' => $_GET['alfa'],
			'beta' => $_GET['beta'],
			'gama' => $_GET['gama'],
			'delta' => $_GET['delta'],
			'epsilon' => $_GET['epsilon']
		)
	);

	if(file_exists($controladorfolder.'c.php')){
		$access=true;
		include_once($controladorfolder.'c.php');
		$classname = "front_".str_replace("-", "_", $_GET['alfa']);
		$controlador = new $classname($db);
		$controladordata = $controlador->call($_GET['beta'],$_GET['gama'],$_GET['delta']);
	}
}
if(!$access || empty($controladordata)){
	include(ROOT . 'e404.php');
	exit;
}else{
	if(isset($controladordata['ajax'])){
		include($controladorfolder.$controladordata['file'].'.php');
	}else{
		include($controladorfolder.$controladordata['file'].'.php');
	}
}
?>
