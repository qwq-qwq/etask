<?php

Kernel::Import('classes.unit.tasks.DeliveryCityTask');

class DeliveryCityCourierTask extends DeliveryCityTask {

	protected $exec_time = 14400;

	function getSenderDepartment() {
		// get asm_shop_id
		$order = $this->ordersTable->Get(array('Ord_id' => $this->task_data['intOrderID']));

		$depts = $this->page->getUserAllDepartments();
		if ($order["Asm_shop_id"] == PARITET_CODE_SHOP) $order["Asm_shop_id"] = PARITET_REPLACEMENT_CODE_SHOP;
		foreach ($depts as $dept) {
			if ($dept['intCodeShopSprut'] == $order["Asm_shop_id"]) {
				$this->addinfo['varSender'] = $dept['varValue'];
				break;
			}
		}
	}

}