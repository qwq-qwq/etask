<?php

Kernel::Import("system.db.abstracttable");

class TaskStateTable extends AbstractTable
{
	function TaskStateTable(&$connection)
	{
		parent::AbstractTable($connection, DB_TABLE_TASK_STATE);

		$this->addTableField('intID', DB_COLUMN_NUMERIC, true);
		$this->addTableField('varName');
	}	
}