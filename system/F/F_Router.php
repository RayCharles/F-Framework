<?php

/**

 * User: Arthur
 * Date: 27.03.11
 * Time: 21:16
 * Router class
 */
class F_Router {
    
    private $_defaultController;
    private $_defaultAction;
    private $_controller;
    private $_action;

    /**
     * @var FRouter
     */
    private static $instance = NULL;

    /**
     * @return FRouter
     */
    public static function getInstance()
    {
        if ( NULL === self::$instance ) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->_defaultController = F_Configuration::getInstance()->default_controller;
        $this->_defaultAction = F_Configuration::getInstance()->default_action;
    }

    private function __clone()
    {
        
    }

    public function route()
    {
        $this->uri = FURI::getInstance();
        $this->uri->parse_uri();

        if ( !$this->uri->get_controller() ) {
            $this->_set_default_controller();
            return;
        }

        $this->_set_routing();
    }

    private function _set_default_controller()
    {
        require_once ROOT . DS . APPS . DS . 'Controllers' . DS . F_Configuration::getInstance()->default_controller . 'Controller.php';

        $this->_controller = $this->_defaultController;
        $controller = $this->_controller . "Controller";

        $controller = new $controller;
        if ( $this->uri->get_params() ) {
            call_user_func_array( array ( $controller, $this->_defaultAction ), $this->uri->get_params() );
        } else {
            $this->_action = $this->_defaultAction;
            $controller->{$this->_action}();
        }

        // todo: From config file
    }

    private function _set_routing()
    {
        if ( !$this->_validate_request() ) {
            $this->_set_default_controller();
            return;
        }

        $this->_controller = $this->uri->get_controller();
        $controller = $this->_controller . 'Controller';
        $module = $this->uri->get_module() . 'Action';

        require_once ROOT . DS . APPS . DS . 'Controllers' . DS . $this->uri->get_controller() . 'Controller.php';

        $controller = new $controller ();
        if ( $this->uri->get_params() ) { // if some parameters have been passed
            if ( method_exists( $controller, $module ) ) { // if the required method
                // exists
                call_user_func_array( array ( $controller, $module ), $this->uri->get_params() ); 
            } else { // else call default indexHandler
                call_user_func_array( array ( $controller, $this->_defaultAction ), $this->uri->get_params() ); // FUTURE:
            }
        } else {
            if ( method_exists( $controller, $module ) ) {
                $controller->$module();
            } else {
                $controller->{$this->_defaultAction}();
            }
        }
    }

    private function _validate_request()
    {
        if ( !$this->uri->get_controller() ) {
            return FALSE;
        }

        if ( !file_exists( ROOT . DS . APPS . DS . 'Controllers' . DS . $this->uri->get_controller() . 'Controller.php' ) ) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getController()
    {
        return $this->_controller;
    }
}
