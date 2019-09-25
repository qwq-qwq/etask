<?php

Kernel::Import("system.db.abstracttable");

class TaskTypesTable extends AbstractTable
{
	function TaskTypesTable(&$connection)
	{
		parent::AbstractTable($connection, DB_TABLE_TASK_TYPES);

		$this->addTableField('intID', DB_COLUMN_NUMERIC, true);
		$this->addTableField('varName');
		$this->addTableField('varController');
	}	
}