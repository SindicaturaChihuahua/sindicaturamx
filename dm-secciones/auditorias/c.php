<?php
class front_auditorias extends Seccion {

	function __construct($db)
	{
		$this->db = $db;
        $this->name = "revisiones";
        $this->order = '1';
        $this->version = '1.0.0';
		$this->tab = 'editor';
		$this->displayName = 'revisiones';
		$this->description = 'Portada principal del sistema';
		$this->icon = 'edit';
		$this->databaseprefix = 's_auditorias';
		$this->permiso = '';
		$this->static_urls = array(
			'lista' => $this->name
		);
	}

	public function call($accion,$gama=false,$delta=false) {
		if($accion=="lista"){
			return array(
				"file" => "lista",
				"css" => array(
					URLPLUGINS.'toastr/toastr.css'
				),
				'js' => array(
					URLPLUGINS.'toastr/toastr.min.js',
				)
			);
		}
    }

	function install()
	{
		if (parent::install()){
			return true;
		}
		return false;
	}

	function update(){
		parent::update();
	}

	function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}

	function showOptions($options){
		echo 'Base de datos: '.DB_NAME.'<br><br>';
		echo '<table border="1" cellpadding="2">';
		foreach ($options['opciones'] as $key => $value) {
			echo "<tr><td>".$key."</td><td>".$value."</td></tr>";
		}
		echo '</table>';

		echo '<pre>';
		print_r($options['otras_opciones']);
		echo '</pre>';
	}

}
?>
