<?php

Kernel::Import("system.db.abstracttable");

class MailTemplatesTable extends AbstractTable {

	function MailTemplatesTable(&$connection) {
		parent::AbstractTable($connection, DB_EMPIK_TABLE_MAILTEMPLATES);

		$this->addTableField('mail_type'); // key
		$this->addTableField('subject_ru');
		$this->addTableField('subject_en');
		$this->addTableField('subject_ua');
		$this->addTableField('text_ru');
		$this->addTableField('text_en');
		$this->addTableField('text_ua');
	}

}