<?php
Kernel::Import('classes.unit.tasks.CallcentreTask');

class CallcentreConfirmTask extends CallcentreTask {
	
	protected $exec_time = 1800;
	
	function __construct(&$page, $data) {
		parent::__construct($page, $data);		
	}
	
	function render() {
		parent::render();		
	}
		
}