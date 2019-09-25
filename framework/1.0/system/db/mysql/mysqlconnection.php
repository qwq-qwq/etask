<?php

Kernel::Import("system.db.AbstractConnection");
Kernel::Import("system.db.mysql.MysqlDataReader");

class MySQLConnection Extends AbstructConnection {

	/**
	 *
	 * @var MySQLConnectionProperties
	 */
	var $properties;

	function MySQLConnection($properties = null) {
		$this->properties = $properties;
	}

	function Open($isNewConnection=true) {
		$this->_Res = mysql_connect(
							$this->properties->getHost(),
							$this->properties->getUser(),
							$this->properties->getPassword(),
							$isNewConnection ) or Kernel::RaiseError("Unable to make database connect");
		if ($this->_Res) {
			$this->State = DB_CONNECTION_STATE_CONNECTED;
			$this->ChangeDatabase($this->properties->getDatabase());
			$this->ExecuteNonQuery('SET NAMES "' . $this->properties->encoding . '"');
			$this->ExecuteNonQuery('SET CHARACTER SET "' . $this->properties->encoding . '"');
			$this->ExecuteNonQuery('set collation_connection= "utf8_unicode_ci"');
			return true;
		}
		return false;
	}

	function Close() {
		if ($this->State > DB_CONNECTION_STATE_CLOSED) {
			mysql_close($this->_Res);
			$this->State = DB_CONNECTION_STATE_CLOSED;
		}
	}

	function ChangeDatabase($database) {
		if ($this->State > DB_CONNECTION_STATE_CLOSED) {
			mysql_select_db($database, $this->_Res) or Kernel::RaiseError("Unable to select database '".$database."'");
			$this->State = DB_CONNECTION_STATE_OPENED;
			$this->properties->setDatabase($database);
			return true;
		}
		return false;
	}

	function allocate_mysql_query($query) {
		$res = mysql_query($query, $this->_Res) or Kernel::RaiseError("MySQL query '".trim($query)."' failed, because ".mysql_error($this->_Res));
		return $res;
	}

	function ExecuteNonQuery($query) {
		if ($this->State == DB_CONNECTION_STATE_OPENED) {
			$this->allocate_mysql_query($query);
			return true;
		}
		return false;
	}

	function &ExecuteReader($query) {
		$MySqlDataReader = new MysqlDataReader;
		if ($this->State == DB_CONNECTION_STATE_OPENED) {
			$_res = $this->allocate_mysql_query($query);
			if( !strlen( mysql_error($this->_Res) ) ) {
				$_num = mysql_num_rows($_res);
				$MySqlDataReader->RecordCount = $_num;
				$MySqlDataReader->FieldCount = mysql_num_fields($_res);
				$MySqlDataReader->queryId = $_res;
				$MySqlDataReader->state = DB_READER_STATE_OPENED;
			} else {
				$MySqlDataReader->queryId = $_res;
				$MySqlDataReader->RecordCount = 0;
				$MySqlDataReader->FieldCount = 0;
				$MySqlDataReader->mysql_error = mysql_error($this->_Res);
				$MySqlDataReader->mysql_errno = mysql_errno($this->_Res);
			}
			return $MySqlDataReader;
		}
		return $MySqlDataReader;
	}

	function ExecuteTable($query)
	{
		$reader = $this->ExecuteReader($query);
		$result = array();
		while( $item = $reader->Read() )
		{
			$result[] = $item;
		}
		return $result;
	}

	function ExecuteScalar($query, $limited = true) {
		if ($this->State == DB_CONNECTION_STATE_OPENED) {
			$res = $this->allocate_mysql_query($query);
			if( !strlen( mysql_error() ) ) {
				$num = mysql_num_rows($res);
				if ($num > 0) {
					if ($limited) {
						$ret = mysql_fetch_assoc($res);
					} else {
						while ($row = mysql_fetch_assoc($res)) {
							$ret[] = $row;
						}
					}
					return $ret;
				}
			}
		}
		return array();
	}

}
?>