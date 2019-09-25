<?php
Kernel::Import("system.db.AbstructDataReader");

class MysqlDataReader extends AbstructDataReader {

	function Read() {
		if (($this->state == DB_READER_STATE_OPENED) && ($this->Item = mysql_fetch_array($this->queryId, MYSQL_ASSOC))) {
			$this->currentRecord++;
			return $this->Item;
		} else {
			$this->state = DB_READER_STATE_CLOSED;
			return false;
		}
	}

}
?>