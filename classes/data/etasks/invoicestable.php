<?php

Kernel::Import("system.db.abstracttable");

class InvoicesTable extends AbstractTable
{
	function __construct(&$connection)
	{
		parent::AbstractTable($connection, DB_TABLE_INVOICES);
		
		$this->addTableField('intID', DB_COLUMN_NUMERIC, true);				
		$this->addTableField('intTaskID', DB_COLUMN_NUMERIC);
		$this->addTableField('intOrderID', DB_COLUMN_NUMERIC);
		$this->addTableField('intCodeInvoice', DB_COLUMN_NUMERIC);
		$this->addTableField('intNumberInvoice', DB_COLUMN_NUMERIC);	
		$this->addTableField('intQty', DB_COLUMN_NUMERIC);	
		$this->addTableField('intShopFrom', DB_COLUMN_NUMERIC);
		$this->addTableField('intShopTo', DB_COLUMN_NUMERIC);	
		$this->addTableField('varPreorderBarcode');		
		$this->addTableField('varStatus');
		$this->addTableField('varExtStatus');
	}
	
	function GetUndoneInvoices() {
		$SQL = 'Select * from '.$this->tableName.' Where varStatus != "E" and intOrderID > 146803';
		$res = $this->connection->ExecuteScalar($SQL, false);
		return $res;
	}
	
}