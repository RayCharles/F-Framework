<?php
class F_Validation
{

	private $_rules;

	/**
	 * @var F_Validation
	 */
	private static $_instance;

	/**
	 * @return F_Validation
	 */
	public static function getInstance()
	{
		if ( NULL === self::$_instance ) {
			self::$_instance = new self ();
		}
		return self::$_instance;
	}

	private function __construct()
	{

	}

	private function __clone()
	{

	}

	public function setRules( $rules )
	{
		$this->rules = $rules;
	}

	public function validate( $data, $rules = NULL )
	{
		if ( $rules == NULL ) {
			$rules = $this->_rules;
		}

		$result = NULL;
		foreach ( $data as $dataKey => $dataValue ) {
			if ( ( isset( $rules[ $dataKey ][ 'required' ] ) ) AND ( $rules[ $dataKey ][ 'required' ] == TRUE ) AND ( !isset( $dataValue ) ) ) {
				return FALSE;
			}
			$type = 'validate_' . $rules[ $dataKey ][ 'type' ];
			$length = ( isset( $rules[ $dataKey ][ 'length' ] ) ) ? $rules[ $dataKey ][ 'length' ] : NULL;
			if ( !$this->$type( $dataValue, $length ) ) {
				return FALSE;
			}
		}

		return TRUE;
	}

	public function validate_string( $string, $length = NULL )
	{
		if ( ctype_alpha( $string ) AND $this->validate_length( strlen( $string ), $length ) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function validate_int( $int, $length = NULL )
	{
		if ( is_integer( $int ) AND $this->validate_length( strlen( $int ), $length ) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function validate_varchar( $varchar, $length = NULL )
	{
		if ( is_string( $varchar ) AND $this->validate_length( strlen( $varchar ), $length ) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function validate_time( $time )
	{
		echo "time";
	}

	public function validate_length( $length, $rule )
	{
		if ( $rule == NULL ) {
			return TRUE;
		}

		if ( isset( $rule[ 'exact' ] ) ) {
			return ( $rule[ 'exact' ] == $length );
		}

		$min = ( isset( $rule[ 'min' ] ) ) ? $rule[ 'min' ] : 0;
		$max = ( isset( $rule[ 'max' ] ) ) ? $rule[ 'max' ] : 9999999999999;

		if ( ( $min < $length ) AND ( $length < $max ) ) {
			return TRUE;
		}

		return FALSE;
	}

	public function validate_email( $email )
	{
		if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function validate_url( $url )
	{
		if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function validate_ip( $ip )
	{
		if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
