<?php
class Nota {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $post_id;
	public $titulo;
	public $titulo_truncado;
	public $slug;
	public $tipo;
	public $descripcion;
	public $extra;
	public $categoria;
	public $video;
	public $fecha;
	public $archivos = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_noticias";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'noticias/obj'.$this->data['post_id'].'/';
		$imagenes = array("cover","thumb","img1","img2","img3");
		foreach ($imagenes as $imagen) {
			if(isset($this->data[$imagen]) && validaGeneral($this->data[$imagen], 4)){
				$this->data['has_'.$imagen] = true;
				$this->data[$imagen.'_image'] = $this->data['folderpath'].$this->data[$imagen];
				$this->data[$imagen.'_image_medium'] = $this->data['folderpath']."medium_".$this->data[$imagen];
				$this->data[$imagen.'_image_big'] = $this->data['folderpath']."big_".$this->data[$imagen];
			}else{
				$this->data['has_'.$imagen] = false;
				$this->data[$imagen.'_image'] = URLIMAGES.'default/blog_'.$imagen.'.png';
				$this->data[$imagen.'_image_medium'] = URLIMAGES.'default/blog_'.$imagen.'.png';
				$this->data[$imagen.'_image_big'] = URLIMAGES.'default/blog_'.$imagen.'.png';
			}
		}
		$this->titulo = $this->data["titulo"];
		$this->titulo_truncado = truncar_cadena($this->data["titulo"], 76, " ");
		$this->slug = $this->data["slug"];
		$this->tipo = $this->data["tipo"];
		$this->descripcion = $this->data["descripcion"];
		$this->extracto = truncar_cadena(strip_tags($this->descripcion), 100, " ", '...');
		$this->categoria = $this->getNotaCategoria($this->post_id);
		$this->fecha = formatDate($this->data["modificado"],"formal");
		if(!empty($this->data["video"])){
			$this->video = $this->parseVideo($this->data["video"]);
		}else{
			$this->video = false;
		}
		$this->archivos = $this->getArchivos($this->post_id);
		// $this->url = URL.$this->categoria["categoria_slug"].'/'.$this->slug;
		$this->url = URL.'noticias/'.$this->slug;
		if(isset($this->data['extra'])){
			$this->extra = json_decode($this->data['extra'], true);
		}
	}

	public function initFromData($data){
		$this->data = $data;
		$this->post_id = $data['post_id'];
		$this->init();
	}

	public function getNota($slug){
		$sql = "SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE slug = ? LIMIT 1";
		$parametros = array($slug);

        if($stmt = $this->db->prepare($sql)){
			$stmt->execute($parametros);
			$dtmp = $stmt->fetch();
			if($dtmp){
				$this->data = $dtmp;
				$this->post_id = $this->data["post_id"];
				$this->init();
				return true;
			}
		}
		return false;
    }
	public function getNotaById($post_id){
		$sql = "SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE post_id = ? LIMIT 1";
		$parametros = array($post_id);

        if($stmt = $this->db->prepare($sql)){
			$stmt->execute($parametros);
			$dtmp = $stmt->fetch();
			if($dtmp){
				$this->data = $dtmp;
				$this->post_id = $post_id;
				$this->init();
				return true;
			}
		}
		return false;
    }
	public function printNota(){
		echo '<div class="noticia-box">
			<a href="'.$this->url.'" title="'.$this->titulo.'" class="noticia-img" style="background-image:url('.$this->data["cover_image_big"].');"></a>
			<div class="noticia-txt">
				<span class="noticia-categoria">'.$this->categoria["categoria_nombre"].'</span>
				<a href="'.$this->url.'" title="'.$this->titulo.'"><h3>'.$this->titulo_truncado.'</h3></a>
				<p>'.$this->extracto.'</p>
			</div>
		</div>';
	}
	public function printNotaHome(){
		echo '<div class="noticia-box">
			<a href="'.$this->url.'" title="'.$this->titulo.'" class="noticia-img" style="background-image:url('.$this->data["cover_image_big"].');"></a>
			<div class="noticia-txt">
				<span class="noticia-categoria">'.$this->categoria["categoria_nombre"].'</span>
				<a href="'.$this->url.'" title="'.$this->titulo.'"><h3>'.$this->titulo_truncado.'</h3></a>
			</div>
		</div>';
	}
	public function printNotaCintillo(){
		echo '<a href="'.$this->url.'" class="noticia-cintillo" title="'.$this->titulo.'"><span class="noticia-cintillo-categoria">// '.$this->categoria["categoria_nombre"].':</span> '.$this->titulo_truncado.'</a>';
	}
	public function printDestacado(){
		echo '<div class="home-destacado image-first">
			<div class="home-destacado-txt">
				<div class="home-destacado-txt-wrap">
					<a href="'.$this->url.'" title="'.$this->titulo.'"><h2>'.$this->titulo.'</h2></a>
					<span class="home-destacado-fecha">'.$this->fecha.'</span>
					<p>'.$this->extracto.'</p>
					<img class="decor decor01" src="'.URLIMAGES.'decor01.png" alt="">
				</div>
			</div>
			<div class="home-destacado-img">';
			if(!$this->video){
				echo '<a href="'.$this->url.'" title="'.$this->titulo.'" class="home-destacado-cover" style="background-image:url('.$this->data["cover_image_big"].');"></a>';
			}else{
				echo '<div class="noticia-video"><iframe id="videoNoticia" src="https://www.youtube.com/embed/'.$this->video.'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>';
			}


			echo '</div>
		</div>';
	}
	public function printPortada(){
		if(!$this->video && count($this->archivos) < 1){
			echo '<div class="noticia-cover" style="background-image:url('.$this->data["cover_image_big"].');"></div>';
		}elseif(!$this->video && count($this->archivos) > 0){
			echo '<div class="owl-carousel owl-theme owlNota">';
			for ($i=0; $i < count($this->archivos); $i++) {
				echo '<div class="owl-nota"><img src="'.$this->data['folderpath'].'large/'.$this->archivos[$i]["nombre"].'" alt="'.$this->archivos[$i]["descripcion"].'" title="'.$this->archivos[$i]["descripcion"].'"></div>';
			}
			echo '</div>';
		}else{
			echo '<div class="noticia-video"><iframe id="videoNoticia" src="https://www.youtube.com/embed/'.$this->video.'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>';
		}
	}
	public function printSharer(){
		$url_encoded = urlencode($this->url);
    	$name_encoded = urlencode($this->titulo);
		$hashtags = 'SindicaturaChihuahua';
		//Facebook
		echo '<a href="https://www.facebook.com/sharer/sharer.php?u='.$url_encoded.'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
		//Twitter
		echo '<a href="https://twitter.com/intent/tweet?text='.$name_encoded.'&url='.$url_encoded.'&hashtags='.$hashtags.'" target="_blank"><i class="fab fa-twitter"></i></a>';
		//Google+
		// echo '<a href="https://plus.google.com/share?url='.$url_encoded.'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
	}
	public function getNotaCategoria($post_id){
		$stmt = $this->db->prepare("SELECT t1.* FROM ".DB_PREFIX.$this->databaseprefix."_categorias AS t1 INNER JOIN ".DB_PREFIX.$this->databaseprefix."_relation AS t2 ON t1.categoria_id = t2.categoria_id WHERE t2.post_id = ? LIMIT 1");
		$stmt->execute(array($post_id));
		$dtmp = $stmt->fetch();
		if($dtmp){
			return $dtmp;
		}
	}
	public function getSiguiente($fecha,$categoria){
		if($stmt = $this->db->prepare("SELECT t1.*, t2.username, t2.pseudonimo, t2.cover as usercover
			FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX."users AS t2 ON t1.autor_id = t2.uid
			INNER JOIN ".DB_PREFIX.$this->databaseprefix."_relation AS t3 ON t1.post_id = t3.post_id WHERE t1.modificado > ? AND t3.categoria_id = ? AND t1.status = ? AND visibilidad = ? ORDER BY t1.modificado DESC LIMIT 1")){
			$stmt->execute(array($fecha,$categoria,'publicado','publico'));
		}elseif($stmt = $this->db->prepare("SELECT t1.*, t2.username, t2.pseudonimo, t2.cover as usercover
			FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX."users AS t2 ON t1.autor_id = t2.uid
			INNER JOIN ".DB_PREFIX.$this->databaseprefix."_relation AS t3 ON t1.post_id = t3.post_id WHERE t3.categoria_id = ? AND t1.status = ? AND visibilidad = ? ORDER BY t1.modificado ASC LIMIT 1")){
			$stmt->execute(array($categoria,'publicado','publico'));
		}else{
			return false;
		}
		if($siguientetmp=$stmt->Fetch()){
			$siguiente = new Servicio($this->db);
			$siguiente->initFromData($siguientetmp);
			return $siguiente;
		}
	}
	public function getAnterior($fecha,$categoria){
		if($stmt = $this->db->prepare("SELECT t1.*, t2.username, t2.pseudonimo, t2.cover as usercover
			FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX."users AS t2 ON t1.autor_id = t2.uid
			INNER JOIN ".DB_PREFIX.$this->databaseprefix."_relation AS t3 ON t1.post_id = t3.post_id WHERE t1.modificado < ? AND t3.categoria_id = ? AND t1.status = ? AND visibilidad = ? ORDER BY t1.modificado DESC LIMIT 1")){
			$stmt->execute(array($fecha,$categoria,'publicado','publico'));
		}elseif($stmt = $this->db->prepare("SELECT t1.*, t2.username, t2.pseudonimo, t2.cover as usercover
			FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX."users AS t2 ON t1.autor_id = t2.uid
			INNER JOIN ".DB_PREFIX.$this->databaseprefix."_relation AS t3 ON t1.post_id = t3.post_id WHERE t3.categoria_id = ? AND t1.status = ? AND visibilidad = ? ORDER BY t1.modificado DESC LIMIT 1")){
			$stmt->execute(array($categoria,'publicado','publico'));
		}else{
			return false;
		}
		if($anteriortmp=$stmt->Fetch()){
			$anterior = new Servicio($this->db);
			$anterior->initFromData($anteriortmp);
			return $anterior;
		}
	}
	public function printSiguiente($siguiente = false){
		if($siguiente){
			echo '<a href="'.$siguiente->url.'" class="servicio-otro" style="background-image:url('.$siguiente->data["cover_image_big"].')">
				<div class="servicio-otro-txt">
					<h3>'.$siguiente->titulo.'</h3>
					<div class="btn-secondary">Abrir Servicio Siguiente</div>
				</div>
			</a>';
		}else{
			return false;
		}
	}
	public function printAnterior($anterior = false){
		if($anterior){
			echo '<a href="'.$anterior->url.'" class="servicio-otro" style="background-image:url('.$anterior->data["cover_image_big"].')">
				<div class="servicio-otro-txt">
					<h3>'.$anterior->titulo.'</h3>
					<div class="btn-secondary">Abrir Servicio Anterior</div>
				</div>
			</a>';
		}else{
			return false;
		}
	}

	public function isOpen(){
		if($this->data['status']=="publicado" && $this->data['visibilidad']!="oculto"){
			return true;
		}
		return false;
	}
	public function addView(){
		$this->db->query("UPDATE ".DB_PREFIX.$this->databaseprefix." SET visitas=visitas+1 WHERE post_id = ".$this->post_id);
	}
	public function parseVideo($video){
		$parsed = parse_url($video);
		if($parsed["host"] == "youtu.be"){
			return $parsed["path"];
		}elseif($parsed["host"] == "www.youtube.com" || $parsed["host"] == "youtube.com"){
			$query = strstr($parsed["query"], '&', true);
			if($query == false){
				$query = str_replace("v=","",$parsed["query"]);
			}else{
				$query = str_replace("v=","",$query);
			}
			return $query;
		}
	}

	public function getArchivos($id){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_archivos WHERE objeto_id = ? AND status = ? ORDER BY orden ASC");
		$stmt->execute(array($id,'publish'));
		return $stmt->FetchAll();
	}
}
?>
