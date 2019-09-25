<?php

Kernel::Import("system.db.abstracttable");

class WhsTable extends AbstractTable
{
	function WhsTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_WHS);

		$this->addTableField('id', DB_COLUMN_NUMERIC, true);
		$this->addTableField('name_ru');
		$this->addTableField('name_ua');
		$this->addTableField('name_en');
		$this->addTableField('open_ru');
		$this->addTableField('open_ua');
		$this->addTableField('open_en');
		$this->addTableField('city_id');
	}
	
	function getWhsByCityID($city_id) {
		$SQL = sprintf("SELECT * FROM %s WHERE city_id = %s ORDER BY name_ru ASC", $this->tableName, $city_id);
		return $this->connection->ExecuteScalar($SQL, false);
	}

}