<?php

Kernel::Import("system.db.abstracttable");

class AccountsTable extends AbstractTable
{
	function AccountsTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_ACCOUNTS);

		$this->addTableField('id', DB_COLUMN_NUMERIC, true);
		$this->addTableField('login');
		$this->addTableField('password');
		$this->addTableField('email');
		$this->addTableField('create_date');
		$this->addTableField('activate_date');
		$this->addTableField('hash');
		$this->addTableField('last_login');
		$this->addTableField('validated', DB_COLUMN_NUMERIC);
		$this->addTableField('validate_code');
		$this->addTableField('password_restore_code');
		$this->addTableField('account_type', DB_COLUMN_NUMERIC);
		$this->addTableField('courier', DB_COLUMN_NUMERIC);
		$this->addTableField('name');
		$this->addTableField('surname');
		$this->addTableField('phone');
		$this->addTableField('birth_date');
		$this->addTableField('delivery_address');
		$this->addTableField('accumulate_sum');
		$this->addTableField('discount', DB_COLUMN_NUMERIC);
		$this->addTableField('profile_lang');
		$this->addTableField('organization_name');
		$this->addTableField('tax_number');
		$this->addTableField('organization_address');
		$this->addTableField('add_value_number');
		$this->addTableField('edrpou');
		$this->addTableField('nds', DB_COLUMN_NUMERIC);
		$this->addTableField('code_privat', DB_COLUMN_NUMERIC);
		$this->addTableField('bar_code');
		$this->addTableField('discount_code', DB_COLUMN_NUMERIC);
		$this->addTableField('rejected_vip', DB_COLUMN_NUMERIC);
	}
	
	function getCountBarcode($bar_code, $id) {
		if (strlen($bar_code) != 13) return -1;
    	$whereClause = "bar_code = '$bar_code'";
    	if (!is_null($id)) $whereClause .= " AND id != $id";
		$SQL = sprintf("SELECT count(*) AS counter FROM %s%s", $this->tableName, $whereClause);
		$result = $this->connection->ExecuteScalar($SQL);
		return $result['counter'];
	}
	
}