<?php

abstract class F_Controller {

    protected $_view;
    protected $_assets;
    protected $_configs;

    function __construct()
    {
	$this->_configs = F_Configuration::getInstance();
	$this->_assets = ABS . DS . APPS . DS . 'assets';

	$this->_view = F_View::getInstance();
	$this->_view->set_default_path( $this->_configs->template_file_path . DS . F_Router::getInstance()->getController() );

	$this->_init();
    }

    /**
     *	Goto another action in a selected controller without redirection.
     * @param FController $controller Target FController instance
     * @param string $action Target action within $controller instance
     */
    protected function _goto( F_Controller $controller, $action )
    {
	$controller->$action();
	return;
    }

    protected abstract function _init();

    public abstract function defaultAction();
}