<?php

Kernel::Import("system.db.abstracttable");

class DepartmentsTable extends AbstractTable
{
	function DepartmentsTable(&$connection)
	{
		parent::AbstractTable($connection, DB_INTRANET_TABLE_DEAPRTMENTS);

		$this->addTableField('intVarID', DB_COLUMN_NUMERIC, true);
		$this->addTableField('varValue');	
		$this->addTableField('intGroupID', DB_COLUMN_NUMERIC);	
		$this->addTableField('varLeaderUserID', DB_COLUMN_NUMERIC);		
		$this->addTableField('intCodeShopSprut', DB_COLUMN_NUMERIC);
	}

	function getUserDepartments($intUserID) {
		$SQL = sprintf("SELECT intDepartmentID FROM %s WHERE intUserID = %s", DB_INTRANET_TABLE_USERSMULTIDEPTS, $intUserID);
		$result = $this->connection->ExecuteScalar($SQL, false);
		return $result;
	}

	function getDepartmentBySprut($ID) {
		$SQL = sprintf("SELECT intDepartmentID FROM %s WHERE intCodeShopSprut = %d", DB_INTRANET_TABLE_USERSMULTIDEPTS, $ID);
		$result = $this->connection->ExecuteScalar($SQL, false);
		if(empty($result)) return;
		return $result['intDepartmentID'];
	}
}