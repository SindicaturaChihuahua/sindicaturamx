<?php
class db{
	private static $instance = NULL;
	
	private function __construct() {
		
	}
	
	public static function getInstance() {
	
		if (!self::$instance){
			self::$instance = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS);
			self::$instance-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$instance-> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}
		return self::$instance;
	}
	
	private function __clone(){
	}

}
?>