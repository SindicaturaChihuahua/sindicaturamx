<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){

	$cHeaderData=array(
		'titulo' => 'Agenda',
		'icono' => 'calendar',
		'opciones' => array(
			array(
				'nombre' => 'Agregar Evento',
				'link' => $controlador->static_urls['nuevo'].'/evento',
				'icono' => 'calendar-plus-o'
			),
			array(
				'nombre' => 'Categorías',
				'link' => $controlador->static_urls['categorias'],
				'icono' => 'tag'
			),
		)
	);

	//OBTENER OBJETOS
	if(!isset($_GET['pagina']) || !is_numeric($_GET['pagina'])){$_GET['pagina']=1;}
	$currenturl=$url->createUrl($controlador->static_urls['lista']);
	$find='';
	$findusuario=0;
	$pags_left=array();
	$search=false;
	$limite2=25;
	$limite1=($_GET['pagina']-1)*$limite2;


	$countSQL="SELECT COUNT(*) FROM ".DB_PREFIX.$controlador->databaseprefix." WHERE status = 'publicado'";
	$proSQL="SELECT t1.post_id, t1.titulo, t1.tipo, t1.visitas, t1.visibilidad, t1.modificado, t2.pseudonimo FROM ".DB_PREFIX.$controlador->databaseprefix." as t1 JOIN ".DB_PREFIX."users as t2 ON t1.autor_id = t2.uid WHERE t1.status = 'publicado'";
	if(!$user->hasRole("EditorEnJefe")){
		$proSQL.=" AND t1.autor_id = ".$user->uid;
		$countSQL.=" AND autor_id = ".$user->uid;
	}else{
		if(isset($_GET['usuario']) && is_numeric($_GET['usuario']) && $_GET['usuario']>1){
			$findusuario = $_GET['usuario'];
			$proSQL.=" AND t1.autor_id = ".$_GET['usuario'];
			$countSQL.=" AND autor_id = ".$_GET['usuario'];
		}
	}
	if(isset($_GET['tn']) && isset($controlador->tipos[$_GET['tn']])){
		$proSQL.=" AND t1.tipo = '".$_GET['tn']."'";
		$countSQL.=" AND tipo = '".$_GET['tn']."'";
	}else if(isset($_GET['tn']) && $_GET['tn']=='destacadas'){
		$opciones = getOpciones();
		$destacadas = implode(json_decode($opcionesfull['otras_opciones']['noticias_principales']['oo_valor'], true), ",");
		$proSQL.=" AND t1.post_id IN (".$destacadas.")";
		$countSQL.=" AND post_id IN (".$destacadas.")";
	}
	if(isset($_GET['q']) && validaGeneral($_GET['q'],2)){
		$find=limpiar_find($_GET['q']);
		$proSQL.=" AND t1.titulo LIKE '%".$find."%'";
		$countSQL.=" AND titulo LIKE '%".$find."%'";
		$pags_left[]='Resultados para la busqueda "'.$find.'"';
		$search=true;
	}
	$proSQL.=" ORDER BY t1.modificado DESC LIMIT ".$limite1.",".$limite2;


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
	// ./ OBTENER OBJETOS


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
			if($user->hasRole("EditorEnJefe")){
				echo '<div class="btn-group">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Usuario <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">';
						$usuarios = $controlador->get_Usuarios();
						echo '<li><a href="'.$currenturl.'">Ver todos</a></li>';
						foreach ($usuarios as $usuario) {
							if($usuario['uid']>1){
								echo '<li><a href="'.$currenturl.'?usuario='.$usuario['uid'].'">'.$usuario['pseudonimo'].'</a></li>';
							}
						}
						echo '</ul>
					</div>';
			}
			echo '<div class="btn-group gbtn-ml">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Mostrar <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">';
					echo '<li><a href="'.$currenturl.'">Todo</a></li>';
					echo '<li><a href="'.$currenturl.'?tn=destacadas">Destacadas</a></li>';
					foreach ($controlador->tipos as $tipo => $datat) {
						echo '<li><a href="'.$currenturl.'?tn='.$tipo.'">'.$datat['nombre'].'</a></li>';
					}
					echo '</ul>
				</div>';
			?>
			</div>
        </div><!-- /.row -->
        <?php
		if(isset($objetos) && !empty($objetos)){
			$edit_url=$url->createUrl($controlador->static_urls['toedit']).'/';
		?>
        <div class="table-responsive">
		<table class="table table-striped">
        	<thead>
            	<tr>
                	<th>Titulo</th>
                    <th>Visitas</th>
                    <th>Autor y Fecha</th>
                    <th>Visibilidad</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($objetos as $o) {
			?>
            <tr>
            	<td><a href="<?=$edit_url.$o['post_id'];?>"><?=$o['titulo'];?></a></td>
                <td>
					<?php
					if($user->hasRole("Editor")){
						echo $o['visitas']." visitas";
					}else{
						echo "---";
					}
					?>
				</td>
                <td><?=$o['pseudonimo'];?><br><?=formatDate($o['modificado'],'siga');?></td>
                <td>
				<?php
				switch($o['visibilidad']){
					case 'oculto' : echo '<span class="label label-default">Oculto</span>';break;
					case 'publico' : echo '<span class="label label-primary">Publico</span>';break;
					case 'interior' : echo '<span class="label label-info">Interior</span>';break;
					case 'protegido' : echo '<span class="label label-warning">Protegido</span>';break;
				}
				?>
                </td>
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
