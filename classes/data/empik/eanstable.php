<?php

Kernel::Import("system.db.abstracttable");

class EansTable extends AbstractTable {
	function EansTable(&$connection) {
		parent::AbstractTable($connection, DB_EMPIK_TABLE_EANS);

		$this->addTableField('ean', DB_COLUMN_STRING, true);
		$this->addTableField('Wares_id', DB_COLUMN_NUMERIC);	
		$this->addTableField('Report_unit');
	}
}