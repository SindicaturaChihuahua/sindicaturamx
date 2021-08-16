<?php
class TransparenciaSlide {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $titulo;
	public $descripcion;
	public $formato;
	public $btn;
	public $btn_link;
	public $btn_texto;
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_transparenciaslider";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'transparenciaslider/obj'.$this->data['post_id'].'/';
		$imagenes = array("cover","archivo");
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
		$this->btn_texto = false;
		$this->btn_link = false;
		if(!empty($this->data["btn_link"])){
            if(filter_var($this->data["btn_link"], FILTER_VALIDATE_URL)){
				$this->btn_link = $this->data['btn_link'];
			}else{
                $this->btn_link = URL.$this->data["btn_link"];
			}
		}
		if(!empty($this->data["btn_texto"])){
			$this->btn_texto = $this->data["btn_texto"];
		}
		if($this->data["has_archivo"] && empty($this->btn_link)){
			$this->btn_link = $this->data["archivo_image"];
		}
		$this->titulo = $this->data["titulo"];
		$this->descripcion = $this->data["descripcion"];
		$this->formato = $this->data["formato"];
	}

	public function initFromData($data){
		$this->data = $data;
		$this->post_id = $data['post_id'];
		$this->init();
	}

	public function printSlide(){
		$this->btn = '';
        if($this->btn_texto){
            $this->btn = '<div class="carousel-txt carousel-align-'.$this->formato.'">
				<div class="btn-secondary btn-secondary-white">'.$this->btn_texto.'</div>
			</div>';
        }

		if($this->btn_link){
			echo '<a href="'.$this->btn_link.'" target="_blank" class="carousel-item" style="background-image:url('.$this->data["cover_image_big"].')">
				'.$this->btn.'
	        </a>';
		}else{
			echo '<div class="carousel-item" style="background-image:url('.$this->data["cover_image_big"].')">
				'.$this->btn.'
	        </div>';
		}
	}
}
?>
