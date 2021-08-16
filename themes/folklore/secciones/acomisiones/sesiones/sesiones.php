<?php
if(logeado() && $user->exists && $user->hasRole("EditorEnJefe")){

	$cHeaderData=array(
		'titulo' => 'Sesiones',
		'icono' => 'users',
		'opciones' => array(
			array(
				'nombre' => 'Nueva Sesión',
				'link' => $controlador->static_urls['sesiones-nuevo'],
				'icono' => 'plus'
			)
		)
	);
	$bodys->set( 'bottomjs', URLPLUGINS.'jqueryui/jquery-ui.min.js' );
	$url->addBreadcrumb(array('nombre' => 'Sesiones', 'link' => $controlador->static_urls['sesiones']));



	//OBTENER OBJETOS
	if(!isset($_GET['pagina']) || !is_numeric($_GET['pagina'])){$_GET['pagina']=1;}
	$currenturl=$url->createUrl($controlador->static_urls['sesiones']);
	$find='';
	$findusuario=0;
	$pags_left=array();
	$search=false;
	$limite2=25;
	$limite1=($_GET['pagina']-1)*$limite2;


	$countSQL="SELECT COUNT(*) FROM ".DB_PREFIX.$controlador->databaseprefix."_sesiones WHERE status = 'publicado'";
	$proSQL="SELECT * FROM ".DB_PREFIX.$controlador->databaseprefix."_sesiones WHERE status = 'publicado'";
	if(isset($_GET['comision']) && $controlador->getPost($_GET["comision"])){
		$proSQL.=" AND comision_id = '".$_GET['comision']."'";
		$countSQL.=" AND comision_id = '".$_GET['comision']."'";
	}
	if(isset($_GET['q']) && validaGeneral($_GET['q'],2)){
		$find=limpiar_find($_GET['q']);
		$proSQL.=" AND sesion_nombre LIKE '%".$find."%'";
		$countSQL.=" AND sesion_nombre LIKE '%".$find."%'";
		$pags_left[]='Resultados para la busqueda "'.$find.'"';
		$search=true;
	}
	$proSQL.=" ORDER BY modificado DESC LIMIT ".$limite1.",".$limite2;

	$r=$db->prepare($countSQL);
	$r->execute();
	$total_obj = $r->fetch(PDO::FETCH_COLUMN);
	if($total_obj>0){
		if($stmt = $db->prepare($proSQL)){
			$stmt->execute();
			$objetos=$stmt->FetchAll();
		}
		$pags_left[]='Resultados del '.($limite1+1).' al '.($limite1+count($objetos));
	}
	$total_pag=ceil($total_obj/$limite2);
	$l_pag1=$_GET['pagina']-5;
	$l_pag2=$_GET['pagina']+5;
	if($l_pag1<1){$l_pag1=1;}
	if($l_pag2>$total_pag){$l_pag2=$total_pag;}
	$pags_texto='';
	for($a=$l_pag1;$a<=$l_pag2;$a++){
		if($_GET['pagina']==$a){
			$pags_texto.='<li class="active"><span>'.$a.'</span></li>';
		}else{
			$pags_texto.='<li><a href="'.$currenturl.'?pagina='.$a.'&q='.$find.'&usuario='.$findusuario.'">'.$a.'</a></li>';
		}
	}

	$comisiones = $controlador->get_Comisiones();
	// ./ OBTENER OBJETOS




	//OBTENER OBJETOS
	// $objetos=$controlador->get_Sesiones();

	include( DMSIGA .'breadcrumbs.php' );
	include( DMSIGA .'cHeader.php' );

	//Cargar clase de seccion
	echo '<div class="boxcon"><div class="box">';
	?>
		<div class="row row-search">
			<div class="col-lg-3">
				<form action="<?=$currenturl;?>" method="get">
				<div class="input-group">
					<input type="text" class="form-control" name="q" placeholder="¿Qué estás buscando?" value="<?=isset($_GET['q']) ? $_GET['q'] : "";?>">
					<span class="input-group-btn">
						<button type="submit" class="btn btn-default" type="button">Buscar</button>
					</span>
				</div>
				</form>
			</div>
			<div class="col-lg-6">
			<?php
			echo '<div class="btn-group gbtn-ml">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Mostrar <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">';
					echo '<li><a href="'.$currenturl.'">Todo</a></li>';
					foreach($comisiones as $com){
						echo '<li><a href="'.$currenturl.'?comision='.$com["post_id"].'">'.$com["titulo"].'</a></li>';
					}
					echo '</ul>
				</div>';
			?>
			</div>
		</div><!-- /.row -->
		<?php
		if(isset($objetos) && !empty($objetos)){
			$edit_url=$url->createUrl($controlador->static_urls['sesiones-editar']).'/';
		?>
        <div class="table-responsive">
		<table class="table table-striped">
        	<thead>
            	<tr>
                	<th>Sesión</th>
					<th>Comisión</th>
					<th>Modificado</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($objetos as $o) {
				$comision=$controlador->getPost($o["comision_id"]);
			?>
            <tr id="obj_<?=$o['sesion_id'];?>">
            	<td><a href="<?=$edit_url.$o['sesion_id'];?>"><?=$o['sesion_nombre'];?></a></td>
				<td><?=$comision["titulo"];?></td>
				<td><?=formatDate($o['modificado'],'siga');?></td>
            </tr>
            <?php
			}
			?>
            </tbody>
        </table>
        </div>

		<div class="row row-paginacion">
        	<div class="col-md-6">
            	<ul class="pagination"><?=$pags_texto;?></ul>
            </div>
            <div class="col-md-6 rp-info">
            	<?=implode("<br>",$pags_left)?>
            	<br><?=fechayhoraActual();?>
            </div>
        </div>
		<?php
		}else{
			if($search){
				include(THEME.'/templates/norecordsfound.php');
			}else{
				include(THEME.'/templates/norecords.php');
			}
		}
	echo '</div></div>';
?>

<?php
}else{
	include('noaccess.php');
}
?>
