<?php

Kernel::Import("system.db.abstracttable");

class CodesTable extends AbstractTable
{
	function CodesTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_CODE);

		$this->addTableField('Code_id', DB_COLUMN_NUMERIC, true);
		$this->addTableField('Name');
		$this->addTableField('Start_date');
		$this->addTableField('Stop_date');
		$this->addTableField('User_id');
		$this->addTableField('percent');
	}
	function getCodeByProduct($ID,$code){
		$SQL = sprintf('
		SELECT * FROM (
		(SELECT p.Code_id,p.Start_date,p.Stop_date, a1.percent FROM %1$s p JOIN %3$s a1 USING(Code_id) WHERE p.Name="%4$s" AND a1.Warez_id=%5$d)
		UNION
		(SELECT p.Code_id,p.Start_date,p.Stop_date, p.percent FROM %1$s p JOIN %2$s a1 USING(Code_id) WHERE p.Name="%4$s" AND a1.Cat_id = (SELECT Group_id FROM catalog_aggregation WHERE Wares_id=%5$d))
		) tmp WHERE STR_TO_DATE(Stop_date,"%%d.%%m.%%Y %%H:%%i")>=NOW() AND STR_TO_DATE(Start_date,"%%d.%%m.%%Y %%H:%%i")<=NOW()
		',$this->tableName,DB_EMPIK_TABLE_CODE_CATS,DB_EMPIK_TABLE_CODE_PRODS,$code,$ID);
		return $this->connection->ExecuteScalar($SQL);
	}
}
class CodeCatsTable extends AbstractTable
{
	function CodeCatsTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_CODE_CATS);

		$this->addTableField('Code_id', DB_COLUMN_NUMERIC, true);
		$this->addTableField('Cat_id');
	}


}
class CodeProdsTable extends AbstractTable
{
	function CodeProdsTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_CODE_PRODS);

		$this->addTableField('Code_id', DB_COLUMN_NUMERIC, true);
		$this->addTableField('Warez_id');
		$this->addTableField('percent');
		$this->addTableField('Stop_date');
		$this->addTableField('User_id');
		$this->addTableField('percent');
	}
}