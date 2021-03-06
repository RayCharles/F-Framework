<?php

class F_Db
{

	public $db;
	private $db_access;
	private $last_error;
	private $query;
	private $where;
	private $limit;
	private $order_by;
	private $last_query;
	private $last_insert_id;
	private $table;
	private $conn = 'default';
	private $connected = FALSE;
	/**
	 * @var F_Db
	 */
	private static $instance = NULL;

	private function __construct()
	{
		$this->connected = FALSE;
	}

	/**
	 * @return F_Db
	 */
	public static function getInstance()
	{
		if ( NULL === self::$instance ) {
			self::$instance = new self ();
		}
		return self::$instance;
	}

	private function __clone()
	{

	}

	public function setDBAccess( $db_access )
	{
		$this->db_access = $db_access;
	}

	public function set_connection( $conn )
	{
		$this->conn = $conn;
	}

	public function connectToDatabase()
	{
		$db = $this->db [ $this->conn ];

		mysql_connect( $this->db_access[ 'host' ], $this->db_access[ 'username' ], $this->db_access[ 'password' ] );
		mysql_select_db( $this->db_access[ 'database' ] );

		$this->connected = TRUE;
	}

	public function isConnected()
	{
		return $this->connected;
	}

	public function print_last_error()
	{
		echo $this->last_error;
	}

	public function last_error()
	{
		return $this->last_error;
	}

	public function get_current_query()
	{
		return 'Base: ' . $this->query . '	    WHERE:' . $this->where;
	}

	public function last_query()
	{
		return $this->last_query;
	}

	/**
	 * @return FDb
	 */
	public function get( $table )
	{

		$this->query = '';

		$table = mysql_real_escape_string( $table );

		$this->query .= "SELECT * FROM $table";

		return $this;
	}

	/**
	 * @return FDb
	 */
	public function select( $columns = '*' )
	{

		$this->query = '';

		$cols = "";
		if ( $columns === '*' ) {
			$cols = '*';
		} else {
			if ( is_string( $columns ) ) {

				if ( count( $columns = explode( ",", $columns ) ) == 1 ) {
					$cols = "$columns[0]";
					$columns = '';
				}
			}

			if ( is_array( $columns ) ) {
				foreach ( $columns as $c ) {
					$c = str_replace( " ", "", $c );
					$cols .= "$c, ";
				}
				$cols = substr( $cols, 0, -2 );
			}
		}

		$this->query .= "SELECT " . $cols;
		return $this;
	}

	/**
	 * @return FDb
	 */
	public function from( $table )
	{
		$string = " FROM " . $table;

		$this->query .= $string;

		return $this;
	}

	/**
	 * @return FDb
	 */
	public function where( $params, $param2 = NULL )
	{
		$wheres = "";
		// (array("id" => "5",...))
		if ( is_array( $params ) ) {
			foreach ( $params as $key => $value ) {
				$wheres .= "`$key` = '$value' AND ";
			}
			$wheres = substr( $wheres, 0, -4 );
		}
		// ("id", "5")
		if ( is_string( $params ) && ( $param2 != NULL ) ) {
			$wheres = "$params = '$param2'";
		}
		// "id=5"
		if ( is_string( $params ) && ( $param2 == NULL ) ) {
			$wheres = "$params";
		}

		$this->where .= " $wheres";
		return $this;
	}

	/**
	 *  where(array('field', '=', 'value'))
	 *
	 * @param array $cond
	 *
	 * @return F_Db;
	 */
	public function _where( $cond )
	{
		if ( is_array( $cond ) ) {
			switch ( $cond[ 1 ] ) { //TODO: NOT NICE, isn't it!?
				case '=':
				case '<':
				case '>':
				case '!=':
				case '<=':
				case '>=':
					$this->where .= " {$cond[0]} {$cond[1]} '{$cond[2]}'";
					break;
				case 'in':
				case 'IN':
					$this->where .= " {$cond[0]} IN ({$cond[2]})";
					break;
				case 'between':
				case 'BETWEEN':
					$this->where .= " BETWEEN '{$cond[0]}' AND '{$cond[2]}'";
					break;
				case 'not_between':
				case 'NOT_BETWEEN':
					$this->where .= " NOT BETWEEN '{$cond[0]}' AND '{$cond[2]}'";
					break;
				case 'like':
				case 'LIKE':
					$this->where .= " {$cond[0]} LIKE '{$cond[2]}";
					break;
				case 'not_like':
				case 'NOT_LIKE':
					$this->where .= " {$cond[0]} NOT LIKE '{$cond[2]}";
					break;
			}
		} else {
			//TODO: Return warning
		}
		return $this;
	}

