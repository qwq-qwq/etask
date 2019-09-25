<?php

define("DB_COLUMN_NUMERIC", 1);
define("DB_COLUMN_STRING", 2);
if (!defined('DEFAULT_PAGESPERPAGE')) define('DEFAULT_PAGESPERPAGE', 20);

class AbstractTable {
	/**
	 * @var MySQLConnection
	 */
	var $connection;
	var $columns = array();
	var $tableName = '';

	function AbstractTable(&$connection, $tableName) {
		$this->connection = &$connection;
		$this->tableName = $tableName;
	}

	function removeTableKey() {
		foreach ($this->columns as $key => $column) {
			if ( $this->isKey($column) ) {
				$this->columns[$key]['key'] = false;
			}
		}
	}

	function removeTableField($field_name) {
		foreach ($this->columns as $key => $column) {
			if ( isset($column[$field_name]) ) {
				unset($this->columns[$key]);
			}
		}
	}

	function addTableField($name, $type = DB_COLUMN_STRING, $key = false, $translate = NULL) {
		$this->columns[] = array("name" => $name, "type" => $type, "key" => $key, "translate" => $translate);
	}

	function getKeyColumn() {
		$result = array();
		foreach ($this->columns as $column)
		if (AbstractTable::isKey($column)) {
			$result = $column;
		}
		return $result;
	}

	function getColumns(){
		return $this->columns;
	}

	function getInsertId() {
		return mysql_insert_id($this->connection->_Res);
	}

	function isKey(&$column) {
		return (is_array($column) && isset($column["key"]) && $column["key"]);
	}

	function SqlString($string) {
		return "'" . mysql_escape_string((string) $string) . "'";
	}

	function prepareColumnValue($column, $value) {
		$result = "";
		if ($column["type"] == DB_COLUMN_NUMERIC)
		$result = (int) $value;
		else
		$result = AbstractTable::SqlString($value);
		return $result;
	}

