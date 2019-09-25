<?php

Kernel::Import("system.db.abstracttable");

class SalesTable extends AbstractTable
{
	private $est_deliv = array (
		'message_sending_00' => array( 'name'=>'Высылаем в течение 24 часов', 'color'=>'#080'),
		'message_sending_10' => array( 'name'=>'Высылаем в течение 24-48 часов', 'color'=>'#008'),
		'message_sending_20' => array( 'name'=>'Высылаем в течение 36-72 часов', 'color'=>'#088'),
		'message_sending_30' => array( 'name'=>'Высылаем в течение 5-10 дней', 'color'=>'#880'),
		'message_sending_40' => array( 'name'=>'Предварительный заказ', 'color'=>'#800')
	);
	
	function SalesTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_SALES);

		$this->addTableField('Wares_id', DB_COLUMN_NUMERIC);
		$this->addTableField('Name');
		$this->addTableField('Group_id', DB_COLUMN_NUMERIC);
		$this->addTableField('Ord_id', DB_COLUMN_NUMERIC);
		$this->addTableField('Qty', DB_COLUMN_NUMERIC);
		$this->addTableField('Price');
		$this->addTableField('Vat');
		$this->addTableField('Discount_forbidden');
		$this->addTableField('discount', DB_COLUMN_NUMERIC);
		$this->addTableField('Date');
		$this->addTableField('Est_deliv');		
	}
	
	function getEstDeliv($key) {
		return $this->est_deliv[$key];
	}
	
	function Get($data, $orders = null) {
		$result = array();
		$this->OnBeforeGet($data);
		$whereClause = '';
		foreach ($data as $field_name => $val) {
			if (strlen($whereClause) > 0) {
				$whereClause .= ' AND ';
			} else {
				$whereClause = ' WHERE ';
			}
			$whereClause .= $field_name . "=" . AbstractTable::prepareColumnValue($field_name, $val);
		}
		$orderClause = '';
		if (is_array($orders)) {
			$keys = array_keys($orders);
			foreach ($keys as $key) {
				if (strlen($orderClause)) {
					$orderClause .= ", ";
				}
				$orderClause = $orderClause . $key . " ".$orders[$key];
			}
		}
		if (strlen($orderClause)) {
			$orderClause = " ORDER BY " . $orderClause;
		}
		$SQL = sprintf("SELECT * FROM %s %s %s LIMIT 0, 1", $this->tableName, $whereClause, $orderClause);
		$result = $this->connection->ExecuteScalar($SQL);
		$this->OnAfterGet($data, $result);

		return $result;
	}
	
	function Insert($data, $forceInsertKeys=true) {
		$this->OnBeforeInsert($data);
		$sqlColumns = "";
		$sqlValues = "";
		foreach ($this->columns as $column) {
			if (strlen($sqlColumns)) $sqlColumns .= ", ";
			$sqlColumns .= $column["name"];
			if (strlen($sqlValues)) $sqlValues .= ", ";
			$sqlValues .= AbstractTable::prepareColumnValue($column, $data[$column["name"]]);
		}
		$SQL = sprintf("INSERT INTO %s (%s) VALUES (%s)", $this->tableName, $sqlColumns, $sqlValues);
		$res = $this->connection->ExecuteNonQuery($SQL);
		$this->OnAfterInsert($data);
	}
	
	function Update($data, $where = array()) {
		if (count($where) > 0) {
			$this->OnBeforeUpdate($data);
			$SQL = "";
			foreach ($this->columns as $column) {
				if (isset($data[$column["name"]])) {
					if (strlen($SQL)) $SQL .= ", ";
					$SQL .= $column["name"] . '=' . AbstractTable::prepareColumnValue($column, $data[$column["name"]]);
				}
			}
			$whereClause = '';
			foreach ($where as $field_name => $val) {
				if (strlen($whereClause) > 0) {
					$whereClause .= ' AND ';
				}
				$whereClause .= $field_name . "=" . AbstractTable::prepareColumnValue($field_name, $val);
			}
			$SQL = sprintf("UPDATE %s SET %s WHERE %s", $this->tableName, $SQL, $whereClause);

			$this->connection->ExecuteNonQuery($SQL);
			$this->OnAfterUpdate($data);
		}
	}
	
	function Delete($data) {
		$this->OnBeforeDelete($data);
		$whereClause = '';
		foreach ($data as $field_name => $val) {
			if (strlen($whereClause) > 0) {
				$whereClause .= ' AND ';
			}
			$whereClause .= $field_name . "=" . AbstractTable::prepareColumnValue($field_name, $val);
		}
		$SQL = sprintf("DELETE FROM %s WHERE %s", $this->tableName, $whereClause);

		$this->connection->ExecuteNonQuery($SQL);
		$this->OnAfterDelete($data);
	}
}