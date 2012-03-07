<?php
/**

 * User: Arthur
 * Date: 27.03.11
 * Time: 20:22
 * Benchmark class
 */

class Benchmark {
	private $time_markers = array ();
	
	private static $instance = NULL;
	public static function getInstance() {
		
		if (NULL === self::$instance) {
			self::$instance = new self ();
		}
		return self::$instance;
	}
	private function __clone() {
	}
	
	public function set_marker($name) {
		$this->time_markers [$name] = microtime ();
	}
	
	public function elapsed_time($marker1, $marker2 = '') {
		if (! isset ( $this->time_markers [$marker1] )) {
			return;
		}
		if (! isset ( $this->time_markers [$marker2] )) {
			$this->time_markers [$marker2] = microtime ();
		}
		return $this->time_markers [$marker2] - $this->time_markers [$marker1];
	}
}
