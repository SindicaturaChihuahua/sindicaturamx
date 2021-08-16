<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	
	$cHeaderData=array(
		'titulo' => 'Navegación',
		'icono' => 'link',
		'opciones' => array(
			array(
				'nombre' => 'Nuevo Enlace',
				'link' => $controlador->static_urls['nuevo'],
				'icono' => 'plus-square'
			)
		)
	);
	$bodys->set( 'bottomjs', URLPLUGINS.'jqueryui/jquery-ui.min.js' );
	$bodys->set( 'bottomjs', URLPLUGINS.'sortable/jquery.mjs.nestedSortable.js' );
	$bodys->set( 'bottomjs', SIGA.'pages/navegacion/navegacion.js' );
	
	//OBTENER OBJETOS
	$objetos=$controlador->getLinks();
	
	include( DMSIGA .'breadcrumbs.php' );
	include( DMSIGA .'cHeader.php' );

	//Cargar clase de seccion
	echo '<div class="boxcon"><div class="box">';
		if(isset($objetos) && !empty($objetos)){
			$edit_url=$url->createUrl($controlador->static_urls['editar']).'/';
			$gfu_orden=csrfCreate('order-'.$controlador->name,
				array(
					'uid' => $user->uid,
					'tipo' => 'order-',
					'id' => $controlador->name,
					'options' => array(
						'db_table' => DB_PREFIX."opciones",
						'opcion_nombre' => "navegacion",
					)
				)
			);
			$tableorderurl=$url->createUrl($controlador->static_urls['orden-handler']).'?token='.$gfu_orden.'&futipo=order-&fuid='.$controlador->name.'&takedata=true';
			$menu=json_decode($opcionesfull['opciones']['navegacion']);
		?>
        <div class="row nsortablecon" data-url="<?=$tableorderurl;?>">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-3">
                        <h4>Enlaces</h4>
                        <p>Arrastras los enlaces para cambiar el orden en el que aparecen en el menú principal.</p>
                    </div>
                    <div class="col-sm-9">
                    	<div class="row">
                        	<div class="col-sm-6">
                            	<h5>Enlaces Activos <small>(Menú principal)</small></h5>
                                <ol class="nsortable active-nsortable">
                                    <?php
                                    $controlador->printSigaMenu($menu,$objetos,$edit_url,0);
                                    ?>
                                </ol>
                    		</div>
                            <div class="col-sm-6">
                            	<h5>Enlaces Inactivos</h5>
                            	<ol class="nsortable inactive-nsortable">
                                    <?php
                                    $controlador->printSigaMenuInactive($objetos,$edit_url);
                                    ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <a href="#" class="btn btn-primary blockatsubmit nsortable-save-btn">Guardar Cambios</a> <img src="<?=SIGA;?>images/loading.gif" class="floading" />
                        </div>
                    </div> 
                </div><!-- end big row -->
            </div>
        </div>
		<?php
		}else{
				
		}
	echo '</div></div>';
?>

<?php
}else{
	include('noaccess.php');
}
?>