	/**
	 * @param type $cond
	 *
	 * @return \F_Db
	 */
	public function and_where( $cond )
	{
		$this->where .= ' AND';
		$this->_where( $cond );
		return $this;
	}

	public function or_where( $cond )
	{
		if ( $this->where != '' ) {
			$this->where .= ' OR';
			$this->_where( $cond );
			return $this;
		} else {
			//TODO: return warning;
		}
	}

	/**
	 * @return \F_Db
	 */
	public function offset_limit( array $lmt )
	{
		$this->limit = $lmt[ 0 ] . ', ' . $lmt[ 1 ];
		return $this;
	}

	/**
	 * @return \F_Db
	 */
	public function order_by( array $c, $last = FALSE )
	{
		if ( !$last ) {
			$this->order_by .= key( $c ) . ' ' . strtoupper( $c[ key( $c ) ] ) . ', ';
		} else {
			$this->order_by .= key( $c ) . ' ' . strtoupper( $c[ key( $c ) ] );
		}
		return $this;
	}

	/**
	 * @return FDb
	 */
	public function sql( $query )
	{
		$this->query .= " " . $query;
		return $this;
	}

	/**
	 * @return FDb
	 */
	public function insert_into( $table, $data )
	{
		$this->table = $table;
		$table = "`$table`";

		$fields = "";
		$values = "";
		foreach ( $data as $key => $value ) {
			$fields .= "`$key`,";
			$values .= "'$value',";
		}

		$fields = substr( $fields, 0, -1 );
		$values = substr( $values, 0, -1 );

		$this->query = "INSERT INTO $table ($fields) VALUES ($values)";

		return $this;
	}

	/**
	 * @return FDb
	 */
	public function insert_ignore_into( $table, $data )
	{
		$this->table = $table;
		$table = "`$table`";

		$fields = "";
		$values = "";
		foreach ( $data as $key => $value ) {
			$fields .= "`$key`,";
			$values .= "'$value',";
		}

		$fields = substr( $fields, 0, -1 );
		$values = substr( $values, 0, -1 );

		$this->query = "INSERT IGNORE INTO $table ($fields) VALUES ($values)";

		return $this;
	}

	/**
	 * @return FDb
	 */
	public function replace_into( $table, $data )
	{
		$this->table = $table;
		$table = "`$table`";

		$fields = "";
		$values = "";
		foreach ( $data as $key => $value ) {
			$fields .= "`$key`,";
			$values .= "'$value',";
		}

		$fields = substr( $fields, 0, -1 );
		$values = substr( $values, 0, -1 );

		$this->query = "REPLACE INTO $table ($fields) VALUES ($values)";

		return $this;
	}

	/**
	 * @return FDb
	 */
	public function update( $table, $data )
	{
		$this->table = $table;
		$table = "`$table`";

		$set = "";
		foreach ( $data as $key => $value ) {
			$set .= "`$key` = '$value',";
		}

		$set = substr( $set, 0, -1 );

		$this->query = "UPDATE $table SET $set";

		return $this;
	}

	public function last_insert_id()
	{
		return $this->last_insert_id;
	}

	/**
	 * @return FDb
	 */
	public function delete_from( $table )
	{

		$this->query = "DELETE FROM `$table`";
		$this->table = $table;
		return $this;
	}

	private function build_query()
	{
		if ( $this->where != '' ) {
			$this->query .= " WHERE" . $this->where;
		}
		if ( $this->order_by != '' ) {
			$this->query .= " ORDER BY $this->order_by";
		}
		if ( $this->limit != '' ) {
			$this->query .= " LIMIT $this->limit";
		}
	}

	/**
	 * @return bool
	 */
	public function execute()
	{
		$this->build_query();
		if ( mysql_query( $this->query ) ) {
			$this->last_insert_id = mysql_insert_id();
			$this->last_query = $this->query;
			$this->query = '';
			$this->where = '';
			$this->limit = '';
			$this->order_by = '';
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function fetch_object()
	{
		$this->build_query();
		$query = mysql_query( $this->query );

		$return = array();

		while ( $result = @mysql_fetch_object( $query ) ) {
			$return [ ] = $result;
		}

		$this->last_query = $this->query;
		$this->query = '';
		$this->where = '';
		$this->limit = '';
		$this->order_by = '';

		return $return;
	}

	public function stripslashes_deep( &$array )
	{
		$return = array();
		foreach ( $array as $k => $v ) {
			if ( is_array( $v ) ) {
				$this->stripslashes_deep( $v );
			} else {
				stripslashes( $v );
			}
		}
	}

	public function reset_auto_increment( $table )
	{
		$this->sql( "ALTER TABLE $table AUTO_INCREMENT=1" )->execute();
	}

}