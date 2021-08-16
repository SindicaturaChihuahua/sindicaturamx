<?php
class seccionesSlide {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $titulo;
	public $descripcion;
	public $formato;
	public $tagline;
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_slidesecciones";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'slidesecciones/obj'.$this->data['post_id'].'/';
		$imagenes = array("cover");
		foreach ($imagenes as $imagen) {
			if(isset($this->data[$imagen]) && validaGeneral($this->data[$imagen], 4)){
				$this->data['has_'.$imagen] = true;
				$this->data[$imagen.'_image'] = $this->data['folderpath'].$this->data[$imagen];
				$this->data[$imagen.'_image_medium'] = $this->data['folderpath']."medium_".$this->data[$imagen];
				$this->data[$imagen.'_image_big'] = $this->data['folderpath']."big_".$this->data[$imagen];
			}else{
				$this->data['has_'.$imagen] = false;
				$this->data[$imagen.'_image'] = URLIMAGES.'default/slide'.$imagen.'.png';
				$this->data[$imagen.'_image_medium'] = URLIMAGES.'default/slide'.$imagen.'.png';
				$this->data[$imagen.'_image_big'] = URLIMAGES.'default/slide'.$imagen.'.png';
			}
		}
		if(!empty($this->data["btn_texto"]) && !empty($this->data["btn_link"])){
            if(filter_var($this->data["btn_link"], FILTER_VALIDATE_URL)){
				$this->btn_link = $this->data['btn_link'];
			}else{
                $this->btn_link = URL.$this->data["btn_link"];
			}
			$this->btn_texto = $this->data["btn_texto"];
		}else{
			$this->btn_texto = false;
			$this->btn_link = false;
		}
		$this->titulo = $this->data["titulo"];
		$this->descripcion = $this->data["descripcion"];
		$this->formato = $this->data["formato"];
		$this->tagline = $this->data["tagline"];
	}

	public function initFromData($data){
		$this->data = $data;
		$this->post_id = $data['post_id'];
		$this->init();
	}

	public function printSlide(){
		echo '<div class="ohe-item">
			<a href="'.$this->btn_link.'" class="home-enlace-block" style="background-image:url('.$this->data["cover_image_big"].')">
				<div class="home-enlace-block-txt">
					<h2>'.$this->titulo.'</h2>
				</div>
				<div class="home-enlace-block-hover">
					<h2>'.$this->titulo.'</h2>
					<p>'.$this->descripcion.'</p>
					<div class="btn-secondary btn-secondary-white">'.$this->btn_texto.'</div>
				</div>
			</a>
		</div>';

	}
	//GET
	public function getTitulo(){
		return $this->titulo;
	}
	public function getTagline(){
		return $this->tagline;
	}
	public function getBtnTexto(){
		return $this->btn_texto;
	}
	public function getBtnLink(){
		return $this->btn_link;
	}
	public function getBtn(){
		return $this->btn;
	}
	//SET
	public function setTitulo($titulo){
		return $this->titulo = $titulo;
	}
	public function setTagline($tagline){
		return $this->tagline = $tagline;
	}
	public function setBtnTexto($btntexto){
		return $this->btn_texto = $btntexto;
	}
	public function setBtnLink($btnlink){
		return $this->btn_link = $btnlink;
	}
	public function setBtn($btn){
		return $this->btn = $btn;
	}
}
?>
