<?php
class Page {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $post_id;
	public $titulo;
	public $descripcion;
	public $slug;
	public $bloque1 = array();
	public $tipo;
	public $descarga = false;
	public $extra;
	public $fecha;
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_landing";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'landing/obj'.$this->data['post_id'].'/';
		$imagenes = array("cover","img1","img2","icono1","icono2","icono3","archivo", "archivo2", "archivo3");
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
		$this->descripcion = $this->data["descripcion"];

		$this->bloque1 = json_decode($this->data['bloque1'], true);
		if(!$this->bloque1["titulo"] || !$this->bloque1["texto"]){
			$this->bloque1 = false;
		}
		$this->tipo = $this->data["tipo"];
		$this->fecha = formatDate($this->data["modificado"],"formal");
		$this->url = URL.'informacion/'.$this->slug;

		$this->descarga = ($this->data["has_archivo"]) ? '<a href="'.$this->data["archivo_image"].'" class="btn-primary btn-landing-dwn" target="_blank"><i class="fas fa-download"></i> '.$this->data["btntxt1"].'</a>' : '';

		$this->descarga2 = ($this->data["has_archivo2"]) ? '<a href="'.$this->data["archivo2_image"].'" class="btn-primary btn-landing-dwn" target="_blank"><i class="fas fa-download"></i> '.$this->data["btntxt2"].'</a>' : '';

		$this->descarga3 = ($this->data["has_archivo3"]) ? '<a href="'.$this->data["archivo3_image"].'" class="btn-primary btn-landing-dwn" target="_blank"><i class="fas fa-download"></i> '.$this->data["btntxt3"].'</a>' : '';

		if(isset($this->data['extra'])){
			$this->extra = json_decode($this->data['extra'], true);
		}
	}
	public function initFromData($data){
		$this->data = $data;
		$this->post_id = $data['post_id'];
		$this->init();
	}
	public function getPage($slug){
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
	public function printPage(){

	}
	public function printHero(){
		$dwn = '';
		if($this->descarga || $this->descarga2 || $this->descarga3){
			$dwn = '<div class="landing-download">'.$this->descarga.$this->descarga2.$this->descarga3.'</div>';
		}
		echo '<div class="wrap">
			<div class="landing-hero-txt">
				<h1>'.$this->titulo.'</h1>
				'.$this->descripcion.$dwn.'
			</div>
		</div>';
	}
	public function printBloque1(){
		if($this->bloque1){
			echo '<div class="landing-txt"><div class="landing-txt-wrap">';
				if(!empty($this->bloque1["titulo"])){
					echo '<h2>'.$this->bloque1["titulo"].'</h2>';
				}
				if(!empty($this->bloque1["texto"])){
					echo $this->bloque1["texto"];
				}
			echo '</div></div>';
		}
		if($this->data["has_img1"]){
			echo '<div class="landing-img"><img src="'.$this->data["img1_image_big"].'" alt="'.$this->bloque1["titulo"].'"></div>';
		}
	}

	public function printSharer(){
		$url_encoded = urlencode($this->url);
    	$name_encoded = urlencode($this->titulo);
		$hashtags = 'SindicaturaMX';
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

}
?>
