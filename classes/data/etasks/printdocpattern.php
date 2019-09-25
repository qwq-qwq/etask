<?php

Kernel::Import("system.db.abstracttable");

class PrintDocPattern extends AbstractTable {

	function PrintDocPattern(&$connection) {
		parent::AbstractTable($connection, SL_TABLE_PRINT_DOC_PATTERN);
		$this->addTableField('doc_type', DB_COLUMN_NUMERIC, true);
		$this->addTableField('fild_name');
		$this->addTableField('value');
	}
		
}