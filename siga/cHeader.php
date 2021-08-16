<div id="cHeader" class="clearfix">
	<div class="fa fa-<?=isset($cHeaderData['icono']) ? $cHeaderData['icono'] : 'fire';?>"></div>
    <div class="titulo ellipsis"><?=isset($cHeaderData['titulo']) ? ' '.$cHeaderData['titulo'] : '';?></div>
    <div class="opts">
    <?php
	if(isset($cHeaderData['opciones'])){
		foreach($cHeaderData['opciones'] as $chopt){
			$class='';
			if(isset($chopt['class'])){
				$class=$chopt['class'];	
			}
			echo '<a href="'.SIGA.$chopt['link'].'" class="'.$class.'"><div class="fa fa-'.$chopt['icono'].' fa-fw"></div><br><span>'.$chopt['nombre'].'</span></a>';
		}
	}
	?>
    </div>
</div>