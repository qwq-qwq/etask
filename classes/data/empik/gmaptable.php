<?php

Kernel::Import("system.db.abstracttable");

class GmapTable extends AbstractTable
{
	function GmapTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_GMAP);

		$this->addTableField('id', DB_COLUMN_NUMERIC, true);
		$this->addTableField('lat');
		$this->addTableField('long');
		$this->addTableField('name_en');
		$this->addTableField('name_ru');		
		$this->addTableField('name_ua');		
		$this->addTableField('description_en');		
		$this->addTableField('description_ru');		
		$this->addTableField('description_ua');		
		$this->addTableField('is_bukva');		
		$this->addTableField('sprut_code');		
		$this->addTableField('city_id');		
	}

	function GetShopAndCityBySprutCode($code) {
		$SQL = 'SELECT * FROM '.$this->tableName.' 
		LEFT JOIN '.DB_EMPIK_TABLE_CITIES.' ON '.DB_EMPIK_TABLE_CITIES.'.City_id='.$this->tableName.'.city_id 
		WHERE `sprut_code` = '."'$code'";
		return $this->connection->ExecuteScalar($SQL);
	}

}