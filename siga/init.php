<?php
$bodys->mix(array(
	"siga" => true
));

//getInstalledSecciones
$installedSecciones=getInstalledSecciones();
if(!empty($installedSecciones)){
	foreach($installedSecciones as $is){
		$url->addToNavigation($is['tab'],$is['nombre'],$is['displaynombre'],$is['icon'],'secciones',$is['permiso']);
	}
}
?>