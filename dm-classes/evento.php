<?php
class Evento {
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
	public $fecha;
	public $hora;
	public $hayfuturos;
	public $haypasados;
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_agenda";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'agenda/obj'.$this->data['post_id'].'/';
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
		$this->extracto = truncar_cadena(strip_tags($this->descripcion), 120, " ");
		$this->categoria = $this->getNotaCategoria($this->post_id);
		$this->fecha = formatDate($this->data["modificado"],"formal");
		$this->hora = formatDate($this->data["modificado"],"hora");
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
	public function printEvento(){
		$hoy = date("Y-m-d");
		$hoy .= ' 00:00:00';
		if($hoy > $this->data['modificado']){
			$pasado = ' pasado';
		}else{
			$pasado = '';
		}
		echo '<div class="evento-box'.$pasado.'">
			<div class="evento-box-inner">
				<span class="color-dot '.$this->categoria["categoria_formato"].'"></span>
				<h3>'.$this->titulo.'</h3>
				<p>'.$this->fecha.'</p>
				<p>'.$this->hora.' hrs.</p>
			</div>
		</div>';
	}
	public function printDestacado(){
		echo '<div class="noticia-box">
			<a href="'.$this->url.'" title="'.$this->titulo.'" class="noticia-img" style="background-image:url('.$this->data["cover_image_big"].');"></a>
			<div class="noticia-txt">
				<a href="'.$this->url.'" title="'.$this->titulo.'"><h3>'.$this->titulo_truncado.'</h3></a>
				<p>'.$this->extracto.'</p>
			</div>
		</div>';
	}
	public function printSharer(){
		$url_encoded = urlencode($this->url);
    	$name_encoded = urlencode($this->titulo);
		$hashtags = 'PasiónPorJuárez';
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
}
?>
