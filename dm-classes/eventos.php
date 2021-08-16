<?php
class Eventos {
	protected $db;
	public $databaseprefix;
	public $eventos = array();
	public $destacados = array();
	public $categorias = array();
	public $hayfuturos;
	public $haypasados;
	public $year;
	public $mes;
	public $nombre_mes;
	public $num_mes;
	public $next;
	public $prev;
	public $stardDate;
	public $finalMes;
	public $inicioMes;
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_agenda";
	}
	public function init($getA = false, $getM = false){
		$tiempo=array(
	'month' => array("","enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiempre", "octubre", "noviembre", "diciembre"),
	'monthmini' => array("","Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"),
	'day' => array("domingo","lunes","martes","miércoles","jueves","viernes","sábado"),
	'daymini' => array("Dom","Lun","Mar","Mié","Jue","Vie","Sáb"),
	'mes' => array("","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiempre", "Octubre", "Noviembre", "Diciembre"),
	'dia' => array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"),
);
		$this->hayfuturos = 'false';
		$this->haypasados = 'false';
		$this->year = date("Y");
		$this->mes = date("m");
		$this->num_mes = date("n");
		if($getA){
			$a = addslashes(strip_tags($getA));
			$this->year = date("Y", mktime(0, 0, 0, 6, 1, $a));
		}
		if($getM){
			$m = addslashes(strip_tags($getM));
			$this->mes = date("m", mktime(0, 0, 0, $m, 1, $this->year));
			$this->num_mes = date("n", mktime(0, 0, 0, $this->mes, 1, $this->year));
		}
		$this->nombre_mes = $tiempo["mes"][$this->num_mes];

		$this->startDate = $this->year.'-'.$this->mes.'-01';
		$nMonths = 1;
		$this->inicioMes = $this->startDate.' 00:00:00';
		$this->finalMes = $this->endCycle($this->startDate, $nMonths);
		$this->finalMes .= ' 23:59:59';
		$this->next = $this->getMes($this->mes,$this->year,'next');
		$this->prev = $this->getMes($this->mes,$this->year,'prev');
	}
	public function getCalendario($pagina=1, $limit=10, $inicio, $final, $categoria = false, $tipo=false){
		$limite1 = ($pagina-1)*$limit;
		$addtipo = "";
		if($tipo){
			$addtipo = " AND t1.tipo = '".$tipo."'";
		}
		if($categoria && $categoria!=="all"){
			$query = "SELECT t1.*, t2.username, t2.pseudonimo, t2.cover as usercover
			   FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX."users AS t2 ON t1.autor_id = t2.uid
			   INNER JOIN ".DB_PREFIX.$this->databaseprefix."_relation AS t3
			   ON t1.post_id = t3.post_id
			   WHERE t1.modificado >= ? AND t1.modificado <= ? AND t3.categoria_id = ? AND t1.status = ? AND t1.visibilidad = ?".$addtipo."
			   ORDER BY t1.modificado ASC LIMIT ".$limite1.",".$limit;
		    $params = array($inicio, $final, $categoria, 'publicado', 'publico');
	    }else{
			$query = "SELECT t1.*, t2.username, t2.pseudonimo, t2.cover as usercover
	            FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX."users AS t2 ON t1.autor_id = t2.uid
	            WHERE t1.modificado >= ? AND t1.modificado <= ? AND t1.status = ? AND visibilidad = ?".$addtipo."
	            ORDER BY t1.modificado ASC LIMIT ".$limite1.",".$limit;
	    	$params = array($inicio, $final, 'publicado', 'publico');
	    }
		if($stmt = $this->db->prepare($query)){
			$stmt->execute($params);
			$eventos=$stmt->FetchAll();
			foreach($eventos as $key => $tmpevento){
				$evento = new Evento($this->db);
				$evento->initFromData($tmpevento);
				$this->eventos[] = $evento;
			}
		}
	}
	public function get($pagina=1, $limit=10, $categoria = false, $tipo=false){
		$limite1 = ($pagina-1)*$limit;
		$addtipo = "";
		if($tipo){
			$addtipo = " AND t1.tipo = '".$tipo."'";
		}
		if($categoria && $categoria!=="all"){
			$query = "SELECT t1.*, t2.username, t2.pseudonimo, t2.cover as usercover
			   FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX."users AS t2 ON t1.autor_id = t2.uid
			   INNER JOIN ".DB_PREFIX.$this->databaseprefix."_relation AS t3
			   ON t1.post_id = t3.post_id
			   WHERE t3.categoria_id = ? AND t1.status = ? AND t1.visibilidad = ?".$addtipo."
			   ORDER BY t1.modificado DESC LIMIT ".$limite1.",".$limit;
		    $params = array($categoria, 'publicado', 'publico');
	    }else{
			$query = "SELECT t1.*, t2.username, t2.pseudonimo, t2.cover as usercover
	            FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX."users AS t2 ON t1.autor_id = t2.uid
	            WHERE t1.status = ? AND visibilidad = ?".$addtipo."
	            ORDER BY t1.modificado DESC LIMIT ".$limite1.",".$limit;
	    	$params = array('publicado', 'publico');
	    }
		if($stmt = $this->db->prepare($query)){
			$stmt->execute($params);
			$eventos=$stmt->FetchAll();
			foreach($eventos as $key => $tmpevento){
				$evento = new Evento($this->db);
				$evento->initFromData($tmpevento);
				$this->eventos[] = $evento;
			}
		}
	}
	public function getTotal($inicio,$final,$categoria=false, $tipo=false){
		$addtipo = "";
		$addtipo2 = "";
		if($tipo){
			$addtipo = " AND t1.tipo = '".$tipo."'";
			$addtipo2 = " AND tipo = '".$tipo."'";
		}
		if($categoria){
			$query = "SELECT COUNT(t1.post_id) FROM ".DB_PREFIX.$this->databaseprefix." AS t1
		        INNER JOIN ".DB_PREFIX.$this->databaseprefix."_relation AS t3
		        ON t1.post_id = t3.post_id
		        WHERE t3.categoria_id = ? AND t1.modificado >= ? AND t1.modificado <= ? AND t1.status = ? AND t1.visibilidad = ?".$addtipo;
			$params = array($categoria, $inicio, $final, 'publicado', 'publico');
		}else{
		   $query = "SELECT COUNT(post_id) FROM ".DB_PREFIX.$this->databaseprefix." WHERE modificado >= ? AND modificado <= ? AND status = ? AND visibilidad = ?".$addtipo2;
		   $params = array($inicio, $final,'publicado','publico');
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
			$eventos=$stmt->FetchAll();
			foreach($eventos as $key => $tmpevento){
				$evento = new Evento($this->db);
				$evento->initFromData($tmpevento);
				$this->destacados[] = $evento;
			}
		}
	}
	function getCategorias(){
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
	public function getCategoria($slug){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_categorias WHERE status = 'publicado' AND categoria_slug = ? LIMIT 1");
		$stmt->execute(array($slug));
		$dtmp = $stmt->fetch();
		if($dtmp){
			$categoria = $dtmp;
			if($this->noEmptyCategoria($categoria['categoria_id'])){
				$categoria["no_empty"] = true;
				$categoria['folderpath'] = URLCARGAS.'agenda/categorias/obj'.$categoria['categoria_id'].'/';
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
		}else{
			$nocategoria = array();
			$nocategoria["categoria_id"] = 0;
			return $nocategoria;
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
		for($i=0; $i < count($this->eventos); $i++){
			$this->eventos[$i]->printListingNota();
		}
	}
	public function printEventosHeader(){
		echo '<div class="eventos-heading">';
		echo '<h2 class="tcenter"><a href="'.URL.'agenda/'.$this->prev.'#vC"><i class="fas fa-chevron-left"></i></a> '.$this->nombre_mes.' '.$this->year.' <a href="'.URL.'agenda/'.$this->next.'#vC"><i class="fas fa-chevron-right"></i></a></h2>';
		echo '<div class="mPasados"><div id="mostrarPasados" class="btn-primary"></div></div>';
		echo '</div>';
	}
	public function printEventos(){
		echo '<div class="eventos-wrap">';
		if(count($this->eventos) > 0){
			$hoy = date("Y-m-d");
			$hoy .= ' 00:00:00';
			for($i=0; $i < count($this->eventos); $i++){
				if($hoy > $this->eventos[$i]->data['modificado']){
					$this->haypasados = 'true';
				}else{
					$this->hayfuturos = 'true';
				}
				$this->eventos[$i]->printEvento();
			}
		}
		echo '</div>';
	}
	public function printDestacados(){
		echo '<div class="noticias-wrap">';
		if(count($this->destacados) > 0){
			for($i=0; $i < count($this->destacados); $i++){
				$this->destacados[$i]->printDestacado();
			}
		}else{
			$destacados = new Notas($this->db);
			$destacados->get(1,3);
			for($i=0; $i < 3; $i++){
				$destacados->eventos[$i]->printNota();
			}
		}
		echo '</div>';
	}
	public function printNotasByCategoria(){
		if(empty($this->categorias)){
			$this->getNotasCategorias();
		}
		foreach($this->categorias as $categoria){
			if($categoria["no_empty"]){
				echo '<div class="nosotros-eventos-wrap eventos-'.$categoria["categoria_slug"].$first.'"><div class="eventos-slick">';
				$this->get($categoria["categoria_id"]);
	            $this->printThumbNotas();
				echo '</div></div>';
				$first = '';
			}
		}
	}
	public function printListingNotasByCategoria($categoria){
		$eventos = new Notas($this->db);
		$eventos->get($categoria);
		if(count($eventos->eventos) > 0){
			echo '<ul>';
			for($i=0; $i < count($eventos->eventos); $i++){
				$eventos->eventos[$i]->printListingServicioNosotros();
			}
			echo '</ul>';
		}
	}

	public function printCategorias($currenturl){
		if(empty($this->categorias)){
			$this->getCategorias();
		}
		$activeI = '';
		if(!isset($_GET["ver"]) && empty($_GET["ver"])){
			$activeI = ' class="active"';
		}
		echo '<ul>';
		echo '<li><a href="'.$currenturl.'"'.$activeI.'>Mostrar todos</a></li>';
		foreach($this->categorias as $categoria){
			if($categoria["no_empty"]){
				$active = '';
				if(isset($_GET["ver"]) && !empty($_GET["ver"]) && $_GET["ver"] == $categoria["categoria_slug"]){
					$active = ' class="active"';
				}
				echo '<li><a href="'.$currenturl.'&ver='.$categoria["categoria_slug"].'"'.$active.'>'.$categoria["categoria_nombre"].' <span class="cat-color '.$categoria["categoria_formato"].'"></span></a></li>';
			}
		}
		echo '</ul>';
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
			if(isset($_GET["ver"]) && !empty($_GET["ver"])){
				$append = '&ver='.$_GET["ver"];
			}
			$this->printPaginacion($data, $pagina, $append);
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

	public function endCycle($d1, $months){
		$date = new DateTime($d1);
		$newDate = $date->add($this->add_months($months, $date));
		$newDate->sub(new DateInterval('P1D'));
		$dateReturned = $newDate->format('Y-m-d');
		return $dateReturned;
	}
	public function getMes($mes,$year,$arrow){
		if($arrow == 'next'){
			if($mes == 12){
				$year++;
				$getMes = '?m=1&a='.$year;
			}else{
				$mes++;
				$getMes = '?m='.$mes.'&a='.$year;
			}
		}elseif($arrow == 'prev'){
			if($mes == 1){
				$year--;
				$getMes = '?m=12&a='.$year;
			}else{
				$mes--;
				$getMes = '?m='.$mes.'&a='.$year;
			}
		}
		return $getMes;
	}
	public function add_months($months, DateTime $dateObject) {
		$next = new DateTime($dateObject->format('Y-m-d'));
		$next->modify('last day of +'.$months.' month');
		if($dateObject->format('d') > $next->format('d')) {
			return $dateObject->diff($next);
		} else {
			return new DateInterval('P'.$months.'M');
		}
	}
}
?>
