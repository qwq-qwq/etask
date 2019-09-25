<?php

Kernel::Import("system.db.abstracttable");

class CollectArticlesTable extends AbstractTable
{
	function __construct(&$connection)
	{
		parent::AbstractTable($connection, DB_TABLE_COLLECT_ARTICLES);

		$this->addTableField('intID', DB_COLUMN_NUMERIC, true);
		$this->addTableField('intTaskID', DB_COLUMN_NUMERIC);
		$this->addTableField('intArticleID', DB_COLUMN_NUMERIC);
		$this->addTableField('varArticleName');
		$this->addTableField('varPrice');
		$this->addTableField('intDemandQty', DB_COLUMN_NUMERIC);
		$this->addTableField('intDoneQty', DB_COLUMN_NUMERIC);
	}

}