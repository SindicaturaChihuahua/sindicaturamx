<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){

	$cHeaderData=array(
		'titulo' => 'Archivos',
		'icono' => 'paperclip',
		'opciones' => array()
	);
	$bodys->set( 'bottomjs', URLPLUGINS.'jqueryui/jquery-ui.min.js' );
	$bodys->set( 'bottomjs', 'pack-fileupload' );
	$bodys->set( 'bottomjs', URLPLUGINS.'fileupload/archivos.js' );

	//OBTENER OBJETOS
	if(!isset($_GET['pagina']) || !is_numeric($_GET['pagina'])){$_GET['pagina']=1;}
	$currenturl=$url->createUrl($controlador->static_urls['lista']);
	$find='';
	$pags_left=array();
	$search=false;
	$limite2=50;
	$limite1=($_GET['pagina']-1)*$limite2;

	$andsql='';
	$countSQL="SELECT COUNT(*) FROM ".DB_PREFIX.$controlador->databaseprefix." WHERE tipo = ?";
	if(isset($_GET['q']) && validaGeneral($_GET['q'],2)){
		$find=limpiar_find($_GET['q']);
		$countSQL.=" AND nombre LIKE '%".$find."%'";
		$andsql=" AND nombre LIKE '%".$find."%'";
		$pags_left[]='Resultados para la busqueda "'.$find.'"';
		$search=true;
	}
	$limitsql=" LIMIT ".$limite1.",".$limite2;

	$r=$db->prepare($countSQL);
	$r->execute(array('general'));
	$total_obj = $r->fetch(PDO::FETCH_COLUMN);
	if($total_obj>0){
		$pags_left[]='Resultados del '.($limite1+1).' al <span class="updatetotal">'.$limite1.'</span>';
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

		$folder=getFolderDate(false);
		$afu_archivos=csrfCreate('afu-archivos',
			array(
				'uid' => $user->uid,
				'tipo' => 'afu-',
				'id' => 'archivos',
				'options' => array(
					'user_tipo' => "general",
					'upload_dir' => "../../../../../cargas/archivos/",
					'upload_url' => URLCARGAS."archivos/",
					'folder' => $folder,
					'db_table' => DB_PREFIX.$controlador->databaseprefix,
					'dm_limitsql' => $limitsql,
					'dm_andsql' => $andsql,
					'dm_search' => $search
				)
			)
		);
		$tokendata = csrfVerify('afu-archivos', $afu_archivos);
		$gfuurl=URL.'public/plugins/fileupload/server/php/archivos/index.php?token='.$afu_archivos."&futipo=afu-&fuid=archivos&takedata=true";
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
            <div class="col-lg-9">
            	<div class="btn btn-default btn-primary btn-fileuploader"><input id="afu-archivos-files" class="fileupload" type="file" name="files[]" multiple><span class="fa fa-file-image-o"></span> &nbsp;Cargar Archivo</div>
            </div>
        </div><!-- /.row -->

		<table class="table table-striped">
        	<thead>
            	<tr>
                	<th>&nbsp;</th>
                	<th>Archivo</th>
                    <th>Enlace</th>
                    <th>Peso</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody id="afu-archivos" data-url="<?=$gfuurl;?>" class="archivos-upload-presentation">
            </tbody>
        </table>

        <div id="archivos-paginacion" class="row row-paginacion" style="display:none;">
        	<div class="col-md-6">
            	<ul class="pagination"><?=$pags_texto;?></ul>
            </div>
            <div class="col-md-6 rp-info">
            	<?=implode("<br>",$pags_left)?>
            </div>
        </div>

        <?php
	echo '</div></div>';
	include_once(PLUGINS.'fileupload/archivos-upload-template.php');
}else{
	include('noaccess.php');
}
?>
