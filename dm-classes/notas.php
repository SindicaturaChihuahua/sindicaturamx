<?php
class Notas {
	protected $db;
	public $databaseprefix;
	public $notas = array();
	public $destacados = array();
	public $categorias = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_noticias";
	}

	public function get($pagina=1, $limit=10, $destacado = false, $categoria = false, $tipo=false){
		$limite1 = ($pagina-1)*$limit;
		$addtipo = "";
		$addDestacado = '';
		$addDestacado2 = '';
		if($destacado !== false){
			$addDestacado = " AND t1.destacado = '".$destacado."'";
		}
		if($tipo){
			$addtipo = " AND t1.tipo = '".$tipo."'";
		}
		if($categoria && $categoria!=="all"){
			$query = "SELECT t1.*, t2.username, t2.pseudonimo, t2.cover as usercover
			   FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX."users AS t2 ON t1.autor_id = t2.uid
			   INNER JOIN ".DB_PREFIX.$this->databaseprefix."_relation AS t3
			   ON t1.post_id = t3.post_id
			   WHERE t3.categoria_id = ? AND t1.status = ? AND t1.visibilidad = ?".$addtipo.$addDestacado."
			   ORDER BY t1.modificado DESC LIMIT ".$limite1.",".$limit;
		    $params = array($categoria, 'publicado', 'publico');
	    }else{
			$query = "SELECT t1.*, t2.username, t2.pseudonimo, t2.cover as usercover
	            FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX."users AS t2 ON t1.autor_id = t2.uid
	            WHERE t1.status = ? AND visibilidad = ?".$addtipo.$addDestacado."
	            ORDER BY t1.modificado DESC LIMIT ".$limite1.",".$limit;
	    	$params = array('publicado', 'publico');
	    }
		if($stmt = $this->db->prepare($query)){
			$stmt->execute($params);
			$notas=$stmt->FetchAll();
			foreach($notas as $key => $tmpnota){
				$nota = new Nota($this->db);
				$nota->initFromData($tmpnota);
				$this->notas[] = $nota;
			}
		}
	}
	public function getTotal($destacado = false, $categoria=false, $tipo=false){
		$addtipo = "";
		$addtipo2 = "";
		$addDestacado = '';
		$addDestacado2 = '';
		if($tipo){
			$addtipo = " AND t1.tipo = '".$tipo."'";
			$addtipo2 = " AND tipo = '".$tipo."'";
		}
		if($destacado !== false){
			$addDestacado = " AND t1.destacado = '".$destacado."'";
			$addDestacado2 = " AND destacado = '".$destacado."'";
		}
		if($categoria){
			$query = "SELECT COUNT(t1.post_id) FROM ".DB_PREFIX.$this->databaseprefix." AS t1
		        INNER JOIN ".DB_PREFIX.$this->databaseprefix."_relation AS t3
		        ON t1.post_id = t3.post_id
		        WHERE t3.categoria_id = ? AND t1.status = ? AND t1.visibilidad = ?".$addtipo.$addDestacado;
			$params = array($categoria, 'publicado', 'publico');
		}else{
		   $query = "SELECT COUNT(post_id) FROM ".DB_PREFIX.$this->databaseprefix." WHERE status = ? AND visibilidad = ?".$addtipo2.$addDestacado2;
		   $params = array('publicado','publico');
		}
		$stmt = $this->db->prepare($query);
		$stmt->execute($params);
		$total_obj = $stmt->fetch(PDO::FETCH_COLUMN);
		return $total_obj;
	}
	public function getDestacados(){
		if($stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."
			WHERE status = 'publicado' AND visibilidad = 'publico' AND destacado = '1' ORDER BY modificado DESC LIMIT 3")){
			$stmt->execute();
			$notas=$stmt->FetchAll();
			foreach($notas as $key => $tmpnota){
				$nota = new Nota($this->db);
				$nota->initFromData($tmpnota);
				$this->destacados[] = $nota;
			}
		}
	}
	function getNotasCategorias(){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_categorias WHERE status = 'publicado' ORDER BY orden ASC");
		$stmt->execute();
		if($categoriastmp = $stmt->FetchAll()){
			foreach($categoriastmp as $categoria){
				$this->categorias[$categoria['categoria_id']]=$categoria;
				if($this->noEmptyCategoria($categoria['categoria_id'])){
					$this->categorias[$categoria['categoria_id']]["no_empty"] = true;
				}else{
					$this->categorias[$categoria['categoria_id']]["no_empty"] = false;
				}
			}
		}
	}
	function getCategoria($slug){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_categorias WHERE status = 'publicado' AND categoria_slug = ? LIMIT 1");
		$stmt->execute(array($slug));
		$dtmp = $stmt->fetch();
		if($dtmp){
			$categoria = $dtmp;
			if($this->noEmptyCategoria($categoria['categoria_id'])){
				$categoria["no_empty"] = true;
				$categoria['folderpath'] = URLCARGAS.'blog/categorias/obj'.$categoria['categoria_id'].'/';
				$imagenes = array("categoria_cover", "categoria_img1");
				foreach ($imagenes as $imagen) {
					if(isset($categoria[$imagen]) && validaGeneral($categoria[$imagen], 4)){
						$categoria['has_'.$imagen] = true;
						$categoria[$imagen.'_image'] = $categoria['folderpath'].$categoria[$imagen];
						$categoria[$imagen.'_image_medium'] = $categoria['folderpath']."medium_".$categoria[$imagen];
						$categoria[$imagen.'_image_big'] = $categoria['folderpath']."big_".$categoria[$imagen];
					}else{
						$categoria['has_'.$imagen] = false;
						$categoria[$imagen.'_image'] = URLIMAGES.'default/blog_'.$imagen.'.png';
						$categoria[$imagen.'_image_medium'] = URLIMAGES.'default/blog_'.$imagen.'.png';
						$categoria[$imagen.'_image_big'] = URLIMAGES.'default/blog_'.$imagen.'.png';
					}
				}
				return $categoria;
			}else{
				$categoria["no_empty"] = false;
			}
		}
	}
	function getCategoriaById($id){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_categorias WHERE status = 'publicado' AND categoria_id = ? LIMIT 1");
		$stmt->execute(array($id));
		$dtmp = $stmt->fetch();
		if($dtmp){
			$categoria = $dtmp;
			if($this->noEmptyCategoria($categoria['categoria_id'])){
				$categoria["no_empty"] = true;
				$categoria['folderpath'] = URLCARGAS.'blog/categorias/obj'.$categoria['categoria_id'].'/';
				$imagenes = array("categoria_cover", "categoria_img1");
				foreach ($imagenes as $imagen) {
					if(isset($categoria[$imagen]) && validaGeneral($categoria[$imagen], 4)){
						$categoria['has_'.$imagen] = true;
						$categoria[$imagen.'_image'] = $categoria['folderpath'].$categoria[$imagen];
						$categoria[$imagen.'_image_medium'] = $categoria['folderpath']."medium_".$categoria[$imagen];
						$categoria[$imagen.'_image_big'] = $categoria['folderpath']."big_".$categoria[$imagen];
					}else{
						$categoria['has_'.$imagen] = false;
						$categoria[$imagen.'_image'] = URLIMAGES.'default/blog_'.$imagen.'.png';
						$categoria[$imagen.'_image_medium'] = URLIMAGES.'default/blog_'.$imagen.'.png';
						$categoria[$imagen.'_image_big'] = URLIMAGES.'default/blog_'.$imagen.'.png';
					}
				}
				return $categoria;
			}else{
				$categoria["no_empty"] = false;
			}
		}
	}
	function noEmptyCategoria($categoria){
		$stmt = $this->db->prepare("SELECT COUNT(t1.post_id) FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX.$this->databaseprefix."_relation AS t3 ON t1.post_id = t3.post_id WHERE t3.categoria_id = ".$categoria." AND t1.status = 'publicado' AND visibilidad = 'publico' ORDER BY t1.modificado DESC");
		$stmt->execute();
		$total_obj = $stmt->fetch(PDO::FETCH_COLUMN);
	    if($total_obj > 0){
			return true;
		}else{
			return false;
		}
	}
	public function printListingNotas(){
		for($i=0; $i < count($this->notas); $i++){
			$this->notas[$i]->printListingNota();
		}
	}
	public function printNotas(){
		if(count($this->notas) > 0){
			for($i=0; $i < count($this->notas); $i++){
				$this->notas[$i]->printNota();
			}
		}
	}
	public function printNotasHome(){
		if(count($this->notas) > 0){
			for($i=0; $i < count($this->notas); $i++){
				$this->notas[$i]->printNotaHome();
			}
		}
	}
	public function printDestacados(){
		if(count($this->notas) > 0){
			for($i=0; $i < count($this->notas); $i++){
				$this->notas[$i]->printDestacado();
			}
		}
	}
	public function printNotasByCategoria(){
		if(empty($this->categorias)){
			$this->getNotasCategorias();
		}
		foreach($this->categorias as $categoria){
			if($categoria["no_empty"]){
				echo '<div class="nosotros-notas-wrap notas-'.$categoria["categoria_slug"].$first.'"><div class="notas-slick">';
				$this->get($categoria["categoria_id"]);
	            $this->printThumbNotas();
				echo '</div></div>';
				$first = '';
			}
		}
	}
	public function printListingNotasByCategoria($categoria){
		$notas = new Notas($this->db);
		$notas->get($categoria);
		if(count($notas->notas) > 0){
			echo '<ul>';
			for($i=0; $i < count($notas->notas); $i++){
				$notas->notas[$i]->printListingServicioNosotros();
			}
			echo '</ul>';
		}
	}
	public function printNotasCintillo(){
		if(count($this->notas) > 0){
			echo '<div class="noticias-cintillo">';
			for($i=0; $i < count($this->notas); $i++){
				$this->notas[$i]->printNotaCintillo();
			}
			echo '</div>';
		}
	}

	public function paginacion($total, $totalresultados, $limit, $pagina){
	    $data = array();
	    $limite1=($pagina-1)*$limit;
	    $data['resultados']='Resultados del '.($limite1+1).' al '.($limite1+$totalresultados);
	    $data['total']=$total . ' disponibles';
		if(isset($_GET['q']) && validaGeneral($_GET['q'],3)){
			$data['busqueda'] = "Búsqueda: '".$_GET['q']."'";
		}else{
			$data['busqueda'] = false;
		}
	    $total_pag=ceil($total/$limit);
	    $l_pag1=$pagina-5;
	    $l_pag2=$pagina+5;
	    if($l_pag1<1){$l_pag1=1;}
	    if($l_pag2>$total_pag){$l_pag2=$total_pag;}

	    $data['limitebajo'] = $l_pag1;
	    $data['limitealto'] = $l_pag2;
		if($data["limitealto"] > 1){
			$this->printPaginacion($data, $pagina);
		}
	}

	public function printPaginacion($paginacion, $paginaactual, $append=''){
	    echo '<div class="paginacion cfix">';
	        echo '<div class="cl">';
	        for($a=$paginacion['limitebajo'];$a<=$paginacion['limitealto'];$a++){
	        	if($paginaactual==$a){
	        		echo '<div class="btn-page active">'.$a.'<span></span></div>';
	        	}else{
	        		echo '<a class="btn-page" href="'.$_GET['dm_currenturl'].'&p='.$a.$append.'">'.$a.'<span></span></a>';
	        	}
	        }
	        echo '</div>';
	        // echo '<div class="cr">';
	        //     echo $paginacion['resultados'].' de '.$paginacion['total'];
	        //     if($paginacion['busqueda']){
	        //         echo '<br>'.$paginacion['busqueda'];
	        //     }
	        // echo '</div>';
		echo '</div>';
	}
}
?>
