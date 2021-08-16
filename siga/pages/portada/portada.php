<?php
class back_portada
{

    public function __construct($db) {
		$this->permiso = 'Usuario';
    }

    public function call($accion) {
		if($accion=="lista"){
			return array(
				"file" => "lista",
				"css" => "",
				"js" => "",
			);
		}
    }
}
?>
