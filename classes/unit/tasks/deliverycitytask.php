<?php

Kernel::Import('classes.unit.tasks.DeliveryTask');

class DeliveryCityTask extends DeliveryTask {

	public $final_order_state = 100; // доставлен
	
	function render() {
		//Get adress
		$order = $this->ordersTable->Get(array('Ord_id' => $this->task_data['intOrderID']));
		$this->addinfo['varAdress'] = $order['Contact_address'];
		//Check payment type.
		$payment = $this->paymentTypesTable->Get(array('Payment_type' => $order['Payment_type']));
		if (strtolower($payment['cash']) == 'yes') {
			$this->page->GetDocument()->addValue('done_not_allowed', true);
		}
		parent::render();
	}
	
}