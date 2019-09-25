<?php

Kernel::Import("system.db.abstracttable");

class UsersTable extends AbstractTable
{
	function UsersTable(&$connection)
	{
		parent::AbstractTable($connection, DB_INTRANET_TABLE_USERS);

		$this->addTableField('intUserID', DB_COLUMN_NUMERIC, true);
		$this->addTableField('varLogin');
		$this->addTableField('varPassword');
		$this->addTableField('varFIO');
		$this->addTableField('varDepartment');
		$this->addTableField('varMail');
		$this->addTableField('varPhone');
		$this->addTableField('varBirthday');
		$this->addTableField('intRegisterTimestamp', DB_COLUMN_NUMERIC);
		$this->addTableField('intUpdateTimestamp', DB_COLUMN_NUMERIC);
		$this->addTableField('intUpdateUserID', DB_COLUMN_NUMERIC);
		$this->addTableField('intLastLoginTimestamp', DB_COLUMN_NUMERIC);
		$this->addTableField('varPosition');
		$this->addTableField('intDeactivatedTimestamp', DB_COLUMN_NUMERIC);
		$this->addTableField('intDeactivatedUserFIO', DB_COLUMN_NUMERIC);
		$this->addTableField('isDisabled', DB_COLUMN_NUMERIC);
		$this->addTableField('isAdmin', DB_COLUMN_NUMERIC);
	}
	
}