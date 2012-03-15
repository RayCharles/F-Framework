<?php

abstract class F_Model {

    protected static $instance;
    protected $db;
    protected $bindArray = array(),
	    $_pk,
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

    public function find()
    {
	if ($this->beforeFind()) {

	}
	$this->afterFind();
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
	if ($this->beforeSave()) {
	    if (isset($this->_pk)) {
		
	    } else {

	    }
	}
	$this->afterSave();
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
	if ($this->beforeDelete()) {

	}
	$this->afterDelete();
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
     * */
    public function __get($key)
    {
	return $this->$key;
    }

    /**
     * TODO: for methods like find_by_* and find_one_by_*
     * */
    public function __call($name, $args)
    {

    }

}