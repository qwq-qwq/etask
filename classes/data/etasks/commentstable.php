<?php

Kernel::Import("system.db.abstracttable");

class CommentsTable extends AbstractTable {

	function CommentsTable(&$connection) {
		parent::AbstractTable($connection, DB_TABLE_COMMENTS);
		$this->addTableField('intCommentID', DB_COLUMN_NUMERIC, true);
		$this->addTableField('intTaskID', DB_COLUMN_NUMERIC);
		$this->addTableField('intUserID', DB_COLUMN_NUMERIC);
		$this->addTableField('varCreated');
		$this->addTableField('varText');
	}
	
}