<?php
class front_api extends Seccion {

	private $_method = "";
	public $_code = 200;
	public $_error_type = '';
	public $_error_message = '';
	public $_content_type = "application/json";

	function __construct($db)
	{
		$this->db = $db;
        $this->name = "api";
        $this->order = '1';
        $this->version = '1.0.0';
		$this->tab = 'editor';
		$this->displayName = 'api';
		$this->description = 'Api';
		$this->icon = 'edit';
		$this->databaseprefix = 's_noticias';
		$this->permiso = '';
		$this->static_urls = array(

		);
	}

	public function call($accion,$gama=false,$delta=false) {
		if($accion=="lista"){
			return array(
				"file" => "nada",
			);
		}else if($accion=="contacto"){
			return array(
				"file" => "contacto",
				"ajax" => true
			);
		}else if($accion=="registros"){
			return array(
				"file" => "registros",
				"ajax" => true
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

	// Para respuestas
	public function response($data=array(), $html='', $message='', $errores=array(), $status, $mustlogin=true, $reload = ""){
		$this->_code = ($status) ? $status : 200;
		if(count($errores)>0){
			$this->_code = 200;
			$message = implode("<br>",$errores);
		}
		$meta = array(
			'code' => $this->_code,
			'type' => $this->get_status_message(),
			'errores' => count($errores),
			'message' => $message,
			'mustlogin' => $mustlogin,
			'reload' => $reload
		);

		$this->set_headers();
		$respuesta = array(
			'meta' => $meta,
			'data' => $data,
			'html' => $html
		);
		echo json_encode($respuesta);
		exit;

	}

	private function get_status_message(){
		$status = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
		);
		return ($status[$this->_code])?$status[$this->_code]:$status[500];
	}

	private function set_headers(){
		header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
		header("Content-Type:".$this->_content_type);
	}

	public function get_request_method(){
		return $_SERVER['REQUEST_METHOD'];
	}
}
?>
