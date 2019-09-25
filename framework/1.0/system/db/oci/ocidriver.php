<?php

Class OciDriver {
	
	protected $conn_id = null;
	protected $stmt_id = false;
	protected $result_id = false;
	protected $_commit = OCI_COMMIT_ON_SUCCESS;
	protected $tran = 0;
	
	function __construct($properties) {
		//Not beautiful, but fast solution
		$params = explode('|', $properties);
		$params[0] = (!empty($params[0]))? $params[0] : null;
		$params[1] = (!empty($params[1]))? $params[1] : null;
		$params[2] = (!empty($params[2]))? $params[2] : null;
		$params[3] = (!empty($params[3]))? $params[3] : null;
		$params[4] = (!empty($params[4]))? $params[4] : null;
		
		$this->conn_id = oci_connect($params[0], $params[1], $params[2], $params[3], $params[4]) or die("Unable to connect Oracle database");
	}
	
	/**
	 * Starts a transaction
	 *
	 * @return bool
	 */
	
	function begin_tran() {
		if (!$this->_inTransaction()) {
			$this->_commit = OCI_DEFAULT;
			$this->tran++;
		}
		return TRUE;
	}
	
	/**
	 * To check if we currently in transaction
	 *
	 * @return bool
	 */
	
	protected function _inTransaction() {
		return ($this->tran > 0 && $this->_commit == OCI_DEFAULT);
	}
	
	/**
	 * Commits a transaction
	 *
	 * @return bool
	 */
	
	function commit_tran() {
		if ($this->_inTransaction()) {
			$this->tran--;
			$this->_commit = OCI_COMMIT_ON_SUCCESS;
		}
		
		return oci_commit($this->conn_id);
	}
	
	/**
	 * Rolls back a transaction
	 *
	 * @return bool
	 */
	
	function rollback_tran() {
		if ($this->_inTransaction()) {
			$this->tran--;
			$this->_commit = OCI_COMMIT_ON_SUCCESS;
		}
		
		return oci_rollback($this->conn_id);
	}
	
	/**
	 * Execute the query
	 *
	 * @param   string  an SQL query
	 * @return  resource
	 */
	function execute($sql)
	{
		// oracle must parse the query before it is run. All of the actions with
		// the query are based on the statement id returned by ociparse
		$this->stmt_id = FALSE;
		$this->_set_stmt_id($sql);
		oci_set_prefetch($this->stmt_id, 1000);
		return @oci_execute($this->stmt_id, $this->_commit);
	}
	
	/**
	 * Generate a statement ID
	 *
	 * @param   string  an SQL query
	 * @return  none
	 */
	protected function _set_stmt_id($sql)
	{
		if ( ! is_resource($this->stmt_id))
		{
			$this->stmt_id = oci_parse($this->conn_id, $sql);
		}
	}
	
	
	function query($sql)
	{
		if ($sql == '')
		{
			return FALSE;
		}

		// Run the Query
		if (FALSE === ($this->result_id = $this->execute($sql)))
		{
			return FALSE;
		}
		// Was the query a "write" type?
		// If so we'll simply return true
		if ($this->is_write_type($sql) === TRUE)
		{
			return TRUE;
		}
		
		$RES = array();
		
		// oracle's fetch functions do not return arrays.
		// The information is returned in reference parameters
		$row = $this->_fetch_assoc();
		while ($row !== FALSE)
		{
			$RES[] = $row;
			$row = $this->_fetch_assoc();
		}

		return $RES;
	}
	
	protected function _fetch_assoc()
	{
		return oci_fetch_array($this->stmt_id, OCI_ASSOC + OCI_RETURN_NULLS);	
	}
	
	
	function stored_procedure($procedure, $params)
	{
		if ($procedure == '' OR ! is_array($params))
		{
			return FALSE;
		}
		
		// build the query string
		$sql = "begin $procedure(";

		foreach($params as $param)
		{
			if(is_array($param)) $sql .= $param['name'] . ",";
			else $sql .= $param . ",";
		}
		$sql = trim($sql, ",") . "); end;";
				
		$stmt_id = oci_parse($this->conn_id, $sql);
		if($stmt_id == false) return false;

		foreach($params as $param){
			if(is_array($param)){
				$bind = oci_bind_by_name($stmt_id, $param['name'], $param['val'], $param['len'], $param['type']);
				if($bind == false) return false;
			}
		}
		//var_dump($sql);
		return oci_execute($stmt_id, $this->_commit);
	}
	
	function ora_function($name, $params = array(), $char_len = 0){
		if ($name == '' OR ! is_array($params))
		{
			return FALSE;
		}
		$result = null;
		// build the query string
		$sql = "begin :result:=$name" .((count($params) > 0) ? "(" : "");

		foreach($params as $param)
		{
			if(is_array($param)) $sql .= $param['name'] . ",";
			else $sql .= $param . ",";
		}
		$sql = trim($sql, ",").((count($params) > 0)? ")" : "")."; end;";

		$stmt_id = oci_parse($this->conn_id, $sql);

		if($stmt_id == false) return false;
		
		//if($char_len > 0)
		//	$bind = oci_bind_by_name($stmt_id, ':result', &$result, $char_len);
		//else $bind = oci_bind_by_name($stmt_id, ':result', &$result, -1, SQLT_INT);
		//if($bind == false) return false;

		if($char_len > 0)
			$bind = oci_bind_by_name($stmt_id, ':result', $result, $char_len);
		else $bind = oci_bind_by_name($stmt_id, ':result', $result, -1, SQLT_INT);
		if($bind == false) return false;

		foreach($params as $param){
			if(is_array($param)){
				$bind = oci_bind_by_name($stmt_id, $param['name'], $param['val'], $param['len'], $param['type']);
				if($bind == false) return false;
			}
		}
		//var_dump($sql);
		if(!oci_execute($stmt_id, $this->_commit)) return false;
		return $result;
	}
	
	/**
	 * Insert statement
	 *
	 * Generates a platform-specific insert string from the supplied data
	 *
	 * @access  public
	 * @param   string  the table name
	 * @param   array   the insert keys
	 * @param   array   the insert values
	 * @return  string
	 */
	function _insert($table, $keys, $values)
	{
	return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
	}
	
	/**
	 * Update statement
	 *
	 * Generates a platform-specific update string from the supplied data
	 *
	 * @access	public
	 * @param	string	the table name
	 * @param	array	the update data
	 * @param	array	the where clause
	 * @param	array	the orderby clause
	 * @param	array	the limit clause
	 * @return	string
	 */
	function _update($table, $values, $where, $orderby = array(), $limit = FALSE)
	{
		foreach($values as $key => $val)
		{
			$valstr[] = $key." = ".$val;
		}

		$limit = ( ! $limit) ? '' : ' LIMIT '.$limit;

		$orderby = (count($orderby) >= 1)?' ORDER BY '.implode(", ", $orderby):'';

		$sql = "UPDATE ".$table." SET ".implode(', ', $valstr);

		$sql .= ($where != '' AND count($where) >=1) ? " WHERE ".implode(" ", $where) : '';

		$sql .= $orderby.$limit;

		return $sql;
	}
	
		/**
	 * Determines if a query is a "write" type.
	 *
	 * @access	public
	 * @param	string	An SQL query string
	 * @return	boolean
	 */
	function is_write_type($sql)
	{
		if ( ! preg_match('/(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD DATA|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK|TRANSACTION|COMMIT|ROLLBACK)[\s;]+/i', $sql))
		{
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * The error message string
	 *
	 * @return  string
	 */
	function _error_message()
	{
		$error = oci_error($this->conn_id);
		return $error['message'];
	}

	/**
	 * The error message number
	 *
	 * @return  integer
	 */
	function _error_number()
	{
		$error = oci_error($this->conn_id);
		return $error['code'];
	}
}