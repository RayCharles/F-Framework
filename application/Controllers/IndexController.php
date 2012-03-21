<?php

class IndexController extends F_Controller
{
	protected function _init()
	{
		//		$u_m->find( array( 'select' => array( 'user_password', 'user_username' ),
		//		                 'where'    => array( array( 'user_username', '=', 'admin' ),
		//		                                      array( 'and', 'user_email', '=', 'admin@f.com' ) ),
		//                       'where'    => array( 'user_username', '=', 'admin'),
		//		                 'limit'    => array( 0, 30 ),
		//		                 'order_by' => array( 'user_username' => 'desc',
		//		                                      'user_email'    => 'asc' ) ) );


//			$new_user = new UserModel();
//			$new_user->user_username = 'Arthur';
//			$new_user->user_email = 'test@f.com';
//			$new_user->user_password = sha1( 'm8FLa5f6' );
//			$new_user->user_last_login = time();
//			$new_user->user_status = 1;
//			$new_user->user_registered = time();
//			$new_user->save();

	}

	public function defaultAction()
	{
		$this->_view->set_vars( array( '' => '' ) );
		$this->_view->add_template( 'index.php' );
		$this->_view->display();
	}

}