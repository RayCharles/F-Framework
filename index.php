<?php
//Do no edit this file
error_reporting(E_ALL);

define('DS', '/');
define('ROOT', dirname(__FILE__));
define('FRAMEWORK', 'system');
define('APPS', 'application');
define('CORE', 'F');

define ( 'DEVELOPMENT_ENVIRONMENT', TRUE ); // !important //@todo: Find better place for this
define ( 'ABS', 'http://localhost/Framework' ); // without backslash at the end
                                          // //!important //Change in htdocs
                                          // RewriteBase

require_once ROOT . DS . FRAMEWORK . DS . CORE . DS . 'Bootstrap.php' ;