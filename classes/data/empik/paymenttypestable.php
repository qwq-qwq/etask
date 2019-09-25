<?php

Kernel::Import("system.db.abstracttable");

class PaymentTypesTable extends AbstractTable
{
	function PaymentTypesTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_PAYMENT_TYPES);

		$this->addTableField('Payment_type', DB_COLUMN_NUMERIC, true);
		$this->addTableField('cash');
		$this->addTableField('Name_RU');
		$this->addTableField('Name_UA');
		$this->addTableField('Name_EN');		
	}
	
	function getPaymentTypesByCountryID($country_id) {
		$SQL = sprintf("SELECT * FROM %s WHERE Payment_type IN (SELECT Payment_type FROM %s WHERE Country_id = %s) ORDER BY Name_RU ASC", $this->tableName, DB_EMPIK_TABLE_PAY_COUNTRY, $country_id);			
		return $this->connection->ExecuteScalar($SQL, false);
	}
	
	function getPaymentTypesByCityID($city_id) {
		$SQL = sprintf("SELECT * FROM %s WHERE Payment_type IN (SELECT Payment_type FROM %s WHERE City_id = %s) ORDER BY Name_RU ASC", $this->tableName, DB_EMPIK_TABLE_PAY_CITY, $city_id);			
		return $this->connection->ExecuteScalar($SQL, false);
	}
	
	function getPaymentTypesByDeliveryType($delivery_type) {
		$SQL = sprintf("SELECT * FROM %s WHERE Payment_type IN (SELECT Payment_type FROM %s WHERE Delivery_type = %s) ORDER BY Name_RU ASC", $this->tableName, DB_EMPIK_TABLE_PAY_DELIV, $delivery_type);			
		return $this->connection->ExecuteScalar($SQL, false);
	}

}