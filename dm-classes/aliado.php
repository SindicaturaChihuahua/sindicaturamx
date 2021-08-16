<?php
class Aliado {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $titulo;
	public $url;
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_aliados";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'aliados/obj'.$this->data['post_id'].'/';
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
		$this->titulo = $this->data["titulo"];
		$this->url = $this->data["btn_link"];
	}

	public function initFromData($data){
		$this->data = $data;
		$this->post_id = $data['post_id'];
		$this->init();
	}

	public function printSlide(){
		if(!empty($this->url)){
			echo '<div class="aliado">
				<a href="'.$this->url.'" target="_blank"><img src="'.$this->data["cover_image_big"].'" alt="'.$this->titulo.'"></a>
			</div>';
		}else{
			echo '<div class="aliado">
				<img src="'.$this->data["cover_image_big"].'" alt="'.$this->titulo.'">
			</div>';
		}
	}
}
?>
