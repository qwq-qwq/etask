<?php

define("DB_CONNECTION_STATE_CLOSED", 1);
define("DB_CONNECTION_STATE_CONNECTED", 2);
define("DB_CONNECTION_STATE_OPENED", 3);

class AbstructConnection {

	var $State = DB_CONNECTION_STATE_CLOSED;
	var $_Res;

	function Open($properties = null) {
		$this->State = DB_CONNECTION_STATE_CONNECTED;
		return true;
	}

	function Close() {
		$this->State = DB_CONNECTION_STATE_CLOSED;
	}

	function ChangeDatabase($database) {
		$this->State == DB_CONNECTION_STATE_OPENED;
	}

	function ExecuteNonQuery($query) {
		return null;
	}

	function &ExecuteReader($query) {
		return null;
	}

	function ExecuteScalar($query) {
		return array();
	}

}

?>