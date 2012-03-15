<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of F_ArrayReader
 *
 * @author Arthur
 */
class F_ArrayReader implements ArrayAccess, Countable, IteratorAggregate {

    /**
     * @var F_ArrayReader
     */
    private static $_instance = null;
    protected static $_configFile = '';

    /**
     * config array
     * @var array
     */
    protected $_values = array ( );

    /**
     * @return F_ArrayReader
     */
    public static function getInstance()
    {
	if ( self::$_instance === null ) {
	    self::$_instance = new self;
	}
	return self::$_instance;
    }

    public function setFile( $filePath )
    {
	/* make sure instance doesn't exist yet */
	if ( self::$_instance !== null ) {
	    //TODO: Own Exception class
	    throw new Exception( 'You need to set the path before calling ' . __CLASS__ . '::getInstance() method', 0 );
	} else {
	    self::$_configFile = $filePath;
	}
    }

    protected function __construct()
    {

	$values = @include( self::$_configFile );
	if ( is_array( $values ) ) {
	    $this->_values = &$values;
	}
    }

    final protected function __clone()
    {
	// no cloning allowed
    }

    public function count()
    {
	return sizeof( $this->_values );
    }

    public function offsetExists( $offset )
    {
	return key_exists( $offset, $this->_values );
    }

    public function offsetGet( $offset )
    {
	return $this->_values[$offset];
    }

    public function offsetSet( $offset, $value )
    {
	$this->_values[$offset] = $value;
    }

    public function offsetUnset( $offset )
    {
	unset( $this->_values[$offset] );
    }

    public function getIterator()
    {
	return new ArrayIterator( $this->_values );
    }

    public function __set( $key, $value )
    {
	$this->_values[$key] = $value;
    }

    public function __get( $key )
    {
	return $this->_values[$key];
    }

}

?>
