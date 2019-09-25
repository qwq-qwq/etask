<?php

Kernel::Import("system.db.abstracttable");

class CatalogTreeTable extends AbstractTable
{
	function __construct(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_CATALOG_TREE);

		$this->addTableField('Group_id', DB_COLUMN_NUMERIC, true);
		$this->addTableField('Menu_code');
		$this->addTableField('Name_RU');
		$this->addTableField('Name_UA');
		$this->addTableField('Name_EN');
		$this->addTableField('Avg_wgt');
		$this->addTableField('Discount_forbidden');
	}
	
	function isBook($id = 0) {
		$res = false;
		if (is_numeric($id) && $id > 0) {
			$group = $this->Get(array('Group_id' => $id));
			if (!empty($group['Menu_code'])) {
				if ( substr($group['Menu_code'], 0, 2) == '01') $res = true;
			}
		}
		
		return $res;
	}
}