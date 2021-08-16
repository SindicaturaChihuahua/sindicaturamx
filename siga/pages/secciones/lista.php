<?php
if(logeado() && $user->isFolklore()){
	
	$cHeaderData=array(
		'titulo' => 'Secciones',
		'icono' => 'tasks',
		'opciones' => array(
			array(
				'nombre' => 'Actualizar Secciones',
				'link' => 'p/secciones/lista/actualizar',
				'icono' => 'magic'
			)
		)
	);
	//Para BreadCrumbs
	if(isset($_GET['gama']) && $_GET['gama']=='actualizar'){
		$url->addBreadcrumb(array('nombre' => 'Actualizar', 'link' => 'p/secciones/lista/actualizar'));
	}
	
	include( DMSIGA .'breadcrumbs.php' );
	include( DMSIGA .'cHeader.php' );


	//Secciones folders
	foreach (scandir(THEME_SECCIONES) as $module){
		if ($module[0] != '.' && is_dir(THEME_SECCIONES.$module) && file_exists(THEME_SECCIONES.$module.'/'.$module.'.php')){
			$foldersecciones[$module] = 1;
		}
	}
	
	//ACTUALIZAR SECCIONES
	if(isset($_GET['gama']) && $_GET['gama']=='actualizar'){
		$url->addBreadcrumb(array('nombre' => 'Actualizar', 'link' => 'p/secciones/lista/actualizar'));
		$actualizadas=array();
		$instaladas=array();
		foreach ($themeconfig['secciones'] as $aseccion) {
			if(isset($foldersecciones[$aseccion])){
				$instalar=true;
				include_once(THEME_SECCIONES.$aseccion.'/'.$aseccion.'.php');
				$clase=new $aseccion($db);
				if(isset($installedSecciones[$aseccion]) && $clase->version==$installedSecciones[$aseccion]['version']){
					$instalar=false;
				}
				if($instalar){
					if(isset($installedSecciones[$aseccion])){
						$actualizadas[]=$aseccion;
						$clase->update();
					}else{
						$clase->install();
						$instaladas[]=$aseccion;	
					}
				}
			}
		}
		$installedSecciones=getInstalledSecciones();
	}

	//Cargar clase de seccion
	
	echo '<div class="boxcon">';
	if(isset($instaladas)){
		if(!empty($instaladas)){
			echo '<div class="alert alert-info">';
				echo 'Se instalaron <b>'.count($instaladas).' nuevas secciones</b>';
			echo '</div>';
		}
		if(!empty($actualizadas)){
			echo '<div class="alert alert-info">';
				echo 'Se actualizaron <b>'.count($actualizadas).' secciones</b>';
			echo '</div>';
		}
	}
	echo '<div class="box">';
	echo '<div class="unalista">';
	foreach ($themeconfig['secciones'] as $aseccion) {
		if(isset($foldersecciones[$aseccion])){
			//Cargar la clase
			include_once(THEME_SECCIONES.$aseccion.'/'.$aseccion.'.php');
			$clase=new $aseccion($db);
			echo '<div class="row">';
			echo '<div class="col-md-8">';
				$clase->printAdministracionInfo();
			echo '</div>';
			echo '<div class="col-md-4 ar">';
				if(isset($installedSecciones[$aseccion]) && $clase->version==$installedSecciones[$aseccion]['version']){
					echo '<span class="label fklabel l-verde">Instalado</span>';
				}else if(isset($installedSecciones[$aseccion])){
					echo '<span class="label fklabel label-warning">Necesita Actualizar</span>';
				}else{
					echo '<span class="label fklabel l-rojo">No Instalado</span>';	
				}
			echo '</div>';
			echo '</div>';
		}
	}
	echo '</div>'; //Unalista
	echo '</div>';
?>

<?php
}else{
	include('noaccess.php');
}
?>