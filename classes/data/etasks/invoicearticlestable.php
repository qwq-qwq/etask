<?php

Kernel::Import("system.db.abstracttable");

class InvoiceArticlesTable extends AbstractTable
{
	function __construct(&$connection)
	{
		parent::AbstractTable($connection, DB_TABLE_INVOICE_ARTICLES);
		
		$this->addTableField('intID', DB_COLUMN_NUMERIC, true);		
		$this->addTableField('intArticleID', DB_COLUMN_NUMERIC);
		$this->addTableField('intInvoiceID', DB_COLUMN_NUMERIC);
		$this->addTableField('intTaskID', DB_COLUMN_NUMERIC);
		$this->addTableField('varArticleName');	
		$this->addTableField('intDemandQty', DB_COLUMN_NUMERIC);	
		$this->addTableField('intDoneQty', DB_COLUMN_NUMERIC);		
	}

}