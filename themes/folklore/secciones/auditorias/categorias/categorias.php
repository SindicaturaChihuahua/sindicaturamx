<?php
if(logeado() && $user->exists && $user->hasRole("EditorEnJefe")){

	$cHeaderData=array(
		'titulo' => 'Categorías',
		'icono' => 'tag',
		'opciones' => array(
			array(
				'nombre' => 'Nueva Categoría',
				'link' => $controlador->static_urls['categorias-nuevo'],
				'icono' => 'plus'
			)
		)
	);
	$bodys->set( 'bottomjs', URLPLUGINS.'jqueryui/jquery-ui.min.js' );
	$url->addBreadcrumb(array('nombre' => 'Categorías', 'link' => $controlador->static_urls['categorias']));

	//OBTENER OBJETOS
	$objetos=$controlador->get_Categorias();

	include( DMSIGA .'breadcrumbs.php' );
	include( DMSIGA .'cHeader.php' );

	//Cargar clase de seccion
	echo '<div class="boxcon"><div class="box">';
		if(isset($objetos) && !empty($objetos)){
			$edit_url=$url->createUrl($controlador->static_urls['categorias-editar']).'/';

			$gfu_orden=csrfCreate('order-'.$controlador->name,
				array(
					'uid' => $user->uid,
					'tipo' => 'order-',
					'id' => $controlador->name,
					'options' => array(
						'db_table' => DB_PREFIX.$controlador->databaseprefix.'_categorias'
					)
				)
			);
			$tableorderurl=$url->createUrl($controlador->static_urls['categorias-orden-handler']).'?token='.$gfu_orden.'&futipo=order-&fuid='.$controlador->name.'&takedata=true';
		?>
        <div class="table-responsive">
		<table class="table table-striped">
        	<thead>
            	<tr>
					<th></th>
                	<th>Categoria</th>
                    <th>URL Amigable</th>
                </tr>
            </thead>
            <tbody class="reordertable" data-url="<?=$tableorderurl;?>">
            <?php
            foreach ($objetos as $o) {
			?>
            <tr id="obj_<?=$o['categoria_id'];?>">
				<td><i class="fa fa-bars moveorder"></i></td>
            	<td><a href="<?=$edit_url.$o['categoria_id'];?>"><?=$o['categoria_nombre'];?></a></td>
                <td><?=$o['categoria_slug'];?></td>
            </tr>
            <?php
			}
			?>
            </tbody>
        </table>
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
