<?php
class Evento {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $post_id;
	public $titulo;
	public $titulo_truncado;
	public $sesion_lugar;
	public $slug;
	public $tipo;
	public $descripcion;
	public $extra;
	public $categoria_id;
	public $fecha;
	public $hora;
	public $hayfuturos;
	public $haypasados;
	public $comision;
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_acalendario";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'acalendario/obj'.$this->data['post_id'].'/';
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
		$this->sesion_lugar = $this->data["sesion_lugar"];
		$this->slug = $this->data["slug"];
		$this->tipo = $this->data["tipo"];
		$this->descripcion = $this->data["descripcion"];
		$this->extracto = truncar_cadena(strip_tags($this->descripcion), 120, " ");
		$this->categoria_id = $this->data["categoria_id"];
		$this->comision = $this->getComision($this->categoria_id);
		$this->comision["url"] = URL.'ayuntamiento/comisiones/'.$this->comision["slug"];
		$this->fecha = formatDate($this->data["modificado"],"formal");
		$this->hora = formatDate($this->data["modificado"],"hora");
		$this->url = URL.'ayuntamiento/calendario-de-regidores/'.$this->slug;
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
				<h3>'.$this->titulo.'</h3>
				<p>'.$this->sesion_lugar.'</p>
				<a href="'.$this->comision["url"].'"><span class="contacto-alt highlight-blue">'.$this->comision["titulo"].'</span></a>
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

	public function isOpen(){
		if($this->data['status']=="publicado" && $this->data['visibilidad']!="oculto"){
			return true;
		}
		return false;
	}
	public function addView(){
		$this->db->query("UPDATE ".DB_PREFIX.$this->databaseprefix." SET visitas=visitas+1 WHERE post_id = ".$this->post_id);
	}

	public function getComision($id, $tipo=false){
		if(is_numeric($id)){
			if($tipo){
				$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."s_acomisiones WHERE post_id = ? AND tipo = ? LIMIT 1");
				$stmt->execute(array($id, $tipo));
			}else{
				$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."s_acomisiones WHERE post_id = ? LIMIT 1");
				$stmt->execute(array($id));
			}
			if($row = $stmt->Fetch()){
				return $row;
			}
		}
		return false;
	}
}
?>
