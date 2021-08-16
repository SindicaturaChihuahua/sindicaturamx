<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){

	$cHeaderData=array(
		'titulo' => 'Registros',
		'icono' => 'check',
		'opciones' => array(
			// array(
			// 	'nombre' => 'Nueva Imagen',
			// 	'link' => $controlador->static_urls['nuevo'],
			// 	'icono' => 'plus'
			// )
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
	$proSQL="SELECT * FROM ".DB_PREFIX.$controlador->databaseprefix." WHERE status = 'publicado'";
	if(isset($_GET['q']) && validaGeneral($_GET['q'],2)){
		$find=limpiar_find($_GET['q']);
		$proSQL.=" AND correo LIKE '%".$find."%'";
		$countSQL.=" AND correo LIKE '%".$find."%'";
		$pags_left[]='Resultados para la busqueda "'.$find.'"';
		$search=true;
	}
	if(isset($_GET['tn'])){
		$proSQL.=" AND comision_id = '".$_GET['tn']."'";
		$countSQL.=" AND comision_id = '".$_GET['tn']."'";
	}

	$proSQL.=" ORDER BY orden ASC, modificado DESC LIMIT ".$limite1.",".$limite2;

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
			<div class="col-lg-6">
			<?php
			$comisiones = $controlador->getComisiones();
			echo '<div class="btn-group gbtn-ml">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Mostrar <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">';
					echo '<li><a href="'.$currenturl.'">Todo</a></li>';
					foreach ($comisiones as $comision) {
						echo '<li><a href="'.$currenturl.'?tn='.$comision["post_id"].'">'.$comision["titulo"].'</a></li>';
					}
					echo '</ul>
				</div>';
			?>
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
                	<th>Correo electrónico</th>
                    <th>Comisión</th>
                    <th>Fecha de registro</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($objetos as $o) {
				$comision = $controlador->getComision($o["comision_id"]);
			?>
            <tr id="obj_<?=$o['post_id'];?>">
            	<td><?=$o['correo'];?></td>
                <td><?=$comision["titulo"];?></td>
                <td><?=formatDate($o['modificado'],'siga');?></td>
                <td></td>
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
