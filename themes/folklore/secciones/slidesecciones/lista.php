<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){

	$cHeaderData=array(
		'titulo' => 'Carrusel de Secciones',
		'icono' => 'sticky-note',
		'opciones' => array(
			array(
				'nombre' => 'Agregar',
				'link' => $controlador->static_urls['nuevo'],
				'icono' => 'plus'
			)
		)
	);
	$bodys->set( 'bottomjs', URLPLUGINS.'jqueryui/jquery-ui.min.js' );

	//OBTENER OBJETOS
	if(!isset($_GET['pagina']) || !is_numeric($_GET['pagina'])){$_GET['pagina']=1;}
	$currenturl=$url->createUrl($controlador->static_urls['lista']);
	$find='';
	$pags_left=array();
	$search=false;
	$limite2=25;
	$limite1=($_GET['pagina']-1)*$limite2;

	$countSQL="SELECT COUNT(*) FROM ".DB_PREFIX.$controlador->databaseprefix." WHERE status = 'publicado'";
	$proSQL="SELECT t1.post_id, t1.titulo, t1.visibilidad, t1.modificado, t2.pseudonimo FROM ".DB_PREFIX.$controlador->databaseprefix." as t1 JOIN ".DB_PREFIX."users as t2 ON t1.autor_id = t2.uid WHERE t1.status = 'publicado'";
	if(isset($_GET['q']) && validaGeneral($_GET['q'],2)){
		$find=limpiar_find($_GET['q']);
		$proSQL.=" AND t1.titulo LIKE '%".$find."%'";
		$countSQL.=" AND titulo LIKE '%".$find."%'";
		$pags_left[]='Resultados para la busqueda "'.$find.'"';
		$search=true;
	}
	$proSQL.=" ORDER BY t1.orden ASC, t1.modificado DESC LIMIT ".$limite1.",".$limite2;

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
			$pags_texto.='<li><a href="'.$currenturl.'?pagina='.$a.'&q='.$find.'">'.$a.'</a></li>';
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
        </div><!-- /.row -->
        <?php
		if(isset($objetos) && !empty($objetos)){
			$edit_url=$url->createUrl($controlador->static_urls['editar']).'/';

			$gfu_orden=csrfCreate('order-'.$controlador->name,
				array(
					'uid' => $user->uid,
					'tipo' => 'order-',
					'id' => $controlador->name,
					'options' => array(
						'db_table' => DB_PREFIX.$controlador->databaseprefix
					)
				)
			);
			$tableorderurl=$url->createUrl($controlador->static_urls['orden-handler']).'?token='.$gfu_orden.'&futipo=order-&fuid='.$controlador->name.'&takedata=true';
		?>
        <div class="table-responsive">
		<table class="table table-striped">
        	<thead>
            	<tr>
            		<th></th>
                	<th>Titulo</th>
                    <th>Autor</th>
                    <th>Fecha</th>
                    <th>Visibilidad</th>
                </tr>
            </thead>
            <tbody class="reordertable" data-url="<?=$tableorderurl;?>">
            <?php
            foreach ($objetos as $o) {
			?>
            <tr id="obj_<?=$o['post_id'];?>">
            	<td><i class="fa fa-bars moveorder"></i></td>
            	<td><a href="<?=$edit_url.$o['post_id'];?>"><?=$o['titulo'];?></a></td>
                <td><?=$o['pseudonimo'];?></td>
                <td><?=formatDate($o['modificado'],'siga');?></td>
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
