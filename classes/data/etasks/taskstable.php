<?php

Kernel::Import("system.db.abstracttable");

class TasksTable extends AbstractTable
{
	function TasksTable(&$connection)
	{
		parent::AbstractTable($connection, DB_TABLE_TASKS);

		$this->addTableField('intID', DB_COLUMN_NUMERIC, true);
		$this->addTableField('intChildID', DB_COLUMN_NUMERIC);
		$this->addTableField('intOrderID', DB_COLUMN_NUMERIC);
		$this->addTableField('varCreation');
		$this->addTableField('varStart');
		$this->addTableField('intExecutionTime', DB_COLUMN_NUMERIC);
		$this->addTableField('varEnd');
		$this->addTableField('intType', DB_COLUMN_NUMRIC);
		$this->addTableField('intState', DB_COLUMN_NUMERIC);
		$this->addTableField('intCreatorID', DB_COLUMN_NUMERIC);
		$this->addTableField('intExecutorID', DB_COLUMN_NUMERIC);
		$this->addTableField('intDepartmentID', DB_COLUMN_NUMERIC);
		$this->addTableField('varComment');
		$this->addTableField('intKPI', DB_COLUMN_NUMERIC);
		$this->addTableField('intDeliveryService', DB_COLUMN_NUMERIC);

	}

	// calculate KPI
	function OnBeforeUpdate(&$data) {
		/*if (isset($data['intState']) && isset($data['intExecutionTime']) && isset($data['varCreation']) && isset($data['varEnd'])) {
			// do nothing
			$intState = $data['intState'];
			$intExecutionTime = $data['intExecutionTime'];
			$varCreation = $data['varCreation'];
			$varEnd = $data['varEnd'];
		} else {
			$task = $this->Get(array('intID' => $data['intID']));
			$intState = $task['intState'];
			$intExecutionTime = $task['intExecutionTime'];
			$varCreation = $task['varCreation'];
			$varEnd = $task['varEnd'];
		}
		if (in_array($intState, array(3,4,6))) { // only for these states
			$plan_time = $intExecutionTime;
			$fact_time = strtotime($varEnd) - strtotime($varCreation);

			if( $fact_time <= $plan_time ){
				$kpd = 100;
			} else {
				$delay_time = $fact_time - $plan_time;
				$kpd = ($plan_time - $delay_time) / $plan_time;
				if( $kpd < 0 ){
					$kpd = 0;
				}
				$kpd = $kpd * 100;
			}
			$data['intKPI'] = round($kpd);
		}*/
	}

	/**
	 * Генерируем задачу 20 - Изменение содержания/сроков доставки заказа (колл-центр)
	 */
	function generateCallcentreEditTask($orderid, $creatorid, $type = 20) {
		$varCreation = date('Y-m-d H:i:s');
		if (date('Hi') <= 900) {
			// если раньше чем 9 утра, то на сегодня на 9-15
			$varCreation =  date('Y-m-d H:i:s', mktime(9, 15, 00, date('m'), date('d'), date('Y')));
		} elseif (date('Hi') > 2045) {
			// Начало зачи для колл - центра должно выставляться на следующий день на 9:15 утра
			// если время создания заказа  - после 8:45 вечера
			$varCreation =  date('Y-m-d H:i:s', mktime(9, 15, 00, date('m'), date('d')+1, date('Y')));
		}
		$cc = array();
		$cc['intChildID'] = 0;
		$cc['intOrderID'] = $orderid;
		$cc['varCreation'] = $varCreation;
		$cc['intExecutionTime'] = 1800;
		$cc['intType'] = $type;
		$cc['intState'] = 1;
		$cc['intCreatorID'] = $creatorid;
		$cc['intDepartmentID'] = DEPARTMENT_CALLCENTRE_ID;
		return $this->Insert($cc); // return inserted id
	}

	function getCallCentreForOrder($order_id) {
		$SQL = sprintf("select intID from %s where intOrderID=%s and intState IN (1,2) and intType IN (10,20)", $this->tableName, intval($order_id));
		$res = $this->connection->ExecuteScalar($SQL);
		return (int) $res['intID'];
	}

	/**
	 * Есть ли задачи не в состоянии 3,4,6 на этом уровне?
	 */
	function isHasUnfinishedTasks($child_id) {
		$SQL = sprintf("select count(*) as counter from %s where intChildID=%s and intState NOT IN (3,4,6)", $this->tableName, intval($child_id));
		$res = $this->connection->ExecuteScalar($SQL);
		return (bool) $res['counter'];
	}

	/**
	 * Разброкирует следущую задачу
	 */
	function unlockNextTask($child_id) {
		$SQL = sprintf("update %s set intState=1 where intID=%s and intState=5", $this->tableName, intval($child_id));
		$this->connection->ExecuteNonQuery($SQL);
	}

	/**
	 * удаляет все задачи в статусе НОВАЯ(1) и ЗАБЛОКИРОВАНА(5) для order_id
	 */
	function clearNewLockedByOrderID($order_id) {
		$SQL = sprintf("delete from %s where intOrderID=%s and intState IN (1,5)", $this->tableName, intval($order_id));
		$this->connection->ExecuteNonQuery($SQL);
	}

