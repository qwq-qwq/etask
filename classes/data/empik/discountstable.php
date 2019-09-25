<?php

Kernel::Import("system.db.abstracttable");

class DiscountsTable extends AbstractTable
{
	function __construct(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_DISCOUNTS);

		$this->addTableField('id', DB_COLUMN_NUMERIC, true);
		$this->addTableField('sum');
		$this->addTableField('percent', DB_COLUMN_NUMERIC);
		$this->addTableField('accumulate', DB_COLUMN_NUMERIC);
		$this->addTableField('free_delivery_ua', DB_COLUMN_NUMERIC);
	}
}

class EmpDiscountsTable extends AbstractTable
{
	function __construct(&$connection)
	{
		parent::AbstractTable($connection, 'emp_discount_vip');

		$this->addTableField('code_privat', DB_COLUMN_NUMERIC);
		$this->addTableField('discount', DB_COLUMN_NUMERIC);
	}
}