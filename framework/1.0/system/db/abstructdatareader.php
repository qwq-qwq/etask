<?php

define("DB_READER_STATE_CLOSED", 1);
define("DB_READER_STATE_OPENED", 2);

class AbstructDataReader {

	var $RecordCount;
	var $FieldCount;
	var $Item;
	var $state = DB_READER_STATE_CLOSED;
	var $queryId = null;
	var $currentRecord = 0;

	function Close() {
		unset($this);
	}

	function IsClosed() {
		if ($this->state == DB_READER_STATE_CLOSED) {
			return true;
		} else {
			return false;
		}
	}

	function Read() {
	}

}
?>