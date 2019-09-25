<?php

Kernel::Import('classes.unit.tasks.DeliveryPostTask');

class DeliveryNewPostCourierTask extends DeliveryPostTask {

	protected $exec_time = 14400;

	function __construct(&$page, $data) {
		$this->mail_template = 'ord_sent_by_newpost';
		$this->addinfo = array('varAdress' => 'Ближайшее отделение Новой Почты');
		parent::__construct($page, $data);
	}
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