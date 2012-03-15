<?php

/**
 * Description of Authentication
 *
 * @author Arthur
 * @uses FDb.php, FInput.php, common.php
 */
class Authentication {
	
	private $db;
	private $input;
	private $name = 'user_logged_in';
	
	private static $instance = NULL;
	public static function getInstance() {
		if (NULL === self::$instance) {
			session_start ();
			self::$instance = new self ();
			self::$instance->db = FDb::getInstance ();
			self::$instance->input = Input::getInstance ();
		}
		return self::$instance;
	}
	private function __clone() {
	}
	
	public function login($username, $password, $remember = FALSE) {
		/**
		 * Check whether user is already logged_in
		 */
		if ($this->logged_in ())
			return TRUE;
		
		$_username = $this->input->make_db_ready ( $username );
		$_password = $this->decrypt_password ( $password );
		
		$confirm = $this->db->select ( 'user_id, user_username, user_email, user_last_ip, user_last_login, user_status, DisplayName' )->from ( 'users' )->where ( array ("user_username" => $_username, "user_password" => $_password ) )->fetch_object ();
		
		/**
		 * Was the login successful
		 */
		if ($confirm) {
			
			/**
			 * Refresh the database
			 */
			$this->db->update ( 'users', array ('user_last_login' => time (), 'user_last_ip' => ip () ) )->where ( array ("user_id" => $confirm [0]->user_id ) )->execute ();
			
			/**
			 * Save to COOKIE / SESSION
			 */
			if ($remember) {
				setcookie ( $this->name, serialize ( $confirm ), time () + (60 * 60 * 24 * 14), '/' );
			} else {
				$_SESSION [$this->name] = serialize ( $confirm );
			}
			
			return TRUE;
		} else {
			echo FALSE;
		}
	}
	
	public function logged_in($level = NULL) {
		if (isset ( $_COOKIE [$this->name] ) or isset ( $_SESSION [$this->name] )) {
			/**
			 * Parse the data from COOKIE / SESSION
			 */
			$data = $this->get_user_data ();
			
			$_username = $data [0]->user_username;
			$_id = $data [0]->user_id;
			
			/**
			 * Check if user was not removed from the database
			 */
			$logged_in = $this->db->select ( 'user_id, user_username, user_email, user_last_ip, user_last_login, user_status' )->from ( 'users' )->where ( array ('user_username' => $_username, 'user_id' => $_id ) )->fetch_object ();
			
			if (! $logged_in) {
				return FALSE;
			}
			
			if ($level != NULL) {
				if ($level < ( int ) $logged_in [0]->user_status) {
					return FALSE;
				}
			}
			return TRUE;
		}
		return FALSE;
	}
	
	public function reload_user_data($user_id) {
		$confirm = $this->db->select ( 'user_id, user_username, user_email, user_last_ip, user_last_login, user_status, DisplayName' )->from ( 'users' )->where ( array ("user_id" => $user_id ) )->fetch_object ();
		
		/**
		 * Was the login successful
		 */
		if ($confirm) {
			
			/**
			 * Refresh the database
			 */
			$this->db->update ( 'users', array ('user_last_login' => time (), 'user_last_ip' => ip () ) )->where ( array ("user_id" => $confirm [0]->user_id ) )->execute ();
			
			/**
			 * Save to SESSION, delete $_COOKIE
			 */
			$_SESSION [$this->name] = serialize ( $confirm );
			setcookie ( $this->name, "", time () - 36000, '/' );
		}
	}
	
	public function get_user_data() {
		if (isset ( $_COOKIE [$this->name] )) {
			return unserialize ( $_COOKIE [$this->name] );
		} elseif (isset ( $_SESSION [$this->name] )) {
			return unserialize ( $_SESSION [$this->name] );
		} else {
			return FALSE;
		}
	}
	
	public function logout() {
		setcookie ( $this->name, "", time () - 36000, '/' );
		session_unset ();
		unset ( $_SESSION ['PHPSESSID'] );
		session_destroy ();
		return TRUE;
	}
	
	public function register($username, $password, $email) {
		// TODO:
	}
	
	public function forgot_password($username) {
		// TODO:
	}
	
	public function reset_password($username, $key) {
		// TODO:
	}
	
	public function change_password($old_password, $new_password) {
		// TODO:
	}
	
	public function cancel_account() {
		// TODO:
	}
	
	public function is_username_availalble($username) {
		$result = ( bool ) $this->db->select ( '*' )->from ( 'users' )->where ( "user_username = '$username'" )->fetch_object ();
		
		return ! $result;
	}
	
	public function is_email_availalble($email) {
		// TODO:
	}
	
	public function decrypt_password($password) {
		return sha1 ( $password );
		
		// return $password;
	}

}

?>
