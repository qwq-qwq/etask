<?php

Kernel::Import("system.db.abstracttable");

class BillTable extends AbstractTable {

	function BillTable(&$connection) {
		parent::AbstractTable($connection, DB_TABLE_BILL);
		$this->addTableField('intBillID', DB_COLUMN_NUMERIC, true);
		$this->addTableField('intOrderID', DB_COLUMN_NUMERIC);
		$this->addTableField('varTime');
	}
}