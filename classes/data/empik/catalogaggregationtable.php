<?php

Kernel::Import("system.db.abstracttable");

class CatalogAggregationTable extends AbstractTable
{
	function CatalogAggregationTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_CATALOG_AGGREGATION);

		$this->addTableField('Wares_id', DB_COLUMN_NUMERIC, true);
		$this->addTableField('Name');
		$this->addTableField('Prime_name');
		$this->addTableField('Group_id', DB_COLUMN_NUMERIC);
		$this->addTableField('Date_creation');		
		$this->addTableField('Image');		
		$this->addTableField('Price_dealer_withVat');		
		$this->addTableField('Author');		
		$this->addTableField('Actor');		
		$this->addTableField('Stock_Avail');		
		$this->addTableField('Sales_Qty', DB_COLUMN_NUMERIC);		
		$this->addTableField('Descr');	
		$this->addTableField('ISBN');	
		$this->addTableField('updated');		
	}	
	
	function getCatalog ($Wares_id) {
		$SQL = sprintf("SELECT ca.Name as Name,
					   ca.Price_dealer_withVat as Price,
					   ca.Group_id as Group_id,
					   ct.Discount_forbidden as Discount_forbidden,
					   c.Vat as Vat FROM %s AS ca 
				JOIN %s AS ct ON ct.Group_id = ca.Group_id 
				JOIN %s AS c ON c.Wares_id = ca.Wares_id 
				WHERE ca.Wares_id = %s",
		$this->tableName,
		DB_EMPIK_TABLE_CATALOG_TREE,
		DB_EMPIK_TABLE_CATALOG,
		$Wares_id);
		return $this->connection->ExecuteScalar($SQL);
	}

}