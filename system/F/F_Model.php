<?php

abstract class F_Model
{

	/**
	 * @var F_Db
	 */
	protected $db;
	//protected $bindArray = array( );

	protected $_table;
	protected $_primary;
	protected $_fields;
	protected $_created_field;
	protected $_updated_field;
	protected $_created;
	protected $_updated;
	protected $_modified;

	public function __construct( $pk = NULL )
	{
		$this->db = F_Db::getInstance();

		$this->init();

		$this->_modified = FALSE;
		$this->_created = time();
		if ( $pk != NULL ) {
			$this->find_by_pk( $pk );
		}
	}

	protected abstract function init();

	/**
	 *
	 * @param array $conditions Posible conditions are: 'select', 'where', 'order_by', 'limit
	 *
	 * @return array
	 */
	public function find( $conditions, $single = FALSE ) //ToDO: RETHINK $single
	{
		//Select
		$query = $this->db->select( '*' );
		//Are there any select conditions
		if ( isset( $conditions[ 'select' ] ) ) {
			//Is there an array of the fields
			if ( is_array( $conditions[ 'select' ] ) ) {
				//Check whether current field exists in the '$_field'
				foreach ( $conditions[ 'select' ] as $field ) {
					if ( !$this->field_exists( $field ) ) {
						//TODO: Show warning
						xdebug_var_dump( 'Field does not exist' );
					}
				}
				$this->db->select( implode( ', ', $conditions[ 'select' ] ) );
			} else {
				if ( !$this->field_exists( $conditions[ 'select' ] ) ) {
					//TODO: Show warning
					xdebug_var_dump( 'Field does not exist' );
				}
				$query = $this->db->select( $conditions[ 'select' ] );
			}
		}

		$this->db->from( $this->_table );

		//WHERE
		if ( isset( $conditions[ 'where' ] ) ) {
			if ( is_array( $conditions[ 'where' ] ) ) {
				if ( is_array( $conditions[ 'where' ][ 0 ] ) ) {
					for ( $i = 0; $i < count( $conditions[ 'where' ] ); $i++ ) {
						if ( $i == 0 ) {
							$this->db->_where( $conditions[ 'where' ][ $i ] );
						} else {
							if ( array_shift( $conditions[ 'where' ][ $i ] ) == 'and' ) {
								$this->db->and_where( $conditions[ 'where' ][ $i ] );
							} elseif ( array_shift( $conditions[ 'where' ][ $i ] ) == 'or' ) {
								$this->db->or_where( $conditions[ 'where' ][ $i ] );
							} else {
								//TODO: Show warning
							}
						}
					}
				} else {
					$this->db->_where( $conditions[ 'where' ] );
				}
			} else {
				//TODO: Show warning
			}
		}

		//LIMIT, OFFSET
		if ( isset( $conditions[ 'limit' ] ) ) {
			if ( is_array( $conditions[ 'limit' ] ) ) {
				$this->db->offset_limit( $conditions[ 'limit' ] );
			} else {
				//TODO: Show Warning
			}
		}

		//ORDER BY
		if ( isset( $conditions[ 'order_by' ] ) ) {
			if ( is_array( $conditions[ 'order_by' ] ) ) {
				foreach ( $conditions[ 'order_by' ] as $key => $val ) {
					//Check whether current field exists in the '$_field'
					if ( $this->field_exists( $key ) ) {
						//If last pair in the array remove the ',' from the end of the string
						if ( next( $conditions[ 'order_by' ] ) ) {
							$this->db->order_by( array( $key => $val ) );
						} else {
							$this->db->order_by( array( $key => $val ), TRUE );
						}
					} else {
						//TODO: Show warning
					}
				}
			} else {
				//TODO: Show Warning
			}
		}
		//Creating new Models with recieved data
		$object = $this->db->fetch_object();

		if ( $single ) {
			$this->assign_attrs( $this, $object[0] );
		} else {
			$result = array();
			foreach ( $object as $model ) {
				$me = new $this;
				array_push( $result, $this->assign_attrs( $me, $model ) );
			}
			return $result;
		}
	}

	public function find_by_pk( $key )
	{
		$this->find( array( 'where' => array( $this->_primary, '=', $key ) ), TRUE );
	}

	public function find_all( $offset = 0, $limit = 10000 )
	{
		return $this->find( array( 'limit' => array( $offset, $limit ) ) );
	}

	/**
	 * Checks whether a field inside current model exists
	 *
	 * @param string $field Field name
	 */
	protected function field_exists( $field )
	{
		return array_key_exists( $field, $this->_fields );
	}

	/**
	 * Magic method to set values for the fields
	 */
	public function __set( $name, $value )
	{
		if ( $this->field_exists( $name ) ) {
			$this->$name = $value;
			$this->_modified = TRUE;
		} else {
			//TODO: Show warning
		}
	}

	/**
	 * Magic method to get the field values
	 */
	public function __get( $key )
	{
		if ( $this->field_exists( $key ) ) {
			return $this->$key;
		} else {
			//TODO: Show warning
		}
	}

	public function get_primary()
	{
		return $this->_primary;
	}

	/**
	 *	  Magic methods for functions like find_by_* or find_one_by_*
	 *
	 * @param string $name
	 * @param string $args
	 *
	 * @return array/object
	 */
	public function __call( $name, $args )
	{
		if ( strstr( $name, 'find_by_' ) ) {
			$field = substr( $name, 8 );
			if ( $this->field_exists( $field ) ) {
				$where = array( 'where' => array( $field, '=', $args[ 0 ] ) );
				return $this->find( $where );
			} else {
				//TODO: Show warning
			}
		}
		if ( strstr( $name, 'find_one_by_' ) ) {
			$field = substr( $name, 12 );
			if ( $this->field_exists( $field ) ) {
				$where = array( 'where' => array( $field, '=', $args[ 0 ] ) );
				$result = $this->find( $where, TRUE );
			} else {
				//TODO: Show warning
			}
		}
	}

	private function assign_attrs( $model, $object )
	{
		foreach ( get_object_vars( $object ) as $attr => $value ) {
			$model->$attr = $object->$attr;
//			var_dump( $object->$attr);
		}
		return $model;
	}

	public function save()
	{
		$data = array();
		foreach ( $this->_fields as $field => $value ) {
			if ( isset( $this->$field ) ) {
				$data[ $field ] = $this->$field;
			}
		}
		$pk_field = $this->_primary;

		unset( $data[ $this->_primary ] ); //Prevent PK from being updated
		//	if ( $this->_modified ) { TODO: modification toggle! Model
		if ( isset( $this->$pk_field ) ) {
			$this->db->update( $this->_table, $data )->_where( array( $this->_primary, '=',
			                                                        $this->$pk_field ) )->execute();
			$this->_modified = FALSE;
		} else {
			$this->db->insert_ignore_into( $this->_table, $data )->execute();
			echo F_Db::getInstance()->last_query();
			$this->$pk_field = $this->db->last_insert_id();
			$this->_modified = FALSE;
		}
		//	}
	}

	public function delete()
	{
		$pk_field = $this->_primary;
		if ( isset( $this->$pk_field ) ) {
			$this->db->delete_from( $this->_table )->_where( array( $this->_primary, '=',
			                                                     $this->$pk_field ) )->execute();
			echo F_Db::getInstance()->last_query();
			$this->__destruct();
		}
	}

}