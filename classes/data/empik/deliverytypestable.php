<?php

Kernel::Import("system.db.abstracttable");

class DeliveryTypesTable extends AbstractTable
{
	function DeliveryTypesTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_DELIVERY_TYPES);

		$this->addTableField('Delivery_type', DB_COLUMN_NUMERIC, true);
		$this->addTableField('Name_RU');
		$this->addTableField('Name_UA');
		$this->addTableField('Name_EN');		
	}
	
	function getDeliveryTypesByCityID($city_id) {
		$SQL = sprintf("SELECT * FROM %s WHERE Delivery_type IN (SELECT Delivery_type FROM %s WHERE City_id = %s) ORDER BY Name_RU ASC", $this->tableName, DB_EMPIK_TABLE_DELIVERY_CITY, $city_id);			
		return $this->connection->ExecuteScalar($SQL, false);
	}

}