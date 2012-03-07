<?php

return array (
    'db_access' => array ( 'default' => array ( 'username' => 'root', 'password' => '', 'host' => 'localhost', 'database' => 'framework' ) ),
    'db_current' => 'default',
    'template_file_path' => ROOT . DS . APPS . DS . 'Views',
    'template_vars_type' => 'PHP_STYLE',
    'default_controller' => 'Index',
    'default_action' => 'defaultAction',
    'log_default_date_format' => 'H:i:s',
    'view_template_prefix' => 'f_',
    'cache_enabled' => TRUE,
    'cache_dir' => ROOT . DS . SYSTEM . DS . 'tmp' . DS . 'cache',
    'cache_expire' => 60, //One minute;
);