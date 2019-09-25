<?php

Kernel::Import("system.db.abstracttable");

class DocumentsTable extends AbstractTable {

	function DocumentsTable(&$connection) {
		parent::AbstractTable($connection, DB_TABLE_DOCUMENTS);
		$this->addTableField('intDocumentID', DB_COLUMN_NUMERIC, true);
		$this->addTableField('intUserID', DB_COLUMN_NUMERIC);
		$this->addTableField('varTableName');
		$this->addTableField('intIdentID', DB_COLUMN_NUMERIC);
		$this->addTableField('varCreated');
		$this->addTableField('varFilename');
		$this->addTableField('varFile');
		$this->addTableField('intSize', DB_COLUMN_NUMERIC);
		$this->addTableField('varType');
		
	}
		
}