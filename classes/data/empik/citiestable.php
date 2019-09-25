<?php

Kernel::Import("system.db.abstracttable");

class CitiesTable extends AbstractTable
{
	function CitiesTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_CITIES);

		$this->addTableField('City_id', DB_COLUMN_NUMERIC, true);
		$this->addTableField('sort', DB_COLUMN_NUMERIC);
		$this->addTableField('Name_RU');
		$this->addTableField('Name_UA');
		$this->addTableField('Name_EN');		
	}
	
	function getCitiesByCountryID($Country_id) {
		$SQL = sprintf("SELECT * FROM %s WHERE City_id IN (SELECT City_id FROM %s WHERE Country_id = %s) ORDER BY Name_RU ASC", $this->tableName, DB_EMPIK_TABLE_CITY_COUNTRY, $Country_id);			
		return $this->connection->ExecuteScalar($SQL, false);
	}

}