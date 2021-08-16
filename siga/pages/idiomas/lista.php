<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	
	$cHeaderData=array(
		'titulo' => 'Idiomas',
		'icono' => 'globe',
		'opciones' => array()
	);
	
	//OBTENER IDIOMAS
	if($stmt = $db->prepare("SELECT * FROM ".DB_PREFIX."idiomas WHERE (status = 'publicado' || status = 'borrador') ORDER BY orden ASC")){
		$stmt->execute();
		$idiomas = $stmt->FetchAll();
	}
	
	
	include( DMSIGA .'breadcrumbs.php' );
	include( DMSIGA .'cHeader.php' );

	//Cargar clase de seccion
	echo '<div class="boxcon">';
		c_PrintMessages();
		echo '<div class="box">';
		?>
        <div class="table-responsive">
		<table class="table table-striped">
        	<thead>
            	<tr>
                	<th>ID</th>
                    <th>Nombre</th>
                    <th>Código ISO</th>
                    <th class="ac">Activo</th>
                    <th class="ac">Idioma principal</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($idiomas as $i) {
				if($i['status']=='publicado'){
					$opcionactivar='<a href="'.SIGA.'p/idiomas/acciones/'.$i['idioma_id'].'?activo=no" class="label label-success"><span class="fa fa-check-square"></span></a>';
				}else{
					$opcionactivar='<a href="'.SIGA.'p/idiomas/acciones/'.$i['idioma_id'].'?activo=si" class="label label-danger"><span class="fa fa-minus-square"></span></a>';
				}
				if($i['slug']==$opcionesfull['opciones']['idioma_default']){
					$opcionprincipal='<span class="label label-success"><span class="fa fa-check-square"></span></span>';
				}else{
					$opcionprincipal='<a href="'.SIGA.'p/idiomas/acciones/'.$i['idioma_id'].'?default=si" class="label label-default"><span class="fa fa-check-square"></span></a>';
				}
				?>
				<tr>
					<td><?=$i['idioma_id'];?></td>
					<td><?=$i['nombre'];?></td>
					<td><?=$i['slug'];?></td>
					<td class="ac"><?=$opcionactivar;?></td>
                    <td class="ac"><?=$opcionprincipal;?></td>
				</tr>
				<?php
				}
				?>
            </tbody>
        </table>
        </div>
		<?php
	echo '</div></div>';
?>

<?php
}else{
	include('noaccess.php');
}
?>