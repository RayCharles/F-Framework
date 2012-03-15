<?php
class Language {
	private $_lang;
	
	private static $instance = NULL;
	public static function getInstance() {
		
		if (NULL === self::$instance) {
			self::$instance = new self ();
		}
		return self::$instance;
	}
	private function __clone() {
	}
	
	public function set_language($lang) {
		$this->_lang = $lang;
	}
	
	public function initialize() {
		include ROOT . DS . APPS . DS . 'library' . DS . 'languages' . DS . $this->_lang . '.lang.php';
		if (isset ( $lang ))
			return $lang;
		else
			return $language;
	}
}