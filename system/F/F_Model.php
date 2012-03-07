<?php

abstract class F_Model {

    protected static $instance;
    protected $db;
    protected $bindArray = array ( ),
	    $pk,
	    $_table;

    public function __construct()
    {
	$this->db = F_Db::getInstance();
	$this->init();
    }

    protected abstract function init();

    public function beforeFind()
    {
	    return TRUE;
    }

    /**
     *
     * @param string $type Type of the return result.<br />
     * <b>first</b> - returns the first result matching the $params
     * <b>all</b> - returns all results
     * <b>count</b> - returns an integer of the total results
     * @param string $conditions optional Valid SQL conditions (ORDER BY, WHERE etc)
     * @param array $params optional Is used to pass all parameters to the various finds, and has the following possible keys by default - all of which are optional.
     * Possible keys are: ('fields', 'callbacks')
     * @return Object
     */
    //TODO: implement various types(all, first, count, list, neighbours, threaded)
    //TODO: implement complex conditional statements using parseConditions
    //TODO: implement the conversion of results into models, so return objects
    public function find( $type = 'first', $conditions = '', $params = NULL )
    {
	$return = NULL;
	if ( $this->beforeFind() ) {

	    switch ( $type ) {
		case 'all':
		    $return = $this->db->select( implode( ', ', $params['fields'] ) )->from( $this->_table )->sql(' ' . $conditions)->fetch_object();
		    break;
		case 'first':
		    $return = $this->db->select( implode( ', ', $params['fields'] ) )->from( $this->_table )->fetch_object();
		    $return = $return[0];
		    break;
		case 'count':
		    break;
	    }
	}
	return $return;
    }

    public function afterFind()
    {

    }

    public function beforeSave()
    {
	return TRUE;
    }

    public function save()
    {
	if ( $this->beforeSave() ) {

	}
    }

    public function afterSave()
    {

    }

    public function beforeDelete()
    {
	return TRUE;
    }

    public function delete()
    {
	if ( $this->beforeDelete() ) {

	}
    }

    public function afterDelete()
    {

    }

    public function onError()
    {

    }
    /* ---------------------------------------------------------------------- */

    /**
     * TODO: Refactor 
     **/
    public function __get($key) {
        return $this->$key;
    }

    /**
     * TODO: for methods like find_by_* and find_one_by_*
     **/
    public function __call($name, $args) {

    }
}