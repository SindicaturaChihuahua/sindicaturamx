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
	$url->addBreadcrumb(array('nombre' => 'Categorías', 'link' => $controlador->static_urls['categorias']));

	//OBTENER OBJETOS
	$objetos=$controlador->get_Categorias();

	include( DMSIGA .'breadcrumbs.php' );
	include( DMSIGA .'cHeader.php' );

	//Cargar clase de seccion
	echo '<div class="boxcon"><div class="box">';
		if(isset($objetos) && !empty($objetos)){
			$edit_url=$url->createUrl($controlador->static_urls['categorias-editar']).'/';
		?>
        <div class="table-responsive">
		<table class="table table-striped">
        	<thead>
            	<tr>
                	<th>Categoria</th>
                    <th>URL Amigable</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($objetos as $o) {
			?>
            <tr>
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
