<?php

Kernel::Import('classes.unit.tasks.DeliveryPostTask');

class DeliveryNewPostEmployeeTask extends DeliveryPostTask {
	
	protected $exec_time = 14400;

	function __construct(&$page, $data) {
		$this->mail_template = 'ord_sent_by_newpost';
		$this->addinfo = array('varAdress' => 'Ближайшее отделение Новой Почты');
		parent::__construct($page, $data);
	}

}