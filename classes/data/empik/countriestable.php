<?php

Kernel::Import("system.db.abstracttable");

class CountriesTable extends AbstractTable
{
	function CountriesTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_COUNTRIES);

		$this->addTableField('Country_id', DB_COLUMN_NUMERIC, true);
		$this->addTableField('Region_id', DB_COLUMN_NUMERIC);
		$this->addTableField('Name_RU');
		$this->addTableField('Name_UA');
		$this->addTableField('Name_EN');		
	}
	
function &GetWithRegion($data = null, $orders = null, $limitCount = null, $limitOffset = null) {
		$whereClause = "";
		if (!is_null($data)) {
			foreach ($this->columns as $column) {
				if (isset($data[$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= 'p.'.$column["name"] . "=" . AbstractTable::prepareColumnValue($column, $data[$column["name"]]);
				}
				if (!empty($data['LIKE'.$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= 'p.'.$column["name"] . " LIKE(" . AbstractTable::prepareColumnValue($column, '%'.$data['LIKE'.$column["name"]].'%') . ")";
				}
				if (!empty($data['FROM'.$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= 'p.'.$column["name"] . " >= " . AbstractTable::prepareColumnValue($column, $data['FROM'.$column["name"]]);
				}
				if (!empty($data['TO'.$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= 'p.'.$column["name"] . " <= " . AbstractTable::prepareColumnValue($column, $data['TO'.$column["name"]]);
				}
				if (!empty($data['IN'.$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= 'p.'.$column["name"] . " IN (" . $data['IN'.$column["name"]].") ";
				}
			}
		}
		if (strlen($whereClause)) $whereClause = " WHERE " . $whereClause;
		$orderClause = "";
		if (is_array($orders)) {
			$keys = array_keys($orders);
			foreach ($keys as $key) {
				if (strlen($orderClause)) {
					$orderClause .= ", ";
				}
				$orderClause = $orderClause .'p.'.$key . " ".$orders[$key];
			}
		}
		if (strlen($orderClause)) {
			$orderClause = " ORDER BY " . $orderClause;
		}
		$limitClause = "";
		if (!is_null($limitCount)) {
			if (!is_null($limitOffset)) $limitClause = $limitOffset . ", ";
			$limitClause = " LIMIT " . $limitClause . $limitCount;
		}

		$SQL = sprintf("SELECT p.*, (SELECT Name_RU FROM %s WHERE Region_id=p.Region_id) as Region_name FROM %s as p %s%s%s", DB_EMPIK_TABLE_REGIONS, $this->tableName, $whereClause, $orderClause, $limitClause);
		@$reader = &$this->connection->ExecuteReader($SQL);
		return $reader;
	}

}