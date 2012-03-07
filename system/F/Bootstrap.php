<?php

/**
 * User: Arthur
 * Date: 27.03.11
 * Time: 18:45
 * Bootstrap file
 */
/*
 * ---------------------- Load global functions --------------------------------
 */
require_once ROOT . DS . FRAMEWORK . DS . CORE . DS . 'common.php';

/**
 * ---------------------Load all configurations---------------------------------
 */
F_Configuration::setFile( ROOT . DS . APPS . DS . 'config' . DS . 'config.php' );

/*
 * -------------------- Set error reporting ------------------------------------
 */
setReporting();

/*
 * -------------------- Set database ------------------------------------
 */
$db_access = F_Configuration::getInstance()->db_access;
F_Db::getInstance()->setDBAccess($db_access[F_Configuration::getInstance()->db_current]);
F_Db::getInstance()->connectToDatabase();

/*
 * ------------------------------------------------------------ Load the Router
 * class and redirect to the right Controller
 * -----------------------------------------------------------
 */
$ROUTER = F_Router::getInstance();
$ROUTER->route();