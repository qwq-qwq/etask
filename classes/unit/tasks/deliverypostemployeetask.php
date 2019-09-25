<?php

Kernel::Import('classes.unit.tasks.DeliveryPostTask');

class DeliveryPostEmployeeTask extends DeliveryPostTask {
	
	protected $exec_time = 14400;

	function __construct(&$page, $data) {
		parent::__construct($page, $data);
	}

}