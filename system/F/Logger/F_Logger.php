<?php
abstract class F_Logger {

	const L_ERROR = 'error';
	const L_DEBUG = 'debug';
	const L_INFO = 'info';

	/**
	 * @var Date format to output
	 **/
	protected $_dateFormat = "Y-m-d H:i:s";

	/**
	 * @var Logging level
	 */
	protected $_level;

	function __costruct()
	{

	}

	public function getLevel()
	{
	    return $this->_level;
	}

	public function setLevel($new_level)
	{
	    $this->_level = $new_level;
	    return $this;
	}

	public function getDateFormat()
	{
	    return $this->_dateFormat;
	}

	public function setDateFormat($newDateFormat)
	{
	    $this->_dateFormat = $newDateFormat;
	    return $this;
	}

	public static abstract function info($msg, $method);
	public static abstract function debug($msg, $method);
	public static abstract function error($msg, $method);
	public static abstract function write($level, $msg, $method);

}