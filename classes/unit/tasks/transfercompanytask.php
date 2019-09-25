<?php

Kernel::Import('classes.unit.tasks.TransferTask');

class TransferCompanyTask extends TransferTask {
	
	protected $exec_time = 172800;
	
	function __construct(&$page, $data) {
		parent::__construct($page, $data);
		
		$this->template = 'TransferCompanyTask.tpl';
	}
	
	function onDoneTask() {
		die('This action is not allowed. Please do not try to hack the system ;)');
	}
	
}