<?php
class Tools extends CI_Controller {

	function message($to = 'Mundo')
	{
		echo "¡Hola {$to}!".PHP_EOL;
	}
}
?>