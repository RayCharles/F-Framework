<?php

class Input {
	
	private static $instance = NULL;
	public static function getInstance() {
		
		if (NULL === self::$instance) {
			self::$instance = new self ();
		}
		return self::$instance;
	}
	private function __clone() {
	}
	
	public function make_db_ready($str) {
		if (get_magic_quotes_gpc () == 0) {
			
			if (is_array ( $str )) {
				
				$new_array = array ();
				foreach ( $str as $k => $s ) {
					$new_array [$k] = $this->make_db_ready ( $s );
				}
				return $new_array;
			
			} else {
				return addslashes ( htmlentities ( $str ) );
			}
		
		} else {
			return $str;
		}
	}
	
	public function make_html_db_ready($str) {
		if (is_array ( $str )) {
			
			$new_array = array ();
			foreach ( $str as $k => $s ) {
				$new_array [$k] = $this->make_html_db_ready ( $s );
			}
			return $new_array;
		
		} else {
			return htmlentities ( $str, ENT_QUOTES, 'UTF-8' );
		}
	}
	
	public function make_output_ready($str) {
		if (get_magic_quotes_gpc () == 0) {
			if (is_array ( $str )) {
				foreach ( $str as $s ) {
					$this->make_db_ready ( $s );
				}
			} else {
				return stripslashes ( html_entity_decode ( $str ) );
			}
		} else {
			return;
		}
	}
	
	public function make_html_output_ready($str) {
		if (is_array ( $str )) {
			foreach ( $str as $s ) {
				$this->make_html_db_ready ( $s );
			}
		} else {
			return html_entity_decode ( $str, ENT_QUOTES, 'UTF-8' );
		}
	}
	
	public function get($index = '') {
		if ($index == NULL) {
			return $_GET;
		} else {
			return (isset ( $_GET [$index] )) ? $_GET [$index] : false;
		}
	}
	
	public function post($index = NULL) {
		if ($index == NULL) {
			return $_POST;
		} else {
			return (isset ( $_POST [$index] )) ? $_POST [$index] : false;
		}
	}
	
	public function set_post($key, $value) {
		if (isset ( $key )) {
			$_POST [$key] = $value;
		}
		return;
	}
	
	public function session($index = '') {
		if ($index == NULL) {
			return $_SESSION;
		} else {
			return (isset ( $_SESSION [$index] )) ? $_SESSION [$index] : false;
		}
	}
	
	public function get_post($index = '') {
		if (! isset ( $_POST [$index] )) {
			$this->get ( $index );
		} else {
			$this->post ( $index );
		}
	}
	
	public function cookie($index) {
		if ($index == NULL) {
			return $_COOKIE;
		} else {
			return (isset ( $_COOKIE [$index] )) ? $_COOKIE [$index] : false;
		}
	}
	
	public function server($index) {
		if ($index == NULL) {
			return $_SERVER;
		} else {
			return (isset ( $_SERVER [$index] )) ? $_SERVER [$index] : false;
		}
	}

}