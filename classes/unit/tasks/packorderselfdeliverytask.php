<?php

Kernel::Import('classes.unit.tasks.PackOrderTask');

class PackOrderSelfdeliveryTask extends PackOrderTask {
	
	protected $exec_time = 1800;
	
	// каждый таск
	
}