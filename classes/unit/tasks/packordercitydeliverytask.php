<?php

Kernel::Import('classes.unit.tasks.PackOrderTask');

class PackOrderCitydeliveryTask extends PackOrderTask {
	
	protected $exec_time = 1800;
	
	function __construct($page, $data) {
		parent::__construct($page, $data);
		//$this->template = "packorderemployeedeliverytask.tpl";
	}
	
	function render(){
		parent::render();
	}
}