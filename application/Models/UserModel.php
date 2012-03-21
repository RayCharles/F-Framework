<?php

/**
 * Description of UserModel
 *
 * @author Arthur
 */
class UserModel extends F_Model
{

	protected $_fields = array(
		'user_id'             => array( 'type' => 'int' ),
		'user_username'       => array( 'type'     => 'varchar',
		                                'label'    => 'Username',
		                                'required' => TRUE ),
		'user_password'       => array( 'type'     => 'varchar',
		                                'label'    => 'Password',
		                                'required' => TRUE,
		                                'length'   => array( 'min' => 6,
		                                                     'max' => 32 ) ),
		'user_email'          => array( 'type'     => 'varchar',
		                                'label'    => 'Email',
		                                'required' => TRUE,
		                                'length'   => array( 'min' => 8,
		                                                     'max' => 50 ) ),
		'user_last_login'     => array( 'type'  => 'int',
		                                'label' => 'The user\'s last login' ),
		'user_last_ip'        => array( 'type'  => 'varchar',
		                                'label' => 'The user\'s last ip' ),
		'user_status'         => array( 'type'  => 'int',
		                                'label' => 'Status' ),
		'user_activation_key' => array( 'type'  => 'varchar',
		                                'label' => 'Activation key' ),
		'user_registered'     => array( 'type'  => 'int',
		                                'label' => 'Registered' )
	);

	protected function init()
	{
		$this->_table = 'users'; //TODO: SET IN PARENT
		$this->_primary = 'user_id';
//		parent::$_table = 'users';
//		parent::$_primary = 'user_id';
	}

}