	function &GetWithNames($data = null, $orders = null, $limitCount = null, $limitOffset = null) {
		$whereClause = "";
		if (!is_null($data)) {
			foreach ($this->columns as $column) {
				if (isset($data[$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= 'p.'.$column["name"] . "=" . AbstractTable::prepareColumnValue($column, $data[$column["name"]]);
				}
				if (!empty($data['NOTIN'.$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					if (!is_array($data['NOTIN'.$column["name"]])) {
						$whereClause .= 'p.'.$column["name"] . " NOT IN (" . $data['NOTIN'.$column["name"]].") ";
					} else {
						$whereClause .= 'p.'.$column["name"] . "  NOT IN (";
						foreach ($data['NOTIN'.$column["name"]] as $value) {
							$whereClause .=  AbstractTable::prepareColumnValue($column, $value).', ';
						}
						$whereClause = rtrim($whereClause, ', ').') ';
					}
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

		$SQL = sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, (SELECT varName FROM %s WHERE intID=p.intType) as varType, (SELECT varName FROM %s WHERE intID=p.intState) as varState, (SELECT Contact_name FROM ".DB_EMPIK_NAME.".".DB_EMPIK_TABLE_ORDERS." WHERE Ord_id=p.intOrderID) as varFIO FROM %s as p %s%s%s", DB_TABLE_TASK_TYPES, DB_TABLE_TASK_STATE, $this->tableName, $whereClause, $orderClause, $limitClause);

		@$reader = &$this->connection->ExecuteReader($SQL);
		return $reader;
	}

	function getCountState($intState, $intUserID, $intDepartmentID) {
		if (is_array($intDepartmentID)) {
			$intDepartmentID = implode(",", $intDepartmentID);
		}
		$SQL = sprintf("SELECT COUNT(*) AS counter FROM %s WHERE intState = %s AND intExecutorID = %s AND intDepartmentID IN (%s) LIMIT 0, 1", $this->tableName, $intState, $intUserID, $intDepartmentID);
		$result = $this->connection->ExecuteScalar($SQL);
		return $result["counter"];
	}

	function getCountOverdue($intUserID, $intDepartmentID) {
		if (is_array($intDepartmentID)) $intDepartmentID = implode(",", $intDepartmentID);
		$now = date("Y-m-d H:i:s");
		if (!empty($intUserID))
			$SQL = sprintf("SELECT COUNT(*) AS counter FROM %s WHERE intState IN (1,2) AND varEnd < '%s' AND intExecutorID = %s AND intDepartmentID IN (%s) LIMIT 0, 1", $this->tableName, $now, $intUserID, $intDepartmentID);
		else
			$SQL = sprintf("SELECT COUNT(*) AS counter FROM %s WHERE intState IN (1,2) AND varEnd < '%s' AND intDepartmentID IN (%s)  LIMIT 0, 1", $this->tableName, $now, $intDepartmentID);
		$result = $this->connection->ExecuteScalar($SQL);
		return $result["counter"];
	}

	function getCountDepartmentState($intState, $intDepartmentID) {
		if (is_array($intDepartmentID)) $intDepartmentID = implode(",", $intDepartmentID);
		$SQL = sprintf("SELECT COUNT(*) AS counter FROM %s WHERE intState = %s AND intDepartmentID IN (%s)  LIMIT 0, 1", $this->tableName, $intState, $intDepartmentID);
		$result = $this->connection->ExecuteScalar($SQL);
		return $result["counter"];
	}

	function GetTaskArticles($id, $qty_field) {
		$ret = array();
		if (is_numeric($id) && is_string($qty_field) && strlen($qty_field) > 0) {
			$select = ' Select intArticleID, varArticleName, '.$qty_field.' as intDemandQty';
			$where = ' Where intTaskID = '.$id;
			$SQL = 		  $select.' From '.DB_TABLE_COLLECT_ARTICLES.$where.
				' Union '.$select.' From '.DB_TABLE_PACK_ARTICLES.$where.
				' Union '.$select.' From '.DB_TABLE_INVOICE_ARTICLES.$where.
				' Union '.$select.' From '.DB_TABLE_DELIVERY_ARTICLES.$where;
			$ret = $this->connection->ExecuteScalar($SQL, false);
		}
		return $ret;
	}

	function GetOrderDeliveryTask($ord_id) {
		$ret = array();
		if (is_numeric($ord_id) && $ord_id > 0) {
			$SQL = 'Select * from '.$this->tableName.' Where intOrderID='.$ord_id.' AND intType IN (130, 140, 150, 160, 170, 180)';
			$res = $this->connection->ExecuteScalar($SQL);
			if (is_array($res)) $ret = $res;
		}
		return $ret;
	}

	function GetOrderPackTask($ord_id) {
		$ret = array();
		if (is_numeric($ord_id) && $ord_id > 0) {
			$SQL = 'Select * from '.$this->tableName.' Where intOrderID='.$ord_id.' AND intType IN (90, 100, 110, 120)';
			$res = $this->connection->ExecuteScalar($SQL);
			if (is_array($res)) $ret = $res;
		}
		return $ret;
	}

	function GetTaskOrder($tid) {
		$ret = array();
		if (is_numeric($tid) && $tid > 0) {
			$SQL = 'Select DISTINCT sl.Vat from '.$this->tableName.' p LEFT JOIN '.DB_EMPIK_NAME.".".DB_EMPIK_TABLE_ORDERS.' ord ON(ord.Ord_id=p.intOrderID) LEFT JOIN '.DB_EMPIK_NAME.".".DB_EMPIK_TABLE_SALES.' sl ON(sl.Ord_id=p.intOrderID) Where p.intID='.$tid.' AND CHAR_LENGTH( Organization_name ) > 0';
			$ret = $this->connection->ExecuteScalar($SQL, false);
		}
		return $ret;
	}
}