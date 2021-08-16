<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){
	
	$cHeaderData=array(
		'titulo' => 'Usuarios',
		'icono' => 'users',
		'opciones' => array(
			array(
				'nombre' => 'Nuevo Usuario',
				'link' => $controlador->static_urls['nuevo'],
				'icono' => 'user'
			)
		)
	);
	
	//OBTENER OBJETOS
	if(!isset($_GET['pagina']) || !is_numeric($_GET['pagina'])){$_GET['pagina']=1;}
	$currenturl=$url->createUrl($controlador->static_urls['lista']);
	$find='';
	$pags_left=array();
	$search=false;
	$limite2=25;
	$limite1=($_GET['pagina']-1)*$limite2;
	
	$countSQL="SELECT COUNT(*) FROM ".DB_PREFIX.$controlador->databaseprefix." WHERE (status = 1 || status = 2) AND nivel_acc < 100 AND nivel_acc >= 50";
	$proSQL="SELECT * FROM ".DB_PREFIX.$controlador->databaseprefix." WHERE (status = 1 || status = 2) AND nivel_acc < 100 AND nivel_acc >= 50";
	if(isset($_GET['q']) && validaGeneral($_GET['q'],2)){
		$find=limpiar_find($_GET['q']);
		$proSQL.=" AND nombre LIKE '%".$find."%'";
		$countSQL.=" AND nombre LIKE '%".$find."%'";
		$pags_left[]='Resultados para la busqueda "'.$find.'"';
		$search=true;
	}
	$proSQL.=" ORDER BY uid DESC LIMIT ".$limite1.",".$limite2;

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
                    <input type="text" class="form-control" name="q" placeholder="¿A quién estas buscando?" value="<?=isset($_GET['q']) ? $_GET['q'] : "";?>">
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
		?>
        <div class="table-responsive">
		<table class="table table-striped">
        	<thead>
            	<tr>
                	<th>Nombre</th>
                    <th>Rol</th>
                    <th>Fecha de registro</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($objetos as $o) {
			?>
            <tr>
            	<td><a href="<?=$edit_url.$o['uid'];?>"><?=cleanOutput($o['nombre']);?></a></td>
                <td><?=cleanOutput($controlador->permisos[$o['nivel_acc']]['titulo']);?></td>
                <td><?=formatDate($o['registrado'],'siga');?></td>
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