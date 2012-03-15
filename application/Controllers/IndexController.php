<?php

class IndexController extends F_Controller {

    protected function _init()
    {
	$u_m = new UserModel();
	$cache = F_Cache_File::getInstance();
    }

    public function defaultAction()
    {
	$this->_view->set_vars(array('' => ''));
	$this->_view->add_template('index.php');
	$this->_view->display();
    }
}