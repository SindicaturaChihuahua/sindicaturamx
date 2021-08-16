<?php
class Bodys {

	protected $options;
	public $metadata = array();
	public $navigation = array();
	public $urlforma = array();

	function __construct($options=array()){
		$this->options = array(
			"titulosite" => "",
			"sigatitulosite" => "",
			"titulo" => "",
			"page" => "",
			"currenturl"=>false,
			"nexturl"=>false,
			"js" => array(),
			"bottomjs" => array(),
			"css" => array(),
			"movilversion" => false,
			"siga" => false,
			"breadcrumbs" => true,
			"normallayout" => true,
			"quickvideotutorial" => false
		);
		$this->metadata['image'] = URLIMAGES . 'social_share.jpg';

		if ($options) {
            $this->mix($options);
        }
	}

	public function get($type){
		if(isset($this->options[$type]))
			return $this->options[$type];
		else
			return null;
	}

	public function getTitle(){
		if($this->options['siga']==true){
			$this->options['titulosite']=$this->options['sigatitulosite'];
		}
		if($this->options['titulo']!=""){
			return $this->options['titulo'] . " | ".$this->options['titulosite'];
		}else{
			return $this->options['titulosite'];
		}
	}

	public function set($type,$value){
		if(isset($this->options[$type]) && is_array($this->options[$type])){
			$this->options[$type][]=$value;
		}else{
			$this->options[$type]=$value;
		}
		return $this->options[$type];
	}

	public function mix($options=array()){
		if ($options && is_array($options)) {
            $this->options = $options + $this->options;
        }
	}

	public function setCurrentUrl($url){
		$this->options['currenturl']=$url;
		$_SESSION['currenturl']=array($url,time()+180);
		return $this->options['currenturl'];
	}

	public function getPreviousUrl(){
		if(isset($_SESSION['currenturl'])){
			if($_SESSION['currenturl'][1]>time()){
				return $_SESSION['currenturl'];
			}else{
				unset($_SESSION['currenturl']);
			}
		}
		if($this->options['siga']==true){
			return SIGA;
		}
		return URL;
	}

	public function setMeta($type,$value){
		$this->metadata[$type] = $value;
	}

	public function setMetas(array $values){
		$this->metadata = array_merge($this->metadata, $values);
	}

	public function printMetaData(){
		$metas=array('description', 'robots', 'keywords');
		$ogs=array('title','description','url','image');
		$especiales=array('canonical');
		if($this->options['movilversion']){
			echo '<meta name="viewport" content="initial-scale=1.0, width=device-width"/>';
		}
		if(!empty($this->metadata)){
			foreach($this->metadata as $md => $value){
				if(in_array($md,$metas)){
					echo '<meta name="'.$md.'" content="'.$value.'" />';
				}
				if(in_array($md,$ogs)){
					echo '<meta property="og:'.$md.'" content="'.$value.'" />';
				}
				if(in_array($md,$especiales)){
					echo $value;
				}
			}
		}
	}

	public function nav($value){
		$this->navigation[]=$value;
	}

	public function getNav(){
		return $this->navigation;
	}

	public function setUrlForma($type,$value){
		$this->urlforma[$type]=$value;
		return $this->urlforma[$type];
	}

	public function getUrlForma($to='gama',$type='full',$add=false){
		$url='';
		$pedazos=array('page','alfa','beta','gama');
		for($a=0, $total=count($pedazos);$a<$total;$a++){
			if($a!=0){
				$url.='/';
			}
			$url.=$this->urlforma[$pedazos[$a]];
			if($pedazos[$a]==$to){
				break;
			}
		}
		if($add!=false){
			$url.=$add;
		}
		if($type=='full'){
			$url=SIGA.$url;
		}

		return $url;
	}
}

?>
