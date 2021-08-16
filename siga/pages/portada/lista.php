<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){

	$cHeaderData=array(
		'titulo' => 'Portada',
		'icono' => 'home'
	);

	include( DMSIGA .'breadcrumbs.php' );
	include( DMSIGA .'cHeader.php' );

	echo '<div class="boxcon"><div class="box">';
		?>

		<div class="row">
            <div class="col-lg-12">
            	<div class="tablerodeopciones">
					<?php
					foreach ($url->navigation as $tab => $tabopts) {
						if(isset($tabopts['menu']) &&  count($tabopts['menu'])>0){
							foreach ($tabopts['menu'] as $page => $pageopts) {
								if($pageopts['tipo']=="secciones" && $user->hasRole($pageopts['permiso'])){
									echo '<a href="'.SIGA.$pageopts['link'].'" class="t-opt">
				            			<div class="astable"><div class="ascell">
				            				<i class="fa fa-'.$pageopts['icon'].'"></i><span class="titulo">'.$pageopts['nombre'].'</span>
				            			</div></div>
				            		</a>';
								}
							}
						}
					}
					?>
            	</div>
            </div>
        </div><!-- /.row -->

        <?php
	echo '</div></div>';
?>

<?php
}else{
	include('noaccess.php');
}
?>
