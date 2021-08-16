<?php
class url {

	public $navigation;
	public $breadcrumbs=array();
	public $piezas=array(
		'tipo' => false,
		'alfa' => false,
		'beta' => false,
		'gama' => false
	);

	function __construct(){
		$this->navigation = array(
			'portada' => array(
				'nombre' => 'Portada',
				'link' => 'p/portada',
				'tipo' => 'pages'
			),
			'editor' => array(
				'nombre' => 'Contenido',
				'menu' => array(
					'archivos' => array(
						'nombre' => 'Archivos',
						'link' => 'p/archivos',
						'tipo' => 'pages',
						'icon' => 'paperclip',
						'permiso' => 'Editor'
					)
				)
			),
			'nosotros' => array(
				'nombre' => 'Nosotros',
				'menu' => array(
				)
			),
			'ayuntamiento' => array(
				'nombre' => 'Ayuntamiento',
				'menu' => array(
				)
			),
			'indicadores' => array(
				'nombre' => 'Indicadores',
				'menu' => array(
				)
			),
			'contraloria' => array(
				'nombre' => 'Contraloría Social',
				'menu' => array(
				)
			),
			'comunicacion' => array(
				'nombre' => 'Comunicación',
				'menu' => array(
				)
			),
			'administracion' => array(
				'nombre' => 'Administración',
				'menu' => array(
					'usuarios' => array(
						'nombre' => 'Usuarios',
						'link' => 'p/usuarios',
						'tipo' => 'pages',
						'icon' => 'users',
						'permiso' => 'ManejarUsuarios'
					),
					'configuracion' => array(
						'nombre' => 'Configuración',
						'link' => 'p/configuracion',
						'tipo' => 'pages',
						'icon' => 'cog',
						'permiso' => 'Administracion'
					)
				)
			)
		);
	}

	public function addToNavigation($tab,$nombre,$displaynombre,$icon,$tipo,$permiso){
		if(isset($this->navigation[$tab])){
			$this->navigation[$tab]['menu'][$nombre]=array(
				'nombre' => $displaynombre,
				'link' => 's/'.$nombre,
				'tipo' => $tipo,
				'icon' => $icon,
				'permiso' => $permiso
			);
		}
	}

	public function setPiezas($array){
		if ($array && is_array($array)) {
            $this->piezas = $array + $this->piezas;
        }
	}

	public function printMenu($user){
		foreach($this->navigation as $tabkey => $tab){
			$haslinks=false;
			$print='';
			$classtab='';
			if(isset($tab['tipo']) && $tab['tipo']==$this->piezas['tipo'] && $tabkey==$this->piezas['alfa']){
				$classtab=' open active';
				$this->addBreadcrumb(array('nombre' => $tab['nombre'], 'link' => $tab['link']));
			}
			$print .= '<div class="tabcontainer t'.$tabkey.$classtab.'">';
			if(isset($tab['link'])){
				$haslinks=true;
				$print .= '<a href="'.SIGA.$tab['link'].'" class="tab">'.$tab['nombre'].'</a>';
			}else{
				$print .= '<div class="tab">'.$tab['nombre'].'</div>';
			}
			if(isset($tab['menu'])){
				$print .= '<div class="tcontent">';
				foreach($tab['menu'] as $menukey => $menu){
					$classmenu='';
					$icon='';
					if($menu['tipo']==$this->piezas['tipo'] && $menukey==$this->piezas['alfa']){
						$classmenu=' open active';
						$this->addBreadcrumb(array('nombre' => $menu['nombre'], 'link' => $menu['link']));
					}
					if(isset($menu['icon']) && $menu['icon']!=''){
						$icon='<span class="fa fa-'.$menu['icon'].' fa-fw"></span> ';
					}
					if(!isset($menu['permiso']) || $menu['permiso']=='' || $user->hasRole($menu['permiso'])){
						$haslinks=true;
						$print .= '<a href="'.SIGA.$menu['link'].'" class="tm'.$menukey.$classmenu.'" title="'.$menu['nombre'].'">'.$icon.$menu['nombre'].'</a>';
					}
				}
				$print .= '</div>';
			}
			$print .= '</div>';
			if($haslinks){
				echo $print;
			}
		}
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

	public function createUrl($to){
		return SIGA.$to;
	}

	public function addBreadcrumb($array){
		$this->breadcrumbs[]=$array;
	}

	public function printBreadcrumbs(){
		echo '<div id="breadcrumbs">';
		echo '<a href="'.SIGA.'" class="fa fa-home home"><span class="info">Inicio</span></a>';
		if(!empty($this->breadcrumbs)){
			foreach($this->breadcrumbs as $crumb){
				echo '<span class="master m-next next"></span>';
				echo '<a href="'.SIGA.$crumb['link'].'" class="l">'.$crumb['nombre'].'</a>';
			}
		}
		echo '</div>';
	}


}
?>
