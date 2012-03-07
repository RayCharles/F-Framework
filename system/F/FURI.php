<?php
/**

 * User: Arthur
 * Date: 27.03.11
 * Time: 21:20
 * URI Class
 */

class FURI {
	private $uri_parsed = FALSE;
	private $fragments = array ();
	
	private static $instance = NULL;
	private function __construct() {
	}
	
	public static function getInstance() {
		
		if (NULL === self::$instance) {
			self::$instance = new self ();
		}
		return self::$instance;
	}
	private function __clone() {
	}
	
	public function parse_uri() {
		$requestURI = explode ( '/', $_SERVER ['REQUEST_URI'] );
		$scriptName = explode ( '/', $_SERVER ['SCRIPT_NAME'] );
		
		// echo "<pre>", var_dump($requestURI, $scriptName), "</pre>";
		
		for($i = 0; $i < sizeof ( $scriptName ); $i ++) {
			if (strcasecmp ( $requestURI [$i], $scriptName [$i] ) == 0) {
				unset ( $requestURI [$i] );
			}
		}
		
		$this->fragments = array_values ( $requestURI );
		$this->uri_parsed = TRUE;
		return;
	}
	
	public function get_controller() {
		if ($this->uri_parsed and ! empty ( $this->fragments [0] )) {
			return $this->fragments [0];
		} else {
			return FALSE;
		}
	}
	
	public function get_module() {
		if ($this->uri_parsed and ! empty ( $this->fragments [1] )) {
			return $this->fragments [1];
		} else {
			return FALSE;
		}
	}
	
	public function get_params() {
		if ($this->uri_parsed and ! empty ( $this->fragments )) {
			$return = array ();
			for($i = 2; $i < count ( $this->fragments ); $i ++) {
				array_push ( $return, $this->fragments [$i] );
			}
			return $return;
		} else {
			return FALSE;
		}
	}
}