	function GetCount($data = null) {
		$result = array();
		$whereClause = "";
		foreach ($this->columns as $column) {
			if (isset($data[$column["name"]])) {
				if (strlen($whereClause)) $whereClause .= " AND ";
				$whereClause .= $column["name"] . "=" . AbstractTable::prepareColumnValue($column, $data[$column["name"]]);
			} elseif (!empty($data['LIKE'.$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= $column["name"] . " LIKE(" . AbstractTable::prepareColumnValue($column, '%'.$data['LIKE'.$column["name"]].'%') . ")";
			}
		}
		if (strlen($whereClause)) $whereClause = " WHERE " . $whereClause;
		$SQL = sprintf("SELECT COUNT(*) AS counter FROM %s %s LIMIT 0, 1", $this->tableName, $whereClause);
		$result = $this->connection->ExecuteScalar($SQL);
		return $result["counter"];
	}

	function Get($data, $orders = null) {
		$result = array();
		$keyColumn = $this->getKeyColumn();
		if (is_array($keyColumn)) {
			$this->OnBeforeGet($data);
			$whereClause = " WHERE ";
			$whereClause .= $keyColumn["name"] . "=" . AbstractTable::prepareColumnValue($keyColumn, $data[$keyColumn["name"]]);
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
		}
		return $result;
	}

	function OnBeforeGet(&$data) {
	}

	function OnAfterGet(&$data, &$result) {
	}

	function GetByFields($data = null, $orders = null, $limited = true) {
		$result = array();
		$whereClause = "";
		$orderClause = "";
		foreach ($this->columns as $column) {
			if (isset($data[$column["name"]])) {
				if (strlen($whereClause)) $whereClause .= " AND ";
				$whereClause .= $column["name"] . "=" . AbstractTable::prepareColumnValue($column, $data[$column["name"]]);
			}
		}
		if (strlen($whereClause)) $whereClause = " WHERE " . $whereClause;
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
		$SQL = sprintf("SELECT * FROM %s %s %s", $this->tableName, $whereClause, $orderClause);
		$result = $this->connection->ExecuteScalar($SQL, $limited);
		return $result;
	}

	function &GetReader($data = null, $orders = null, $limitCount = null, $limitOffset = null) {
		$whereClause = "";
		if (!is_null($data)) {
			foreach ($this->columns as $column) {
				if (isset($data[$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= $column["name"] . "=" . AbstractTable::prepareColumnValue($column, $data[$column["name"]]);
				} elseif (!empty($data['LIKE'.$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= $column["name"] . " LIKE(" . AbstractTable::prepareColumnValue($column, '%'.$data['LIKE'.$column["name"]].'%') . ")";
				}  elseif (!empty($data['IN'.$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= $column["name"] . " IN (" . $data['IN'.$column["name"]] . ")";
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
				$orderClause = $orderClause . $key . " ".$orders[$key];
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
		$SQL = sprintf("SELECT * FROM %s%s%s%s", $this->tableName, $whereClause, $orderClause, $limitClause);
		@$reader = &$this->connection->ExecuteReader($SQL);
		return $reader;
	}

	function Insert(&$data, $forceInsertKeys=false) {
		$this->OnBeforeInsert($data);
		$sqlColumns = "";
		$sqlValues = "";
		foreach ($this->columns as $column)
		if ($forceInsertKeys || !AbstractTable::isKey($column)) {
			if (strlen($sqlColumns)) $sqlColumns .= ", ";
			$sqlColumns .= $column["name"];
			if (strlen($sqlValues)) $sqlValues .= ", ";
			$sqlValues .= AbstractTable::prepareColumnValue($column, $data[$column["name"]]);
		}
		$SQL = sprintf("INSERT INTO %s (%s) VALUES (%s)", $this->tableName, $sqlColumns, $sqlValues);
		$this->connection->ExecuteNonQuery($SQL);
		$keyColumn = $this->getKeyColumn();
		if (count($keyColumn)) {
			$data[$keyColumn["name"]] = $this->getInsertId();
		}
		$this->OnAfterInsert($data);
		return $this->getInsertId();
	}

	function OnBeforeInsert(&$data) {
	}

	function OnAfterInsert(&$data) {
	}

	function Update(&$data) {
		$keyColumn = $this->getKeyColumn();
		if (is_array($keyColumn)) {
			$this->OnBeforeUpdate($data);
			$SQL = "";
			foreach ($this->columns as $column)
			if (!AbstractTable::isKey($column) && isset($data[$column["name"]])) {
				if (strlen($SQL)) $SQL .= ", ";
				$SQL .= $column["name"] . '=' . AbstractTable::prepareColumnValue($column, $data[$column["name"]]);
			}
			$whereClause = "";
			$whereClause .= $keyColumn["name"] . "=" . AbstractTable::prepareColumnValue($keyColumn, $data[$keyColumn["name"]]);
			$SQL = sprintf("UPDATE %s SET %s WHERE %s", $this->tableName, $SQL, $whereClause);

			$this->connection->ExecuteNonQuery($SQL);
			$this->OnAfterUpdate($data);
		}
	}

	function OnBeforeUpdate(&$data) {
	}

	function OnAfterUpdate(&$data) {
	}

	function Delete(&$data) {
		$keyColumn = $this->getKeyColumn();
		if (is_array($keyColumn)) {
			$this->OnBeforeDelete($data);
			$whereClause = "";
			$whereClause .= $keyColumn["name"] . "=" . AbstractTable::prepareColumnValue($keyColumn, $data[$keyColumn["name"]]);
			$SQL = sprintf("DELETE FROM %s WHERE %s", $this->tableName, $whereClause);
			$this->connection->ExecuteNonQuery($SQL);
			$this->OnAfterDelete($data);
			$data = array();
		}
	}

	function OnBeforeDelete(&$data) {
	}

	function OnAfterDelete(&$data) {
	}

	function DeleteByFields (&$data) {
		if (count($data)) {
			$this->OnBeforeDelete($data);
			$whereClause = "";
			while (list($_key, $_value) = each($data)) {
				if (strlen($whereClause))
				$whereClause .= " AND ";
				$whereClause .= $_key . "='" . $_value ."'";
			}
			$SQL = sprintf("DELETE FROM %s WHERE %s", $this->tableName, $whereClause);
			$this->connection->ExecuteNonQuery($SQL);
			$this->OnAfterDelete($data);
			$data = array();
		}
	}

	function GetList($data = null, $orders = null, $decorator = null, $readerMethod = null, $counterMethod = null, $withPager = false, $page = 1, $itemsPerPage = 10) {
		if (is_null($counterMethod))
		$counterMethod = "GetCount";
		if (is_null($readerMethod))
		$readerMethod = "GetReader";
		if (!$withPager) {
			$reader = $this->$readerMethod($data, $orders);
			return $this->buildList($reader, $decorator);
		}
		if ($page < 1 ) $page = 1;
//		if( _is_debug_enabled() ){
//			_dump($itemsPerPage);
//			_dump($itemsPerPage * ($page - 1));
//		}
		$return = $this->buildList($this->$readerMethod($data, $orders, $itemsPerPage, $itemsPerPage * ($page - 1)), $decorator);
		// With pager section
		$itemsCount = $this->$counterMethod($data);
		$pageCount = (int) (($itemsCount - 1) / $itemsPerPage) + 1;
		if ($page < 1 || $page > $pageCount) $page = 1;

		if ($pageCount > 1) {
			$return['pager'] = $this->buildPager($page, $itemsCount, $itemsPerPage);
		}
		if( is_null($return) )
		{
			$return = array();
		}
		return $return;
	}

	function buildList($dataReader, $decorator = null) {
		$useDecorator = (strlen($decorator) && method_exists($this, $decorator));
		$row = array();
		while ($dataReader->Read()) {
			if ($useDecorator) {
				$row[] = $this->$decorator($dataReader->Item);
			}
			else {
				foreach($dataReader->Item as $key => $value) {
					$tmp[$key] = $value;
				}
				$row[] = $tmp;
			}
		}
		$dataReader->Close();
		return $row;
	}

	function buildPager($page, $itemsCount, $itemsPerPage) {
		$pageCount = ceil(($itemsCount) / $itemsPerPage);
		$startItem = $itemsPerPage * ($page - 1) + 1;
		$endItem = $itemsPerPage * $page;
		if ($endItem > $itemsCount) $endItem = $itemsCount;

		$row = array();
		$row["total"] = $itemsCount;
		$row["items_per_page"] = $itemsPerPage;
		$row["page_count"] = $pageCount;
		$row["current_page"] = $page;

		$row["start_item"] = $startItem;
		$row["end_item"] = $endItem;

		$row["first_page"] = 1;
		$row["previous_page"] = $page - ($page == 1 ? 0 : 1);
		$row["next_page"] = $page + ($page == $pageCount ? 0 : 1);
		$row["last_page"] = $pageCount;

		$pagesPerPager = DEFAULT_PAGESPERPAGE;
		if ($pagesPerPager < $pageCount) {
			$startPage = $page - (int) ($pagesPerPager / 2);
			if ($startPage < 1) $startPage = 1;
			if ($page + (int) ($pagesPerPager / 2) > $pageCount) $startPage = $startPage - ($page + (int) (($pagesPerPager) / 2) - $pageCount) + ($pagesPerPager % 2 == 1 ? 0 : 1);
			for ($i = $startPage; $i < $startPage + $pagesPerPager; $i++) {
				$row["page"][$i] = $i;
			}
		} else {
			for ($i = 1; $i <= $pageCount; $i++) {
				$row["page"][$i] = $i;
			}
		}
		return $row;
	}

	function clear()
	{
		$SQL='truncate '.$this->tableName;
		$this->connection->ExecuteNonQuery($SQL);
	}

	function getSQLRows() {
		$SQL = 'select FOUND_ROWS() as cnt';
		$result = $this->connection->ExecuteScalar($SQL);
		return isset($result['cnt'])?(int)$result['cnt']:0;
	}

	function lookup($table, $field, $where_field, $where_value, $separator = ", ") {
		$SQL = sprintf("SELECT %s FROM %s WHERE %s = '%s'  LIMIT 0, 1", $field, $table, $where_field, $where_value);
		$result = $this->connection->ExecuteScalar($SQL);
		$fields = explode(",", $field);
		$n = count($fields);
		if ($n > 0) {
			$res = "";	$i = 0;
			foreach ($fields as $f) {
				if ( !empty($result[$f]) ) {
					if (++$i < $n) $res .= isset($result[$f]) ? $result[$f].$separator : NULL;
					else $res .= isset($result[$f]) ? $result[$f] : NULL;
				}
			}
			return $res;
		}
		return $result[$field];
	}

	function getRows($table, $wheres, $sort = NULL){
		$where = "";
		$order = "1";
		if (is_array($wheres)) {
			foreach ($wheres as $field_name => $field_value) {
				if ( empty ($where) ) $where .= " WHERE $field_name = '$field_value' ";
				else  $where .= " AND $field_name = '$field_value' ";
			}
		}
		if ( ! empty($sort) ) $sort = " ORDER BY ".$sort;
		$SQL = sprintf("SELECT * FROM %s %s %s", $table, $where, $sort);
		return $this->connection->ExecuteScalar($SQL, false);
	}


}
