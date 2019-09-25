<?php

Kernel::Import('classes.unit.tasks.CallcentreTask');

class CallcentreEditTask extends CallcentreTask {
	
	protected $exec_time = 1800;
	
	function __construct(&$page, $data) {
		parent::__construct($page, $data);
	}
	
	function render() {
		parent::render();
	}
	
}