<?php

/**
 * User: Arthur
 * Date: 25.02.12
 * Time: 21:00
 * Configuration class
 */
class F_Configuration extends F_ArrayReader {

    /**
     * @var F_Configuration
     */
    protected static $_instance = null;

    /**
     * @return F_Configuration
     */
    public static function getInstance()
    {
	if ( self::$_instance === null ) {
	    self::setFile( ROOT . DS . APPS . DS . 'config' . DS . 'config.php' );
	    self::$_instance = new self;
	}
	return self::$_instance;
    }
}